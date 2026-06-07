{{-- Search Overlay --}}
<div id="searchOverlay" onclick="closeSearch()"
    class="fixed inset-0 bg-black/50 z-50 opacity-0 invisible transition-opacity duration-300"></div>

{{-- Search Modal --}}
<div id="searchModal"
    class="fixed top-0 left-0 right-0 z-50 transform -translate-y-full transition-transform duration-300 ease-out">
    <div class="bg-white shadow-2xl max-h-[85vh] overflow-hidden">
        <div class="max-w-4xl mx-auto">
            {{-- Search Header --}}
            <div class="p-4 md:p-5 border-b border-gray-100">
                <div class="relative flex items-center gap-3">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search for products, categories..."
                            autocomplete="off"
                            class="w-full h-12 md:h-14 pl-12 pr-12 bg-gray-100 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition"
                            oninput="handleSearchInput(this.value)">
                        <button onclick="clearSearch()" id="clearSearchBtn"
                            class="hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center hover:bg-gray-400 transition">
                            <i class="fas fa-times text-white text-xs"></i>
                        </button>
                    </div>
                    <button onclick="closeSearch()"
                        class="w-12 h-12 md:hidden rounded-xl hover:bg-gray-100 flex items-center justify-center transition">
                        <i class="fas fa-times text-gray-500 text-lg"></i>
                    </button>
                    <button onclick="closeSearch()"
                        class="hidden md:flex items-center gap-2 text-gray-500 hover:text-gray-700 transition text-sm">
                        <span>ESC</span>
                    </button>
                </div>
            </div>

            {{-- Search Content --}}
            <div class="overflow-y-auto max-h-[calc(85vh-80px)]">

                {{-- Default State (No Search) --}}
                <div id="searchDefaultState" class="p-4 md:p-5">

                </div>

                {{-- Search Results State --}}
                <div id="searchResultsState" class="hidden">

                    {{-- Product Results --}}
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-900">
                                Products <span id="resultCount" class="text-gray-400 font-normal">(0 results)</span>
                            </h3>
                        </div>

                        <div id="productResults" class="flex flex-col gap-2">
                            {{-- Products will be populated by JS --}}
                        </div>

                        {{-- No Results State --}}
                        <div id="noResultsState" class="hidden py-10 text-center">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-3xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No products found</h3>
                            <p class="text-sm text-gray-500 mb-4">Try searching with different keywords</p>
                            <a href="{{ route('products.index') }}" onclick="closeSearch()"
                                class="inline-flex items-center gap-2 text-primary font-medium text-sm hover:underline">
                                Browse All Products
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

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
                    class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-primary hover:shadow-md transition group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img src="${escapeHtml(product.image)}"
                                alt="${escapeHtml(product.name)}"
                                class="w-full h-full object-cover group-hover:scale-105 transition">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] text-gray-400 uppercase tracking-wide">
                                ${escapeHtml(product.category)}
                            </span>

                            <h4 class="text-sm font-medium text-gray-900 truncate group-hover:text-primary transition">
                                ${escapeHtml(product.name)}
                            </h4>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-sm font-bold text-primary">
                                ৳${product.price.toLocaleString()}
                            </div>

                            ${product.originalPrice ? `
                                <div class="text-xs text-gray-400 line-through">
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
            <p class="text-sm text-gray-400">No recent searches</p>
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