<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReturnStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReturnStatusRequest;
use App\Models\ReturnRequest;
use App\Services\ReturnService;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function __construct(
        protected ReturnService $returnService
    ) {
    }

    /**
     * List all return/exchange requests with optional status filter.
     */
    public function index(Request $request)
    {
        $status = $request->filled('status') ? ReturnStatus::from($request->status) : null;

        $query = ReturnRequest::with(['order', 'orderItem.product', 'user', 'exchangeDetail.requestedVariant'])
            ->latest('requested_at');

        if ($status) {
            $query->where('status', $status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $requests = $query->paginate(20)->appends($request->all());

        $statusCounts = ReturnRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.returns.index', compact('requests', 'statusCounts', 'status'));
    }

    /**
     * Show a single request with its timeline + admin action panel.
     */
    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load([
            'order',
            'orderItem.product',
            'orderItem.variant',
            'user',
            'resolver',
            'exchangeDetail.originalVariant',
            'exchangeDetail.requestedVariant',
            'images',
            'statusHistories.changer',
        ]);

        $nextStatuses = $this->returnService->nextAllowedStatuses($returnRequest);

        return view('admin.returns.show', compact('returnRequest', 'nextStatuses'));
    }

    /**
     * Apply an admin status transition.
     */
    public function updateStatus(UpdateReturnStatusRequest $request, ReturnRequest $returnRequest)
    {
        try {
            $this->returnService->transition(
                $returnRequest,
                ReturnStatus::from($request->status),
                $request->admin_note,
                $request->user(),
                [
                    'refund_amount' => $request->refund_amount,
                    'refund_method' => $request->refund_method,
                ]
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Request updated to ' . $returnRequest->status->label() . '.');
    }
}
