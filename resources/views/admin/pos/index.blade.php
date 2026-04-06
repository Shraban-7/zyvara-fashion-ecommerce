@extends('admin.layouts.app')

@section('title', 'Point of Sale')

@section('content')
    <div class="pos-container h-screen flex flex-col" id="posSystem">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 px-4 py-3 shrink-0">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-cash-register text-blue-600"></i>
                    Point of Sale
                </h1>

                <div class="flex items-center gap-3">
                    <button id="clearCartBtn"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        <i class="fas fa-refresh mr-2"></i>New Sale
                    </button>
                    <button
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        <i class="fas fa-history mr-2"></i>Orders
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex overflow-hidden">
            {{-- Products Section --}}
            <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
                {{-- Search and Filters --}}
                <div class="bg-white border-b border-gray-200 p-4 shrink-0">
                    <div class="flex flex-col sm:flex-row gap-3">

                        <!-- SEARCH (NAME ONLY) -->
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="searchInput" placeholder="Search products by name..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- SKU SCANNER FIELD (NEW) -->
                        <div class="flex-1 relative">
                            <i class="fas fa-barcode absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="skuInput" placeholder="Scan or enter SKU..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- CATEGORY FILTER -->
                        <select id="categoryFilter"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Products Grid --}}
                <div class="flex-1 overflow-y-auto p-4">
                    <div id="productsGrid"
                        class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-2">
                        @foreach($products as $product)
                            <x-pos-product-card :product="$product" />
                        @endforeach
                    </div>

                    {{-- No Products Found --}}
                    <div id="noProducts" class="hidden flex-col items-center justify-center py-16">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No products found</p>
                    </div>
                </div>
            </div>

            {{-- Cart Sidebar --}}
            <div class="w-full sm:w-96 lg:w-105 bg-white border-l border-gray-200 flex flex-col">
                {{-- Customer Info --}}
                <div class="p-4 border-b border-gray-200 bg-gray-50 relative">

                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        Customer
                    </label>

                    <div class="grid grid-cols-2 gap-2">

                        <!-- Customer Name -->
                        <div class="relative">
                            <input type="text" id="customerName" name="customer_name" placeholder="Customer Name"
                                autocomplete="off"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg 
                                                                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div id="customerNameDropdown"
                                class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden max-h-60 overflow-y-auto">
                            </div>
                        </div>

                        <!-- Customer Phone -->
                        <div class="relative">
                            <input type="text" id="customerPhone" name="customer_phone" placeholder="Phone Number"
                                autocomplete="off"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg 
                                                                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div id="customerPhoneDropdown"
                                class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden max-h-60 overflow-y-auto">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Cart Items --}}
                <div class="flex-1 overflow-y-auto p-4">
                    <h3 class="text-sm font-semibold text-gray-700">
                        Cart Items (<span id="cartItemCount">0</span>)
                    </h3>

                    <!-- EMPTY STATE -->
                    <div id="emptyCart" class="flex flex-col items-center justify-center py-16 text-center">
                        <i class="fas fa-shopping-cart text-gray-300 text-5xl mb-3"></i>
                        <p class="text-gray-500">Cart is empty</p>
                        <p class="text-gray-400 text-sm mt-1">Add products to continue</p>
                    </div>

                    <!-- DYNAMIC ITEMS -->
                    <div id="cartItemsContainer" class="space-y-2"></div>

                </div>

                {{-- Cart Summary --}}
                <div class="border-t border-gray-200 p-4 bg-gray-50">
                    {{-- Discount Section --}}
                    <div class="mb-4">
                        <div class="flex gap-2 mb-3">

                            <!-- Discount Type -->
                            <select id="discountType"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="fixed">৳ Fixed</option>
                                <option value="percent">%</option>
                            </select>

                            <!-- Discount Input -->
                            <input type="number" id="discountInput" placeholder="Enter discount"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Applied Info -->
                        <div id="discountApplied" class="hidden flex items-center justify-between text-sm text-green-600">
                            <span><i class="fas fa-tag mr-1"></i>Discount Applied</span>
                            <span class="font-semibold">-৳<span id="discountAmount">0.00</span></span>
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">৳<span id="subtotalAmount">0.00</span></span>
                        </div>
                        <div id="discountRow" class="hidden flex justify-between text-sm text-green-600">
                            <span>Discount</span>
                            <span class="font-semibold">-৳<span id="discountDisplay">0.00</span></span>
                        </div>
                        <div class="border-t border-gray-300 pt-2 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-blue-600">৳<span id="totalAmount">0.00</span></span>
                        </div>
                    </div>

                    <div class="flex items-end gap-3 mt-3">

                        <!-- PAID -->
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Due
                                </label>

                                <div class="text-xs font-bold text-red-600">
                                    ৳<span id="dueAmount">0.00</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <input type="number" id="paidAmount" min="0" step="0.01"
                                    class="w-full border rounded px-2 py-2 text-sm focus:outline-none focus:ring"
                                    placeholder="Enter paid amount" />

                                <button type="button" id="fullPaidBtn"
                                    class="bg-green-600 text-white px-2 py-2 rounded text-xs hover:bg-green-700 whitespace-nowrap">
                                    Full Paid
                                </button>
                            </div>
                        </div>

                        <!-- DUE -->


                    </div>

                    <div class="flex justify-between gap-2 mb-2">
                        <!-- CASH RECEIVED -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Cash Received
                            </label>

                            <input type="number" id="cash_received" min="0" step="0.01"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring"
                                placeholder="Enter cash received" />
                        </div>

                        <!-- CASH RETURNED -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Cash Returned
                            </label>

                            <input type="number" id="cash_returned" readonly
                                class="w-full border rounded px-3 py-2 text-sm bg-gray-100" />
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="relative">
                            <select id="employeeId" name="employee_id"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled selected>Choose an employee...</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mb-4">

                        <div class="grid grid-cols-5 gap-2">

                            <!-- NONE / DRAFT -->
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="" class="hidden peer payment_method"
                                    checked>

                                <div class="flex flex-col items-center justify-center py-2 rounded-lg border 
                                        border-gray-300 text-gray-500 text-xs
                                        peer-checked:border-gray-600 peer-checked:bg-gray-100 peer-checked:text-gray-700
                                        transition">
                                    None
                                </div>
                            </label>

                            <!-- CASH -->
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cash" class="hidden peer">

                                <div class="flex flex-col items-center justify-center py-2 rounded-lg border 
                                        border-gray-300 text-gray-600 text-xs
                                        peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600
                                        transition">

                                    Cash
                                </div>
                            </label>

                            <!-- CARD -->
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="card" class="hidden peer">

                                <div class="flex flex-col items-center justify-center py-2 rounded-lg border 
                                        border-gray-300 text-gray-600 text-xs
                                        peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600
                                        transition">

                                    Card
                                </div>
                            </label>

                            <!-- BKASH -->
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="bkash" class="hidden peer">

                                <div class="flex flex-col items-center justify-center py-2 rounded-lg border 
                                        border-gray-300 text-gray-600 text-xs
                                        peer-checked:border-pink-600 peer-checked:bg-pink-50 peer-checked:text-pink-600
                                        transition">
                                    <span class="font-semibold text-xs">bKash</span>

                                </div>
                            </label>

                            <!-- NAGAD -->
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="nagad" class="hidden peer">

                                <div class="flex flex-col items-center justify-center py-2 rounded-lg border 
                                        border-gray-300 text-gray-600 text-xs
                                        peer-checked:border-orange-600 peer-checked:bg-orange-50 peer-checked:text-orange-600
                                        transition">
                                    <span class="font-semibold text-xs">Nagad</span>

                                </div>
                            </label>

                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="grid grid-cols-2 gap-2">
                        <button id="holdOrderBtn" disabled
                            class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <i class="fas fa-pause mr-2"></i>Hold
                        </button>
                        <button id="completeOrderBtn" disabled
                            class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <i class="fas fa-check mr-2"></i>Complete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Variant Selection Modal --}}
        <div id="variantModal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-xl">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 id="modalProductName" class="text-xl font-bold text-gray-900"></h2>
                            <p class="text-gray-500 text-sm mt-1">Select size and color</p>
                        </div>
                        <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                    <div id="modalContent">
                        {{-- Product Image --}}
                        <div class="mb-6">
                            <img id="modalProductImage" src="" alt="" class="w-full h-64 object-cover rounded-lg">
                        </div>

                        {{-- Variants List --}}
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-900 mb-3">Available Variants</h3>
                            <div id="variantsList" class="grid gap-2">
                                {{-- Variants will be rendered here --}}
                            </div>

                            <div id="noVariantsMessage" class="hidden text-center py-8">
                                <p class="text-gray-500">No variants available for this product</p>
                                <button id="addWithoutVariantBtn"
                                    class="mt-4 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                                    Add to Cart - ৳<span id="noVariantPrice">0.00</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function () {

                var selectedProduct = null;
                var paymentMethod = 'cash';

                // =========================
                // INIT
                // =========================
                function init() {
                    attachProductCardHandlers();
                }

                // =========================
                // PRODUCT CLICK
                // =========================
                function attachProductCardHandlers() {
                    $('.product-card').on('click', function () {
                        var productData = $(this).data('product');
                        if (productData) {
                            selectProduct(productData);
                        }
                    });
                }

                /* =========================
                    FILTER HELPERS
                ========================= */

                function matchesSearch(productData, query) {
                    if (!query) return true;
                    return productData.name.toLowerCase().includes(query);
                }

                function matchesCategory(productData, categoryId) {
                    if (!categoryId) return true;
                    return productData.category_id == categoryId;
                }

                /* =========================
                   MAIN FILTER FUNCTION
                ========================= */

                function filterProducts() {
                    var searchQuery = $('#searchInput').val().trim().toLowerCase();
                    var categoryId = $('#categoryFilter').val();

                    var visibleCount = 0;

                    $('.product-card').each(function () {
                        var productData = $(this).data('product');
                        if (!productData) return;

                        var matches =
                            matchesSearch(productData, searchQuery) &&
                            matchesCategory(productData, categoryId);

                        if (matches) {
                            $(this).show();
                            visibleCount++;
                        } else {
                            $(this).hide();
                        }
                    });

                    $('#noProducts')
                        .toggleClass('hidden', visibleCount !== 0)
                        .toggleClass('flex', visibleCount === 0);
                }

                let skuScanLock = false;
                let skuScanTimeout = null;

                $('#skuInput').on('input', function () {
                    let sku = $(this).val().trim().toLowerCase();

                    if (!sku) return;

                    // debounce (wait for scanner burst)
                    clearTimeout(skuScanTimeout);

                    skuScanTimeout = setTimeout(() => {

                        if (skuScanLock) return;

                        let foundProduct = null;
                        let foundVariant = null;

                        $('.product-card').each(function () {
                            let productData = $(this).data('product');
                            if (!productData) return;

                            // PRODUCT SKU MATCH
                            if (productData.sku &&
                                productData.sku.toLowerCase() === sku) {
                                foundProduct = productData;
                            }

                            // VARIANT SKU MATCH
                            if (productData.variants && productData.variants.length) {
                                productData.variants.forEach(v => {
                                    if (v.sku &&
                                        v.sku.toLowerCase() === sku) {
                                        foundProduct = productData;
                                        foundVariant = v;
                                    }
                                });
                            }
                        });

                        if (foundProduct) {
                            skuScanLock = true;

                            $('#skuInput').val('');

                            if (window.posCartManager) {
                                window.posCartManager.addToCart(
                                    foundProduct.id,
                                    foundVariant ? foundVariant.id : null,
                                    1
                                );
                            }

                            // unlock after short delay (prevents duplicate scans)
                            setTimeout(() => {
                                skuScanLock = false;
                            }, 500);
                        }

                    }, 150); // scanner-friendly delay
                });

                $('#searchInput').on('input', filterProducts);
                $('#categoryFilter').on('change', filterProducts);
                // $('#skuInput').on('change', handleSkuInput);

                // =========================
                // PRODUCT SELECT
                // =========================
                function selectProduct(product) {
                    if (product.variants && product.variants.length > 0) {
                        selectedProduct = product;
                        showVariantModal(product);
                    } else {
                        addToCartWithoutVariant(product);
                    }
                }

                // =========================
                // VARIANT MODAL
                // =========================
                function showVariantModal(product) {

                    $('#modalProductName').text(product.name);
                    $('#modalProductImage')
                        .attr('src', product.thumbnail)
                        .attr('alt', product.name);

                    var $variantsList = $('#variantsList');
                    $variantsList.empty();

                    if (product.variants.length > 0) {

                        $('#noVariantsMessage').addClass('hidden');

                        product.variants.forEach(function (variant) {

                            var disabled = variant.stock <= 0 ? 'disabled' : '';
                            var borderClass = variant.stock > 0 ? 'border-gray-200' : 'border-red-200 bg-red-50';
                            var stockText = variant.stock > 0 ? `Stock: ${variant.stock}` : 'Out of Stock';
                            var stockClass = variant.stock > 0 ? 'text-green-600' : 'text-red-600';

                            var btn = `
                <button class="variant-btn flex items-center justify-between p-4 border-2 rounded-lg hover:border-blue-500 transition disabled:opacity-50 disabled:cursor-not-allowed ${borderClass}" 
                    data-variant-id="${variant.id}" ${disabled}>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded border-2 border-gray-300" style="background-color: ${variant.hex_code}"></div>
                            <div class="text-left">
                                <p class="font-semibold text-gray-900">${variant.size_name} - ${variant.color_name}</p>
                                <p class="text-sm text-gray-500">SKU: ${variant.sku}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">৳${parseFloat(variant.price).toFixed(2)}</p>
                            <p class="text-xs ${stockClass}">${stockText}</p>
                        </div>
                </button>                                                                                                   
                `;

                            $variantsList.append(btn);
                        });

                        // click
                        $('.variant-btn').on('click', function () {
                            var variantId = $(this).data('variant-id');
                            var variant = product.variants.find(v => v.id == variantId);

                            if (variant) {
                                addToCart(product, variant);
                            }
                        });

                    } else {
                        $('#noVariantsMessage').removeClass('hidden');
                    }

                    $('#variantModal').removeClass('hidden');
                }

                // =========================
                // CLOSE MODAL
                // =========================
                $('#variantModal, #closeModalBtn').on('click', function (e) {
                    if (e.target === this) {
                        $('#variantModal').addClass('hidden');
                        selectedProduct = null;
                    }
                });

                // =========================
                // ADD TO CART (API ONLY)
                // =========================
                function addToCart(product, variant) {

                    if (window.posCartManager) {
                        window.posCartManager.addToCart(product.id, variant.id, 1);
                    }

                    $('#variantModal').addClass('hidden');
                    selectedProduct = null;
                }

                function addToCartWithoutVariant(product) {

                    if (window.posCartManager) {
                        window.posCartManager.addToCart(product.id, null, 1);
                    }

                    $('#variantModal').addClass('hidden');
                    selectedProduct = null;
                }

                $('#addWithoutVariantBtn').on('click', function () {
                    if (selectedProduct) {
                        addToCartWithoutVariant(selectedProduct);
                    }
                });

                // =========================
                // CLEAR CART
                // =========================
                document.addEventListener("click", (e) => {
                    if (e.target.id === "clearCartBtn") {

                        if (!confirm("Clear all items from cart?")) return;

                        if (window.posCartManager) {
                            window.posCartManager.clearCart();
                        }
                    }
                });

                // =========================
                // PAYMENT METHOD
                // =========================
                $('.payment-method-btn').on('click', function () {
                    $('.payment-method-btn')
                        .removeClass('bg-blue-600 text-white')
                        .addClass('bg-gray-200');

                    $(this)
                        .addClass('bg-blue-600 text-white');

                    paymentMethod = $(this).data('payment');
                });

                // Discount

                $('#discountInput, #discountType').on('input change', function () {
                    if (window.posCartManager) {
                        window.posCartManager.loadCart();
                    }
                });



                // =========================
                // COMPLETE ORDER (API CART)
                // =========================
                $('#completeOrderBtn').on('click', async function () {

                    const res = await fetch('/admin/pos/cart');
                    const data = await res.json();

                    const discount = $("#discountDisplay").text();
                    const employee_id = $("#employeeId").val();

                    if (!data.success || !data.cart.items.length) {
                        alert('Cart is empty');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("admin.pos.store") }}',
                        method: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: JSON.stringify({
                            customer_name: $('#customerName').val(),
                            customer_phone: $('#customerPhone').val(),
                            payment_method: paymentMethod,
                            items: data.cart.items,
                            subtotal: data.cart.subtotal,
                            discount: discount,
                            total: (data.cart.total - discount),
                            employee_id: employee_id,
                            paid: $('#paidAmount').val(),
                            payable: parseFloat($("#totalAmount").text()),
                            due: parseFloat($("#totalAmount").text()) - $('#paidAmount').val(),
                            cash_received: $("#cash_received").val(),
                            cash_returned: $("#cash_returned").val()
                        }),
                        success: function (res) {
                            if (res.success) {
                                alert('Order completed');
                                window.posCartManager.clearCart();
                                $('#customerName').val('');
                                $('#customerPhone').val('');
                                $("#employeeId").val('');
                                $('#paidAmount').val('');
                                $('#discountInput').val('');
                                $('#dueAmount').text(0.00);
                                $("#cash_received").val('');
                                $("#cash_returned").val("0.00");
                                $('input[name="payment_method"][value=""]').prop('checked', true);

                            }
                        },
                        error: function () {
                            alert('Failed to complete order');
                        }
                    });
                });

                // =========================
                // DRAFT ORDER (API CART)
                // =========================
                $('#holdOrderBtn').on('click', async function () {

                    const res = await fetch('/admin/pos/cart');
                    const data = await res.json();

                    const discount = $("#discountDisplay").text();
                    const employee_id = $("#employeeId").val();

                    if (!data.success || !data.cart.items.length) {
                        alert('Cart is empty');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("admin.pos.saveDraft") }}',
                        method: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: JSON.stringify({
                            customer_name: $('#customerName').val(),
                            customer_phone: $('#customerPhone').val(),
                            payment_method: paymentMethod,
                            items: data.cart.items,
                            subtotal: data.cart.subtotal,
                            discount: discount,
                            total: (data.cart.total - discount),
                            employee_id: employee_id,
                            paid: $('#paidAmount').val(),
                            payable: parseFloat($("#totalAmount").text()),
                            due: parseFloat($("#totalAmount").text()) - $('#paidAmount').val(),
                            cash_received: $("#cash_received").val(),
                            cash_returned: $("#cash_returned").val()
                        }),
                        success: function (res) {
                            if (res.success) {
                                alert('Order Draft Save successfully');
                                window.posCartManager.clearCart();
                                $('#customerName').val('');
                                $('#customerPhone').val('');
                                $("#employeeId").val('');
                                $('#paidAmount').val('');
                                $('#discountInput').val('');
                                $('#dueAmount').text(0.00);
                                $("#cash_received").val('');
                                $("#cash_returned").val("0.00");
                                $('input[name="payment_method"][value=""]').prop('checked', true);
                            }
                        },
                        error: function () {
                            alert('Failed to complete order');
                        }
                    });
                });



                (function () {

                    let customerExists = false;
                    let selectedIndex = -1;
                    let currentList = [];
                    let isSelected = false; // 🔥 LOCK FLAG

                    // debounce helper
                    function debounce(fn, delay) {
                        let timer;
                        return function () {
                            clearTimeout(timer);
                            timer = setTimeout(() => fn.apply(this, arguments), delay);
                        };
                    }

                    function setupDropdown($input, $dropdown, type) {

                        const fetchCustomers = debounce(function () {

                            let val = $input.val().trim();

                            if (isSelected) return;

                            $dropdown.empty().addClass('hidden');
                            selectedIndex = -1;
                            currentList = [];

                            $('#customerId').val('');
                            customerExists = false;

                            if (val.length < 2) return;

                            $.ajax({
                                url: "{{ route('admin.pos.searchCustomers') }}",
                                data: { term: val },
                                dataType: 'json',
                                success: function (data) {

                                    if (!data.length) {
                                        $dropdown.addClass('hidden');
                                        return;
                                    }

                                    currentList = data;

                                    let html = '';

                                    data.forEach((c, i) => {

                                        let text = type === 'name'
                                            ? `${c.value} (${c.phone})`
                                            : `${c.phone} (${c.value})`;

                                        html += `
                                             <button type="button"
                                                class="dropdown-item text-start px-3 py-2 text-sm hover:bg-gray-100 w-100" data-index="${i}">
                                                ${text}
                                             </button>                                                                                       
                                            `;
                                    });

                                    $dropdown.html(html).removeClass('hidden');
                                }
                            });

                        }, 250);

                        // INPUT → unlock + search
                        $input.on('input', function () {
                            isSelected = false;
                            fetchCustomers();
                        });

                        // CLICK SELECT
                        $dropdown.on('click', '.dropdown-item', function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                            let index = $(this).data('index');
                            selectCustomer(index);
                            $('#customerNameDropdown').addClass('hidden').empty();
                            $('#customerPhoneDropdown').addClass('hidden').empty();
                        });

                        // KEYBOARD NAVIGATION
                        $input.on('keydown', function (e) {

                            let items = $dropdown.find('.dropdown-item');

                            if ($dropdown.hasClass('hidden') || !items.length) return;

                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                selectedIndex++;
                            }
                            else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                selectedIndex--;
                            }
                            else if (e.key === 'Enter') {
                                e.preventDefault();
                                if (selectedIndex >= 0) {
                                    selectCustomer(selectedIndex);
                                }
                                return;
                            }

                            if (selectedIndex >= items.length) selectedIndex = 0;
                            if (selectedIndex < 0) selectedIndex = items.length - 1;

                            items.removeClass('bg-gray-100');
                            items.eq(selectedIndex).addClass('bg-gray-100');
                        });
                    }

                    function selectCustomer(index) {

                        let c = currentList[index];

                        $('#customerName').val(c.value);
                        $('#customerPhone').val(c.phone);
                        $('#customerId').val(c.id);

                        customerExists = true;
                        isSelected = true;

                        $('#customerNameDropdown').addClass('hidden').empty();
                        $('#customerPhoneDropdown').addClass('hidden').empty();

                        currentList = [];
                        selectedIndex = -1;
                    }

                    // OUTSIDE CLICK
                    $(document).on('click', function (e) {

                        if (
                            $(e.target).closest('#customerName, #customerPhone').length === 0 &&
                            $(e.target).closest('#customerNameDropdown, #customerPhoneDropdown').length === 0
                        ) {
                            $('#customerNameDropdown').addClass('hidden').empty();
                            $('#customerPhoneDropdown').addClass('hidden').empty();
                        }
                    });

                    // INIT
                    setupDropdown($('#customerName'), $('#customerNameDropdown'), 'name');
                    setupDropdown($('#customerPhone'), $('#customerPhoneDropdown'), 'phone');

                })();

                // =========================
                // GET VALUES
                // =========================
                function getTotal() {
                    return parseFloat($("#totalAmount").text()) || 0;
                }

                function getPaid() {
                    return parseFloat($("#paidAmount").val()) || 0;
                }

                // =========================
                // UPDATE DUE CALCULATION
                // =========================
                function updateDue() {
                    let total = getTotal();
                    let paid = getPaid();

                    if (paid < 0) paid = 0;

                    if (paid > total) {
                        paid = total;
                        $("#paidAmount").val(total.toFixed(2));
                    }

                    let due = total - paid;

                    $("#dueAmount").text(due.toFixed(2));
                }

                // =========================
                // PAID INPUT EVENT
                // =========================
                $("#paidAmount").on("input", function () {
                    updateDue();
                });

                // =========================
                // FULL PAID BUTTON
                // =========================
                $("#fullPaidBtn").on("click", function () {
                    let total = getTotal();

                    $("#paidAmount").val(total.toFixed(2));
                    updateDue();
                });

                // =========================
                // SAFE INIT
                // =========================
                updateDue();

                // =========================
                // EXTERNAL CALL (when cart/total changes)
                // =========================
                window.refreshPaymentUI = function () {
                    updateDue();
                };

                // =========================
                // GET PAYABLE (TOTAL)
                // =========================
                function getTotal() {
                    return parseFloat($("#totalAmount").text()) || 0;
                }

                // =========================
                // UPDATE CASH LOGIC
                // =========================
                function updateCash() {

                    let total = getTotal();
                    let cash = parseFloat($("#cash_received").val()) || 0;

                    if (cash < 0) cash = 0;

                    let returned = 0;

                    // CASE 1: Enough or extra cash
                    if (cash >= total) {
                        returned = cash - total;
                    } else {
                        returned = 0;
                    }

                    $("#cash_returned").val(returned.toFixed(2));
                }

                // =========================
                // INPUT EVENT
                // =========================
                $("#cash_received").on("input", function () {
                    updateCash();
                });




                // =========================
                // INIT
                // =========================
                updateCash();

                // =========================
                // EXTERNAL REFRESH SUPPORT
                // =========================
                window.refreshCashUI = function () {
                    updateCash();
                };

                // =========================
                // INIT CALL
                // =========================
                init();
            });
        </script>
    @endpush

    <style>
        .pos-container {
            max-height: calc(100vh - 64px);
        }

        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Line clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Product card hover enhancements */
        .product-card {
            min-height: 140px;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .product-card:active {
            transform: translateY(0);
        }
    </style>
@endsection