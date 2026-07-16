@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-surface-elevated border-b border-primary-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ url('/') }}" class="text-secondary hover:text-primary transition-colors duration-200">Home</a>
            <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
            <a href="#" onclick="openCartDrawer(); return false;" class="text-secondary hover:text-primary transition-colors duration-200">Cart</a>
            <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
            <span class="text-primary font-medium">Checkout</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-4">
    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="bg-danger-50 border border-danger-100 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-danger text-xl mt-0.5"></i>
            <div class="flex-1">
                <h4 class="font-bold text-danger-800 mb-2">Please fix the following errors:</h4>
                <ul class="list-disc list-inside space-y-1 text-sm text-danger-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" class="grid lg:grid-cols-3 gap-6 lg:gap-8">
        @csrf
        {{-- Left Column - Customer Details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Delivery Address --}}
            <div class="bg-surface-elevated rounded-2xl p-5 md:p-6 border border-primary-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-primary text-surface-elevated rounded-full flex items-center justify-center text-sm font-bold shadow-sm">1</div>
                    <h2 class="text-lg font-bold text-primary">Delivery Information</h2>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-primary mb-2">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" required placeholder="Enter your full name" value="{{ old('name', $user?->name) }}" class="w-full h-12 px-4 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200 @error('name') border-danger @else border-primary-100 @enderror">
                        @error('name')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-2">Phone Number <span class="text-danger">*</span></label>
                        <div class="flex">
                            <span class="h-12 px-3 bg-light border border-r-0 rounded-l-xl flex items-center text-sm text-secondary font-semibold @error('phone') border-danger @else border-primary-100 @enderror">+88</span>
                            <input type="tel" name="phone" required placeholder="01XXXXXXXXX" pattern="01[3-9][0-9]{8}" maxlength="11" value="{{ old('phone', $user?->phone) }}" class="flex-1 h-12 px-4 bg-light border rounded-r-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200 @error('phone') border-danger @else border-primary-100 @enderror">
                        </div>
                        @error('phone')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @else
                        <p class="text-xs text-secondary-300 mt-1">We'll contact you on this number</p>
                        @enderror
                    </div>
                </div>

                {{-- Delivery Zone Selection --}}
                <div class="mb-5 mt-5">
                    <label class="block text-sm font-bold text-primary mb-3">Select Delivery Zone <span class="text-danger">*</span></label>
                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach($shippingZones as $index => $zone)
                        <label class="delivery-zone-option relative cursor-pointer">
                            <input type="radio" name="delivery_zone" value="{{ $zone->code }}" data-cost="{{ $zone->shipping_cost }}" class="sr-only peer" {{ $index === 0 ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-primary-100 rounded-xl peer-checked:border-primary peer-checked:bg-primary-50 transition-all duration-200 hover:border-primary-200">
                                <div class="flex items-center mb-2">
                                    <span class="font-bold text-primary">{{ $zone->name }}</span>
                                    <span class="text-primary font-bold ms-2">({{ money($zone->shipping_cost) }})</span>
                                </div>
                                <p class="text-xs text-secondary">Delivery within {{ $zone->estimated_days }}</p>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 border-2 border-primary-200 rounded-full peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all duration-200">
                                <i class="fas fa-check text-surface-elevated text-xs hidden peer-checked:block"></i>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-primary mb-2">District <span class="text-danger">*</span></label>
                        <select name="district" required class="w-full h-12 px-4 bg-light border rounded-xl text-sm text-primary focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200 @error('district') border-danger @else border-primary-100 @enderror">
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ old('district') == $district->id ? 'selected' : '' }}>
                                {{ $district->display_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('district')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-2">City/Area <span class="text-danger">*</span></label>
                        <input type="text" name="city" required placeholder="e.g., Uttara, Mirpur, Dhanmondi" value="{{ old('city') }}" class="w-full h-12 px-4 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200 @error('city') border-danger @else border-primary-100 @enderror">
                        @error('city')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-primary mb-2">Full Address <span class="text-danger">*</span></label>
                        <textarea name="address" required rows="3" placeholder="House/Flat No, Road, Block, Area (Be specific for faster delivery)" class="w-full px-4 py-3 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200 resize-none @error('address') border-danger @else border-primary-100 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Order Notes --}}
            <div class="bg-surface-elevated rounded-2xl p-5 md:p-6 border border-primary-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-primary-100 text-primary rounded-full flex items-center justify-center text-sm">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <h2 class="text-lg font-bold text-primary">Order Notes <span class="text-secondary font-normal text-sm">(Optional)</span></h2>
                </div>
                <textarea name="notes" rows="3" placeholder="Any special instructions for your order? e.g., Gift wrapping, specific delivery time, etc." class="w-full px-4 py-3 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200 resize-none @error('notes') border-danger @else border-primary-100 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Right Column - Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-surface-elevated rounded-2xl p-5 md:p-6 border border-primary-100 shadow-sm sticky top-24">
                <h2 class="text-lg font-bold text-primary mb-5">Order Summary</h2>

                {{-- Cart Items --}}
                <div class="space-y-4 max-h-64 overflow-y-auto mb-5 pr-2 qv-scroll">
                    @foreach($cart->items as $item)
                    <div class="flex gap-3">
                        <div class="w-16 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-light border border-primary-100">
                            <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-primary line-clamp-2">{{ $item->product->name }}</h4>
                            @if($item->variant)
                            <p class="text-xs text-secondary mt-1">
                                @if($item->variant->size)
                                Size: {{ $item->variant->size->name }}
                                @endif
                                @if($item->variant->size && $item->variant->color) | @endif
                                @if($item->variant->color)
                                Color: {{ $item->variant->color->name }}
                                @endif
                            </p>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-secondary">Qty: {{ $item->quantity }}</span>
                                <span class="text-sm font-bold text-primary">{{ money($item->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <a href="#" onclick="openCartDrawer(); return false;" class="text-primary text-sm font-bold hover:text-secondary transition-colors duration-200 mb-5 inline-block">
                    <i class="fas fa-edit mr-1"></i> Edit Cart
                </a>

                <div class="mb-5">
                    <div class="flex gap-2">
                        <input type="text" name="coupon" id="couponCode" placeholder="Coupon code" class="flex-1 h-11 px-4 bg-light border border-primary-100 rounded-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200">
                        <button type="button" id="applyCouponBtn" class="h-11 px-4 bg-light text-secondary border border-primary-100 rounded-xl font-semibold text-sm hover:bg-primary-50 hover:text-primary hover:border-primary transition-all duration-200">Apply</button>
                    </div>
                    <div id="couponMessage" class="mt-2 text-sm hidden"></div>
                </div>

                {{-- Price Breakdown --}}
                <div class="space-y-3 border-t border-primary-100 pt-5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-secondary">Subtotal ({{ $cart->items_count }} {{ Str::plural('item', $cart->items_count) }})</span>
                        <span class="font-semibold text-primary" id="subtotalAmount">{{ money($cart->subtotal) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-secondary">Shipping</span>
                        <span class="font-semibold text-primary" id="shippingCost">{{ money($shippingZones->first()->shipping_cost ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-accent-600 hidden" id="discountRow">
                        <span>Discount</span>
                        <span class="font-semibold" id="discountAmount">-৳0</span>
                    </div>
                    <div class="h-px bg-primary-100"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-base font-bold text-primary">Total</span>
                        <span class="text-2xl font-black text-primary" id="totalAmount">{{ money($cart->subtotal + ($shippingZones->first()->shipping_cost ?? 0)) }}</span>
                    </div>
                </div>

                <div class="mb-5 mt-6">
                    <label class="block text-sm font-bold text-primary mb-3">Payment Method <span class="text-danger">*</span></label>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <label class="payment-method-option relative cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" class="sr-only peer">
                            <div class="p-4 border-2 border-primary-100 rounded-xl peer-checked:border-primary peer-checked:bg-primary-50 transition-all duration-200 hover:border-primary-200">
                                <div class="flex items-center mb-2">
                                    <span class="font-bold text-primary">COD</span>
                                </div>
                                <p class="text-xs text-secondary">Cash on delivery.</p>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 border-2 border-primary-200 rounded-full peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all duration-200">
                                <i class="fas fa-check text-surface-elevated text-xs hidden peer-checked:block"></i>
                            </div>
                        </label>
                        <label class="payment-method-option relative cursor-pointer">
                            <input type="radio" name="payment_method" value="online" class="sr-only peer">
                            <div class="p-4 border-2 border-primary-100 rounded-xl peer-checked:border-primary peer-checked:bg-primary-50 transition-all duration-200 hover:border-primary-200">
                                <div class="flex items-center mb-2">
                                    <span class="font-bold text-primary">Pay Now</span>
                                </div>
                                <p class="text-xs text-secondary">Payment online.</p>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 border-2 border-primary-200 rounded-full peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all duration-200">
                                <i class="fas fa-check text-surface-elevated text-xs hidden peer-checked:block"></i>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Place Order Button --}}
                <button type="submit" class="w-full mt-6 bg-primary text-surface-elevated py-4 rounded-xl font-bold text-base hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 tap-effect shadow-xl shadow-primary/20 flex items-center justify-center gap-2 group">
                    <i class="fas fa-check-circle group-hover:scale-110 transition-transform duration-200"></i>
                    Place Order
                </button>

                {{-- Terms --}}
                <p class="text-xs text-secondary-300 text-center mt-4">
                    By placing this order, you agree to our
                    <a href="#" class="text-primary hover:text-secondary font-semibold transition-colors duration-200">Terms & Conditions</a> and
                    <a href="#" class="text-primary hover:text-secondary font-semibold transition-colors duration-200">Privacy Policy</a>
                </p>

                {{-- Trust Badges --}}
                <div class="flex items-center justify-center gap-4 mt-5 pt-5 border-t border-primary-100">
                    <div class="flex items-center gap-2 text-secondary-300">
                        <i class="fas fa-shield-alt text-accent"></i>
                        <span class="text-xs font-medium">Secure</span>
                    </div>
                    <div class="flex items-center gap-2 text-secondary-300">
                        <i class="fas fa-undo text-primary"></i>
                        <span class="text-xs font-medium">7 Days Return</span>
                    </div>
                    <div class="flex items-center gap-2 text-secondary-300">
                        <i class="fas fa-truck text-warning-500"></i>
                        <span class="text-xs font-medium">Fast Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Cart data from server
    const cartSubtotal = "{{ $cart->subtotal }}";
    let currentDiscount = 0;
    let currentShippingCost = "{{ $shippingZones->first()->shipping_cost ?? 0 }}";
    let appliedCouponCode = '';

    // Update total calculations
    function updateTotals() {
        const total = parseFloat(cartSubtotal) + parseFloat(currentShippingCost) - parseFloat(currentDiscount);
        document.getElementById('totalAmount').textContent = '৳' + total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Delivery zone shipping cost update
    document.querySelectorAll('input[name="delivery_zone"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentShippingCost = parseFloat(this.dataset.cost);
            document.getElementById('shippingCost').textContent = '৳' + currentShippingCost.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            updateTotals();
        });
    });

    // Coupon validation
    document.getElementById('applyCouponBtn').addEventListener('click', async function() {
        const couponInput = document.getElementById('couponCode');
        const couponCode = couponInput.value.trim();
        const messageDiv = document.getElementById('couponMessage');
        const btn = this;

        if (!couponCode) {
            showCouponMessage('Please enter a coupon code', 'error');
            return;
        }

        // Disable button during validation
        btn.disabled = true;
        btn.textContent = 'Checking...';

        try {
            const response = await fetch("{{ route('checkout.validate-coupon') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        coupon: couponCode,
                        subtotal: cartSubtotal
                    })
                });

            const data = await response.json();

            if (response.ok && data.success) {
                currentDiscount = data.discount;
                appliedCouponCode = couponCode;

                // Show discount in UI
                document.getElementById('discountRow').classList.remove('hidden');
                document.getElementById('discountAmount').textContent = '-' + data.discount_formatted;

                // Update totals
                updateTotals();

                // Show success message
                showCouponMessage(data.message, 'success');

                // Change button to "Remove"
                btn.textContent = 'Remove';
                btn.classList.remove('bg-light', 'text-secondary', 'hover:bg-primary-50', 'hover:text-primary', 'border-primary-100');
                btn.classList.add('bg-danger-50', 'text-danger-600', 'hover:bg-danger-100', 'border-danger-200');
                btn.onclick = removeCoupon;

                // Disable input
                couponInput.disabled = true;
            } else {
                showCouponMessage(data.message || 'Invalid coupon code', 'error');
                btn.textContent = 'Apply';
            }
        } catch (error) {
            showCouponMessage('Error validating coupon. Please try again.', 'error');
            btn.textContent = 'Apply';
        } finally {
            btn.disabled = false;
        }
    });

    // Remove coupon
    function removeCoupon() {
        const btn = document.getElementById('applyCouponBtn');
        const couponInput = document.getElementById('couponCode');

        currentDiscount = 0;
        appliedCouponCode = '';

        // Hide discount row
        document.getElementById('discountRow').classList.add('hidden');

        // Update totals
        updateTotals();

        // Reset button
        btn.textContent = 'Apply';
        btn.classList.remove('bg-danger-50', 'text-danger-600', 'hover:bg-danger-100', 'border-danger-200');
        btn.classList.add('bg-light', 'text-secondary', 'hover:bg-primary-50', 'hover:text-primary', 'border-primary-100');
        btn.onclick = null;

        // Enable and clear input
        couponInput.disabled = false;
        couponInput.value = '';

        // Hide message
        document.getElementById('couponMessage').classList.add('hidden');
    }

    // Show coupon message
    function showCouponMessage(message, type) {
        const messageDiv = document.getElementById('couponMessage');
        messageDiv.textContent = message;
        messageDiv.classList.remove('hidden', 'text-accent-600', 'text-danger-600');
        messageDiv.classList.add(type === 'success' ? 'text-accent-600' : 'text-danger-600');
    }

    // Copy to clipboard
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('i');
            icon.classList.remove('far', 'fa-copy');
            icon.classList.add('fas', 'fa-check');

            setTimeout(() => {
                icon.classList.remove('fas', 'fa-check');
                icon.classList.add('far', 'fa-copy');
            }, 2000);
        });
    }

    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (paymentMethod === 'bkash') {
            const trxId = document.querySelector('input[name="bkash_trx_id"]').value;
            if (!trxId) {
                alert('Please enter your bKash Transaction ID');
                return;
            }
        } else if (paymentMethod === 'nagad') {
            const trxId = document.querySelector('input[name="nagad_trx_id"]').value;
            if (!trxId) {
                alert('Please enter your Nagad Transaction ID');
                return;
            }
        }

        // Submit the form
        this.submit();
    });

    // Update radio button checkmarks visibility
    document.querySelectorAll('.delivery-zone-option input, .payment-method-option input').forEach(input => {
        const updateCheckmark = () => {
            document.querySelectorAll('.delivery-zone-option, .payment-method-option').forEach(label => {
                const check = label.querySelector('.fa-check');
                const radio = label.querySelector('input[type="radio"]');
                if (check && radio) {
                    check.parentElement.classList.toggle('bg-primary', radio.checked);
                    check.parentElement.classList.toggle('border-primary', radio.checked);
                    check.parentElement.classList.toggle('border-primary-200', !radio.checked);
                    check.classList.toggle('hidden', !radio.checked);
                }
            });
        };
        input.addEventListener('change', updateCheckmark);
        updateCheckmark();
    });
</script>
@endpush