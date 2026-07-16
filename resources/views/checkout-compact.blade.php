@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="bg-light min-h-screen pb-10">
        {{-- Compact Breadcrumb --}}
        <div class="bg-surface-elevated border-b border-primary-100 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
                <nav class="flex items-center gap-2 text-xs font-bold">
                    <a href="{{ url('/') }}" class="text-secondary hover:text-primary transition-colors duration-200">Home</a>
                    <span class="text-primary-100">/</span>
                    <a href="#" onclick="openCartDrawer(); return false;" class="text-secondary hover:text-primary transition-colors duration-200">Cart</a>
                    <span class="text-primary-100">/</span>
                    <span class="text-primary">Checkout</span>
                </nav>
                <div class="text-xs text-secondary-300 hidden sm:block font-medium">
                    Secure SSL Encrypted Transaction
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6">
            @if ($errors->any())
                <div class="bg-danger-50 border border-danger-100 rounded-lg p-3 mb-4 flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <span class="text-sm text-danger-700 font-bold">Please fix the errors below.</span>
                </div>
            @endif

            <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST"
                class="grid lg:grid-cols-12 gap-5 items-start">
                @csrf

                {{-- Left Column: All Inputs --}}
                <div class="lg:col-span-8 space-y-4">

                    {{-- Section 1: Customer & Address Combined (Dense Grid) --}}
                    <div class="bg-surface-elevated rounded-lg shadow-sm border border-primary-100 overflow-hidden">
                        <div class="bg-light px-4 py-2 border-b border-primary-100 flex items-center gap-2">
                            <div
                                class="w-5 h-5 bg-primary text-surface-elevated rounded-full flex items-center justify-center text-xs font-bold shadow-sm">
                                1</div>
                            <h2 class="text-sm font-bold text-primary uppercase tracking-wide">Shipping Information</h2>
                        </div>

                        <div class="p-4 grid gap-4">
                            {{-- Row 1: Name, Phone, Email --}}
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-bold text-primary mb-1">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" required value="{{ old('name', $user?->name) }}"
                                        class="w-full h-9 px-3 bg-light border rounded-md text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100"
                                        placeholder="Full Name">
                                    @error('name') <p class="text-xs text-danger mt-0.5">{{ $message }}</p> @enderror
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-bold text-primary mb-1">Phone <span
                                            class="text-danger">*</span></label>
                                    <div class="flex">
                                        <span
                                            class="h-9 px-2 bg-light border border-r-0 border-primary-100 rounded-l-md flex items-center text-xs text-secondary font-bold">+88</span>
                                        <input type="tel" name="phone" required value="{{ old('phone', $user?->phone) }}"
                                            class="flex-1 h-9 px-3 bg-light border rounded-r-md text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100"
                                            placeholder="01XXX...">
                                    </div>
                                    @error('phone') <p class="text-xs text-danger mt-0.5">{{ $message }}</p> @enderror
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-bold text-primary mb-1">Email <span
                                            class="text-secondary font-normal">(Opt)</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user?->email) }}"
                                        class="w-full h-9 px-3 bg-light border rounded-md text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100"
                                        placeholder="Email Address">
                                </div>
                            </div>

                            <hr class="border-primary-100">

                            {{-- Row 2: Delivery Zone (Horizontal Pills) --}}
                            <div>
                                <label class="block text-xs font-bold text-primary mb-2">Delivery Zone <span
                                        class="text-danger">*</span></label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($shippingZones as $index => $zone)
                                        <label class="relative cursor-pointer group flex-1 sm:flex-none">
                                            <input type="radio" name="delivery_zone" value="{{ $zone->code }}"
                                                data-cost="{{ $zone->shipping_cost }}" class="sr-only peer" {{ $index === 0 ? 'checked' : '' }}>
                                            <div
                                                class="px-3 py-2 border border-primary-100 rounded-md peer-checked:border-primary peer-checked:bg-primary-50 peer-checked:text-primary hover:bg-light transition-all duration-200 text-center min-w-[120px]">
                                                <div class="text-xs font-bold text-primary">{{ $zone->name }}</div>
                                                <div class="text-[10px] text-secondary peer-checked:text-primary font-medium">
                                                    {{ money($zone->shipping_cost) }} - {{ $zone->estimated_days }}</div>
                                            </div>
                                            <div
                                                class="absolute -top-1 -right-1 w-3 h-3 bg-primary rounded-full items-center justify-center hidden peer-checked:flex shadow-sm">
                                                <i class="fas fa-check text-surface-elevated text-[8px]"></i>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Row 3: District, City, Address --}}
                            <div class="grid sm:grid-cols-4 gap-3">
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-bold text-primary mb-1">District <span
                                            class="text-danger">*</span></label>
                                    <select name="district" required
                                        class="w-full h-9 px-2 bg-light border rounded-md text-sm text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100">
                                        <option value="">Select</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ old('district') == $district->id ? 'selected' : '' }}>{{ $district->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-bold text-primary mb-1">Area/City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="city" required value="{{ old('city') }}"
                                        class="w-full h-9 px-3 bg-light border rounded-md text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100"
                                        placeholder="e.g. Uttara">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-primary mb-1">Full Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="address" required value="{{ old('address') }}"
                                        class="w-full h-9 px-3 bg-light border rounded-md text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100"
                                        placeholder="House, Road, Block...">
                                    @error('address') <p class="text-xs text-danger mt-0.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Payment & Notes --}}
                    <div class="bg-surface-elevated rounded-lg shadow-sm border border-primary-100 overflow-hidden">
                        <div class="bg-light px-4 py-2 border-b border-primary-100 flex items-center gap-2">
                            <div
                                class="w-5 h-5 bg-primary text-surface-elevated rounded-full flex items-center justify-center text-xs font-bold shadow-sm">
                                2</div>
                            <h2 class="text-sm font-bold text-primary uppercase tracking-wide">Payment Method</h2>
                        </div>

                        <div class="p-4">
                            {{-- Horizontal Payment Tabs --}}
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                {{-- COD --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" class="sr-only peer" checked
                                        onchange="togglePaymentDetails('cod')">
                                    <div
                                        class="h-full flex flex-col items-center justify-center p-3 border border-primary-100 rounded-lg hover:bg-light peer-checked:border-accent peer-checked:bg-accent-50 transition-all duration-200">
                                        <i class="fas fa-money-bill-wave text-accent-600 text-lg mb-1"></i>
                                        <span class="text-xs font-bold text-primary">Cash on Delivery</span>
                                    </div>
                                </label>

                                {{-- bKash --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="bkash" class="sr-only peer"
                                        onchange="togglePaymentDetails('bkash')">
                                    <div
                                        class="h-full flex flex-col items-center justify-center p-3 border border-primary-100 rounded-lg hover:bg-light peer-checked:border-accent-500 peer-checked:bg-accent-50 transition-all duration-200">
                                        <img src="{{ asset('assets/images/bkash.png') }}" alt="bKash"
                                            class="h-5 mb-1 object-contain">
                                        <span class="text-xs font-bold text-primary">bKash</span>
                                    </div>
                                </label>

                                {{-- Nagad --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="nagad" class="sr-only peer"
                                        onchange="togglePaymentDetails('nagad')">
                                    <div
                                        class="h-full flex flex-col items-center justify-center p-3 border border-primary-100 rounded-lg hover:bg-light peer-checked:border-warning-500 peer-checked:bg-warning-50 transition-all duration-200">
                                        <img src="{{ asset('assets/images/nagad.png') }}" alt="Nagad"
                                            class="h-5 mb-1 object-contain">
                                        <span class="text-xs font-bold text-primary">Nagad</span>
                                    </div>
                                </label>
                            </div>

                            {{-- Compact bKash Instructions --}}
                            <div id="bkashInstructions"
                                class="hidden bg-accent-50 rounded-lg p-4 border border-accent-100 text-sm animate-fade-in-down">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex-1 space-y-2">
                                        <div
                                            class="flex items-center justify-between text-xs text-accent-800 bg-surface-elevated p-2 rounded border border-accent-200">
                                            <span class="font-bold">Merchant Number:</span>
                                            <div class="flex items-center gap-2">
                                                <code class="font-mono font-bold text-base">{{ $bkashNumber }}</code>
                                                <button type="button"
                                                    onclick="copyToClipboard('{{ str_replace('-', '', $bkashNumber) }}')"
                                                    class="text-accent-500 hover:text-accent-700 transition-colors"><i
                                                        class="far fa-copy"></i></button>
                                            </div>
                                        </div>
                                        <ol class="list-decimal list-inside text-xs text-accent-800 space-y-1 pl-1">
                                            <li>Dial *247# or use App -> <strong>Send Money</strong></li>
                                            <li>Reference: Your Phone Number</li>
                                        </ol>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-accent-800 mb-1">Transaction ID (TrxID)
                                            <span class="text-danger">*</span></label>
                                        <input type="text" name="bkash_trx_id" placeholder="e.g. 8N72KS92JD"
                                            class="w-full h-9 px-3 border border-accent-300 rounded-md text-sm uppercase focus:outline-none focus:ring-2 focus:ring-accent-500/20 focus:border-accent-500 transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            {{-- Compact Nagad Instructions --}}
                            <div id="nagadInstructions"
                                class="hidden bg-warning-50 rounded-lg p-4 border border-warning-100 text-sm animate-fade-in-down">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex-1 space-y-2">
                                        <div
                                            class="flex items-center justify-between text-xs text-warning-800 bg-surface-elevated p-2 rounded border border-warning-200">
                                            <span class="font-bold">Nagad Number:</span>
                                            <div class="flex items-center gap-2">
                                                <code class="font-mono font-bold text-base">{{ $nagadNumber }}</code>
                                                <button type="button"
                                                    onclick="copyToClipboard('{{ str_replace('-', '', $nagadNumber) }}')"
                                                    class="text-warning-500 hover:text-warning-700 transition-colors"><i
                                                        class="far fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-warning-800 mb-1">Transaction ID (TrxID)
                                            <span class="text-danger">*</span></label>
                                        <input type="text" name="nagad_trx_id" placeholder="e.g. N8K72J92"
                                            class="w-full h-9 px-3 border border-warning-300 rounded-md text-sm uppercase focus:outline-none focus:ring-2 focus:ring-warning-500/20 focus:border-warning-500 transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="mt-4 pt-4 border-t border-primary-100">
                                <label class="block text-xs font-bold text-secondary mb-1">Order Notes (Optional)</label>
                                <input type="text" name="notes" placeholder="Special instructions..."
                                    class="w-full h-9 px-3 bg-light border rounded-md text-xs text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 border-primary-100">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Summary --}}
                <div class="lg:col-span-4">
                    <div class="bg-surface-elevated rounded-lg shadow-sm border border-primary-100 sticky top-20">
                        <div class="p-4 bg-light border-b border-primary-100">
                            <h2 class="text-sm font-bold text-primary uppercase tracking-wide">Order Summary</h2>
                        </div>

                        {{-- Scrollable Cart List --}}
                        <div class="max-h-[240px] overflow-y-auto p-4 space-y-3 qv-scroll">
                            @foreach($cart->items as $item)
                                <div class="flex gap-3 items-center group">
                                    <div class="w-10 h-12 flex-shrink-0 bg-light rounded overflow-hidden border border-primary-100">
                                        <img src="{{ $item->product->thumbnail }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-semibold text-primary truncate">{{ $item->product->name }}</h4>
                                        <div class="text-[10px] text-secondary">
                                            Qty: {{ $item->quantity }}
                                            @if($item->variant)
                                                | {{ $item->variant->size->name ?? '' }} {{ $item->variant->color->name ?? '' }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-xs font-bold text-primary">{{ money($item->total_price) }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Totals Section --}}
                        <div class="p-4 bg-light border-t border-primary-100">
                            {{-- Coupon --}}
                            <div class="flex gap-2 mb-4">
                                <input type="text" name="coupon" id="couponCode" placeholder="Coupon Code"
                                    class="flex-1 h-8 px-3 text-xs bg-surface-elevated border border-primary-100 rounded text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                                <button type="button" id="applyCouponBtn"
                                    class="h-8 px-3 bg-primary text-surface-elevated text-xs rounded font-bold hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-sm">Apply</button>
                            </div>
                            <div id="couponMessage" class="text-xs mb-2 hidden font-medium"></div>

                            <div class="space-y-1.5 text-xs text-secondary">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-primary"
                                        id="subtotalAmount">{{ money($cart->subtotal) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span class="font-semibold text-primary"
                                        id="shippingCost">{{ money($shippingZones->first()->shipping_cost ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between text-accent-600 hidden" id="discountRow">
                                    <span>Discount</span>
                                    <span class="font-bold" id="discountAmount">-৳0</span>
                                </div>
                            </div>

                            <div class="h-px bg-primary-100 my-3"></div>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-bold text-primary">Total</span>
                                <span class="text-lg font-black text-primary"
                                    id="totalAmount">{{ money($cart->subtotal + ($shippingZones->first()->shipping_cost ?? 0)) }}</span>
                            </div>

                            <button type="submit"
                                class="w-full bg-primary text-surface-elevated py-3 rounded-lg font-bold text-sm hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-xl shadow-primary/20 flex items-center justify-center gap-2 group">
                                <i class="fas fa-check-circle group-hover:scale-110 transition-transform duration-200"></i>
                                Confirm Order
                            </button>

                            <div
                                class="mt-3 flex justify-center gap-3 opacity-40 grayscale hover:grayscale-0 transition-all duration-300">
                                <i class="fab fa-cc-visa text-xl text-secondary"></i>
                                <i class="fab fa-cc-mastercard text-xl text-secondary"></i>
                                <i class="fas fa-shield-alt text-xl text-accent"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple toggle script for payment methods
        function togglePaymentDetails(method) {
            document.getElementById('bkashInstructions').classList.add('hidden');
            document.getElementById('nagadInstructions').classList.add('hidden');

            if (method === 'bkash') {
                document.getElementById('bkashInstructions').classList.remove('hidden');
            } else if (method === 'nagad') {
                document.getElementById('nagadInstructions').classList.remove('hidden');
            }
        }

        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                if (window.showSuccess) {
                    window.showSuccess('Copied to clipboard!');
                }
            });
        }

        // Delivery zone shipping cost update
        document.querySelectorAll('input[name="delivery_zone"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const cost = parseFloat(this.dataset.cost);
                document.getElementById('shippingCost').textContent = '৳' + cost.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                updateTotals();
            });
        });

        // Update total calculations
        function updateTotals() {
            const subtotal = parseFloat("{{ $cart->subtotal }}");
            const shipping = parseFloat(document.querySelector('input[name="delivery_zone"]:checked')?.dataset.cost || 0);
            const discount = parseFloat(document.getElementById('discountAmount')?.dataset.value || 0);
            const total = subtotal + shipping - discount;
            document.getElementById('totalAmount').textContent = '৳' + total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

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
                        subtotal: "{{ $cart->subtotal }}"
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById('discountRow').classList.remove('hidden');
                    document.getElementById('discountAmount').textContent = '-' + data.discount_formatted;
                    document.getElementById('discountAmount').dataset.value = data.discount;
                    updateTotals();
                    showCouponMessage(data.message, 'success');
                    btn.textContent = 'Remove';
                    btn.classList.remove('bg-primary', 'hover:bg-primary-700');
                    btn.classList.add('bg-danger-50', 'text-danger-600', 'hover:bg-danger-100', 'border', 'border-danger-200');
                    btn.onclick = removeCoupon;
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

        function removeCoupon() {
            const btn = document.getElementById('applyCouponBtn');
            const couponInput = document.getElementById('couponCode');
            document.getElementById('discountRow').classList.add('hidden');
            document.getElementById('discountAmount').dataset.value = 0;
            updateTotals();
            btn.textContent = 'Apply';
            btn.classList.remove('bg-danger-50', 'text-danger-600', 'hover:bg-danger-100', 'border', 'border-danger-200');
            btn.classList.add('bg-primary', 'hover:bg-primary-700');
            btn.onclick = null;
            couponInput.disabled = false;
            couponInput.value = '';
            document.getElementById('couponMessage').classList.add('hidden');
        }

        function showCouponMessage(message, type) {
            const messageDiv = document.getElementById('couponMessage');
            messageDiv.textContent = message;
            messageDiv.classList.remove('hidden', 'text-accent-600', 'text-danger-600');
            messageDiv.classList.add(type === 'success' ? 'text-accent-600' : 'text-danger-600');
        }
    </script>
@endsection