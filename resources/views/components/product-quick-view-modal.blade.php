{{-- Product Quick View Modal --}}
<div id="productQuickViewModal"
    class="modal-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
    <div class="modal-container bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-100 sticky top-0 bg-white z-10">
            <h3 class="text-lg font-bold text-
g               ray-900">Quick View</h3>
            <button onclick="closeQuickView()"
                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition">
                <i class="fas fa-times text-gray-600"></i>
            </button>
        </div>

        {{-- Modal Content --}}
        <div id="quickViewContent" class="overflow-y-auto max-h-[calc(90vh-5rem)]">
            <div class="grid md:grid-cols-2 gap-6 p-6">
                {{-- Product Image --}}
                <div class="space-y-3">

                    <div class="relative bg-gray-50 rounded-xl overflow-hidden aspect-square">
                        <img id="quickViewImage" src="{{ asset('assets/images/default.png') }}" alt=""
                            class="w-full h-full object-cover">
                        {{-- Badge Container --}}
                        <div id="quickViewBadges" class="absolute top-3 left-3 flex flex-col gap-2"></div>
                    </div>
                    {{-- Thumbnails --}}
                    <div id="quickViewThumbnails" class="flex gap-2 overflow-x-auto hide-scrollbar pb-1"></div>
                </div>

                {{-- Product Info --}}
                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <p id="quickViewBrand" class="text-sm text-primary font-medium mb-1"></p>
                        <h2 id="quickViewName" class="text-xl md:text-2xl font-bold text-gray-900 mb-2"></h2>

                        {{-- Rating --}}
                        <div id="quickViewRating" class="flex items-center gap-2 mb-3"></div>
                    </div>

                    {{-- Price --}}
                    <div class="bg-gradient-to-r from-blue-50 to-light rounded-xl p-4">
                        <div class="flex items-end gap-2
                                flex-wrap">
                            <span id="quickViewPrice" class="text-2xl md:text-3xl font-bold text-primary"></span>
                            <span id="quickViewComparePrice" class="text-lg text-gray-400 line-through hidden"></span>
                            <span id="quickViewDiscount"
                                class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-lg hidden"></span>
                        </div>
                    </div>

                    {{-- Stock Status --}}
                    <div id="quickViewStock" class="flex items-center gap-2">
                    </div>

                    {{-- Variants Section --}}
                    <div id="variantsSection" class="space-y-4 hidden">
                        {{-- Color Selection --}}

                        <div id="colorSection" class="hidden">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-sm font-semibold text-gray-900">
                                    Color: <span id="selectedColorName" class="font-normal text-gray-600">Select a
                                        color</span>
                                </span>
                            </div>
                            <div id="colorOptions" class="flex gap-3 flex-wrap"></div>
                        </div>


                        {{-- Size Selection --}}
                        <div id="sizeSection" class="hidden">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-sm font-semibold text-gray-900">
                                    Size: <span id="selectedSizeName" class="font-normal text-gray-600">Select a
                                        size</span>
                                </span>

                            </div>
                            <div id="sizeOptions" class="flex gap-2.5 flex-wrap"></div>
                        </div>

                        {{-- Variant Error Message --}}
                        <div id="variantError"
                            class="hidden bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span id="variantErrorText">Please select all options</span>
                        </div>

                    </div>

                    {{-- Quantity --}}

                    <div>
                        <span class="text-sm font-semibold text-gray-900 mb-3 block">Quantity</span>
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden w-fit">
                            <button onclick="updateQuickViewQuantity(-1)"
                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" id="quickViewQuantity" value="1" min="1" max="999"
                                class="w-12 h-10 text-center text-sm font-semibold border-xl border-gray-200 focus:outline-none">
                            <button onclick="updateQuickViewQuantity(1)"
                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-plus text-sm"></i>
                            </button>

                        </div>
                    </div>

                    <div
                        class="sticky bottom-0 z-50 bg-white border-t border-gray-100 px-0 py-5 pb-[calc(env(safe-area-inset-bottom)+12px)] shadow-[0_-6px_20px_rgba(0,0,0,0.06)]">

                        <div class="flex gap-3">

                            {{-- Add to Cart --}}
                            <button id="quickViewAddToCart" onclick="addToCartFromQuickView()" class="flex-1 h-12 bg-primary text-white rounded-xl font-semibold text-sm
                   flex items-center justify-center gap-2
                   active:scale-[0.98] transition">

                                <i class="fas fa-shopping-cart text-sm"></i>
                                <span>Add to Cart</span>
                            </button>

                            {{-- View Details --}}
                            <button id="quickViewDetails" class="flex-1 h-12 border-2 border-primary text-primary rounded-xl font-semibold text-sm
                   flex items-center justify-center gap-2
                   hover:bg-primary hover:text-white
                   active:scale-[0.98] transition">

                                <i class="fas fa-eye text-sm"></i>
                                <span>View Details</span>
                            </button>

                        </div>
                    </div>


                    {{-- Short Description --}}
                    <div id="quickViewDescription" class="text-sm text-gray-600 pt-2 border-t border-gray-100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        animation: fadeIn 0.2s ease-out;
    }

    .modal-overlay.show {
        display: flex !important;
    }

    .modal-container {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @media (max-width: 768px) {
        .modal-container {
            max-width: 95vw;
        }
    }
</style>

<script>
    let quickViewProduct = null;
    let quickViewVariants = [];

    // ==============================
    // INIT QUICK VIEW DATA
    // ==============================
    function openQuickView(product, variants = []) {
        quickViewProduct = product;
        quickViewVariants = variants;

        // Reset UI
        resetQuickViewUI();

        // Set basic info
        document.getElementById('quickViewName').textContent = product.name ?? '';
        document.getElementById('quickViewBrand').textContent = product.brand ?? '';
        document.getElementById('quickViewDescription').innerHTML = product.short_description ?? '';

        // Image
        document.getElementById('quickViewImage').src = product.thumbnail || '/assets/images/default.png';

        // Price
        setQuickViewPrice(product.price, product.compare_price);

        // Stock
        updateQuickViewStock(product.stock_in ?? 0);

        // Variants
        renderQuickViewVariants(variants);

        // Show modal
        const modal = document.getElementById('productQuickViewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }



    // ==============================
    // RESET UI
    // ==============================
    function resetQuickViewUI() {
        document.getElementById('quickViewQuantity').value = 1;
        document.getElementById('selectedColorName').textContent = 'Select';
        document.getElementById('selectedSizeName').textContent = 'Select';

        document.getElementById('colorOptions').innerHTML = '';
        document.getElementById('sizeOptions').innerHTML = '';

        document.getElementById('variantsSection').classList.add('hidden');
    }

    // ==============================
    // PRICE HANDLING
    // ==============================
    function setQuickViewPrice(price, comparePrice = 0) {
        const priceEl = document.getElementById('quickViewPrice');
        const compareEl = document.getElementById('quickViewComparePrice');
        const discountEl = document.getElementById('quickViewDiscount');

        priceEl.textContent = `৳${Number(price).toLocaleString()}`;

        if (comparePrice && comparePrice > price) {
            compareEl.textContent = `৳${Number(comparePrice).toLocaleString()}`;
            compareEl.classList.remove('hidden');

            const discount = comparePrice - price;
            discountEl.textContent = `Save ৳${discount.toLocaleString()}`;
            discountEl.classList.remove('hidden');
        } else {
            compareEl.classList.add('hidden');
            discountEl.classList.add('hidden');
        }
    }

    // ==============================
    // STOCK
    // ==============================
    function updateQuickViewStock(stock) {
        const stockEl = document.getElementById('quickViewStock');

        if (stock <= 0) {
            stockEl.innerHTML = `<span class="text-red-600 font-medium">Out of stock</span>`;
        } else if (stock <= 5) {
            stockEl.innerHTML = `<span class="text-orange-500 font-medium">Only ${stock} left!</span>`;
        } else {
            stockEl.innerHTML = `<span class="text-green-600 font-medium">${stock} in stock</span>`;
        }

        const qty = document.getElementById('quickViewQuantity');
        qty.max = stock > 0 ? stock : 1;
    }

    // ==============================
    // RENDER VARIANTS
    // ==============================
    function renderQuickViewVariants(variants) {
        if (!variants || variants.length === 0) return;

        const colorBox = document.getElementById('colorOptions');
        const sizeBox = document.getElementById('sizeOptions');

        const colors = [...new Map(
            variants.filter(v => v.color).map(v => [v.color.id, v.color])
        ).values()];

        const sizes = [...new Map(
            variants.filter(v => v.size).map(v => [v.size.id, v.size])
        ).values()];

        // Show section
        document.getElementById('variantsSection').classList.remove('hidden');

        // COLORS
        if (colors.length > 0) {
            document.getElementById('colorSection').classList.remove('hidden');

            colors.forEach(color => {
                const btn = document.createElement('button');
                btn.className = "w-10 h-10 rounded-full border border-gray-300 hover:border-primary transition";
                btn.style.backgroundColor = color.hex_code || '#eee';
                btn.dataset.colorId = color.id;
                btn.title = color.name;

                btn.onclick = () => selectQuickViewColor(btn, color.name);

                colorBox.appendChild(btn);
            });
        }

        // SIZES
        if (sizes.length > 0) {
            document.getElementById('sizeSection').classList.remove('hidden');

            sizes.forEach(size => {
                const btn = document.createElement('button');
                btn.className = "px-3 py-1 border rounded-md text-sm hover:border-primary";
                btn.dataset.sizeId = size.id;
                btn.textContent = size.name;

                btn.onclick = () => selectQuickViewSize(btn, size.name);

                sizeBox.appendChild(btn);
            });
        }
    }

    // ==============================
    // SELECTIONS
    // ==============================
    function selectQuickViewColor(btn, name) {
        document.querySelectorAll('#colorOptions button').forEach(b => {
            b.classList.remove('border-primary');
        });

        btn.classList.add('border-primary');
        document.getElementById('selectedColorName').textContent = name;

        updateQuickViewVariant();
    }

    function selectQuickViewSize(btn, name) {
        document.querySelectorAll('#sizeOptions button').forEach(b => {
            b.classList.remove('border-primary');
        });

        btn.classList.add('border-primary');
        document.getElementById('selectedSizeName').textContent = name;

        updateQuickViewVariant();
    }

    // ==============================
    // FIND VARIANT
    // ==============================
    function updateQuickViewVariant() {
        const selectedColor = document.querySelector('#colorOptions .border-primary');
        const selectedSize = document.querySelector('#sizeOptions .border-primary');

        const colorId = selectedColor ? selectedColor.dataset.colorId : null;
        const sizeId = selectedSize ? selectedSize.dataset.sizeId : null;

        const variant = quickViewVariants.find(v => {
            const colorMatch = !colorId || v.color_id == colorId;
            const sizeMatch = !sizeId || v.size_id == sizeId;
            return colorMatch && sizeMatch;
        });

        if (variant) {
            setQuickViewPrice(variant.price, variant.compare_price);
            updateQuickViewStock(variant.stock_in - (variant.stock_out ?? 0));
        }
    }

    // ==============================
    // QUANTITY
    // ==============================
    function updateQuickViewQuantity(change) {
        const input = document.getElementById('quickViewQuantity');
        let val = parseInt(input.value || 1) + change;

        if (val < 1) val = 1;

        const max = parseInt(input.max || 999);
        if (val > max) val = max;

        input.value = val;
    }

    // ==============================
    // ADD TO CART
    // ==============================
    function addToCartFromQuickView() {
        if (!quickViewProduct) return;

        const qty = parseInt(document.getElementById('quickViewQuantity').value || 1);

        const color = document.querySelector('#colorOptions .border-primary');
        const size = document.querySelector('#sizeOptions .border-primary');

        const payload = {
            product_id: quickViewProduct.id,
            variant_id: findQuickViewVariantId(color, size),
            quantity: qty
        };

        if (window.cartManager) {
            window.cartManager.addToCart(
                payload.product_id,
                payload.variant_id,
                payload.quantity
            ).then(success => {
                if (success && window.openCartDrawer) {
                    window.openCartDrawer();
                }
            });
        }
    }

    function findQuickViewVariantId(colorEl, sizeEl) {
        const colorId = colorEl ? colorEl.dataset.colorId : null;
        const sizeId = sizeEl ? sizeEl.dataset.sizeId : null;

        const variant = quickViewVariants.find(v => {
            return (!colorId || v.color_id == colorId) &&
                (!sizeId || v.size_id == sizeId);
        });

        return variant ? variant.id : null;
    }

    // ==============================
    // CLOSE ON ESC
    // ==============================
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeQuickView();
        }
    });
</script>