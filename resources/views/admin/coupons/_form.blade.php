@php
    $isEdit = isset($coupon);
    $selectedCategories = old('applicable_categories', $isEdit && $coupon->applicable_categories ? $coupon->applicable_categories : []);
    $selectedProducts = old('applicable_products', $isEdit && $coupon->applicable_products ? $coupon->applicable_products : []);
@endphp

<form action="{{ $isEdit ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}"
    method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Left: core details --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-secondary-800 uppercase tracking-wide">Coupon Details</h3>

            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Code *</label>
                <div class="flex gap-2">
                    <input type="text" name="code" id="couponCode" required
                        value="{{ old('code', $coupon->code ?? '') }}"
                        placeholder="SUMMER25"
                        class="flex-1 block px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition uppercase tracking-wide font-mono">
                    <button type="button" onclick="generateCode()"
                        class="px-4 py-2.5 text-sm font-medium text-primary bg-accent-50 rounded-lg hover:bg-accent-100 transition whitespace-nowrap">
                        <i class="fas fa-dice mr-1"></i> Generate
                    </button>
                </div>
                @error('code') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <x-input name="name" type="text" label="Name *" :value="old('name', $coupon->name ?? '')" placeholder="Summer Sale 2026" required />
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Description</label>
                <textarea name="description" rows="2"
                    class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">{{ old('description', $coupon->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Start Date</label>
                    <input type="datetime-local" name="starts_at"
                        value="{{ old('starts_at', $isEdit && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}"
                        class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                    @error('starts_at') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">End Date</label>
                    <input type="datetime-local" name="expires_at"
                        value="{{ old('expires_at', $isEdit && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"
                        class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                    @error('expires_at') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-secondary-800 uppercase tracking-wide">Product / Category Restrictions</h3>
            <p class="text-xs text-secondary-500 -mt-2">Leave empty to apply the coupon to the entire cart.</p>

            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Restrict to Categories</label>
                <select name="applicable_categories[]" multiple size="5"
                    class="block w-full rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition p-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Restrict to Products</label>
                <select name="applicable_products[]" multiple size="6"
                    class="block w-full rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition p-2">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                            {{ $product->name }}@if($product->sku) ({{ $product->sku }})@endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Right: value + limits --}}
    <div class="space-y-5">
        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-secondary-800 uppercase tracking-wide">Discount</h3>

            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Type *</label>
                <div class="grid grid-cols-2 gap-2" x-data="{ type: '{{ old('type', $coupon->type->value ?? 'percentage') }}' }">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="percentage" x-model="type" class="peer sr-only">
                        <div class="text-center px-3 py-2.5 rounded-lg border border-secondary-300 text-sm font-medium text-secondary-700 peer-checked:border-primary peer-checked:bg-primary-50 peer-checked:text-primary transition">Percentage</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="fixed" x-model="type" class="peer sr-only">
                        <div class="text-center px-3 py-2.5 rounded-lg border border-secondary-300 text-sm font-medium text-secondary-700 peer-checked:border-primary peer-checked:bg-primary-50 peer-checked:text-primary transition">Fixed Amount</div>
                    </label>
                </div>
            </div>

            <x-input name="value" type="number" step="0.01" min="0" label="Value *"
                :value="old('value', $coupon->value ?? '')" placeholder="10" required />

            <x-input name="minimum_order_amount" type="number" step="0.01" min="0" label="Minimum Order Amount"
                :value="old('minimum_order_amount', $coupon->minimum_order_amount ?? '')" placeholder="0" />

            <x-input name="maximum_discount" type="number" step="0.01" min="0" label="Max Discount Cap (percentage only)"
                :value="old('maximum_discount', $coupon->maximum_discount ?? '')" placeholder="Leave blank = no cap" />
        </div>

        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-secondary-800 uppercase tracking-wide">Usage Limits</h3>

            <x-input name="usage_limit" type="number" min="1" label="Total Usage Limit"
                :value="old('usage_limit', $coupon->usage_limit ?? '')" placeholder="Leave blank = unlimited" />

            <x-input name="usage_limit_per_user" type="number" min="1" label="Per-User Usage Limit"
                :value="old('usage_limit_per_user', $coupon->usage_limit_per_user ?? 1)" placeholder="1" />

            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                    class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                <span class="ml-2 text-sm text-secondary-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}"
                class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</a>
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition">
                <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Update' : 'Create' }} Coupon
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function generateCode() {
        fetch('{{ route('admin.coupons.generate-code') }}', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.code) {
                document.getElementById('couponCode').value = data.code;
            }
        });
    }
</script>
@endpush
