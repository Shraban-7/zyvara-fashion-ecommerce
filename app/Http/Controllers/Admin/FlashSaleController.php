<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::ordered()->withCount('products')->get();

        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        return view('admin.flash-sales.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $flashSale = FlashSale::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        $this->syncProducts($flashSale, $request);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale created successfully!');
    }

    public function edit(FlashSale $flash_sale)
    {
        $flash_sale->load('products.primaryImage', 'products.category');

        return view('admin.flash-sales.edit', ['flashSale' => $flash_sale]);
    }

    public function update(Request $request, FlashSale $flash_sale)
    {
        $validated = $this->validateData($request);

        $flash_sale->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        $this->syncProducts($flash_sale, $request);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale updated successfully!');
    }

    public function destroy(FlashSale $flash_sale)
    {
        $flash_sale->delete();

        return redirect()->back()->with('success', 'Flash sale deleted successfully!');
    }

    public function toggleStatus(FlashSale $flash_sale)
    {
        $flash_sale->update(['is_active' => ! $flash_sale->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $flash_sale->is_active,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('query');

        $products = Product::with('primaryImage')
            ->where('is_active', true)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('sku', 'like', "%{$query}%");
                });
            })
            ->limit(30)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'image' => $product->primaryImage->first()?->image
                        ? storage_url($product->primaryImage->first()->image)
                        : null,
                ];
            });

        return response()->json($products);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'integer|exists:products,id',
            'sale_prices' => 'nullable|array',
            'sale_prices.*' => 'nullable|numeric|min:0',
        ]);
    }

    private function syncProducts(FlashSale $flashSale, Request $request): void
    {
        $productIds = $request->input('products', []);
        $salePrices = $request->input('sale_prices', []);

        $sync = [];
        foreach ($productIds as $index => $productId) {
            $sync[$productId] = [
                'sale_price' => $salePrices[$productId] ?? null,
                'sort_order' => $index,
            ];
        }

        $flashSale->products()->sync($sync);
    }
}
