<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Invoice') - {{ $siteName }}</title>

    @if($settings['site_favicon'])
    <link rel="icon" href="{{ storage_url($settings['site_favicon']) }}" type="image/x-icon">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
    <div id="invoiceTemplate" >
        <div class="invoice-container"
            style="width: 210mm; padding: 12mm 15mm; background: white; font-family: 'Arial', 'Helvetica', sans-serif; color: #000;">
            {{-- Invoice Header --}}
            <div
                style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 3px solid #000;">
                <div style="flex: 1;">
                    <h1 style="font-size: 28px; font-weight: 700; color: #000; margin: 0 0 3px 0; letter-spacing: -0.5px;">
                        SPINNER FASHION</h1>
                    <div style="width: 50px; height: 2px; background: #000; margin-bottom: 8px;"></div>
                    <p style="font-size: 9px; color: #444; margin: 0; line-height: 1.6;">
                        123 Fashion Street, Dhaka 1215, Bangladesh<br>
                        Phone: +880 1711-123456 | Email: info@spinnerfashion.com<br>
                        Web: www.spinnerfashion.com
                    </p>
                </div>
                <div style="text-align: right;">
                    <h2 style="font-size: 30px; font-weight: 700; color: #000; margin: 0 0 5px 0; letter-spacing: 2px;">
                        INVOICE</h2>
                    <table style="margin-left: auto; border-collapse: collapse; margin-top: 10px;">
                        <tr>
                            <td
                                style="padding: 3px 10px 3px 0; font-size: 9px; color: #666; text-align: right; font-weight: 600;">
                                Invoice No:</td>
                            <td style="padding: 3px 0; font-size: 9px; color: #000; font-weight: 700;">
                                {{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding: 3px 10px 3px 0; font-size: 9px; color: #666; text-align: right; font-weight: 600;">
                                Invoice Date:</td>
                            <td style="padding: 3px 0; font-size: 9px; color: #000; font-weight: 700;">
                                {{ $order->created_at->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding: 3px 10px 3px 0; font-size: 9px; color: #666; text-align: right; font-weight: 600;">
                                Order Status:</td>
                            <td style="padding: 3px 0; font-size: 9px; color: #000; font-weight: 700;">
                                {{ strtoupper($order->status->label()) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
    
            {{-- Bill To & Payment Info --}}
            <div style="display: flex; gap: 30px; margin-bottom: 20px;">
                <div style="flex: 1; border-left: 3px solid #000; padding-left: 10px;">
                    <h3
                        style="font-size: 9px; font-weight: 700; color: #000; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
                        BILL TO</h3>
                    <p style="font-size: 10px; color: #000; margin: 0; line-height: 1.7;">
                        <strong
                            style="font-size: 12px; display: block; margin-bottom: 5px; color: #000;">{{ $order->shipping_name }}</strong>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_district }}@if($order->shipping_postal_code) -
                        {{ $order->shipping_postal_code }}@endif<br>
                        <strong>Phone:</strong>
                        {{ $order->shipping_phone }}@if($order->shipping_email)<br><strong>Email:</strong>
                        {{ $order->shipping_email }}@endif
                    </p>
                </div>
                <div style="flex: 1; border-left: 3px solid #000; padding-left: 10px;">
                    <h3
                        style="font-size: 9px; font-weight: 700; color: #000; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
                        PAYMENT DETAILS</h3>
                    <table style="width: 100%; font-size: 10px; line-height: 1.7;">
                        <tr>
                            <td style="color: #666; padding: 1px 0; width: 45%;">Payment Method:</td>
                            <td style="color: #000; padding: 1px 0; font-weight: 600;">{{ $order->payment_method->label() }}
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #666; padding: 1px 0;">Payment Status:</td>
                            <td style="color: #000; padding: 1px 0; font-weight: 600;">
                                {{ strtoupper($order->payment_status->label()) }}</td>
                        </tr>
                        @if($order->transaction_id)
                            <tr>
                                <td style="color: #666; padding: 1px 0; vertical-align: top;">Transaction ID:</td>
                                <td
                                    style="color: #000; padding: 1px 0; font-family: 'Courier New', monospace; font-size: 8px; word-break: break-all; font-weight: 600;">
                                    {{ $order->transaction_id }}</td>
                            </tr>
                        @endif
                        @if($order->paid_at)
                            <tr>
                                <td style="color: #666; padding: 1px 0;">Paid Date:</td>
                                <td style="color: #000; padding: 1px 0; font-weight: 600;">
                                    {{ $order->paid_at->format('d M, Y') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
    
            {{-- Order Items Table --}}
            {{-- Order Items Table --}}
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <thead>
                    <tr style="background: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                        <th
                            style="padding: 10px 8px; text-align: left; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; border-right: 1px solid #333; width: 5%;">
                            SL</th>
                        <th
                            style="padding: 10px 8px; text-align: left; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; border-right: 1px solid #333;">
                            Product Description</th>
                        <th
                            style="padding: 10px 8px; text-align: center; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; border-right: 1px solid #333; width: 8%;">
                            Qty</th>
                        <th
                            style="padding: 10px 8px; text-align: right; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; border-right: 1px solid #333; width: 15%;">
                            Unit Price</th>
                        <th
                            style="padding: 10px 8px; text-align: right; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; width: 15%;">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td
                                style="padding: 10px 8px; font-size: 9px; color: #666; border-right: 1px solid #e5e7eb; text-align: center;">
                                {{ $index + 1 }}</td>
                            <td style="padding: 10px 8px; border-right: 1px solid #e5e7eb;">
                                <div style="font-size: 10px; font-weight: 600; color: #000; margin-bottom: 3px;">
                                    {{ $item->product_name }}</div>
                                @if($item->size_name || $item->color_name)
                                    <div style="font-size: 8px; color: #666;">
                                        @if($item->size_name)<span
                                            style="background: #f3f4f6; padding: 2px 5px; border-radius: 2px; margin-right: 3px;">Size:
                                        {{ $item->size_name }}</span>@endif
                                        @if($item->color_name)<span
                                            style="background: #f3f4f6; padding: 2px 5px; border-radius: 2px;">Color:
                                        {{ $item->color_name }}</span>@endif
                                    </div>
                                @endif
                            </td>
                            <td
                                style="padding: 10px 8px; text-align: center; font-size: 10px; color: #000; font-weight: 600; border-right: 1px solid #e5e7eb;">
                                {{ $item->quantity }}</td>
                            <td
                                style="padding: 10px 8px; text-align: right; font-size: 10px; color: #000; border-right: 1px solid #e5e7eb;">
                                {{ money($item->unit_price) }}</td>
                            <td style="padding: 10px 8px; text-align: right; font-size: 10px; color: #000; font-weight: 700;">
                                {{ money($item->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
            {{-- Totals Section --}}
            <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                <div style="width: 300px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td
                                style="padding: 8px 12px; font-size: 10px; color: #000; background: #f9fafb; font-weight: 600;">
                                Subtotal</td>
                            <td
                                style="padding: 8px 12px; text-align: right; font-size: 10px; color: #000; font-weight: 600; background: #fff;">
                                {{ money($order->subtotal) }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td
                                style="padding: 8px 12px; font-size: 10px; color: #000; background: #f9fafb; font-weight: 600;">
                                Shipping Charge<br><span
                                    style="font-size: 8px; color: #666; font-weight: 400;">{{ $order->delivery_zone ? '( '.$order->delivery_zone->label().' )' : '' }}</span>
                            </td>
                            <td
                                style="padding: 8px 12px; text-align: right; font-size: 10px; color: #000; font-weight: 600; background: #fff;">
                                {{ money($order->shipping_cost) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td
                                    style="padding: 8px 12px; font-size: 10px; color: #059669; background: #f9fafb; font-weight: 600;">
                                    Discount @if($order->coupon)<br><span
                                    style="font-size: 8px; font-weight: 400;">({{ $order->coupon->code }})</span>@endif</td>
                                <td
                                    style="padding: 8px 12px; text-align: right; font-size: 10px; color: #059669; font-weight: 600; background: #fff;">
                                    -{{ money($order->discount_amount) }}</td>
                            </tr>
                        @endif
                        <tr style="background: #000;">
                            <td
                                style="padding: 12px; font-size: 11px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">
                                Grand Total</td>
                            <td style="padding: 12px; text-align: right; font-size: 14px; font-weight: 700; color: #fff;">
                                {{ money($order->total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
    
            {{-- Shipping & Tracking Information --}}
            @if($order->tracking_number)
                <div style="margin-bottom: 15px; border-left: 3px solid #000; padding: 10px 0 10px 10px;">
                    <h3
                        style="font-size: 9px; font-weight: 700; color: #000; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
                        SHIPPING & TRACKING</h3>
                    <table style="width: 100%; font-size: 10px; line-height: 1.7;">
                        <tr>
                            <td style="padding: 1px 0; color: #666; width: 20%; font-weight: 600;">Courier Service:</td>
                            <td style="padding: 1px 0; color: #000; font-weight: 700;">{{ $order->courier }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px 0; color: #666; vertical-align: top; font-weight: 600;">Tracking Number:
                            </td>
                            <td
                                style="padding: 1px 0; color: #000; font-family: 'Courier New', monospace; font-size: 9px; font-weight: 700;">
                                {{ $order->tracking_number }}</td>
                        </tr>
                    </table>
                </div>
            @endif
    
            {{-- Customer Notes --}}
            @if($order->notes)
                <div
                    style="margin-bottom: 15px; border-left: 3px solid #f59e0b; padding: 10px 0 10px 10px; background: #fffbeb; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <h3
                        style="font-size: 9px; font-weight: 700; color: #000; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
                        CUSTOMER NOTES</h3>
                    <p style="margin: 0; font-size: 10px; color: #000; line-height: 1.6;">{{ $order->notes }}</p>
                </div>
            @endif
    
            {{-- Terms & Footer --}}
            <div style="border-top: 2px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
                <h3
                    style="font-size: 9px; font-weight: 700; color: #000; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
                    TERMS & CONDITIONS</h3>
                <p style="margin: 0 0 12px 0; font-size: 8px; color: #666; line-height: 1.6;">
                    • Payment is due within 15 days from the invoice date. Please include the invoice number with your
                    payment.<br>
                    • All sales are final. Returns or exchanges are only accepted for defective products within 7 days of
                    delivery.<br>
                    • For any queries regarding this invoice, please contact us at +880 1711-123456 or
                    info@spinnerfashion.com
                </p>
                <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 12px;">
                    <p style="margin: 0 0 4px 0; font-size: 10px; color: #000; font-weight: 700;">Thank you for shopping
                        with Spinner Fashion!</p>
                    <p style="margin: 0; font-size: 8px; color: #666;">
                        For support & inquiries: +880 1711-123456 | info@spinnerfashion.com | www.spinnerfashion.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>