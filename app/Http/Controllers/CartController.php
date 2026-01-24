<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Get or create cart for the current user/session
     */
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            // For logged-in users
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );
        } else {
            // For guest users - use session
            $sessionId = session()->getId();

            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => null]
            );
        }

        return $cart;
    }

    /**
     * Get cart data with items
     */
    public function getCart()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.images', 'items.variant.size', 'items.variant.color']);

        $subtotal = (float) $cart->subtotal;
        $freeShippingThreshold = 1500;
        $shippingFee = $subtotal >= $freeShippingThreshold ? 0 : 60;
        $discount = 0;
        $tax = 0;
        $total = $subtotal + $shippingFee + $tax - $discount;

        return response()->json([
            'success' => true,
            'cart' => [
                'id' => $cart->id,
                'items' => $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_image' => $item->product->thumbnail,
                        'variant_id' => $item->product_variant_id,
                        'size' => $item->variant?->size?->name ?? 'N/A',
                        'color' => $item->variant?->color?->name ?? 'N/A',
                        'quantity' => $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'total_price' => (float) $item->total_price,
                    ];
                }),
                'subtotal' => $subtotal,
                'shipping' => $shippingFee,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'items_count' => $cart->items_count,
                'total_items' => $cart->total_items,
            ]
        ]);
    }

    /**
     * Add item to cart
     */
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
            $product = Product::findOrFail($request->product_id);

            // Get variant if provided
            $variant = null;
            if ($request->product_variant_id) {
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where('id', $request->product_variant_id)
                    ->firstOrFail();
            }

            // Check if item already exists in cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('product_variant_id', $request->product_variant_id)
                ->first();

            if ($cartItem) {
                // Update quantity if item exists
                $cartItem->quantity += $request->quantity;
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

    /**
     * Update cart item quantity
     */
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

            if($cart->items->count() == 0 ){
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
