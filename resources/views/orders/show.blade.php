@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 md:py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Back Button & Header -->
            <div class="mb-8">

                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center gap-2 text-primary hover:text-blue-700 font-medium mb-4">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Order Number</p>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                            #{{ $order->order_number }}
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">

                        <!-- Status -->
                        <div class="flex items-center gap-2 bg-{{ $order->status->color() }}-100 px-4 py-3 rounded-full">
                            <div class="w-3 h-3 rounded-full animate-pulse" style="background-color: currentColor;"></div>
                            <span class="text-sm font-bold text-{{ $order->status->color() }}-600">
                                {{ $order->status->label() }}
                            </span>
                        </div>

                        <!-- Download Invoice Button -->
                        <button onclick="printReceipt('{{ route('orders.invoice', $order->order_number) }}')"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-full bg-gray-900 text-white hover:bg-gray-800 transition">
                            <i class="fas fa-file-download text-sm"></i>
                            <span class="text-sm font-semibold">Invoice</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-6">
                <!-- Order Date & Info -->
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-gray-200">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Order Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->created_at->format('F d, Y h:i a') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estimated Delivery</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $order->updated_at->addDays(5)->format('F d, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Customer & Delivery Info -->
                <div class="grid md:grid-cols-2 gap-6 py-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <i class="fas fa-user text-primary text-lg"></i>
                            Customer Information
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-500 mb-1">Full Name</p>
                                <p class="font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Email</p>
                                <p class="font-medium text-gray-900">{{ $order->user->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Phone</p>
                                <p class="font-medium text-gray-900">{{ $order->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <i class="fas fa-map-marker-alt text-primary text-lg"></i>
                            Delivery Address
                        </h3>
                        <div class="space-y-3 text-sm text-gray-900">
                            <div>
                                <p class="font-medium">
                                    {{ $order->shipping_address ?? $order->user->address->address_line1 ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <span
                                    class="font-medium">{{ $order->shipping_city ?? $order->user->address->city ?? 'N/A' }},</span>
                                <span
                                    class="font-medium">{{ $order?->district?->name ?? $order->user->address->district ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="py-6 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-box text-primary text-lg"></i>
                        Order Items ({{ $order->items ? count($order->items) : 0 }})
                    </h3>
                    <div class="space-y-4">
                        @if($order->items && count($order->items) > 0)
                            @foreach($order->items as $item)
                                <div class="flex gap-3 p-3 bg-gray-50 rounded-lg relative">
                                    <!-- Refund Badge -->
                                    @if(!empty($item->return_item_id))
                                        <span
                                            class="absolute top-2 right-2 text-[10px] font-semibold px-2 py-1 rounded-full bg-red-100 text-red-600 border border-red-200">
                                            Returned
                                        </span>
                                    @endif

                                    <!-- Product Image -->
                                    <div class="w-20 h-24 shrink-0 rounded-lg overflow-hidden bg-white">
                                        <img src="{{ $item->product_image }}" 
                                            alt="{{ $item->product_name }}" 
                                            class="w-full h-full object-cover">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">

                                        <h4 class="font-semibold text-sm text-gray-900 leading-5 break-words">
                                            {{ $item->product_name }}
                                        </h4>

                                        @if($item->size_name || $item->color_name)
                                            <p class="text-sm text-gray-500 mb-2">
                                                @if($item->size_name)Size: {{ $item->size_name }}@endif
                                                @if($item->size_name && $item->color_name) | @endif
                                                @if($item->color_name)Color: {{ $item->color_name }}@endif
                                            </p>
                                        @endif

                                        <!-- Qty & Price -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mt-2">
                                            <span class="text-sm text-gray-600">
                                                Qty: {{ $item->quantity }} × {{ money($item->unit_price) }}
                                            </span>

                                            <span class="text-sm font-bold text-primary">
                                                {{ money($item->total) }}
                                            </span>
                                        </div>

                                        @if($order->status->value === 'delivered')
                                            <div class="mt-3">
                                                <button type="button"
                                                    onclick="openReviewModal({{ $item->product_id }}, '{{ addslashes($item->product_name) }}')"
                                                    class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium bg-primary text-white rounded-lg hover:opacity-90 transition">
                                                    <i class="fas fa-star"></i>
                                                    Write Review
                                                </button>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center py-4">No items found for this order</p>
                        @endif
                    </div>
                </div>

                <!-- Payment & Price Summary -->
                <div class="py-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Payment Info -->
                        <div>
                            <h3
                                class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                                <i class="fas fa-credit-card text-primary text-lg"></i>
                                Payment Information
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-gray-500 mb-1">Payment Method</p>
                                    <span
                                        class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-semibold">
                                        {{ $order->payment_method->label() }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Payment Status</p>
                                    @php
                                        $paymentStatusColor = $order->payment_status->isPending() ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700';
                                    @endphp
                                    <span
                                        class="inline-block {{ $paymentStatusColor }} px-3 py-1 rounded-lg text-xs font-semibold">
                                        {{ $order->payment_status->label() }}
                                    </span>
                                    @if($order->payment_status->isPending() && $order->payment_method->isOnline())
                                        <form action="{{ route('orders.payNow', $order->order_number) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                                                Pay Now
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                            <h3 class="text-sm font-bold text-gray-900 mb-4">Order Summary</h3>

                            <div class="space-y-2 text-sm">

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">{{ money($order->subtotal) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900">{{ money($order->shipping_cost ?? 0) }}</span>
                                </div>

                                @if($order->discount_amount && $order->discount_amount > 0)
                                    <div class="flex justify-between text-green-600">
                                        <span>Discount</span>
                                        <span class="font-medium">{{ money($order->discount_amount) }}</span>
                                    </div>
                                @endif


                                {{-- Refund Summary --}}
                                @if($totalRefund > 0)
                                    <div class="flex justify-between text-red-600">
                                        <span>Total Refund</span>
                                        <span class="font-medium">-{{ money($totalRefund) }}</span>
                                    </div>

                                    {{-- Method-wise breakdown --}}
                                    <div class="pt-1 space-y-1">
                                        @foreach($refunds as $method => $amount)
                                            <div class="flex justify-between text-xs text-gray-500 pl-2">
                                                <span class="capitalize">{{ $method }}</span>
                                                <span>-{{ money($amount) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif


                                <div class="h-px bg-gray-300 my-3"></div>


                                <div class="flex justify-between text-lg">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-primary">{{ money($order->total) }}</span>
                                </div>


                                {{-- Net Paid --}}
                                @if($totalRefund > 0)
                                    <div class="flex justify-between text-sm text-gray-700 mt-2">
                                        <span>Net Paid</span>
                                        <span class="font-semibold">
                                            {{ money($order->total - $totalRefund) }}
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-clock text-primary"></i>
                    Order Timeline
                </h2>
                <div class="space-y-4">
                    @forelse($order->statusHistories as $history)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                                    <i class="fas fa-circle text-blue-600 text-xs"></i>
                                </div>
                                @if(!$loop->last)
                                    <div class="flex-1 w-0.5 bg-gray-200 my-1"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="flex items-start justify-between gap-4 mb-1">
                                    <span class="font-semibold text-gray-900">{{ $history->status->label() }}</span>
                                    <span class="text-sm text-gray-500">{{ $history->created_at->diffForHumans() }}</span>
                                </div>
                                @if($history->comment)
                                    <p class="text-sm text-gray-600 mb-1">{{ $history->comment }}</p>
                                @endif
                                @if($history->updater)
                                    <p class="text-xs text-gray-500">By: {{ $history->updater->name }}</p>
                                @endif
                                <p class="text-xs text-gray-400">{{ $history->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No status history available</p>
                    @endforelse
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('orders.index') }}"
                    class="flex items-center justify-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-semibold hover:bg-blue-700 transition shadow-lg">
                    <i class="fas fa-arrow-left"></i>
                    Back to Orders
                </a>
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center gap-2 px-8 py-3 bg-white text-primary border-2 border-primary rounded-xl font-semibold hover:bg-blue-50 transition">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
            </div>

            <!-- Support Section -->
            <div class="text-center mt-8 text-sm bg-blue-50 rounded-xl p-4">
                <p class="text-gray-600 mb-2">Have questions about your order?</p>
                <a href="mailto:support@example.com" class="text-primary hover:underline font-semibold">Contact our support
                    team</a>
            </div>
        </div>
    </div>


    <div id="reviewModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-pencil text-amber-500 text-sm"></i>
                    </div>
                    <h3 class="font-medium text-gray-900 text-base">Write a review</h3>
                </div>
                <button type="button" onclick="closeReviewModal()"
                    class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-gray-50">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form action="{{ route('review.store') }}" method="POST" class="p-5">
                @csrf
                <input type="hidden" name="product_id" id="review_product_id">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="rating" id="ratingVal" value="0">

                {{-- Product pill --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 mb-5">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                        <i class="fas fa-box text-blue-400"></i>
                    </div>
                    <div>
                        <p id="review_product_name" class="text-sm font-medium text-gray-800"></p>
                        <p class="text-xs text-gray-400">Purchased item</p>
                    </div>
                </div>

                {{-- Star rating --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Your rating</label>
                    <div class="flex items-center gap-1.5" id="starGroup">
                        @for ($i = 1; $i <= 5; $i++)
                            <span data-val="{{ $i }}" class="star text-4xl cursor-pointer select-none text-gray-200
                                              transition-transform hover:scale-110">☆</span>
                        @endfor
                        <span id="ratingLabel" class="text-xs text-gray-400 ml-2"></span>
                    </div>
                </div>

                {{-- Review text --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Your review</label>
                    <textarea name="comment" rows="4" placeholder="Share your experience with this product…" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none
                      focus:ring-2 focus:ring-gray-300 resize-none text-gray-800 placeholder-gray-300">
                    </textarea>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-3 rounded-xl text-sm font-medium flex items-center justify-center gap-2 hover:bg-primary-800 transition-colors">
                    <i class="fas fa-paper-plane text-xs"></i>
                    Submit review
                </button>

                <p class="text-center text-xs text-gray-300 mt-3">Your review helps other shoppers make better decisions.
                </p>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function printReceipt(url) {
                let printWindow = window.open(url, '_blank', 'width=800,height=600');

                printWindow.onload = function () {
                    printWindow.focus();
                    printWindow.print();

                    printWindow.onafterprint = function () {
                        printWindow.close();
                    };
                };
            }

            const labels = ['', 'Terrible', 'Poor', 'Okay', 'Good', 'Excellent'];
            const stars = document.querySelectorAll('#starGroup .star');
            let selected = 0;

            function paintStars(upTo) {
                stars.forEach((s, i) => {
                    s.textContent = i < upTo ? '★' : '☆';
                    s.classList.toggle('text-amber-400', i < upTo);
                    s.classList.toggle('text-gray-200', i >= upTo);
                });
                document.getElementById('ratingLabel').textContent = labels[upTo] ?? '';
            }

            stars.forEach(s => {
                s.addEventListener('mouseenter', () => paintStars(+s.dataset.val));
                s.addEventListener('mouseleave', () => paintStars(selected));
                s.addEventListener('click', () => {
                    selected = +s.dataset.val;
                    document.getElementById('ratingVal').value = selected;
                    paintStars(selected);
                });
            });

            function openReviewModal(productId, productName) {
                document.getElementById('review_product_id').value = productId;
                document.getElementById('review_product_name').textContent = productName;
                selected = 0;
                paintStars(0);
                document.getElementById('reviewModal').classList.replace('hidden', 'flex');
            }

            function closeReviewModal() {
                document.getElementById('reviewModal').classList.replace('flex', 'hidden');
            }
        </script>
    @endpush
@endsection