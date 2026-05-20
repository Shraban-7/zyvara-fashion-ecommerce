@extends('admin.layouts.app')
@section('title', 'Manage Stock')
@section('content')
    @push('styles')
        <style>
            .radio-input:checked+.radio-label {
                background-color: white;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            input[value="add"].radio-input:checked+.radio-label {
                color: #16a34a;
            }

            input[value="remove"].radio-input:checked+.radio-label {
                color: #dc2626;
            }
        </style>
    @endpush

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Manage Stock</h1>
                    <p class="text-gray-500">{{ $product->name }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.stock-history', $product) }}"
                        class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-history mr-2"></i>Stock History
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Edit
                    </a>
                </div>
            </div>
        </div>


        @if($product->variants->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 max-w-2xl">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Base Product Stock</h2>
                        <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $product->sku ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] font-medium text-gray-500 block uppercase tracking-wider">Current</span>
                        <span class="text-2xl font-black text-gray-900"
                            id="product-stock-{{ $product->id }}">{{ $product->currentStock }}</span>
                    </div>
                </div>

                <form class="stock-form" data-product-id="{{ $product->id }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="grid grid-cols-12 gap-3 items-end">
                        <!-- Action -->
                        <div class="col-span-3">
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Action</label>
                            <div class="flex p-0.5 bg-gray-100 rounded-lg">
                                <label class="flex-1 cursor-pointer text-center">
                                    <input type="radio" name="action_type" value="add" class="sr-only peer" checked>
                                    <span
                                        class="block py-1 text-xs font-medium text-gray-600 rounded-md peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm transition">Add</span>
                                </label>
                                <label class="flex-1 cursor-pointer text-center">
                                    <input type="radio" name="action_type" value="remove" class="sr-only peer">
                                    <span
                                        class="block py-1 text-xs font-medium text-gray-600 rounded-md peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm transition">Remove</span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-3">
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Qty</label>
                            <input type="number" name="quantity" min="1" required placeholder="Qty"
                                class="quantity-input w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        </div>

                        <!-- Note -->
                        <div class="col-span-3">
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Note <span
                                    class="text-gray-400 font-normal">(opt)</span></label>
                            <input type="text" name="note" placeholder="Reason..."
                                class="w-full px-2.5 py-1.5 text-xs border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-span-3">
                            <button type="submit" disabled
                                class="submit-btn w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition shadow-sm text-xs font-semibold">

                                <i class="fas fa-plus text-[10px]"></i>

                                <span class="btn-text">
                                    Add
                                </span>

                                <span class="preview-stock text-[10px] opacity-90">
                                    (+0)
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- Product Variants Stock List (Compact Rows) --}}
        @if($product->variants->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm ">
                {{-- Prevent horizontal scroll --}}
                <div class="divide-y divide-gray-100">
                    @foreach($product->variants as $variant)
                        <div class="p-4 hover:bg-gray-50/50 transition">
                            <form class="stock-form" data-variant-id="{{ $variant->id }}">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $variant->id }}">

                                {{-- Responsive Layout --}}
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">

                                    <!-- Variant Info -->
                                    <div class="lg:col-span-3">
                                        <h3 class="text-xs font-bold text-gray-900 truncate" title="{{ $variant->variant_name }}">
                                            {{ $variant->variant_name }}
                                        </h3>

                                        <p class="text-[10px] text-gray-400 truncate mt-0.5">
                                            SKU: {{ $variant->sku ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <!-- Current Stock -->
                                    <div class="lg:col-span-1 text-center lg:border-r border-gray-100 lg:pr-2">
                                        <span class="text-[10px] text-gray-400 block uppercase">Current Stock</span>

                                        <span class="text-sm font-black text-gray-800 stock-display"
                                            id="variant-stock-{{ $variant->id }}">
                                            {{ $variant->currentStock }}
                                        </span>
                                    </div>

                                    <!-- Action Toggle -->
                                    <div class="lg:col-span-2">
                                        <div class="grid grid-cols-2 gap-1 p-0.5 bg-gray-100 rounded-lg">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="action_type" value="add" class="hidden radio-input"
                                                    checked>
                                                <span
                                                    class="block py-1 text-center text-[11px] font-semibold text-gray-500 rounded-md radio-label transition">
                                                    <i class="fas fa-plus"></i>
                                                    <span class="hidden lg:inline ml-1">Add</span>
                                                </span>
                                            </label>

                                            <label class="cursor-pointer">
                                                <input type="radio" name="action_type" value="remove" class="hidden radio-input">
                                                <span
                                                    class="block py-1 text-center text-[11px] font-semibold text-gray-500 rounded-md radio-label transition">
                                                    <i class="fas fa-minus"></i>
                                                    <span class="hidden lg:inline ml-1">Remove</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="lg:col-span-2">
                                        <input type="number" name="quantity" min="1" required placeholder="Qty"
                                            class="quantity-input w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                    </div>

                                    <!-- Note -->
                                    <div class="lg:col-span-3">
                                        <input type="text" name="note" placeholder="Note (optional)"
                                            class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="lg:col-span-1">
                                        <button type="submit" disabled
                                            class="submit-btn w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition shadow-sm text-xs font-semibold">

                                            <i class="fas fa-plus text-[10px]"></i>

                                            <span class="btn-text">
                                                Add
                                            </span>

                                            <span class="preview-stock text-[10px] opacity-90">
                                                (+0)
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>

            </div>
        @endif
    </div>



    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const forms = document.querySelectorAll('.stock-form');

                forms.forEach(form => {

                    const quantityInput = form.querySelector('.quantity-input');
                    const actionRadios = form.querySelectorAll('input[name="action_type"]');
                    const submitBtn = form.querySelector('.submit-btn');
                    const btnText = form.querySelector('.btn-text');
                    const previewStock = form.querySelector('.preview-stock');

                    const variantId = form.dataset.variantId;
                    const productId = form.dataset.productId;

                    function getCurrentStock() {

                        let stockElement = null;

                        if (variantId) {
                            stockElement = document.getElementById('variant-stock-' + variantId);
                        } else if (productId) {
                            stockElement = document.getElementById('product-stock-' + productId);
                        }

                        return parseInt(stockElement?.textContent || 0);
                    }

                    function updateButtonState() {

                        const quantity = parseInt(quantityInput.value || 0);

                        const actionEl = form.querySelector('input[name="action_type"]:checked');
                        if (!actionEl) return;

                        const actionType = actionEl.value;

                        const currentStock = getCurrentStock();

                        let finalStock = currentStock;

                        // Disable if invalid quantity
                        if (quantity <= 0 || isNaN(quantity)) {

                            submitBtn.disabled = true;

                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                        } else {

                            submitBtn.disabled = false;

                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }

                        if (actionType === 'add') {

                            finalStock += quantity;

                            submitBtn.classList.remove(
                                'bg-red-600',
                                'hover:bg-red-700',
                                'active:bg-red-800'
                            );

                            submitBtn.classList.add(
                                'bg-green-600',
                                'hover:bg-green-700',
                                'active:bg-green-800'
                            );

                            btnText.textContent = 'Add';

                            previewStock.textContent = quantity > 0
                                ? `(${currentStock} → ${finalStock})`
                                : '(+0)';

                        } else {

                            finalStock -= quantity;

                            submitBtn.classList.remove(
                                'bg-green-600',
                                'hover:bg-green-700',
                                'active:bg-green-800'
                            );

                            submitBtn.classList.add(
                                'bg-red-600',
                                'hover:bg-red-700',
                                'active:bg-red-800'
                            );

                            btnText.textContent = 'Remove';

                            previewStock.textContent = quantity > 0
                                ? `(${currentStock} → ${finalStock})`
                                : '(-0)';
                        }
                    }

                    // Events
                    quantityInput?.addEventListener('input', updateButtonState);

                    actionRadios.forEach(radio => {
                        radio.addEventListener('change', updateButtonState);
                    });

                    updateButtonState();

                    form.addEventListener('submit', async function (e) {

                        e.preventDefault();

                        const originalHTML = submitBtn.innerHTML;

                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';

                        try {

                            const formData = new FormData(form);

                            const actionType = formData.get('action_type');

                            const route = actionType === 'add'
                                ? '{{ route("admin.products.stock.add") }}'
                                : '{{ route("admin.products.stock.remove") }}';

                            formData.delete('action_type');

                            const response = await fetch(route, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            });

                            const data = await response.json();

                            if (data.success) {

                                const productId = formData.get('product_id');
                                const variantId = formData.get('variant_id');

                                if (variantId) {

                                    const stockDisplay =
                                        document.getElementById('variant-stock-' + variantId);

                                    if (stockDisplay) {
                                        stockDisplay.textContent = data.stock_after;
                                    }

                                } else if (productId) {

                                    const stockDisplay =
                                        document.getElementById('product-stock-' + productId);

                                    if (stockDisplay) {
                                        stockDisplay.textContent = data.stock_after;
                                    }
                                }

                                showToast('success', data.message);

                            } else {

                                showToast('error', data.message || 'An error occurred');
                            }

                        } catch (error) {

                            showToast('error', 'An error occurred. Please try again.');

                        } finally {

                            submitBtn.disabled = false;

                            submitBtn.innerHTML = `
                                <i class="fas fa-plus text-[10px]"></i>
                                <span class="btn-text">Add</span>
                                <span class="preview-stock text-[10px] opacity-90">(+0)</span>
                            `;

                            form.reset();

                            const defaultAdd = form.querySelector(
                                'input[name="action_type"][value="add"]'
                            );

                            if (defaultAdd) {
                                defaultAdd.checked = true;
                            }

                            // reset styling
                            submitBtn.classList.remove(
                                'opacity-50',
                                'cursor-not-allowed',
                                'bg-red-600',
                                'hover:bg-red-700',
                                'active:bg-red-800'
                            );

                            submitBtn.classList.add(
                                'bg-green-600',
                                'hover:bg-green-700',
                                'active:bg-green-800'
                            );

                            updateButtonState();
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection