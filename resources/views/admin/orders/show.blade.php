@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-xl hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        @if ($order->status->value !== 'draft')
            @if ($order->is_pos)
                <button onclick="printReceipt('{{ route('admin.pos.receipt', $order->order_number) }}')" class="px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span>Print Receipt</span>
            </button>
            @endif
            <button onclick="printReceipt('{{ route('admin.orders.invoice', $order->order_number) }}')" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span>Download Invoice</span>
            </button>
            <button onclick="openReturnModal()" 
                class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition flex items-center gap-2">
                <i class="fas fa-undo"></i>
                <span>Sale Return</span>
            </button> 
        @endif
        @if($order->status->value !== 'cancelled')
        <button onclick="openDeleteModal()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center gap-2">
            <i class="fas fa-trash"></i>
            <span>Delete</span>
        </button>
        @endif
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Left Column - Order Details --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Order Status --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                Order Status
            </h2>

            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status->value === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="shipped" {{ $order->status->value === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status->value === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                        <input type="text" name="comment" placeholder="Add a note..." class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Update Status
                </button>
            </form>
        </div>

        {{-- Order Items --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-box text-blue-600"></i>
                Order Items ({{ $order->items->count() }})
            </h2>

            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl relative">

                    <!-- RETURNED BADGE -->
                    @if(!empty($item->return_item_id))
                        <span class="absolute top-2 right-2 text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full font-semibold">
                            Returned
                        </span>
                    @endif

                    <div class="w-20 h-24 shrink-0 rounded-lg overflow-hidden bg-white">
                        <img src="{{ $item->product_image }}" 
                            alt="{{ $item->product_name }}" 
                            class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">

                        <h4 class="font-semibold text-gray-900 mb-1">
                            {{ $item->product_name }}
                        </h4>

                        @if($item->size_name || $item->color_name)
                        <p class="text-sm text-gray-500 mb-2">
                            @if($item->size_name)Size: {{ $item->size_name }}@endif
                            @if($item->size_name && $item->color_name) | @endif
                            @if($item->color_name)Color: {{ $item->color_name }}@endif
                        </p>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                Qty: {{ $item->quantity }} × {{ money($item->unit_price) }}
                            </span>

                            <span class="text-base font-bold text-gray-900">
                                {{ money($item->total) }}
                            </span>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-900">{{ money($order->subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Shipping Cost</span>
                    <span class="font-medium text-gray-900">{{ money($order->shipping_cost) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm text-green-600">
                    <span>Discount @if($order->coupon)({{ $order->coupon->code }})@endif</span>
                    <span class="font-medium">-{{ money($order->discount_amount) }}</span>
                </div>
                @endif
                <div class="h-px bg-gray-200"></div>
                <div class="flex justify-between text-lg">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-blue-600">{{ money($order->total) }}</span>
                </div>
            </div>
        </div>

        {{-- Status History --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-history text-blue-600"></i>
                Status History
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
    </div>

    {{-- Right Column - Customer & Shipping Info --}}
    <div class="space-y-6">
        {{-- Customer Information --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-600"></i>
                Customer Information
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Name</p>
                    <p class="font-semibold text-gray-900">{{ $order->shipping_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Phone</p>
                    <a href="tel:{{ $order->shipping_phone }}" class="font-semibold text-blue-600 hover:text-blue-800">
                        {{ $order->shipping_phone }}
                    </a>
                </div>
                @if($order->shipping_email)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Email</p>
                    <a href="mailto:{{ $order->shipping_email }}" class="font-semibold text-blue-600 hover:text-blue-800">
                        {{ $order->shipping_email }}
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-blue-600"></i>
                Shipping Address
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_district }}</p>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                        <i class="fas fa-truck"></i>
                        {{ $order->delivery_zone ? $order->delivery_zone->label() : '' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-credit-card text-blue-600"></i>
                Payment Information
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Payment Method</p>
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 rounded-full text-sm font-medium">
                        {{ $order->payment_method->label() }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Payment Status</p>
                    @if($order->payment_status->value === 'paid')
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle"></i>
                        Paid
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                        <i class="fas fa-clock"></i>
                        {{ $order->payment_status->label() }}
                    </span>
                    @endif
                </div>
                @if($order->transaction_id)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Transaction ID</p>
                    <code class="block bg-gray-50 px-3 py-2 rounded-lg text-sm font-mono">{{ $order->transaction_id }}</code>
                </div>
                @endif
                @if($order->paid_at)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Paid At</p>
                    <p class="text-sm text-gray-900">{{ $order->paid_at->format('M d, Y h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tracking Information --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-shipping-fast text-blue-600"></i>
                Tracking Information
            </h2>

            @if($order->tracking_number)
            <div class="space-y-3 mb-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Courier</p>
                    <p class="font-semibold text-gray-900">{{ $order->courier }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tracking Number</p>
                    <code class="block bg-gray-50 px-3 py-2 rounded-lg text-sm font-mono">{{ $order->tracking_number }}</code>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.orders.update-tracking', $order->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Courier</label>
                    <input type="text" name="courier" value="{{ old('courier', $order->courier) }}" placeholder="e.g., Sundarban, Pathao" class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Enter tracking number" class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    <i class="fas fa-save mr-2"></i>Save Tracking Info
                </button>
            </form>
        </div>

        {{-- Employee Information --}}
        @if ($order->is_pos == 1)
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i>
                    Employee Information
                </h2>

                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Name</p>
                        <p class="font-semibold text-gray-900">{{ $order->employee->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                        <a href="tel:{{ $order->employee->phone }}" class="font-semibold text-blue-600 hover:text-blue-800">
                            {{ $order->employee->phone }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <a href="mailto:{{ $order->employee->email }}" class="font-semibold text-blue-600 hover:text-blue-800">
                            {{ $order->employee->email }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Admin Notes --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-sticky-note text-blue-600"></i>
                Admin Notes
            </h2>

            <form action="{{ route('admin.orders.update-notes', $order->id) }}" method="POST">
                @csrf
                <textarea name="admin_notes" rows="4" placeholder="Add internal notes about this order..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                <button type="submit" class="mt-3 w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    <i class="fas fa-save mr-2"></i>Save Notes
                </button>
            </form>
        </div>

        @if($order->notes)
        {{-- Customer Notes --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-comment text-blue-600"></i>
                Customer Notes
            </h2>
            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $order->notes }}</p>
        </div>
        @endif
    </div>
</div>


{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Order?</h3>
            <p class="text-gray-600">Are you sure you want to delete this order? This action cannot be undone.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                Cancel
            </button>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <input type="hidden" name="source" value="{{ $source }}">
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div id="returnModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg p-5">

        <!-- HEADER -->
        <div class="flex justify-between items-center border-b pb-3">
            <h2 class="text-lg font-semibold">Sale Return</h2>
            <button onclick="closeReturnModal()" class="text-gray-500 hover:text-red-500">
                ✕
            </button>
        </div>

        <!-- ITEMS -->
        <div class="mt-4 max-h-72 overflow-y-auto space-y-2">

            @foreach($order->items as $item)
                <div class="border p-3 rounded flex gap-3 items-start">

                    <!-- CHECKBOX -->
                    <input type="checkbox"
                           class="return-check mt-1 {{ $item->return_item_id ? 'hidden' : '' }}"
                           data-id="{{ $item->id }}"
                           data-max="{{ $item->quantity }}"
                           data-default-price="{{ $item->unit_price }}">

                    <!-- INFO -->
                    <div class="flex-1">

                        <p class="text-sm font-semibold">
                            {{ $item->product->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Qty: {{ $item->quantity }} | Price: {{ $item->unit_price }}
                        </p>

                        <!-- EDIT SECTION -->
                        <div id="edit-{{ $item->id }}" class="hidden mt-2 flex gap-2">

                            <input type="number"
                                   class="qty-input border rounded px-2 py-1 text-xs w-20"
                                   min="1"
                                   max="{{ $item->quantity }}"
                                   value="{{ $item->quantity }}">

                            <input type="number"
                                   class="price-input border rounded px-2 py-1 text-xs w-24"
                                   value="{{ $item->unit_price }}">

                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        <!-- REFUND SECTION -->
        <div class="mt-4 space-y-3">

            <div class="mt-4 space-y-2">

                <div class="flex justify-between text-sm">
                    <span>Calculated Refund:</span>
                    <span class="font-bold text-green-600">৳<span id="calculatedRefund">0.00</span></span>
                </div>

                <input type="number"
                    id="refundAmount"
                    placeholder="Refund Amount"
                    class="w-full border rounded px-3 py-2 text-sm">

            </div>

            <!-- ENUM PAYMENT METHOD -->
            <select id="refundMethod"
                    class="w-full border rounded px-3 py-2 text-sm">

                <option value="">Select Refund Method</option>

                <option value="cash">Cash</option>
                <option value="bkash">bKash</option>
                <option value="bank">Bank</option>
                <option value="card">Card</option>

            </select>

            <textarea id="refundRemarks"
                      placeholder="Remarks"
                      class="w-full border rounded px-3 py-2 text-sm"></textarea>

        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-2 mt-5">

            <button onclick="closeReturnModal()"
                    class="px-4 py-2 border rounded-lg">
                Cancel
            </button>

            <button onclick="openConfirmReturnModal()"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Process Return
            </button>

        </div>

    </div>
</div>

<div id="confirmReturnModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-5">

        <h3 class="text-lg font-semibold mb-3">Confirm Return</h3>

        <!-- DETAILS -->
        <div id="confirmDetails" class="text-sm text-gray-700 space-y-2 max-h-60 overflow-y-auto">
            <!-- dynamic content -->
        </div>

        <div class="border-t mt-3 pt-3 space-y-1 text-sm">
            <div class="flex justify-between">
                <span>Total Refund:</span>
                <span class="font-bold text-green-600">৳<span id="confirmRefund">0.00</span></span>
            </div>

            <div class="flex justify-between">
                <span>Method:</span>
                <span id="confirmMethod" class="font-medium"></span>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button onclick="closeConfirmReturnModal()" class="px-4 py-2 border rounded-lg">
                Cancel
            </button>

            <button onclick="confirmReturnAction()"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Yes, Proceed
            </button>
        </div>

    </div>
</div>

@push('scripts')
{{-- html2pdf.js library for PDF generation --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>

/* =========================
   PRINT RECEIPT
========================= */
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

/* =========================
   RETURN MODAL
========================= */
function openReturnModal() {
    document.getElementById('returnModal').classList.remove('hidden');

    setTimeout(() => {
        calculateRefund();
    }, 100);
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}

/* =========================
   CALCULATE REFUND
========================= */
$(document).on("change input", ".return-check, .qty-input, .price-input", function () {
    calculateRefund();
});

function calculateRefund() {

    let total = 0;

    $(".return-check:checked").each(function () {

        let id = $(this).data("id");
        let row = $("#edit-" + id);

        let qty = parseFloat(row.find(".qty-input").val()) || 0;
        let price = parseFloat(row.find(".price-input").val()) || 0;

        total += qty * price;
    });

    $("#calculatedRefund").text(total.toFixed(2));
    $("#refundAmount").val(total.toFixed(2));
}

/* =========================
   CONFIRM MODAL (FIXED)
========================= */

function openConfirmReturnModal() {

    // 1. Close return modal FIRST
    $("#returnModal").addClass("hidden");

    // 2. Build confirmation data
    let itemsHTML = '';
    let total = 0;

    $(".return-check:checked").each(function () {

        let id = $(this).data("id");
        let row = $("#edit-" + id);

        let name = row.closest('.flex').find('p').first().text();

        let qty = parseFloat(row.find(".qty-input").val()) || 0;
        let price = parseFloat(row.find(".price-input").val()) || 0;

        let lineTotal = qty * price;
        total += lineTotal;

        itemsHTML += `
            <div class="flex justify-between border-b pb-1">
                <span class="truncate">${name} (x${qty})</span>
                <span>৳${lineTotal.toFixed(2)}</span>
            </div>
        `;
    });

    if (!itemsHTML) {
        alert("Select at least one item");
        return;
    }

    let refundInput = parseFloat($("#refundAmount").val()) || 0;

    if (refundInput <= 0) {
        alert("Invalid refund amount");
        return;
    }

    let method = $("#refundMethod").val() || 'N/A';

    // 3. Inject data into confirm modal
    $("#confirmDetails").html(itemsHTML);
    $("#confirmRefund").text(total.toFixed(2));
    $("#confirmMethod").text(method.toUpperCase());

    // 4. Small delay ensures smooth UI transition
    setTimeout(() => {
        $("#confirmReturnModal").removeClass("hidden");
    }, 150);
}

function closeConfirmReturnModal() {
    $("#confirmReturnModal").addClass("hidden");
}

let isProcessingReturn = false;

function confirmReturnAction() {
    if (isProcessingReturn) return;

    isProcessingReturn = true;

    closeConfirmReturnModal();
    submitReturn();
}

/* =========================
   SUBMIT RETURN
========================= */
function submitReturn() {

    let items = [];

    $(".return-check:checked").each(function () {

        let id = $(this).data("id");
        let row = $("#edit-" + id);

        let quantity = parseInt(row.find(".qty-input").val());
        let unit_price = parseFloat(row.find(".price-input").val());

        if (!quantity || quantity <= 0) return;

        items.push({
            id: id,
            quantity: quantity,
            unit_price: unit_price
        });
    });

    if (items.length === 0) {
        alert("Select at least one item");
        isProcessingReturn = false;
        return;
    }

    let payload = {
        items: items,
        refund_amount: parseFloat($("#refundAmount").val()) || 0,
        refund_method: $("#refundMethod").val(),
        remarks: $("#refundRemarks").val(),
    };

    $.ajax({
        url: `/admin/orders/{{ $order->id }}/return`,
        type: "POST",
        data: JSON.stringify(payload),
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.success) {

                window.showSuccess(res.message || "Return processed successfully");

                setTimeout(() => {
                    location.reload();
                }, 800);

            } else {
                window.showError(res.message || "Failed");
            }
        },

        error: function (xhr) {

            let message = "Something went wrong";

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                if (xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join("\n");
                }
            }

            window.showError(message);
        },
        complete: function () {
            isProcessingReturn = false;
        }
    });
}

/* =========================
   DELETE MODAL
========================= */
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.getElementById('deleteModal')?.addEventListener('click', function (e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

</script>
@endpush
@endsection