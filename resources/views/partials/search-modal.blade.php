{{-- Search Overlay --}}
<div id="searchOverlay" onclick="closeSearch()"
    class="fixed inset-0 bg-primary/50 z-50 opacity-0 invisible transition-opacity duration-300"></div>

{{-- Search Modal --}}
<div id="searchModal"
    class="fixed top-0 left-0 right-0 z-50 transform -translate-y-full transition-transform duration-300 ease-out">
    <div class="bg-surface-elevated shadow-2xl shadow-black/20 max-h-[85vh] overflow-hidden">
        <div class="max-w-4xl mx-auto">
            {{-- Search Header --}}
            <div class="p-4 md:p-5 border-b border-primary-100">
                <div class="relative flex items-center gap-3">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-secondary-300"></i>
                        <input type="text" id="searchInput" placeholder="Search for products, categories..."
                            autocomplete="off"
                            class="w-full h-12 md:h-14 pl-12 pr-12 bg-light rounded-xl text-base text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-elevated focus:border-primary transition-all duration-200 border border-transparent"
                            oninput="handleSearchInput(this.value)">
                        <button onclick="clearSearch()" id="clearSearchBtn"
                            class="hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 bg-secondary-200 rounded-full flex items-center justify-center hover:bg-secondary-300 transition-colors duration-200">
                            <i class="fas fa-times text-surface-elevated text-xs"></i>
                        </button>
                    </div>
                    <button onclick="closeSearch()"
                        class="w-12 h-12 md:hidden rounded-xl hover:bg-primary-50 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-times text-secondary-500 text-lg"></i>
                    </button>
                    <button onclick="closeSearch()"
                        class="hidden md:flex items-center gap-2 text-secondary-400 hover:text-primary transition-colors duration-200 text-sm font-medium px-3 py-2 rounded-lg hover:bg-primary-50">
                        <span class="bg-primary-100 px-2 py-0.5 rounded text-xs font-bold text-secondary-600">ESC</span>
                    </button>
                </div>
            </div>

            {{-- Search Content --}}
            <div class="overflow-y-auto max-h-[calc(85vh-80px)] qv-scroll">

                {{-- Default State (No Search) --}}
                <div id="searchDefaultState" class="p-4 md:p-5">
                    {{-- Popular Searches --}}
                    <div class="mb-6">
                        <h3 class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-3">Popular Searches</h3>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="setSearchQuery('Shirt')" class="px-4 py-2 bg-light rounded-lg text-sm text-secondary hover:text-primary hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">Shirt</button>
                            <button onclick="setSearchQuery('Panjabi')" class="px-4 py-2 bg-light rounded-lg text-sm text-secondary hover:text-primary hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">Panjabi</button>
                            <button onclick="setSearchQuery('T-Shirt')" class="px-4 py-2 bg-light rounded-lg text-sm text-secondary hover:text-primary hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">T-Shirt</button>
                            <button onclick="setSearchQuery('Polo')" class="px-4 py-2 bg-light rounded-lg text-sm text-secondary hover:text-primary hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">Polo</button>
                        </div>
                    </div>
                    
                    {{-- Trending Categories --}}
                    <div>
                        <h3 class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-3">Trending Categories</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <a href="{{ route('products.index') }}?categories=men" onclick="closeSearch()" class="group p-4 bg-light rounded-xl hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">
                                <div class="w-10 h-10 bg-surface-elevated rounded-lg flex items-center justify-center mb-2 shadow-sm group-hover:shadow-md transition-shadow">
                                    <i class="fas fa-male text-primary text-lg"></i>
                                </div>
                                <span class="text-sm font-semibold text-primary group-hover:text-primary transition-colors">Men's Fashion</span>
                            </a>
                            <a href="{{ route('products.index') }}?categories=women" onclick="closeSearch()" class="group p-4 bg-light rounded-xl hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">
                                <div class="w-10 h-10 bg-surface-elevated rounded-lg flex items-center justify-center mb-2 shadow-sm group-hover:shadow-md transition-shadow">
                                    <i class="fas fa-female text-primary text-lg"></i>
                                </div>
                                <span class="text-sm font-semibold text-primary group-hover:text-primary transition-colors">Women's Fashion</span>
                            </a>
                            <a href="{{ route('products.index') }}?categories=kids" onclick="closeSearch()" class="group p-4 bg-light rounded-xl hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">
                                <div class="w-10 h-10 bg-surface-elevated rounded-lg flex items-center justify-center mb-2 shadow-sm group-hover:shadow-md transition-shadow">
                                    <i class="fas fa-child text-primary text-lg"></i>
                                </div>
                                <span class="text-sm font-semibold text-primary group-hover:text-primary transition-colors">Kids</span>
                            </a>
                            <a href="{{ route('products.index') }}?categories=accessories" onclick="closeSearch()" class="group p-4 bg-light rounded-xl hover:bg-primary-50 transition-all duration-200 border border-transparent hover:border-primary-100">
                                <div class="w-10 h-10 bg-surface-elevated rounded-lg flex items-center justify-center mb-2 shadow-sm group-hover:shadow-md transition-shadow">
                                    <i class="fas fa-gem text-primary text-lg"></i>
                                </div>
                                <span class="text-sm font-semibold text-primary group-hover:text-primary transition-colors">Accessories</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Search Results State --}}
                <div id="searchResultsState" class="hidden">

                    {{-- Product Results --}}
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-primary">
                                Products <span id="resultCount" class="text-secondary-300 font-normal">(0 results)</span>
                            </h3>
                        </div>

                        <div id="productResults" class="flex flex-col gap-2">
                            {{-- Products will be populated by JS --}}
                        </div>

                        {{-- No Results State --}}
                        <div id="noResultsState" class="hidden py-10 text-center">
                            <div
                                class="w-20 h-20 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-3xl text-secondary-300"></i>
                            </div>
                            <h3 class="text-lg font-bold text-primary mb-2">No products found</h3>
                            <p class="text-sm text-secondary mb-4">Try searching with different keywords</p>
                            <a href="{{ route('products.index') }}" onclick="closeSearch()"
                                class="inline-flex items-center gap-2 text-primary font-semibold text-sm hover:text-secondary transition-colors duration-200 group">
                                Browse All Products
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* Search scrollbar styling */
#searchModal .overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}
#searchModal .overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}
#searchModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c2c2c2;
    border-radius: 99px;
}
#searchModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a3a3a3;
}
#searchModal .overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #c2c2c2 transparent;
}

