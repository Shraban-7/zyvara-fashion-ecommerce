<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants.size', 'variants.color', 'images'])
            ->where('is_active', true)
            ->get()
            ->sortByDesc('total_stock')
            ->values();

        $categories = Category::where('is_active', true)->get();

        $cart = $this->getCart();

        $employees = User::where('role', 'staff')->get();

        return view('admin.pos.index', compact('products', 'categories', 'cart', 'employees'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('query');
        $categoryId = $request->get('category_id');

        $products = Product::with(['category', 'variants.size', 'variants.color'])
            ->where('is_active', true)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('sku', 'like', "%{$query}%");
                });
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->limit(50)
            ->get();

        return response()->json($products);
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->term;

        return Customer::select(
            'id',
            'name as value',
            'phone'
        )
            ->where('name', 'like', "%$term%")
            ->orWhere('phone', 'like', "%$term%")
            ->limit(8)
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.product_image' => 'nullable|string',
            'items.*.sku' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'items.*.size' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payable' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'due' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'discount' => 'nullable',
            'employee_id' => 'nullable',
            'customer_name' => ['nullable', 'string', 'max:255', 'required_with:customer_phone'],
            'customer_phone' => ['nullable', 'string', 'max:20', 'required_with:customer_name'],
            'cash_received' => 'nullable',
            'cash_returned' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // Map payment method string to enum
            $paymentMethodEnum = match (strtolower($request->payment_method ?? '')) {
                'cash' => PaymentMethod::CASH,
                'card' => PaymentMethod::CARD,
                'bkash' => PaymentMethod::BKASH,
                'nagad' => PaymentMethod::NAGAD,
                '' => null, 
                default => null, 
            };



            $customer_id = null;

            if (!$customer_id && $request->filled('customer_name') && $request->filled('customer_phone')) {

                $customer = Customer::firstOrCreate(
                    ['phone' => $request->customer_phone],
                    ['name' => $request->customer_name]
                );

                $customer_id = $customer->id;
            }


            // Create order
            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(uniqid()),
                'is_pos' => 1,
                'user_id' => null,
                'customer_id' => $customer_id,
                'employee_id' => $request->employee_id ? $request->employee_id : null,
                'shipping_name' => $request->customer_name ?? 'Walk-in Customer',
                'shipping_phone' => null,
                'shipping_email' => null,
                'subtotal' => $request->subtotal,
                'discount_amount' => $request->discount ?? 0,
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total' => $request->total,
                'payable' => $request->payable,
                'paid' => $request->paid,
                'due' => $request->due,
                'cash_received' => $request->cash_received,
                'cash_returned' => $request->cash_returned,
                'status' => OrderStatus::DELIVERED,
                'payment_method' => $paymentMethodEnum->value??'',
                'payment_status' => PaymentStatus::PAID,
                'notes' => 'POS Order',
                'paid_at' => now(),
            ]);

            // Create order items and update stock
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['sku'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'size_name' => $item['size'],
                    'color_name' => $item['color'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'total' => ($item['price'] * $item['quantity']),
                ]);

                // Update stock
                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant) {
                        $variant->decrement('stock_in', $item['quantity']);
                    }
                } else {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock_in', $item['quantity']);
                        $product->increment('sold_count', $item['quantity']);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order completed successfully',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete order: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function saveDraft(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.product_image' => 'nullable|string',
            'items.*.sku' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'items.*.size' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payable' => 'required|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
            'due' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            'discount' => 'nullable',
            'employee_id' => 'nullable',
            'customer_name' => ['nullable', 'string', 'max:255', 'required_with:customer_phone'],
            'customer_phone' => ['nullable', 'string', 'max:20', 'required_with:customer_name'],
            'cash_received' => 'nullable',
            'cash_returned' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $paymentMethodEnum = match (strtolower($request->payment_method ?? '')) {
                'cash' => PaymentMethod::CASH,
                'card' => PaymentMethod::CARD,
                'bkash' => PaymentMethod::BKASH,
                'nagad' => PaymentMethod::NAGAD,
                '' => null, 
                default => null, 
            };

            $customer_id = null;

            if (!$customer_id && $request->filled('customer_name') && $request->filled('customer_phone')) {

                $customer = Customer::firstOrCreate(
                    ['phone' => $request->customer_phone],
                    ['name' => $request->customer_name]
                );

                $customer_id = $customer->id;
            }


            // Create order
            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(uniqid()),
                'is_pos' => 1,
                'user_id' => null,
                'customer_id' => $customer_id,
                'employee_id' => $request->employee_id ? $request->employee_id : null,
                'shipping_name' => $request->customer_name ?? 'Walk-in Customer',
                'shipping_phone' => null,
                'shipping_email' => null,
                'subtotal' => $request->subtotal,
                'discount_amount' => $request->discount ?? 0,
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total' => $request->total,
                'payable' => $request->payable,
                'paid' => $request->paid,
                'due' => $request->due,
                'cash_received' => $request->cash_received,
                'cash_returned' => $request->cash_returned,
                'status' => OrderStatus::DRAFT,
                'payment_method' => $paymentMethodEnum->value??'',
                'payment_status' => PaymentStatus::PAID,
                'notes' => 'POS Order',
                'paid_at' => now(),
            ]);

            // Create order items and update stock
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['sku'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'size_name' => $item['size'],
                    'color_name' => $item['color'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'total' => ($item['price'] * $item['quantity']),
                ]);

                // Update stock
                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant) {
                        $variant->decrement('stock_in', $item['quantity']);
                    }
                } else {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock_in', $item['quantity']);
                        $product->increment('sold_count', $item['quantity']);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order completed successfully',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete order: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getOrCreateCart()
    {
        $sessionId = session()->getId();

        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => null
            ]
        );

        $cart->update([
            'is_pos' => 1
        ]);

        return $cart;
    }

    public function getCart()
    {
        $cart = $this->getOrCreateCart();

        $cart->load([
            'items.product',
            'items.variant.size',
            'items.variant.color'
        ]);

        $items = $cart->items->map(function ($item) {

            $size = $item->variant?->size?->name;
            $color = $item->variant?->color?->name;
            $sku = $item->product_variant_id ? $item->variant->sku : $item->product->sku;

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'sku' => $sku,
                'product_name' => $item->product->name ?? '',
                'product_image' => $item->product->thumbnail ?? asset('assets/images/default.png'),
                'variant_id' => $item->product_variant_id,
                'variant_name' => trim(($size ?? '') . ' - ' . ($color ?? ''), ' -') ?: 'Standard',
                'size' => $size,
                'color' => $color,
                'quantity' => (int) $item->quantity,
                'price' => (float) $item->unit_price,
                'stock' => (int) ($item->variant->stock ?? 999),
                'total_price' => (float) $item->total_price,
            ];
        });

        $subtotal = $items->sum('total_price');
        $discount = 0;
        $tax = 0;
        $total = $subtotal + $tax - $discount;

        return response()->json([
            'success' => true,
            'cart' => [
                'id' => $cart->id,

                'items' => $items->values(),

                // ✅ totals
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,

                // ✅ correct counts
                'items_count' => $items->count(),
                'total_items' => $items->sum('quantity'),
            ]
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $cart = $this->getOrCreateCart();

            $product = Product::with('variants')->findOrFail($request->product_id);

            // Check if product has variants and variant is required but not provided
            if ($product->variants->count() > 0 && !$request->product_variant_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select product options (size/color) before adding to cart',
                    'requires_variant' => true
                ], 400);
            }

            // Get variant if provided
            $variant = null;
            if ($request->product_variant_id) {
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where('id', $request->product_variant_id)
                    ->firstOrFail();

                // Check variant stock
                if ($variant->currentStock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected variant does not have enough stock'
                    ], 400);
                }
            } else {
                // Check product stock if no variant
                if ($product->currentStock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product does not have enough stock'
                    ], 400);
                }
            }

            // Check if item already exists in cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('product_variant_id', $request->product_variant_id)
                ->first();

            if ($cartItem) {
                // Update quantity if item exists
                $newQuantity = $cartItem->quantity + $request->quantity;

                // Check stock for updated quantity
                $availableStock = $variant ? $variant->currentStock : $product->currentStock;
                if ($newQuantity > $availableStock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add more items. Only {$availableStock} items available"
                    ], 400);
                }

                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                // Create new cart item
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'product_variant_id' => $request->product_variant_id,
                    'quantity' => $request->quantity,
                ]);
            }

            DB::commit();

            // Reload cart with items
            $cart->load(['items.product.images', 'items.variant.size', 'items.variant.color']);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart' => [
                    'items_count' => $cart->items_count,
                    'subtotal' => (float) $cart->subtotal,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $cart = $this->getOrCreateCart();
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('id', $itemId)
                ->firstOrFail();

            if ($cartItem->variant) {
                $availableStock = $cartItem->variant->currentStock;
            } else {
                $availableStock = $cartItem->product->currentStock;
            }

            if ($request->quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot update quantity. Only {$availableStock} items available"
                ], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Reload cart
            $cart->load('items');

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart' => [
                    'items_count' => $cart->items_count,
                    'subtotal' => (float) $cart->subtotal,
                ],
                'item' => [
                    'id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => (float) $cartItem->total_price,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem($itemId)
    {
        try {
            $cart = $this->getOrCreateCart();
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('id', $itemId)
                ->firstOrFail();

            $cartItem->delete();

            // Reload cart
            $cart->load('items');

            if ($cart->items->count() == 0) {
                $cart->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart' => [
                    'items_count' => $cart->items_count,
                    'subtotal' => (float) $cart->subtotal,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        try {
            $cart = $this->getOrCreateCart();
            $cart->items()->delete();
            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart: ' . $e->getMessage()
            ], 500);
        }
    }
}
