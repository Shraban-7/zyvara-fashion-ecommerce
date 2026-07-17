<?php

namespace App\Http\Controllers;

use App\Enums\ReturnStatus;
use App\Http\Requests\StoreReturnRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ReturnRequest;
use App\Services\ReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReturnRequestController extends Controller
{
    public function __construct(
        protected ReturnService $returnService
    ) {
    }

    /**
     * List the customer's return/exchange requests.
     */
    public function index()
    {
        $requests = ReturnRequest::with(['order', 'orderItem.product', 'exchangeDetail.requestedVariant', 'images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.returns.index', compact('requests'));
    }

    /**
     * Show a single request with its status timeline.
     */
    public function show(ReturnRequest $returnRequest)
    {
        if ($returnRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $returnRequest->load(['order', 'orderItem.product', 'orderItem.variant', 'exchangeDetail.originalVariant', 'exchangeDetail.requestedVariant', 'images', 'statusHistories.changer']);

        return view('customer.returns.show', compact('returnRequest'));
    }

    /**
     * Eligibility + available variant data for a given order item (AJAX).
     */
    public function itemOptions(Request $request)
    {
        $item = OrderItem::with(['order', 'product.variants.size', 'product.variants.color', 'variant'])
            ->findOrFail($request->order_item_id);

        $eligibility = $this->returnService->checkEligibility($item);

        if (! $eligibility['eligible']) {
            return response()->json([
                'eligible' => false,
                'message' => $eligibility['message'],
            ]);
        }

        $variants = $item->product->variants->map(function ($v) use ($item) {
            return [
                'id' => $v->id,
                'name' => $v->variant_name,
                'size' => $v->size?->name,
                'color' => $v->color?->name,
                'price' => (float) $v->final_price,
                'stock' => (int) ($v->currentStock ?? 0),
                'is_current' => $v->id === $item->product_variant_id,
            ];
        });

        return response()->json([
            'eligible' => true,
            'order_item_id' => $item->id,
            'product_name' => $item->product_name,
            'current_variant' => $item->variant?->variant_name,
            'variants' => $variants,
            'return_window_days' => $this->returnService->returnWindowDays,
        ]);
    }

    /**
     * Store a new return/exchange request.
     */
    public function store(StoreReturnRequest $request)
    {
        $item = OrderItem::with('order')->findOrFail($request->order_item_id);

        if ($item->order->user_id !== Auth::id()) {
            abort(403);
        }

        // Persist uploaded images to disk
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('return-requests', 'public');
                $imagePaths[] = $path;
            }
        }

        try {
            $returnRequest = $this->returnService->createRequest(
                $request->validated() + ['images' => $imagePaths],
                Auth::user(),
                $item
            );
        } catch (\Exception $e) {
            // Clean up any uploaded images on failure
            foreach ($imagePaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('customer.returns.show', $returnRequest->id)
            ->with('success', 'Your ' . ($returnRequest->isExchange ? 'exchange' : 'return') . ' request has been submitted.');
    }
}
