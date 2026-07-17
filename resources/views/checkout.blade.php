@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-surface-elevated border-b border-secondary-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ url('/') }}" class="text-secondary hover:text-primary transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
            <a href="#" onclick="openCartDrawer(); return false;" class="text-secondary hover:text-primary transition-colors">Cart</a>
            <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
            <span class="text-primary font-medium">Checkout</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
    @if ($errors->any())
        <div class="bg-danger-50 border border-danger-200 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-danger text-xl mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="font-bold text-danger-800 mb-1">Please fix the following errors:</h4>
                    <ul class="list-disc list-inside space-y-0.5 text-sm text-danger-700">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Step indicator --}}
    <ol class="flex items-center justify-center mb-8 max-w-2xl mx-auto" id="stepIndicator">
        @foreach(['Shipping','Delivery','Payment','Review'] as $i => $label)
            <li class="flex items-center {{ $loop->last ? '' : 'flex-1' }}" data-step="{{ $i }}">
                <div class="flex items-center gap-2">
                    <span class="step-dot w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold border-2 transition
                        {{ $i == 0 ? 'bg-accent border-accent text-primary' : 'bg-surface-elevated border-secondary-200 text-secondary-400' }}">{{ $i + 1 }}</span>
                    <span class="step-label hidden sm:block text-sm font-medium {{ $i == 0 ? 'text-primary' : 'text-secondary-400' }}">{{ $label }}</span>
                </div>
                @if(!$loop->last)<span class="step-bar flex-1 h-0.5 mx-2 {{ $i == 0 ? 'bg-accent' : 'bg-secondary-200' }}"></span>@endif
            </li>
        @endforeach
    </ol>

    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" class="grid lg:grid-cols-3 gap-6 lg:gap-8" novalidate>
        @csrf

        {{-- ============ STEP 1: SHIPPING ADDRESS ============ --}}
        <section class="checkout-step lg:col-span-2 bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6" data-step="0">
            <h2 class="font-heading text-lg font-semibold text-primary mb-5 flex items-center gap-2"><i class="fas fa-location-dot text-accent-600"></i> Shipping Address</h2>

            @if(isset($savedAddresses) && $savedAddresses->count())
                <div class="grid sm:grid-cols-2 gap-3 mb-5" id="savedAddressWrap">
                    @foreach($savedAddresses as $addr)
                        <label class="cursor-pointer">
                            <input type="radio" name="address_id" value="{{ $addr->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-secondary-200 peer-checked:border-accent peer-checked:bg-accent-50 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-primary text-sm">{{ $addr->name }}</span>
                                    @if($addr->is_default)<span class="text-[10px] bg-accent-100 text-accent-700 px-2 py-0.5 rounded-full">Default</span>@endif
                                </div>
                                <p class="text-xs text-secondary-500 line-clamp-2">{{ $addr->address_line_1 }}, {{ $addr->city }}</p>
                            </div>
                        </label>
                    @endforeach
                    <label class="cursor-pointer">
                        <input type="radio" name="address_id" value="new" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-dashed border-secondary-200 peer-checked:border-accent peer-checked:bg-accent-50 transition flex items-center justify-center gap-2 text-sm font-medium text-secondary-600">
                            <i class="fas fa-plus"></i> Add New Address
                        </div>
                    </label>
                </div>
            @endif

            <div id="newAddressFields" class="grid sm:grid-cols-2 gap-4 {{ (isset($savedAddresses) && $savedAddresses->count()) ? 'hidden' : '' }}">
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $user?->name) }}" class="w-full h-12 px-4 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:border-accent focus:ring-2 focus:ring-accent/20 @error('name') border-danger @enderror">
                    @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2">Phone <span class="text-danger">*</span></label>
                    <div class="flex">
                        <span class="h-12 px-3 bg-light border border-r-0 rounded-l-xl flex items-center text-sm text-secondary-500 font-semibold">+88</span>
                        <input type="tel" name="phone" required pattern="01[3-9][0-9]{8}" maxlength="11" value="{{ old('phone', $user?->phone) }}" class="flex-1 h-12 px-4 bg-light border rounded-r-xl text-sm text-primary placeholder-secondary-300 focus:border-accent focus:ring-2 focus:ring-accent/20 @error('phone') border-danger @enderror">
                    </div>
                    @error('phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@else<p class="text-xs text-secondary-300 mt-1">We'll contact you on this number</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-primary mb-2">Full Address <span class="text-danger">*</span></label>
                    <textarea name="address" required rows="3" placeholder="House/Flat, Road, Block, Area" class="w-full px-4 py-3 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:border-accent focus:ring-2 focus:ring-accent/20 resize-none @error('address') border-danger @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2">City/Area <span class="text-danger">*</span></label>
                    <input type="text" name="city" required value="{{ old('city') }}" class="w-full h-12 px-4 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:border-accent focus:ring-2 focus:ring-accent/20 @error('city') border-danger @enderror">
                    @error('city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2">District <span class="text-danger">*</span></label>
                    <select name="district" required class="w-full h-12 px-4 bg-light border rounded-xl text-sm text-primary focus:border-accent focus:ring-2 focus:ring-accent/20 @error('district') border-danger @enderror">
                        <option value="">Select District</option>
                        @foreach($districts ?? [] as $district)
                            <option value="{{ $district->id }}" {{ old('district') == $district->id ? 'selected' : '' }}>{{ $district->display_name }}</option>
                        @endforeach
                    </select>
                    @error('district')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" onclick="goStep(1)" class="bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition">Continue to Delivery</button>
            </div>
        </section>

        {{-- ============ STEP 2: DELIVERY METHOD ============ --}}
        <section class="checkout-step lg:col-span-2 bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6 hidden" data-step="1">
            <h2 class="font-heading text-lg font-semibold text-primary mb-5 flex items-center gap-2"><i class="fas fa-truck-fast text-accent-600"></i> Delivery Method</h2>
            <div class="grid sm:grid-cols-2 gap-3">
                @foreach($shippingZones ?? [] as $index => $zone)
                    <label class="cursor-pointer">
                        <input type="radio" name="delivery_zone" value="{{ $zone->code }}" data-cost="{{ $zone->shipping_cost }}" class="sr-only peer" {{ $index === 0 ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-secondary-200 rounded-xl peer-checked:border-accent peer-checked:bg-accent-50 transition">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-primary">{{ $zone->name }}</span>
                                <span class="font-semibold text-primary">{{ money($zone->shipping_cost) }}</span>
                            </div>
                            <p class="text-xs text-secondary-500">Delivery within {{ $zone->estimated_days }}</p>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-5">
                <label class="block text-sm font-semibold text-primary mb-2">Order Notes <span class="text-secondary-400 font-normal">(optional)</span></label>
                <textarea name="notes" rows="3" placeholder="Gift wrapping, delivery time, etc." class="w-full px-4 py-3 bg-light border rounded-xl text-sm text-primary placeholder-secondary-300 focus:border-accent focus:ring-2 focus:ring-accent/20 resize-none @error('notes') border-danger @enderror">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" onclick="goStep(0)" class="px-6 py-3 rounded-xl border border-secondary-200 text-secondary-600 font-medium hover:bg-light transition">Back</button>
                <button type="button" onclick="goStep(2)" class="bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition">Continue to Payment</button>
            </div>
        </section>

        {{-- ============ STEP 3: PAYMENT ============ --}}
        <section class="checkout-step lg:col-span-2 bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6 hidden" data-step="2">
            <h2 class="font-heading text-lg font-semibold text-primary mb-5 flex items-center gap-2"><i class="fas fa-credit-card text-accent-600"></i> Payment Method</h2>
            <div class="grid sm:grid-cols-2 gap-3">
                @foreach([['cod','fa-money-bill-wave','Cash on Delivery','Pay when you receive.'],['online','fa-globe','Pay Online','Card / mobile banking.']] as $pm)
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $pm[0] }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-secondary-200 rounded-xl peer-checked:border-accent peer-checked:bg-accent-50 transition">
                            <div class="flex items-center gap-2 mb-1"><i class="fas {{ $pm[1] }} text-primary-500"></i><span class="font-semibold text-primary">{{ $pm[2] }}</span></div>
                            <p class="text-xs text-secondary-500">{{ $pm[3] }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-secondary-400 mt-3"><i class="fas fa-lock text-accent-600"></i> Payments are encrypted & secure.</p>

            <div class="flex justify-between mt-6">
                <button type="button" onclick="goStep(1)" class="px-6 py-3 rounded-xl border border-secondary-200 text-secondary-600 font-medium hover:bg-light transition">Back</button>
                <button type="button" onclick="goStep(3)" class="bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition">Review Order</button>
            </div>
        </section>

        {{-- ============ STEP 4: REVIEW ============ --}}
        <section class="checkout-step lg:col-span-2 bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6 hidden" data-step="3">
            <h2 class="font-heading text-lg font-semibold text-primary mb-5 flex items-center gap-2"><i class="fas fa-clipboard-check text-accent-600"></i> Review & Place Order</h2>
            <div id="reviewSummary" class="space-y-3 text-sm"></div>
            <div class="flex justify-between mt-6">
                <button type="button" onclick="goStep(2)" class="px-6 py-3 rounded-xl border border-secondary-200 text-secondary-600 font-medium hover:bg-light transition">Back</button>
                <button type="submit" class="bg-accent text-primary px-8 py-3 rounded-xl font-semibold hover:bg-accent-600 transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-lock"></i> Place Order
                </button>
            </div>
        </section>

        {{-- ============ ORDER SUMMARY SIDEBAR ============ --}}
        <aside class="lg:col-span-1">
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6 lg:sticky lg:top-28">
                <h2 class="font-heading text-lg font-semibold text-primary mb-4">Order Summary</h2>

                <div class="space-y-3 max-h-64 overflow-y-auto mb-4 pr-1 qv-scroll">
                    @foreach($cart->items ?? [] as $item)
                        <div class="flex gap-3">
                            <div class="w-14 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-light border border-secondary-100">
                                @if($item->product)<img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">@endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-primary line-clamp-2">{{ $item->product->name ?? 'Item' }}</h4>
                                @if($item->variant)
                                    <p class="text-xs text-secondary-500">
                                        @if($item->variant->size)Size: {{ $item->variant->size->name }}@endif
                                        @if($item->variant->size && $item->variant->color) · @endif
                                        @if($item->variant->color)Color: {{ $item->variant->color->name }}@endif
                                    </p>
                                @endif
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-secondary-500">Qty: {{ $item->quantity }}</span>
                                    <span class="text-sm font-semibold text-primary">{{ money($item->total_price ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="#" onclick="openCartDrawer(); return false;" class="text-accent-600 text-sm font-medium hover:text-accent-700 transition mb-4 inline-block"><i class="fas fa-pen"></i> Edit Cart</a>

                <div class="mb-4">
                    <div class="flex gap-2">
                        <input type="text" name="coupon" id="couponCode" placeholder="Coupon code" value="{{ old('coupon') }}"
                            class="flex-1 h-11 px-4 bg-light border border-secondary-200 rounded-xl text-sm text-primary placeholder-secondary-300 focus:border-accent focus:ring-2 focus:ring-accent/20">
                        <button type="button" id="applyCouponBtn" class="h-11 px-4 bg-light text-secondary-600 border border-secondary-200 rounded-xl font-semibold text-sm hover:bg-primary-50 hover:text-primary transition">Apply</button>
                    </div>
                    <div id="couponMessage" class="mt-2 text-sm hidden"></div>
                </div>

                <div class="space-y-2 border-t border-secondary-100 pt-4 text-sm">
                    <div class="flex justify-between"><span class="text-secondary-500">Subtotal</span><span class="font-medium text-primary" id="subtotalAmount">{{ money($cart->subtotal ?? 0) }}</span></div>
                    <div class="flex justify-between"><span class="text-secondary-500">Shipping</span><span class="font-medium text-primary" id="shippingCost">{{ money($shippingZones->first()->shipping_cost ?? 0) }}</span></div>
                    <div class="flex justify-between text-accent-600 hidden" id="discountRow"><span>Discount</span><span class="font-semibold" id="discountAmount">-৳0</span></div>
                    <div class="h-px bg-secondary-100 my-2"></div>
                    <div class="flex justify-between"><span class="text-base font-bold text-primary">Total</span><span class="font-heading text-xl font-bold text-primary" id="totalAmount">{{ money(($cart->subtotal ?? 0) + ($shippingZones->first()->shipping_cost ?? 0)) }}</span></div>
                </div>

                <p class="text-xs text-secondary-400 text-center mt-4"><i class="fas fa-lock text-accent-600"></i> Secure Checkout — your data is encrypted.</p>
            </div>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ---- Step wizard ----
    let currentStep = 0;
    const steps = document.querySelectorAll('.checkout-step');
    function goStep(n) {
        if (n > currentStep && !validateSteps(currentStep, n)) return;
        currentStep = n;
        steps.forEach(s => s.classList.toggle('hidden', +s.dataset.step !== n));
        document.querySelectorAll('#stepIndicator li').forEach(li => {
            const i = +li.dataset.step;
            const dot = li.querySelector('.step-dot');
            const label = li.querySelector('.step-label');
            const bar = li.querySelector('.step-bar');
            dot.className = 'step-dot w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold border-2 transition ' +
                (i < n ? 'bg-primary border-primary text-surface-elevated' : (i === n ? 'bg-accent border-accent text-primary' : 'bg-surface-elevated border-secondary-200 text-secondary-400'));
            if (label) label.className = 'step-label hidden sm:block text-sm font-medium ' + (i <= n ? 'text-primary' : 'text-secondary-400');
            if (bar) bar.className = 'step-bar flex-1 h-0.5 mx-2 ' + (i < n ? 'bg-accent' : 'bg-secondary-200');
        });
        if (n === 3) buildReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function validateSteps(from, to) {
        // validate all required fields in steps between from..to
        for (let s = from; s < to; s++) {
            const sec = document.querySelector(`.checkout-step[data-step="${s}"]`);
            const required = sec.querySelectorAll('[required]');
            let ok = true;
            required.forEach(el => {
                if (!el.value.trim()) { ok = false; el.classList.add('border-danger'); }
                else el.classList.remove('border-danger');
            });
            if (!ok) {
                // jump to first invalid step
                goStep(from);
                alert('Please fill in all required fields.');
                return false;
            }
        }
        return true;
    }
    function buildReview() {
        const f = document.getElementById('checkoutForm');
        const name = f.name.value, phone = f.phone.value, address = f.address.value, city = f.city.value;
        const zone = f.querySelector('input[name="delivery_zone"]:checked');
        const pm = f.querySelector('input[name="payment_method"]:checked');
        document.getElementById('reviewSummary').innerHTML = `
            <div class="p-3 bg-light rounded-xl border border-secondary-100">
                <p class="font-semibold text-primary mb-1">Ship To</p>
                <p class="text-secondary-600">${name || '—'}, ${phone || ''}</p>
                <p class="text-secondary-600">${address || ''}, ${city || ''}</p>
            </div>
            <div class="p-3 bg-light rounded-xl border border-secondary-100">
                <p class="font-semibold text-primary mb-1">Delivery</p>
                <p class="text-secondary-600">${zone ? zone.closest('label').querySelector('.font-semibold').textContent.trim() : '—'}</p>
            </div>
            <div class="p-3 bg-light rounded-xl border border-secondary-100">
                <p class="font-semibold text-primary mb-1">Payment</p>
                <p class="text-secondary-600">${pm ? pm.closest('label').querySelector('.font-semibold').textContent.trim() : '—'}</p>
            </div>`;
    }

    // ---- Saved address toggle ----
    const addrRadios = document.querySelectorAll('input[name="address_id"]');
    addrRadios.forEach(r => r.addEventListener('change', () => {
        const newSel = document.querySelector('input[name="address_id"][value="new"]').checked;
        document.getElementById('newAddressFields').classList.toggle('hidden', !newSel);
    }));

    // ---- Coupon + totals (from original) ----
    let currentDiscount = 0;
    let currentShippingCost = "{{ $shippingZones->first()->shipping_cost ?? 0 }}";
    const cartSubtotal = "{{ $cart->subtotal ?? 0 }}";
    function updateTotals() {
        const total = parseFloat(cartSubtotal) + parseFloat(currentShippingCost) - parseFloat(currentDiscount);
        document.getElementById('totalAmount').textContent = '৳' + total.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    }
    document.querySelectorAll('input[name="delivery_zone"]').forEach(r => r.addEventListener('change', function() {
        currentShippingCost = parseFloat(this.dataset.cost);
        document.getElementById('shippingCost').textContent = '৳' + currentShippingCost.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        updateTotals();
    }));
    document.getElementById('applyCouponBtn').addEventListener('click', async function() {
        const code = document.getElementById('couponCode').value.trim();
        const msg = document.getElementById('couponMessage');
        if (!code) { showCoupon('Please enter a coupon code', 'error'); return; }
        this.disabled = true; this.textContent = 'Checking...';
        try {
            const res = await fetch("{{ route('checkout.validate-coupon') }}", {
                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
                body: JSON.stringify({coupon:code, subtotal:cartSubtotal})
            });
            const data = await res.json();
            if (res.ok && data.success) {
                currentDiscount = data.discount;
                document.getElementById('discountRow').classList.remove('hidden');
                document.getElementById('discountAmount').textContent = '-' + data.discount_formatted;
                updateTotals();
                showCoupon(data.message, 'success');
                this.textContent = 'Remove'; this.classList.add('bg-danger-50','text-danger-600','border-danger-200');
                this.onclick = removeCoupon;
                document.getElementById('couponCode').disabled = true;
            } else { showCoupon(data.message || 'Invalid coupon', 'error'); this.textContent = 'Apply'; }
        } catch(e) { showCoupon('Error validating coupon.', 'error'); this.textContent = 'Apply'; }
        finally { this.disabled = false; }
    });
    function removeCoupon() {
        currentDiscount = 0;
        document.getElementById('discountRow').classList.add('hidden');
        updateTotals();
        const btn = document.getElementById('applyCouponBtn');
        btn.textContent = 'Apply'; btn.classList.remove('bg-danger-50','text-danger-600','border-danger-200');
        btn.onclick = null;
        const inp = document.getElementById('couponCode'); inp.disabled = false; inp.value = '';
        document.getElementById('couponMessage').classList.add('hidden');
    }
    function showCoupon(message, type) {
        const m = document.getElementById('couponMessage');
        m.textContent = message; m.classList.remove('hidden');
        m.className = 'mt-2 text-sm ' + (type === 'success' ? 'text-accent-600' : 'text-danger-600');
    }

    // Prevent implicit submit on Enter; require final button
    document.getElementById('checkoutForm').addEventListener('keydown', e => { if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') e.preventDefault(); });
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        if (!validateSteps(0, 3)) { e.preventDefault(); }
    });
</script>
@endpush
