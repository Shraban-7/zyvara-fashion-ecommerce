<?php

namespace App\Services;

use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;



class SteadfastService
{
    public function createConsignment($order)
    {
        $steadFast = SteadfastCourier::placeOrder([
            'invoice' => $order->order_number,
            'recipient_name' => $order->shipping_name,
            'recipient_phone' => $this->formatPhone($order->shipping_phone),
            'recipient_address' => $order->shipping_address,
            'cod_amount' => $order->total,
            'note' => 'Order #' . $order->order_number,
        ]);

        return $steadFast;
    }



    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '880')) {
            $phone = '0' . substr($phone, 3);
        }

        return $phone;
    }
}