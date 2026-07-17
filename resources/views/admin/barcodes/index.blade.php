@extends('admin.layouts.app')

@section('title', 'Print Barcode')

@section('content')

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">
                    Print Barcode
                </h1>

                <p class="text-secondary-500">
                    Generate and print barcode labels for products
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[420px_minmax(0,1fr)] gap-6 items-start">

        {{-- Left Side --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-fit">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-primary">
                    Product Information
                </h2>
            </div>

            <div class="p-6">

                <form id="productForm" class="space-y-6">

                    {{-- Product Select --}}
                    <div>
                        <label for="product" class="block text-sm font-semibold text-secondary-700 mb-2">
                            Select Product
                        </label>

                        <select name="variant_id" id="product" class="w-full" required>

                            <option value="" disabled selected>
                                Select a product
                            </option>

                            @foreach ($products as $product)

                                @if($product->variants->count() == 0)

                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-variant=""
                                        data-price="{{ $product->price }}" data-stock="{{ $product->currentStock }}"
                                        data-sku="{{ $product->sku }}">

                                        {{ $product->name }}
                                        |
                                        Stock: {{ $product->currentStock }}

                                    </option>

                                @else

                                    @foreach ($product->variants as $variant)

                                        <option value="{{ $variant->id }}" data-name="{{ $product->name }}"
                                            data-variant="{{ $variant->variantName }}" data-price="{{ $variant->price }}"
                                            data-stock="{{ $variant->currentStock }}" data-sku="{{ $variant->sku }}">

                                            {{ $product->name }}
                                            |
                                            {{ $variant->variantName }}
                                            |
                                            Stock: {{ $variant->currentStock }}

                                        </option>

                                    @endforeach

                                @endif

                            @endforeach

                        </select>
                    </div>

                    {{-- Product Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-semibold text-secondary-700 mb-2">
                                Product Name
                            </label>

                            <input type="text" id="name"
                                class="w-full px-4 py-3 border border-secondary-300 rounded-xl bg-secondary-50 text-secondary-700"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-secondary-700 mb-2">
                                Variant
                            </label>

                            <input type="text" id="variant"
                                class="w-full px-4 py-3 border border-secondary-300 rounded-xl bg-secondary-50 text-secondary-700"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-secondary-700 mb-2">
                                SKU
                            </label>

                            <input type="text" id="sku"
                                class="w-full px-4 py-3 border border-secondary-300 rounded-xl bg-secondary-50 text-secondary-700"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-secondary-700 mb-2">
                                Price
                            </label>

                            <input type="text" id="price"
                                class="w-full px-4 py-3 border border-secondary-300 rounded-xl bg-secondary-50 text-secondary-700"
                                readonly>
                        </div>

                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm font-semibold text-secondary-700 mb-2">
                            Number of Labels
                        </label>

                        <input type="number" id="qty" name="quantity" min="1" value="1" required
                            class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary transition">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                        <button type="button" id="generate"
                            class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap px-5 py-3 bg-primary hover:bg-primary-700 text-white font-semibold rounded-xl transition">

                            <i class="fas fa-eye text-sm"></i>

                            <span>
                                Preview Labels
                            </span>

                        </button>

                        <button type="button" id="printBtn" disabled
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap px-5 py-3 bg-gray-900 hover:bg-black text-white font-semibold rounded-xl transition opacity-50 cursor-not-allowed sm:w-auto">

                            <i class="fas fa-print text-sm"></i>

                            <span>
                                Print
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- Preview --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[70vh]">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100 shrink-0">
                <h2 class="text-lg font-semibold text-primary">
                    Barcode Preview
                </h2>
            </div>

            {{-- Scrollable Preview Area --}}
            <div id="labelsContainer" class="flex-1 overflow-y-auto p-5 bg-secondary-50/50">

                <div class="flex flex-col items-center justify-center h-full text-center">

                    <i class="fas fa-barcode text-5xl text-secondary-300 mb-4"></i>

                    <h3 class="text-lg font-semibold text-secondary-700 mb-2">
                        No Preview Generated
                    </h3>

                    <p class="text-sm text-secondary-500">
                        Select a product and generate barcode labels.
                    </p>

                </div>

            </div>

        </div>

    </div>

@endsection

@push('styles')

    {{-- Select2 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .label-preview {
            width: 220px;
            min-height: 150px;
            border-radius: 16px;
            transition: all .2s ease;
        }

        .label-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }

        /* Select2 */

        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 52px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 14px !important;
            display: flex !important;
            align-items: center !important;
            transition: .2s ease !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 50px !important;
            padding-left: 16px !important;
            color: #111827 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
            right: 14px !important;
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 14px !important;
            overflow: hidden !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08) !important;
        }

        .select2-search--dropdown {
            padding: 12px !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
        }

        .select2-results__option {
            padding: 12px 16px !important;
            font-size: 14px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: #2563eb !important;
        }

        #labelsContainer::-webkit-scrollbar {
            width: 8px;
        }

        #labelsContainer::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 999px;
        }

        #labelsContainer::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        #labelsContainer::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

@endpush

@push('scripts')
    {{-- Select2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- JsBarcode --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <script>

        $(document).ready(function () {

            $('#product').select2({
                placeholder: 'Search and select product',
                width: '100%'
            });

        });

        document.addEventListener('DOMContentLoaded', () => {

            const productSelect = document.getElementById('product');
            const nameInput = document.getElementById('name');
            const variantInput = document.getElementById('variant');
            const skuInput = document.getElementById('sku');
            const priceInput = document.getElementById('price');
            const qtyInput = document.getElementById('qty');
            const labelsDiv = document.getElementById('labelsContainer');
            const printBtn = document.getElementById('printBtn');
            const siteName = "{{ $siteName }}";

            console.log(siteName);
            

            $(productSelect).on('select2:select', function (e) {

                const selectedOption = e.params.data.element;
                const opt = selectedOption ? $(selectedOption) : null;

                if (opt) {

                    nameInput.value = opt.data('name');
                    variantInput.value = opt.data('variant');
                    skuInput.value = opt.data('sku');
                    priceInput.value = opt.data('price');

                    qtyInput.value = opt.data('stock');

                }

            });

            document.getElementById('generate').addEventListener('click', function () {

                labelsDiv.innerHTML = `
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                        `;

                const grid = labelsDiv.querySelector('div');

                let qty = parseInt(qtyInput.value);

                const sku = skuInput.value;
                const name = nameInput.value;
                const variant = variantInput.value;
                const price = priceInput.value;

                if (!qty || qty <= 0) {
                    qty = 1;
                }

                for (let i = 0; i < qty; i++) {

                    const wrap = document.createElement('div');

                    wrap.className = 'label-preview bg-white border border-secondary-200 p-4 text-center mb-4 shadow-sm';

                    wrap.innerHTML = `
                                        <div class="text-xs font-bold text-primary mb-1">
                                            ${siteName}
                                        </div>
                                        <div class="text-[13px] font-bold text-primary truncate">
                                            ${name}
                                        </div>

                                        <div class="text-[10px] text-secondary-500 mb-2 truncate">
                                            ${variant || 'Standard Variant'}
                                        </div>

                                        <div class="flex justify-center py-1">
                                            <svg class="barcode"
                                                jsbarcode-value="${sku}"
                                                jsbarcode-width="1.4"
                                                jsbarcode-height="42"
                                                jsbarcode-fontSize="10"
                                                jsbarcode-margin="0">
                                            </svg>
                                        </div>

                                        <div class="flex justify-between items-center mt-2 text-[11px]">
                                            <span class="text-secondary-500">
                                                SKU
                                            </span>

                                            <span class="font-semibold text-secondary-800">
                                                ${sku}
                                            </span>
                                        </div>

                                        <div class="mt-2 text-sm font-bold text-primary">
                                            ৳ ${price}
                                        </div>

                                    `;

                    grid.appendChild(wrap);

                }

                JsBarcode(".barcode").init();

                printBtn.disabled = false;

                printBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            });

            $('#printBtn').on('click', function () {

                const quantity = qtyInput.value;

                if (!quantity || quantity <= 0) {
                    return;
                }

                const url =
                    "{{ route('admin.products.printBarcodeLabels') }}" +
                    "?sku=" + skuInput.value +
                    "&quantity=" + quantity;

                window.open(url, '_blank');

                resetForm();

            });

            function resetForm() {

                labelsDiv.innerHTML = `

                                    <div class="flex flex-col items-center justify-center py-20 text-center">

                                        <i class="fas fa-barcode text-5xl text-secondary-300 mb-4"></i>

                                        <h3 class="text-lg font-semibold text-secondary-700 mb-2">
                                            No Preview Generated
                                        </h3>

                                        <p class="text-sm text-secondary-500">
                                            Select a product and generate barcode labels.
                                        </p>

                                    </div>

                                `;

                printBtn.disabled = true;

                printBtn.classList.add('opacity-50', 'cursor-not-allowed');

                $('#productForm')[0].reset();

                $('#product').val(null).trigger('change');

            }

        });

    </script>

@endpush