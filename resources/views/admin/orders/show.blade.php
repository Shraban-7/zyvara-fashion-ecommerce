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
        <button onclick="window.print()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
            <i class="fas fa-print"></i>
            <span>Print Invoice</span>
        </button>
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
                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="w-20 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-white">
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 mb-1">{{ $item->product_name }}</h4>
                        @if($item->size || $item->color)
                        <p class="text-sm text-gray-500 mb-2">
                            @if($item->size)Size: {{ $item->size }}@endif
                            @if($item->size && $item->color) | @endif
                            @if($item->color)Color: {{ $item->color }}@endif
                        </p>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Qty: {{ $item->quantity }} × {{ money($item->unit_price) }}</span>
                            <span class="text-base font-bold text-gray-900">{{ money($item->total_price) }}</span>
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
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
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
                        {{ $order->delivery_zone->label() }}
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
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection