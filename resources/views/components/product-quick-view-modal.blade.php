{{-- Product Quick View Modal — Updated to Match Tailwind Theme --}}
<div id="productQuickViewModal"
    class="qv-overlay fixed inset-0 z-[9999] hidden items-center justify-center p-3 sm:p-4 md:p-6"
    onclick="handleQuickViewOverlayClick(event)">

    <div class="qv-container bg-white w-full sm:max-w-[480px] md:max-w-[720px] max-h-[90vh] sm:max-h-[85vh] md:max-h-[82vh] overflow-hidden shadow-2xl flex flex-col rounded-2xl">

        {{-- Header --}}
        <div class="qv-header flex items-center justify-between px-4 py-3 border-b border-primary-100 shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-1 h-4 bg-primary rounded-full"></div>
                <h3 class="text-sm font-bold text-primary tracking-tight">Quick View</h3>
            </div>
            <button id="qvCloseBtn" onclick="closeQuickView()" aria-label="Close quick view"
                class="qv-close-btn w-8 h-8 flex items-center justify-center rounded-full text-secondary-400 hover:text-primary hover:bg-primary-50 transition-all duration-200">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div id="quickViewContent" class="flex-1 overflow-y-auto qv-scroll md:overflow-hidden md:flex md:flex-col min-h-0">

            {{-- Loading Skeleton --}}
            <div id="qvSkeleton" class="grid md:grid-cols-2 gap-0 h-full hidden">
                <div class="p-5 md:p-6 space-y-4 border-r border-primary-100">
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
                <div class="qv-image-panel flex flex-col border-b md:border-b-0 md:border-r border-primary-100">

                    {{-- Mobile: compact image strip --}}
                    <div class="relative bg-light overflow-hidden group qv-main-image-wrap flex-1">
                        <img id="quickViewImage"
                            src="{{ asset('assets/images/default.png') }}"
                            alt="Product image"
                            class="w-full h-full object-contain object-center transition-all duration-500 qv-main-img">

                        {{-- Zoom Icon (desktop only) --}}
                        <div class="hidden md:flex absolute inset-0 items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <div class="bg-white/80 backdrop-blur-sm rounded-full w-9 h-9 flex items-center justify-center shadow-md">
                                <i class="fas fa-search-plus text-secondary text-xs"></i>
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
                            <h2 id="quickViewName" class="text-base sm:text-lg md:text-xl font-bold text-primary leading-snug mb-2"></h2>

                            {{-- Rating --}}
                            <div id="quickViewRating" class="flex items-center gap-1.5"></div>
                        </div>

                        {{-- Price Block --}}
                        <div class="qv-price-block rounded-xl p-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span id="quickViewPrice" class="text-2xl font-extrabold text-primary leading-none"></span>
                                <div class="flex items-center gap-1.5">
                                    <span id="quickViewComparePrice" class="text-sm text-secondary-400 line-through hidden"></span>
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
                                    <span class="text-sm font-semibold text-primary">
                                        Color:
                                        <span id="selectedColorName" class="font-normal text-secondary-400 ml-1">Select a color</span>
                                    </span>
                                </div>
                                <div id="colorOptions" class="flex gap-2.5 flex-wrap"></div>
                            </div>

                            {{-- Size --}}
                            <div id="sizeSection" class="hidden">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-primary">
                                        Size:
                                        <span id="selectedSizeName" class="font-normal text-secondary-400 ml-1">Select a size</span>
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
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-primary shrink-0">Quantity</span>
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
                        <div id="quickViewDescription" class="text-sm text-secondary leading-relaxed border-t border-primary-100 pt-4 hidden"></div>

                    </div>

                    {{-- Sticky Action Buttons --}}
                    <div class="qv-actions-bar border-t border-primary-100 px-3 md:px-5 py-3 shrink-0">
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
       QUICK VIEW MODAL — CHARCOAL & GRAPHITE THEME
       Primary: #1c1c1e  |  Secondary: #636366  |  Light: #f5f5f7
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
    .qv-scroll::-webkit-scrollbar-thumb { background: #c2c2c2; border-radius: 99px; }
    .qv-scroll { scrollbar-width: thin; scrollbar-color: #c2c2c2 transparent; }

    .qv-thumb-scroll::-webkit-scrollbar { height: 3px; }
    .qv-thumb-scroll::-webkit-scrollbar-thumb { background: #c2c2c2; border-radius: 99px; }
    .qv-thumb-scroll { scrollbar-width: thin; scrollbar-color: #c2c2c2 transparent; }

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
        border: 2px solid #e0e0e0;
        cursor: pointer;
        transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        flex-shrink: 0;
    }

    .qv-thumb:hover {
        border-color: #1c1c1e;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(28, 28, 30, 0.15);
    }

    .qv-thumb.active {
        border-color: #1c1c1e;
        box-shadow: 0 0 0 3px rgba(28, 28, 30, 0.12);
    }

    /* ---------- Price Block ---------- */
    .qv-price-block {
        background: linear-gradient(135deg, #f5f5f7 0%, #f0f0f0 100%);
        border: 1px solid #e0e0e0;
    }

    /* ---------- Badge Save ---------- */
    .qv-badge-save {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
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
        border: 2px solid #e0e0e0;
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
        border-color: #1c1c1e;
        box-shadow: 0 0 0 3px rgba(28, 28, 30, 0.18);
        transform: scale(1.08);
    }

    /* ---------- Size Buttons ---------- */
    .qv-size-btn {
        min-width: 44px;
        height: 36px;
        padding: 0 14px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #363638;
        background: #fff;
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .qv-size-btn:hover {
        border-color: #1c1c1e;
        color: #1c1c1e;
        background: #f5f5f7;
    }

    .qv-size-btn.active {
        border-color: #1c1c1e;
        background: #1c1c1e;
        color: #fff;
        box-shadow: 0 3px 10px rgba(28, 28, 30, 0.25);
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
        border: 1.5px solid #e0e0e0;
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
        color: #636366;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }

    .qv-qty-btn:hover {
        background: #f5f5f7;
        color: #1c1c1e;
    }

    .qv-qty-btn:active { background: #f0f0f0; }

    .qv-qty-input {
        width: 52px;
        height: 42px;
        text-align: center;
        font-size: 15px;
        font-weight: 700;
        color: #1c1c1e;
        border: none;
        border-left: 1.5px solid #e0e0e0;
        border-right: 1.5px solid #e0e0e0;
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
        background: linear-gradient(135deg, #1c1c1e 0%, #111113 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
        box-shadow: 0 4px 14px rgba(28, 28, 30, 0.3);
        letter-spacing: 0.01em;
    }

    .qv-btn-primary:hover {
        filter: brightness(1.15);
        box-shadow: 0 6px 20px rgba(28, 28, 30, 0.4);
        transform: translateY(-1px);
    }

    .qv-btn-primary:active { transform: scale(0.98); }

    .qv-btn-outline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 48px;
        border: 2px solid #1c1c1e;
        color: #1c1c1e;
        background: transparent;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.01em;
    }

    .qv-btn-outline:hover {
        background: #1c1c1e;
        color: #fff;
        box-shadow: 0 4px 14px rgba(28, 28, 30, 0.25);
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
       MOBILE  (< 640px)
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