/* Search input focus glow */
#searchInput:focus {
    box-shadow: 0 0 0 4px rgba(28, 28, 30, 0.08);
}

/* Product result hover lift */
#productResults a {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
#productResults a:hover {
    transform: translateY(-1px);
}

/* Loading shimmer for search results */
.search-skeleton {
    background: linear-gradient(90deg, #f5f5f7 25%, #e8e8ec 37%, #f5f5f7 63%);
    background-size: 800px 100%;
    animation: searchShimmer 1.4s infinite linear;
    border-radius: 8px;
}

@keyframes searchShimmer {
    0% { background-position: -400px 0; }
    100% { background-position: 400px 0; }
}
</style>

{{-- Search Scripts --}}
<script>
    const searchUrl = "{{ route('products.search') }}";
    const suggestionUrl = "{{ route('products.suggestions') }}";


    let searchTimeout;
    let searchAbortController;
    let latestSearchRequestId = 0;

    function escapeRegex(value) {
        return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeSearchProducts(payload) {

        const productList =
            payload?.products ??
            payload?.data?.products ??
            payload?.data?.data ??
            payload?.data ??
            payload?.results ??
            payload?.items ??
            [];

        const list = Array.isArray(productList)
            ? productList
            : Object.values(productList || {});

        return list.map(item => ({
            name: item?.name ?? item?.title ?? 'Unnamed Product',
            slug: item?.slug ?? item?.id ?? '',
            url: item?.url ?? item?.product_url ?? null,
            image: item?.image ?? item?.image_url ?? item?.thumbnail ?? item?.thumbnail_url ?? item?.featured_image ?? 'https://via.placeholder.com/300x400?text=No+Image',
            category: item?.category?.name ?? item?.category_name ?? '',
            price: Number(item?.price ?? 0),
            originalPrice: item?.original_price ?? null
        })).filter(p => p.name);
    }

    function openSearch() {
        const modal = document.getElementById('searchModal');
        const overlay = document.getElementById('searchOverlay');
        const input = document.getElementById('searchInput');

        modal.classList.remove('-translate-y-full');
        overlay.classList.remove('opacity-0', 'invisible');
        overlay.classList.add('opacity-100', 'visible');
        document.body.style.overflow = 'hidden';

        setTimeout(() => input.focus(), 100);
    }

    function closeSearch() {
        const modal = document.getElementById('searchModal');
        const overlay = document.getElementById('searchOverlay');

        modal.classList.add('-translate-y-full');
        overlay.classList.add('opacity-0', 'invisible');
        overlay.classList.remove('opacity-100', 'visible');
        document.body.style.overflow = '';
    }

    function clearSearch() {
        const input = document.getElementById('searchInput');
        input.value = '';
        input.focus();
        handleSearchInput('');
    }

    function setSearchQuery(query) {
        const input = document.getElementById('searchInput');
        input.value = query;
        handleSearchInput(query);
    }

    function handleSearchInput(query) {
        const clearBtn = document.getElementById('clearSearchBtn');
        const defaultState = document.getElementById('searchDefaultState');
        const resultsState = document.getElementById('searchResultsState');

        if (query.length === 0) {
            clearBtn.classList.add('hidden');
            defaultState.classList.remove('hidden');
            resultsState.classList.add('hidden');
            return;
        }

        clearBtn.classList.remove('hidden');
        defaultState.classList.add('hidden');
        resultsState.classList.remove('hidden');

        // Debounce search
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => performSearch(query), 150);
    }

    async function performSearch(query) {

        const requestId = ++latestSearchRequestId;

        if (searchAbortController) {
            searchAbortController.abort();
        }

        searchAbortController = new AbortController();

        const requestUrl =
            `${searchUrl}?query=${encodeURIComponent(query)}`;

        try {

            const response = await fetch(requestUrl, {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: searchAbortController.signal
            });

            if (!response.ok) {
                throw new Error('Search failed');
            }

            const payload = await response.json();

            if (requestId !== latestSearchRequestId) {
                return;
            }

            renderProducts(
                normalizeSearchProducts(payload)
            );

        } catch (error) {

            if (error.name === 'AbortError') {
                return;
            }

            renderProducts([]);
        }
    }

    function renderProducts(products) {
        const container = document.getElementById('productResults');
        const noResults = document.getElementById('noResultsState');
        const resultCount = document.getElementById('resultCount');

        resultCount.textContent = `(${products.length} results)`;

        if (products.length === 0) {
            container.classList.add('hidden');
            noResults.classList.remove('hidden');
            return;
        }

        container.classList.remove('hidden');
        noResults.classList.add('hidden');

        const query = document.getElementById('searchInput').value;

        const html = products.slice(0, products.length).map(product => `
                    <a href="${escapeHtml(product.url || `{{ url('/products') }}/${product.slug}`)}"
                    onclick="closeSearch()"
                    class="flex items-center gap-3 p-3 bg-surface-elevated rounded-xl border border-primary-100 hover:border-primary hover:shadow-lg hover:shadow-primary/5 transition-all duration-200 group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 rounded-lg overflow-hidden bg-light">
                            <img src="${escapeHtml(product.image)}"
                                alt="${escapeHtml(product.name)}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] text-secondary-400 uppercase tracking-wide font-medium">
                                ${escapeHtml(product.category)}
                            </span>

                            <h4 class="text-sm font-semibold text-primary truncate group-hover:text-secondary transition-colors duration-200">
                                ${escapeHtml(product.name)}
                            </h4>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-sm font-bold text-primary">
                                ৳${product.price.toLocaleString()}
                            </div>

                            ${product.originalPrice ? `
                                <div class="text-xs text-secondary-300 line-through">
                                    ৳${product.originalPrice.toLocaleString()}
                                </div>
                            ` : ''}
                        </div>

                    </a>
                `).join('');

        container.innerHTML = html;
    }

    function clearRecentSearches() {
        document.getElementById('recentSearchesList').innerHTML = `
            <p class="text-sm text-secondary-300">No recent searches</p>
        `;
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSearch();
        }
        // Open search with Ctrl/Cmd + K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            openSearch();
        }
    });
</script>