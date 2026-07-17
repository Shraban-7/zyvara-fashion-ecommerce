@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Products</h1>
            <p class="text-secondary-500">Manage your product catalog</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i>Add Product
        </a>
    </div>
</div>

{{-- Filters & Search --}}
<div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
    <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
        <div class="grid md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-secondary-700 mb-2">Search Products</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="Search by name, SKU, or brand...">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-400"></i>
                </div>
            </div>

            {{-- Category Filter --}}
            <div>
                <label for="category" class="block text-sm font-medium text-secondary-700 mb-2">Category</label>
                <select name="category" id="category"
                    class="w-full px-4 py-2.5 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2.5 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Featured</option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-4 gap-4">
            {{-- Min Price --}}
            <div>
                <label for="min_price" class="block text-sm font-medium text-secondary-700 mb-2">Min Price (৳)</label>
                <input type="number" step="0.01" name="min_price" id="min_price" value="{{ request('min_price') }}"
                    class="w-full px-4 py-2.5 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                    placeholder="0.00">
            </div>

            {{-- Max Price --}}
            <div>
                <label for="max_price" class="block text-sm font-medium text-secondary-700 mb-2">Max Price (৳)</label>
                <input type="number" step="0.01" name="max_price" id="max_price" value="{{ request('max_price') }}"
                    class="w-full px-4 py-2.5 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                    placeholder="0.00">
            </div>

            {{-- Sort --}}
            <div>
                <label for="sort" class="block text-sm font-medium text-secondary-700 mb-2">Sort By</label>
                <select name="sort" id="sort"
                    class="w-full px-4 py-2.5 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-secondary-100 text-secondary-700 font-medium rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Products Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-secondary-50 border-b border-secondary-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-secondary-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->thumbnail }}"
                                alt="image"
                                class="w-12 h-12 rounded-lg object-cover bg-secondary-100">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-primary">{{ $product->name }}</p>
                                @if($product->brand)
                                <p class="text-xs text-secondary-500">{{ $product->brand->name }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-primary">{{ $product->sku ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-primary">{{ $product->category?->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-primary">{{ money($product->price) }}</p>
                            @if($product->compare_price && $product->compare_price > $product->price)
                            <p class="text-xs text-secondary-500 line-through">{{ money($product->compare_price) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->totalStock <= 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-danger-100 text-danger">
                            <i class="fas fa-times-circle mr-1"></i> Out of Stock
                            </span>
                            @elseif($product->totalStock <= $product->low_stock_threshold)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Low ({{ $product->totalStock}})
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success-100 text-success">
                                    <i class="fas fa-check-circle mr-1"></i> In Stock ({{ $product->totalStock }})
                                </span>
                                @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success-100 text-success">
                                <i class="fas fa-eye mr-1"></i> Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800">
                                <i class="fas fa-eye-slash mr-1"></i> Inactive
                            </span>
                            @endif
                            @if($product->is_featured)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary">
                                <i class="fas fa-star mr-1"></i> Featured
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            {{-- <a href="#" class="p-2 text-secondary-600 hover:text-primary hover:bg-primary-50 rounded-lg transition" title="View">
                                <i class="fas fa-eye"></i>
                            </a> --}}
                            <a href="{{ route('admin.products.manage-stock', $product) }}" class="p-2 text-secondary-600 hover:text-accent hover:bg-accent-50 rounded-lg transition" title="Manage Stock">
                                <i class="fas fa-boxes"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-secondary-600 hover:text-success hover:bg-success-50 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $product->id }})" class="p-2 text-secondary-600 hover:text-danger hover:bg-danger-50 rounded-lg transition" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-6xl text-secondary-300 mb-4"></i>
                            <h3 class="text-lg font-semibold text-primary mb-2">No Products Found</h3>
                            <p class="text-secondary-500 mb-4">Start by adding your first product to the catalog.</p>
                            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-700 transition">
                                <i class="fas fa-plus mr-2"></i>Add Product
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-secondary-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-secondary-700">
                Showing <span class="font-medium">{{ $products->firstItem() }}</span> to <span class="font-medium">{{ $products->lastItem() }}</span> of <span class="font-medium">{{ $products->total() }}</span> results
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(productId) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${productId}/delete`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Auto-submit form on filter change
    document.querySelectorAll('#category, #status, #sort').forEach(element => {
        element.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

@endsection