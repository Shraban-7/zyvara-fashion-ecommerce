<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Setting;
use App\Models\District;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Enums\DeliveryZone;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\ShippingZone;
use App\Services\CouponService;
use Illuminate\Http\Request;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function __construct(
        protected CouponService $couponService
    ) {
    }

    /**
     * Load the current user/session cart with items.
     */
    protected function getOrCreateCart()
    {
        return Auth::check()
            ? Cart::with(['items.product', 'items.variant.size', 'items.variant.color'])
                ->where('user_id', Auth::id())
                ->first()
            : Cart::with(['items.product', 'items.variant.size', 'items.variant.color'])
                ->where('session_id', session()->getId())
                ->first();
    }

    public function index()
    {
        $cart = Auth::check()
            ? Cart::with(['items.product.images', 'items.variant.size', 'items.variant.color'])
            ->where('user_id', Auth::id())
            ->first()
            : Cart::with(['items.product.images', 'items.variant.size', 'items.variant.color'])
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            toast_warning('Your cart is empty. Add some items first.');
            return redirect()->route('home');
        }

        $shippingZones = ShippingZone::active()->orderBy('shipping_cost')->get();

        $districts = District::with('shippingZone')
            ->active()
            ->ordered()
            ->get();

        $user = Auth::user();

        $savedAddresses = $user
            ? Address::where('user_id', $user->id)->get()
            : collect();

        $bkashNumber = Setting::get('bkash_merchant_number');
        $nagadNumber = Setting::get('nagad_merchant_number');

        return view('checkout', compact('cart', 'shippingZones', 'districts', 'user', 'savedAddresses', 'bkashNumber', 'nagadNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^01[3-9][0-9]{8}$/',
            'delivery_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'district' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,online',
            'notes' => 'nullable|string|max:500',
            'coupon' => 'nullable|string|max:50',
        ]);

        try {
            $cart = Auth::check()
                ? Cart::with(['items.product', 'items.variant.size', 'items.variant.color'])
                ->where('user_id', Auth::id())
                ->first()
                : Cart::with(['items.product', 'items.variant.size', 'items.variant.color'])
                ->where('session_id', session()->getId())
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                toast_error('Your cart is empty. Please add items before checkout.');
                return redirect()->route('home');
            }

            foreach($cart->items as $item) {
                $product = $item->product;
                if ($item->variant) {
                    $variant = $item->variant;
                    if ($variant->currentStock < $item->quantity) {
                        toast_error("Insufficient stock for {$product->name} ({$variant->size?->name}, {$variant->color?->name}). Available: " . $variant->currentStock);
                        return back()->withInput();
                    }
                } else {
                    if ($product->currentStock < $item->quantity) {
                        toast_error("Insufficient stock for {$product->name}. Available: " . $product->currentStock);
                        return back()->withInput();
                    }
                }
            }

            $shippingZone = ShippingZone::where('code', $validated['delivery_zone'])->first();
            if (!$shippingZone) {
                toast_error('Invalid delivery zone selected.');
                return back()->withInput();
            }

            $subtotal = $cart->subtotal;
            $shippingCost = $shippingZone->shipping_cost;
            $discountAmount = 0;
            $couponId = null;
            $appliedCoupon = null;

            // Re-validate coupon server-side (do not trust session/frontend state alone)
            $sessionCode = session('applied_coupon_code', $validated['coupon'] ?? null);

            if (!empty($sessionCode)) {
                $result = $this->couponService->apply($sessionCode, $cart, Auth::user());

                if ($result->success) {
                    $discountAmount = $result->discount;
                    $appliedCoupon = $result->coupon;
                    $couponId = $appliedCoupon->id;
                } else {
                    // Coupon became invalid between cart and checkout
                    session()->forget(['applied_coupon_code', 'applied_coupon_discount']);
                    toast_warning($result->error ?? 'Coupon code is invalid or expired.');

                    return back()->withInput();
                }
            }

            $total = $subtotal + $shippingCost - $discountAmount;

            DB::beginTransaction();

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'coupon_id' => $couponId,
                'status' => OrderStatus::PENDING,
                'payment_method' => PaymentMethod::from($validated['payment_method']),
                'payment_status' => PaymentStatus::PENDING,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'tax_amount' => 0,
                'total' => $total,
                'shipping_name' => $validated['name'],
                'shipping_phone' => $validated['phone'],
                'shipping_district' => $validated['district'],
                'shipping_city' => $validated['city'],
                'shipping_address' => $validated['address'],
                'delivery_zone' => DeliveryZone::from($validated['delivery_zone']),
                'notes' => $validated['notes'],
            ]);

            foreach ($cart->items as $cartItem) {
                $unitPrice = $cartItem->product->price;
                if ($cartItem->variant && $cartItem->variant->price != null) {
                    $unitPrice = $cartItem->variant->price;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku ?? '',
                    'product_image' => $cartItem->product->thumbnail,
                    'size_name' => $cartItem->variant?->size?->name ?? null,
                    'color_name' => $cartItem->variant?->color?->name ?? null,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $cartItem->quantity * $unitPrice,
                    'total' => $cartItem->quantity * $unitPrice,
                ]);

                if ($cartItem->variant) {
                    $cartItem->variant->increment('stock_out', $cartItem->quantity);
                } else {
                    $cartItem->product->increment('stock_out', $cartItem->quantity);
                }
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatus::PENDING,
                'comment' => 'Order placed successfully',
                'updated_by' => Auth::id(),
            ]);

            if ($couponId && $appliedCoupon) {
                // Race-safe: re-check usage limit inside the transaction
                $appliedCoupon->refresh();
                if ($appliedCoupon->usage_limit !== null && $appliedCoupon->used_count >= $appliedCoupon->usage_limit) {
                    throw new \Exception('This coupon has just reached its usage limit. Please remove it and try again.');
                }

                $appliedCoupon->increment('used_count');
                $this->couponService->recordUsage($appliedCoupon, Auth::user(), $order, $discountAmount);
            }

            session()->forget(['applied_coupon_code', 'applied_coupon_discount']);

            $cart->items()->delete();
            $cart->delete();

            if ($request->payment_method == PaymentMethod::ONLINE->value) {
                $paymentData = $this->preparePaymentData($order);
                $paymentGatewayResponse = Http::post(env('SLASHPAY_PAYMENT_URL'), $paymentData);
                $jsonResponse = $paymentGatewayResponse->json();
                if ($paymentGatewayResponse->successful()) {
                    $order->payment_id = $jsonResponse['payment_id'];
                    $order->save();

                    DB::commit();

                    return redirect()->away($jsonResponse['payment_url']);
                } else {
                    DB::rollBack();
                    Log::error('Checkout error: Payment gateway response unsuccessful', [
                        'order_id' => $order->id,
                        'response' => $jsonResponse,
                        'paymentData' => $paymentData,
                    ]);
                    toast_error('Payment gateway error. Please try again.');
                    return back()->withInput();
                }
            } else {
                DB::commit();
            }

            session(['last_order_id' => $order->id]);

            toast_success('Order placed successfully! Thank you for shopping with us.');
            return redirect()->route('track-order.index', ['order_number' => $order->order_number]);
            //return redirect()->route('checkout.success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            toast_error($e->getMessage());
            return back()->withInput();
        }
    }

    public function success()
    {
        $orderId = session('last_order_id');

        if (!$orderId) {
            toast_warning('No order found.');
            return redirect()->route('home');
        }

        $order = Order::with(['items.product', 'items.variant'])
            ->find($orderId);

        if (!$order) {
            toast_error('Order not found.');
            return redirect()->route('home');
        }

        session()->forget('last_order_id');

        return view('checkout-success', compact('order'));
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon' => 'required|string|max:50',
        ]);

        $cart = $this->getOrCreateCart();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'error_code' => 'empty_cart',
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $result = $this->couponService->apply($request->coupon, $cart, Auth::user());

        if (! $result->success) {
            session()->forget(['applied_coupon_code', 'applied_coupon_discount']);

            return response()->json([
                'success' => false,
                'error_code' => $result->errorCode,
                'message' => $result->error,
            ], 422);
        }

        // Persist applied coupon in session until checkout completes or is removed
        session([
            'applied_coupon_code' => $result->coupon->code,
            'applied_coupon_discount' => $result->discount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $result->discount,
            'discount_formatted' => money($result->discount),
            'coupon_type' => $result->coupon->type->value,
            'coupon_value' => $result->coupon->value,
            'coupon_code' => $result->coupon->code,
        ]);
    }

    public function payNow(Order $order)
    {
        if ($order->payment_status != PaymentStatus::PENDING) {
            toast_warning('This order has already been paid or is not eligible for payment.');
            return redirect()->route('orders.show', $order);
        }

        $paymentGatewayResponse = Http::post(env('SLASHPAY_PAYMENT_URL'), $this->preparePaymentData($order));

        $jsonResponse = $paymentGatewayResponse->json();

        if (!$paymentGatewayResponse->successful()) {
            toast_error('Failed to initiate payment. Please try again later.');
            return redirect()->route('orders.show', $order);
        }

        $order->payment_id = $jsonResponse['payment_id'] ?? null;
        $order->save();

        return redirect()->away($jsonResponse['payment_url']);
    }

    private function preparePaymentData(Order $order): array
    {
        return [
            'api_key' => env('SLASHPAY_API_KEY'),
            'order_id' => (string) $order->id,
            'amount' => $order->total,
            'cus_name' => $order->shipping_name,
            'cus_email_mobile' => $order->shipping_phone,
            'ipn_url' => route('payment.ipn'),
            'cancel_url' => route('payment.cancelled'),
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.failed'),
            'currency' => 'BDT',
        ];
    }
}
