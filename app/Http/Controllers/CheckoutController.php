<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        return view('checkout');
    }

    /**
     * Process the checkout/order placement.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^1[3-9][0-9]{8}$/',
            'email' => 'nullable|email|max:255',
            'delivery_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'district' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bkash,nagad',
            'bkash_trx_id' => 'required_if:payment_method,bkash|nullable|string|max:50',
            'nagad_trx_id' => 'required_if:payment_method,nagad|nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
            'coupon' => 'nullable|string|max:50',
        ]);

        // Here you would:
        // 1. Create the order in database
        // 2. Associate cart items with the order
        // 3. Send confirmation SMS/email
        // 4. Clear the cart
        // 5. Redirect to order confirmation page

        // For now, redirect to a success page (you can create this later)
        return redirect()->route('checkout.success');
    }

    /**
     * Display order success page.
     */
    public function success()
    {
        return view('checkout-success');
    }
}
