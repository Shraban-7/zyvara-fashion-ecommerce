<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->invoice_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="bg-secondary-100 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white min-h-screen relative p-10">

        {{-- Header --}}
        <header class="mb-6">
            <div class="flex justify-between items-start pb-4 border-b-4 border-secondary-200">

                {{-- Left: Logo & Seller Info --}}
                <div class="flex-1">
                    <h1 style="font-size: 28px; font-weight: 700; color: #000; margin: 0 0 3px 0; letter-spacing: -0.5px;">
                        {{ $siteName }}
                    </h1>
                    <div class="w-12 h-0.5 bg-black mb-2"></div>
                    <p class="text-xs text-secondary-500 leading-relaxed">
                        {{ settings('contact_address') }}<br>
                        Phone: {{ settings('contact_phone') }} | Email: {{ settings('contact_email') }}<br>
                        Web: {{ preg_replace('#^https?://#', '', config('app.url')) }}
                    </p>

                </div>

                {{-- Right: Invoice Info --}}
                <div class="text-right">
                    <h2 class="text-3xl font-bold tracking-widest text-secondary-700 mb-3">INVOICE</h2>

                    <table class="ml-auto border border-gray-400 text-xs mt-2">
                        <tr>
                            <td class="border px-2 py-1 text-secondary-500 font-semibold text-right">Date:</td>
                            <td class="border px-2 py-1 text-black font-bold">
                                {{ $order->created_at->format('d M, Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-secondary-500 font-semibold text-right">Order No:</td>
                            <td class="border px-2 py-1 text-black font-bold">
                                {{ $order->order_number }}
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </header>

        <main>

            {{-- Invoiced To --}}
            <div class="flex gap-8 mb-5">
                <div class="flex-1 border-l-4 border-gray-400 pl-3">
                    <h3 class="text-xs font-bold text-secondary-700 uppercase tracking-widest mb-1">Invoiced To</h3>
                    <address class="not-italic text-sm text-secondary-800 leading-relaxed">
                        <strong class="text-base block mb-1">
                            {{ $order->customer->name ?? $order->shipping_name ?? '' }}
                        </strong>
                        {{ $order->customer->phone ?? $order->shipping_phone ?? '' }}<br>
                        {{ $order->shipping_address ?? '' }}
                    </address>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="overflow-x-auto mb-4">
                <table class="w-full border border-secondary-300 border-collapse text-sm">
                    <thead>
                        <tr class="bg-secondary-100 text-secondary-700">
                            <th class="p-2 text-left text-xs font-semibold uppercase border-r border-secondary-300">
                                Item
                            </th>
                            <th class="p-2 text-center text-xs font-semibold uppercase border-r border-secondary-300 w-32">
                                Rate
                            </th>
                            <th class="p-2 text-center text-xs font-semibold uppercase border-r border-secondary-300 w-16">
                                QTY
                            </th>
                            <th class="p-2 text-right text-xs font-semibold uppercase w-28">
                                Amount
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-secondary-200">
                                <td class="p-2 border-r border-secondary-200">
                                    <div class="text-sm font-semibold text-secondary-800">
                                        {{ $item->product_name }}
                                    </div>

                                    @if ($item->size_name || $item->color_name)
                                        <div class="text-secondary-500 text-[11px] mt-1">
                                            @if ($item->size_name)
                                                <span class="mr-2">Size: {{ $item->size_name }}</span>
                                            @endif
                                            @if ($item->color_name)
                                                <span>Color: {{ $item->color_name }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="p-2 text-center border-r border-secondary-200">
                                    @if($item->product->compare_price)
                                        <span class="line-through text-secondary-400 text-xs block">
                                            {{ money($item->product->compare_price) }}
                                        </span>
                                    @endif
                                    <span class="text-sm font-semibold text-secondary-800">
                                        {{ money($item->unit_price) }}
                                    </span>
                                </td>

                                <td class="p-2 text-center text-sm font-semibold text-secondary-800 border-r border-secondary-200">
                                    {{ $item->quantity }}
                                </td>

                                <td class="p-2 text-right">
                                    @if($item->product->compare_price)
                                        <span class="line-through text-secondary-400 text-xs block">
                                            {{ money($item->product->compare_price * $item->quantity) }}
                                        </span>
                                    @elseif($item->subtotal!=$item->total)
                                        <span class="line-through text-secondary-400 text-xs block">
                                            {{ money($item->subtotal) }}
                                        </span>
                                    @endif

                                    <span class="text-sm font-bold text-secondary-400">
                                        {{ money($item->total) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary + Totals --}}
            <div class="flex gap-6 mb-5">

                {{-- Summary --}}
                <div class="flex-1 text-sm text-secondary-700">
                    <p class="mb-1"><strong>Total Qty:</strong> {{ $order->items->sum('quantity') }}</p>
                    <p class="mb-1"><strong>In Words:</strong> {{ convert_number_to_words_bdt($order->total) }} Taka
                        only.</p>
                    <p><strong>Payment Term:</strong> {{ strtoupper($order->payment_status->label()) }}</p>
                </div>

                {{-- Totals --}}
                <div class="w-72">
                    <table class="w-full border border-secondary-300 border-collapse text-sm">

                        <tr class="border-b border-secondary-200">
                            <td class="py-2 px-3 bg-secondary-50 text-secondary-700 font-semibold text-xs uppercase">
                                Subtotal
                            </td>
                            <td class="py-2 px-3 text-right font-semibold text-secondary-800">
                                {{ money($order->subtotal) }}
                            </td>
                        </tr>

                        <tr class="border-b border-secondary-200">
                            <td class="py-2 px-3 bg-secondary-50 text-secondary-700 font-semibold text-xs uppercase">
                                Shipping Fee
                            </td>
                            <td class="py-2 px-3 text-right font-semibold text-secondary-800">
                                {{ money($order->shipping_cost) }}
                            </td>
                        </tr>

                        @if($order->discount_amount)
                            <tr class="border-b border-secondary-200">
                                <td class="py-2 px-3 bg-secondary-50 text-secondary-800 font-semibold text-xs uppercase">
                                    Discount
                                </td>
                                <td class="py-2 px-3 text-right font-semibold text-secondary-800">
                                    {{ money($order->discount_amount) }}
                                </td>
                            </tr>
                        @endif

                        <tr class="border-b border-secondary-200">
                            <td class="py-2 px-3 bg-secondary-50 text-secondary-700 font-semibold text-xs uppercase">
                                Total
                            </td>
                            <td class="py-2 px-3 text-right font-semibold text-secondary-800">
                                {{ money($order->payable) }}
                            </td>
                        </tr>

                        @if($order->due > 0)
                            <tr class="border-b border-secondary-200">
                                <td class="py-2 px-3 bg-secondary-50 text-secondary-700 font-semibold text-xs uppercase">
                                    Paid
                                </td>
                                <td class="py-2 px-3 text-right font-semibold text-secondary-800">
                                    {{ money($order->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="py-2 px-3 bg-secondary-50 text-danger font-bold text-xs uppercase">
                                    Due
                                </td>
                                <td class="py-2 px-3 text-right font-bold text-danger">
                                    {{ money($order->due) }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="py-2 px-3 bg-secondary-50 text-secondary-700 font-semibold text-xs uppercase">
                                    Paid
                                </td>
                                <td class="py-2 px-3 text-right font-bold text-secondary-800">
                                    {{ money($order->paid) }}
                                </td>
                            </tr>
                        @endif

                    </table>
                </div>

            </div>

        </main>
    </div>

    {{--
    <script>
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    </script> --}}
</body>

</html>