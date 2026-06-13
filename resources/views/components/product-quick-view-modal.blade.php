{{-- Product Quick View Modal - Professional Redesign --}}
<div id="productQuickViewModal"
    class="qv-overlay fixed inset-0 z-[9999] hidden items-center justify-center p-3 sm:p-4 md:p-6"
    onclick="handleQuickViewOverlayClick(event)">

    <div class="qv-container bg-white w-full sm:max-w-[480px] md:max-w-[720px] max-h-[90vh] sm:max-h-[85vh] md:max-h-[82vh] overflow-hidden shadow-2xl flex flex-col rounded-2xl">

        {{-- Header --}}
        <div class="qv-header flex items-center justify-between px-4 py-3 border-b border-gray-100 shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-1 h-4 bg-primary rounded-full"></div>
                <h3 class="text-sm font-bold text-gray-800 tracking-tight">Quick View</h3>
            </div>
            <button id="qvCloseBtn" onclick="closeQuickView()" aria-label="Close quick view"
                class="qv-close-btn w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div id="quickViewContent" class="flex-1 overflow-y-auto qv-scroll md:overflow-hidden md:flex md:flex-col min-h-0">

            {{-- Loading Skeleton --}}
            <div id="qvSkeleton" class="grid md:grid-cols-2 gap-0 h-full hidden">
                <div class="p-5 md:p-6 space-y-4 border-r border-gray-100">
                    <div class="qv-skeleton aspect-square rounded-xl w-full"></div>
                    <div class="flex gap-2">
                        <div class="qv-skeleton h-14 w-14 rounded-lg flex-shrink-0"></div>
                        <div class="qv-skeleton h-14 w-14 rounded-lg flex-shrink-0"></div>
                        <div class="qv-skeleton h-14 w-14 rounded-lg flex-shrink-0"></div>
                    </div>
                </div>
                <div class="p-5 md:p-6 space-y-4">
                    <div class="qv-skeleton h-4 w-24 rounded-md"></div>
                    <div class="qv-skeleton h-7 w-4/5 rounded-md"></div>
                    <div class="qv-skeleton h-4 w-32 rounded-md"></div>
                    <div class="qv-skeleton h-20 w-full rounded-xl"></div>
                    <div class="qv-skeleton h-4 w-28 rounded-md"></div>
                    <div class="flex gap-2">
                        <div class="qv-skeleton h-10 w-10 rounded-full"></div>
                        <div class="qv-skeleton h-10 w-10 rounded-full"></div>
                        <div class="qv-skeleton h-10 w-10 rounded-full"></div>
                    </div>
                    <div class="qv-skeleton h-12 w-full rounded-xl mt-4"></div>
                </div>
            </div>

            {{-- Actual Content --}}
            <div id="qvMainContent" class="flex flex-col md:grid md:grid-cols-2 md:flex-1 md:min-h-0">

                {{-- Left: Image Gallery --}}
                <div class="qv-image-panel flex flex-col border-b md:border-b-0 md:border-r border-gray-100">

                    {{-- Mobile: compact image strip --}}
                    <div class="relative bg-gray-50 overflow-hidden group qv-main-image-wrap flex-1">
                        <img id="quickViewImage"
                            src="{{ asset('assets/images/default.png') }}"
                            alt="Product image"
                            class="w-full h-full object-contain object-center transition-all duration-500 qv-main-img">

                        {{-- Zoom Icon (desktop only) --}}
                        <div class="hidden md:flex absolute inset-0 items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <div class="bg-white/80 backdrop-blur-sm rounded-full w-9 h-9 flex items-center justify-center shadow-md">
                                <i class="fas fa-search-plus text-gray-500 text-xs"></i>
                            </div>
                        </div>

                        {{-- Badges --}}
                        <div id="quickViewBadges" class="absolute top-2 left-2 flex flex-col gap-1"></div>
                    </div>

                    {{-- Thumbnails --}}
                    <div id="quickViewThumbnails" class="flex gap-1.5 p-2 overflow-x-auto qv-thumb-scroll"></div>
                </div>

                {{-- Right: Product Info --}}
                <div class="qv-info-panel flex flex-col md:overflow-hidden">
                    <div class="flex-1 p-3 sm:p-4 md:p-5 space-y-3 overflow-y-auto qv-scroll">

                        {{-- Brand & Name --}}
                        <div>
                            <p id="quickViewBrand" class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1"></p>
                            <h2 id="quickViewName" class="text-base sm:text-lg md:text-xl font-bold text-gray-900 leading-snug mb-2"></h2>

                            {{-- Rating --}}
                            <div id="quickViewRating" class="flex items-center gap-1.5"></div>
                        </div>

                        {{-- Price Block --}}
                        <div class="qv-price-block rounded-xl p-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span id="quickViewPrice" class="text-2xl font-extrabold text-primary leading-none"></span>
                                <div class="flex items-center gap-1.5">
                                    <span id="quickViewComparePrice" class="text-sm text-gray-400 line-through hidden"></span>
                                    <span id="quickViewDiscount" class="qv-badge-save hidden"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Stock Status --}}
                        <div id="quickViewStock" class="flex items-center gap-2 text-sm"></div>

                        {{-- Variants --}}
                        <div id="variantsSection" class="space-y-4 hidden">

                            {{-- Color --}}
                            <div id="colorSection" class="hidden">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-800">
                                        Color:
                                        <span id="selectedColorName" class="font-normal text-gray-500 ml-1">Select a color</span>
                                    </span>
                                </div>
                                <div id="colorOptions" class="flex gap-2.5 flex-wrap"></div>
                            </div>

                            {{-- Size --}}
                            <div id="sizeSection" class="hidden">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-800">
                                        Size:
                                        <span id="selectedSizeName" class="font-normal text-gray-500 ml-1">Select a size</span>
                                    </span>
                                </div>
                                <div id="sizeOptions" class="flex gap-2 flex-wrap"></div>
                            </div>

                            {{-- Variant Error --}}
                            <div id="variantError" class="hidden qv-variant-error">
                                <i class="fas fa-exclamation-circle mr-1.5"></i>
                                <span id="variantErrorText">Please select all required options</span>
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-semibold text-gray-800 shrink-0">Quantity</span>
                            <div class="qv-qty-control">
                                <button onclick="updateQuickViewQuantity(-1)" class="qv-qty-btn" aria-label="Decrease quantity">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" id="quickViewQuantity" value="1" min="1" max="999"
                                    class="qv-qty-input" aria-label="Quantity">
                                <button onclick="updateQuickViewQuantity(1)" class="qv-qty-btn" aria-label="Increase quantity">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Short Description --}}
                        <div id="quickViewDescription" class="text-sm text-gray-500 leading-relaxed border-t border-gray-100 pt-4 hidden"></div>

                    </div>

                    {{-- Sticky Action Buttons --}}
                    <div class="qv-actions-bar border-t border-gray-100 px-3 md:px-5 py-3 shrink-0">
                        <div class="flex gap-3">
                            <button id="quickViewAddToCart" onclick="addToCartFromQuickView()"
                                class="qv-btn-primary flex-1">
                                <i class="fas fa-shopping-cart text-sm"></i>
                                <span>Add to Cart</span>
                            </button>
                            <button id="quickViewDetails"
                                class="qv-btn-outline flex-1">
                                <i class="fas fa-eye text-sm"></i>
                                <span>View Details</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* ============================================================
       QUICK VIEW MODAL – PROFESSIONAL REDESIGN
       Primary: #228bcc  |  Font: system stack
    ============================================================ */

    /* ---------- Overlay ---------- */
    .qv-overlay {
        background: rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        animation: qvFadeIn 0.22s ease-out both;
    }

    .qv-overlay.show {
        display: flex !important;
    }

    /* ---------- Container ---------- */
    .qv-container {
        animation: qvSlideUp 0.3s cubic-bezier(0.34, 1.26, 0.64, 1) both;
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    /* ---------- Keyframes ---------- */
    @keyframes qvFadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    @keyframes qvSlideUp {
        from { opacity: 0; transform: translateY(28px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0)    scale(1);    }
    }

    @keyframes qvShimmer {
        0%   { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }

    /* ---------- Scrollbar ---------- */
    .qv-scroll::-webkit-scrollbar { width: 4px; }
    .qv-scroll::-webkit-scrollbar-track { background: transparent; }
    .qv-scroll::-webkit-scrollbar-thumb { background: #dde3ed; border-radius: 99px; }
    .qv-scroll { scrollbar-width: thin; scrollbar-color: #dde3ed transparent; }

    .qv-thumb-scroll::-webkit-scrollbar { height: 3px; }
    .qv-thumb-scroll::-webkit-scrollbar-thumb { background: #dde3ed; border-radius: 99px; }
    .qv-thumb-scroll { scrollbar-width: thin; scrollbar-color: #dde3ed transparent; }

    /* ---------- Image Panel ---------- */
    .qv-main-image-wrap {
        aspect-ratio: 1 / 1;
        cursor: zoom-in;
        min-height: 200px;
    }

    .qv-main-img {
        max-height: 240px;
        min-height: 160px;
    }

    @media (min-width: 768px) {
        /* On desktop, remove aspect-ratio so it fills full column height */
        .qv-main-image-wrap {
            aspect-ratio: unset;
            flex: 1;
            min-height: 0;
        }

        .qv-main-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            max-height: none;
            min-height: 0;
        }
    }

    /* ---------- Thumbnails ---------- */
    .qv-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        object-position: center;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        flex-shrink: 0;
    }

    .qv-thumb:hover {
        border-color: #228bcc;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(34, 139, 204, 0.2);
    }

    .qv-thumb.active {
        border-color: #228bcc;
        box-shadow: 0 0 0 3px rgba(34, 139, 204, 0.18);
    }

    /* ---------- Price Block ---------- */
    .qv-price-block {
        background: linear-gradient(135deg, #f0f7fd 0%, #e8f4fb 100%);
        border: 1px solid #d1e9f7;
    }

    /* ---------- Badge Save ---------- */
    .qv-badge-save {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
        letter-spacing: 0.02em;
    }

    /* ---------- Stock Badge ---------- */
    .qv-stock-in    { color: #16a34a; }
    .qv-stock-low   { color: #ea580c; }
    .qv-stock-out   { color: #dc2626; }

    .qv-stock-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }

    .qv-stock-dot.in  { background: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.15); }
    .qv-stock-dot.low { background: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,.15); }
    .qv-stock-dot.out { background: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.15); }

    /* ---------- Color Swatches ---------- */
    .qv-color-swatch {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
        position: relative;
        flex-shrink: 0;
    }

    .qv-color-swatch:hover {
        transform: scale(1.12);
        box-shadow: 0 3px 10px rgba(0,0,0,0.18);
    }

    .qv-color-swatch.active {
        border-color: #228bcc;
        box-shadow: 0 0 0 3px rgba(34, 139, 204, 0.25);
        transform: scale(1.08);
    }

    /* ---------- Size Buttons ---------- */
    .qv-size-btn {
        min-width: 44px;
        height: 36px;
        padding: 0 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        background: #fff;
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .qv-size-btn:hover {
        border-color: #228bcc;
        color: #228bcc;
        background: #f0f7fd;
    }

    .qv-size-btn.active {
        border-color: #228bcc;
        background: #228bcc;
        color: #fff;
        box-shadow: 0 3px 10px rgba(34, 139, 204, 0.3);
    }

    .qv-size-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    /* ---------- Variant Error ---------- */
    .qv-variant-error {
        display: flex;
        align-items: center;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #dc2626;
    }

    /* ---------- Quantity Control ---------- */
    .qv-qty-control {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .qv-qty-btn {
        width: 40px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }

    .qv-qty-btn:hover {
        background: #f0f7fd;
        color: #228bcc;
    }

    .qv-qty-btn:active { background: #e8f4fb; }

    .qv-qty-input {
        width: 52px;
        height: 42px;
        text-align: center;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        border: none;
        border-left: 1.5px solid #e5e7eb;
        border-right: 1.5px solid #e5e7eb;
        outline: none;
        background: transparent;
        -moz-appearance: textfield;
    }

    .qv-qty-input::-webkit-inner-spin-button,
    .qv-qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }

    /* ---------- Action Buttons ---------- */
    .qv-btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 48px;
        background: linear-gradient(135deg, #228bcc 0%, #1b6fa3 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
        box-shadow: 0 4px 14px rgba(34, 139, 204, 0.35);
        letter-spacing: 0.01em;
    }

    .qv-btn-primary:hover {
        filter: brightness(1.08);
        box-shadow: 0 6px 20px rgba(34, 139, 204, 0.45);
        transform: translateY(-1px);
    }

    .qv-btn-primary:active { transform: scale(0.98); }

    .qv-btn-outline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 48px;
        border: 2px solid #228bcc;
        color: #228bcc;
        background: transparent;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.01em;
    }

    .qv-btn-outline:hover {
        background: #228bcc;
        color: #fff;
        box-shadow: 0 4px 14px rgba(34, 139, 204, 0.3);
        transform: translateY(-1px);
    }

    .qv-btn-outline:active { transform: scale(0.98); }

    /* ---------- Skeleton Shimmer ---------- */
    .qv-skeleton {
        background: linear-gradient(90deg, #f3f4f6 25%, #e9ebee 37%, #f3f4f6 63%);
        background-size: 800px 100%;
        animation: qvShimmer 1.4s infinite linear;
        border-radius: 8px;
    }

    /* ---------- Close Button ---------- */
    .qv-close-btn {
        transition: background 0.18s, color 0.18s, transform 0.18s;
    }

    .qv-close-btn:hover { transform: rotate(90deg); }

    /* ============================================================
       MOBILE  (< 640px) — centred with space around
    ============================================================ */
    @media (max-width: 639px) {
        .qv-container {
            animation: qvSlideUp 0.28s cubic-bezier(0.34, 1.2, 0.64, 1) both;
        }

        .qv-main-image-wrap { aspect-ratio: 16/9; min-height: 0; border-radius: 0; }
        .qv-main-img        { max-height: none; min-height: 0; }
        .qv-image-panel     { padding: 0; }
        .qv-price-block     { padding: 10px 12px; }
        .qv-thumb           { width: 44px; height: 44px; border-radius: 8px; }
        .qv-qty-btn         { width: 36px; height: 36px; }
        .qv-qty-input       { width: 44px; height: 36px; font-size: 13px; }
        .qv-btn-primary,
        .qv-btn-outline     { height: 44px; font-size: 13px; border-radius: 10px; }
    }

    /* ============================================================
       TABLET  (640px – 767px)
    ============================================================ */
    @media (min-width: 640px) and (max-width: 767px) {
        .qv-container {
            animation: qvSlideUp 0.28s cubic-bezier(0.34, 1.2, 0.64, 1) both;
        }

        .qv-main-image-wrap { aspect-ratio: 4/3; min-height: 0; }
        .qv-main-img        { max-height: none; min-height: 0; }
        .qv-thumb           { width: 50px; height: 50px; }
    }

    /* stacked layout on < md */
    @media (max-width: 767px) {
        .qv-main-image-wrap { aspect-ratio: 16/9; }
        #qvMainContent      { overflow-y: auto; }
    }

    /* ============================================================
       DESKTOP  (≥ 768px)
    ============================================================ */
    @media (min-width: 768px) {
        .qv-info-panel { overflow: hidden; }
    }
</style>

{{--
<script>
    // NOTE: Script tag is commented-out in original; preserved here.
    // All IDs and function signatures remain unchanged.

    let quickViewProduct = null;
    let quickViewVariants = [];

    // ==============================
    // INIT QUICK VIEW DATA
    // ==============================
    function openQuickView(product, variants = []) {
        quickViewProduct = product;
        quickViewVariants = variants;

        resetQuickViewUI();

        document.getElementById('quickViewName').textContent        = product.name               ?? '';
        document.getElementById('quickViewBrand').textContent       = product.brand              ?? '';
        document.getElementById('quickViewDescription').innerHTML   = decodeHTML(product.short_description ?? '');

        // Image
        const img = document.getElementById('quickViewImage');
        img.src = product.thumbnail || '/assets/images/default.png';
        img.alt = product.name ?? 'Product image';

        // Thumbnails
        if (product.images && product.images.length > 1) {
            const thumbsEl = document.getElementById('quickViewThumbnails');
            thumbsEl.innerHTML = '';
            product.images.forEach((imgSrc, i) => {
                const t = document.createElement('img');
                t.src       = imgSrc;
                t.alt       = `${product.name} image ${i + 1}`;
                t.className = 'qv-thumb' + (i === 0 ? ' active' : '');
                t.onclick   = () => {
                    document.getElementById('quickViewImage').src = imgSrc;
                    document.querySelectorAll('.qv-thumb').forEach(x => x.classList.remove('active'));
                    t.classList.add('active');
                };
                thumbsEl.appendChild(t);
            });
        }

        // Badges
        const badgesEl = document.getElementById('quickViewBadges');
        badgesEl.innerHTML = '';
        if (product.is_new_arrival) badgesEl.innerHTML += `<span class="bg-green-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow">NEW</span>`;
        if (product.is_best_seller) badgesEl.innerHTML += `<span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow">HOT</span>`;
        if (product.is_on_sale)     badgesEl.innerHTML += `<span class="bg-orange-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow">SALE</span>`;

        // Price
        setQuickViewPrice(product.price, product.compare_price);

        // Rating
        if (product.average_rating && product.review_count > 0) {
            const ratingEl = document.getElementById('quickViewRating');
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= Math.floor(product.average_rating))      stars += '<i class="fas fa-star text-yellow-400 text-xs"></i>';
                else if (i - 0.5 <= product.average_rating)       stars += '<i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>';
                else                                               stars += '<i class="far fa-star text-yellow-300 text-xs"></i>';
            }
            ratingEl.innerHTML = `<div class="flex gap-0.5">${stars}</div><span class="text-xs text-gray-500 font-medium">${product.average_rating} <span class="text-gray-400">(${product.review_count} reviews)</span></span>`;
        }

        // Stock & Variants
        updateQuickViewStock(product.stock_in ?? 0);
        renderQuickViewVariants(variants);

        // Short description
        if (product.short_description) {
            document.getElementById('quickViewDescription').classList.remove('hidden');
        }

        // View Details link
        if (product.slug) {
            document.getElementById('quickViewDetails').onclick = () => {
                window.location.href = `/products/${product.slug}`;
            };
        }

        // Show modal
        const modal = document.getElementById('productQuickViewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function decodeHTML(html) {
        const txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    }

    function handleQuickViewOverlayClick(e) {
        if (e.target === document.getElementById('productQuickViewModal')) {
            closeQuickView();
        }
    }

    function closeQuickView() {
        const modal = document.getElementById('productQuickViewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        quickViewProduct = null;
        quickViewVariants = [];
    }

    // ==============================
    // RESET UI
    // ==============================
    function resetQuickViewUI() {
        document.getElementById('quickViewQuantity').value  = 1;
        document.getElementById('selectedColorName').textContent = 'Select a color';
        document.getElementById('selectedSizeName').textContent  = 'Select a size';
        document.getElementById('colorOptions').innerHTML   = '';
        document.getElementById('sizeOptions').innerHTML    = '';
        document.getElementById('quickViewThumbnails').innerHTML = '';
        document.getElementById('quickViewRating').innerHTML    = '';
        document.getElementById('quickViewBadges').innerHTML    = '';
        document.getElementById('quickViewDescription').classList.add('hidden');
        document.getElementById('variantsSection').classList.add('hidden');
        document.getElementById('colorSection').classList.add('hidden');
        document.getElementById('sizeSection').classList.add('hidden');
        document.getElementById('variantError').classList.add('hidden');
    }

    // ==============================
    // PRICE
    // ==============================
    function setQuickViewPrice(price, comparePrice = 0) {
        const priceEl    = document.getElementById('quickViewPrice');
        const compareEl  = document.getElementById('quickViewComparePrice');
        const discountEl = document.getElementById('quickViewDiscount');

        priceEl.textContent = `৳${Number(price).toLocaleString()}`;

        if (comparePrice && comparePrice > price) {
            compareEl.textContent = `৳${Number(comparePrice).toLocaleString()}`;
            compareEl.classList.remove('hidden');

            const pct = Math.round(((comparePrice - price) / comparePrice) * 100);
            discountEl.textContent = `${pct}% OFF`;
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
            stockEl.innerHTML = `
                <span class="qv-stock-dot out"></span>
                <span class="qv-stock-out text-sm font-semibold">Out of stock</span>`;
        } else if (stock <= 5) {
            stockEl.innerHTML = `
                <span class="qv-stock-dot low"></span>
                <span class="qv-stock-low text-sm font-semibold">Only ${stock} left!</span>
                <span class="text-xs text-gray-400">— Order soon</span>`;
        } else {
            stockEl.innerHTML = `
                <span class="qv-stock-dot in"></span>
                <span class="qv-stock-in text-sm font-semibold">In stock</span>
                <span class="text-xs text-gray-400">(${stock} available)</span>`;
        }

        const qty = document.getElementById('quickViewQuantity');
        qty.max = stock > 0 ? stock : 1;
    }

    // ==============================
    // VARIANTS
    // ==============================
    function renderQuickViewVariants(variants) {
        if (!variants || variants.length === 0) return;

        const colorBox = document.getElementById('colorOptions');
        const sizeBox  = document.getElementById('sizeOptions');

        const colors = [...new Map(variants.filter(v => v.color).map(v => [v.color.id, v.color])).values()];
        const sizes  = [...new Map(variants.filter(v => v.size).map(v =>  [v.size.id,  v.size])).values()];

        document.getElementById('variantsSection').classList.remove('hidden');

        if (colors.length > 0) {
            document.getElementById('colorSection').classList.remove('hidden');
            colors.forEach(color => {
                const btn = document.createElement('button');
                btn.className           = 'qv-color-swatch';
                btn.style.backgroundColor = color.hex_code || '#eee';
                btn.dataset.colorId     = color.id;
                btn.title               = color.name;
                btn.setAttribute('aria-label', color.name);
                btn.onclick = () => selectQuickViewColor(btn, color.name);
                colorBox.appendChild(btn);
            });
        }

        if (sizes.length > 0) {
            document.getElementById('sizeSection').classList.remove('hidden');
            sizes.forEach(size => {
                const btn = document.createElement('button');
                btn.className       = 'qv-size-btn';
                btn.dataset.sizeId  = size.id;
                btn.textContent     = size.name;
                btn.onclick = () => selectQuickViewSize(btn, size.name);
                sizeBox.appendChild(btn);
            });
        }
    }

    // ==============================
    // SELECTIONS
    // ==============================
    function selectQuickViewColor(btn, name) {
        document.querySelectorAll('#colorOptions .qv-color-swatch').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('selectedColorName').textContent = name;
        updateQuickViewVariant();
    }

    function selectQuickViewSize(btn, name) {
        document.querySelectorAll('#sizeOptions .qv-size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('selectedSizeName').textContent = name;
        updateQuickViewVariant();
    }

    // ==============================
    // FIND VARIANT
    // ==============================
    function updateQuickViewVariant() {
        const selectedColor = document.querySelector('#colorOptions .qv-color-swatch.active');
        const selectedSize  = document.querySelector('#sizeOptions .qv-size-btn.active');
        const colorId = selectedColor ? selectedColor.dataset.colorId : null;
        const sizeId  = selectedSize  ? selectedSize.dataset.sizeId   : null;

        const variant = quickViewVariants.find(v => {
            return (!colorId || v.color_id == colorId) && (!sizeId || v.size_id == sizeId);
        });

        if (variant) {
            setQuickViewPrice(variant.price, variant.compare_price);
            updateQuickViewStock(variant.stock_in - (variant.stock_out ?? 0));
            document.getElementById('variantError').classList.add('hidden');
        }
    }

    // ==============================
    // QUANTITY
    // ==============================
    function updateQuickViewQuantity(change) {
        const input = document.getElementById('quickViewQuantity');
        let val = parseInt(input.value || 1) + change;
        if (val < 1)   val = 1;
        const max = parseInt(input.max || 999);
        if (val > max) val = max;
        input.value = val;
    }

    // ==============================
    // ADD TO CART
    // ==============================
    function addToCartFromQuickView() {
        if (!quickViewProduct) return;

        const hasColors = document.getElementById('colorSection') && !document.getElementById('colorSection').classList.contains('hidden');
        const hasSizes  = document.getElementById('sizeSection')  && !document.getElementById('sizeSection').classList.contains('hidden');
        const colorSel  = document.querySelector('#colorOptions .qv-color-swatch.active');
        const sizeSel   = document.querySelector('#sizeOptions .qv-size-btn.active');

        if ((hasColors && !colorSel) || (hasSizes && !sizeSel)) {
            const errEl = document.getElementById('variantError');
            const errTxt = document.getElementById('variantErrorText');
            errTxt.textContent = !colorSel && hasColors ? 'Please select a color' : 'Please select a size';
            errEl.classList.remove('hidden');
            errEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            return;
        }

        const qty     = parseInt(document.getElementById('quickViewQuantity').value || 1);
        const payload = {
            product_id: quickViewProduct.id,
            variant_id: findQuickViewVariantId(colorSel, sizeSel),
            quantity:   qty
        };

        if (window.cartManager) {
            window.cartManager.addToCart(payload.product_id, payload.variant_id, payload.quantity)
                .then(success => {
                    if (success) {
                        closeQuickView();
                        if (window.openCartDrawer) window.openCartDrawer();
                    }
                });
        }
    }

    function findQuickViewVariantId(colorEl, sizeEl) {
        const colorId = colorEl ? colorEl.dataset.colorId : null;
        const sizeId  = sizeEl  ? sizeEl.dataset.sizeId   : null;
        const variant = quickViewVariants.find(v =>
            (!colorId || v.color_id == colorId) && (!sizeId || v.size_id == sizeId)
        );
        return variant ? variant.id : null;
    }

    // ==============================
    // ESC TO CLOSE
    // ==============================
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeQuickView();
    });
</script>
--}}