@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('content')

<div class="mb-3">
    <div class="flex items-center justify-between">
        <h1 class="text-lg md:text-2xl font-bold text-gray-900 mb-0">Add New Product</h1>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Products
        </a>
    </div>
</div>

<form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Basic Information --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h2>

                {{-- Product Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                        placeholder="e.g., Men's Slim Fit Cotton Shirt">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SKU --}}
                <div class="mb-5">
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('sku') border-red-500 @enderror"
                        placeholder="e.g., SHIRT-BLU-M-001">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate</p>
                    @error('sku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Short Description --}}
                <div class="mb-5">
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('short_description') border-red-500 @enderror"
                        placeholder="Brief description for product listing">{{ old('short_description') }}</textarea>
                    @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Full Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                    <textarea name="description" id="description" rows="6"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                        placeholder="Detailed product description, features, and specifications">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Pricing</h2>

                <div class="grid md:grid-cols-3 gap-5">
                    {{-- Regular Price --}}
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Regular Price (৳) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('price') border-red-500 @enderror"
                            placeholder="0.00">
                        @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Compare Price --}}
                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-2">Compare Price (৳)</label>
                        <input type="number" step="0.01" name="compare_price" id="compare_price" value="{{ old('compare_price') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('compare_price') border-red-500 @enderror"
                            placeholder="0.00">
                        <p class="mt-1 text-xs text-gray-500">Original price before discount</p>
                        @error('compare_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cost Price --}}
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">Cost Price (৳)</label>
                        <input type="number" step="0.01" name="cost_price" id="cost_price" value="{{ old('cost_price') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('cost_price') border-red-500 @enderror"
                            placeholder="0.00">
                        <p class="mt-1 text-xs text-gray-500">For profit calculation</p>
                        @error('cost_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Product Details --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Product Details</h2>

                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Brand --}}
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('brand') border-red-500 @enderror"
                            placeholder="e.g., Nike, Adidas">
                        @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Material --}}
                    <div>
                        <label for="material" class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                        <input type="text" name="material" id="material" value="{{ old('material') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('material') border-red-500 @enderror"
                            placeholder="e.g., 100% Cotton">
                        @error('material')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fit Type --}}
                    <div>
                        <label for="fit_type" class="block text-sm font-medium text-gray-700 mb-2">Fit Type</label>
                        <select name="fit_type" id="fit_type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('fit_type') border-red-500 @enderror">
                            <option value="">Select Fit Type</option>
                            @foreach($fitTypes as $fitType)
                            <option value="{{ $fitType->value }}" {{ old('fit_type') == $fitType->value ? 'selected' : '' }}>
                                {{ $fitType->label() }}
                            </option>
                            @endforeach
                        </select>
                        @error('fit_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pattern --}}
                    <div>
                        <label for="pattern" class="block text-sm font-medium text-gray-700 mb-2">Pattern</label>
                        <select name="pattern" id="pattern"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('pattern') border-red-500 @enderror">
                            <option value="">Select Pattern</option>
                            @foreach($patterns as $pattern)
                            <option value="{{ $pattern->value }}" {{ old('pattern') == $pattern->value ? 'selected' : '' }}>
                                {{ $pattern->label() }}
                            </option>
                            @endforeach
                        </select>
                        @error('pattern')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Occasion --}}
                    <div>
                        <label for="occasion" class="block text-sm font-medium text-gray-700 mb-2">Occasion</label>
                        <select name="occasion" id="occasion"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('occasion') border-red-500 @enderror">
                            <option value="">Select Occasion</option>
                            @foreach($occasions as $occasion)
                            <option value="{{ $occasion->value }}" {{ old('occasion') == $occasion->value ? 'selected' : '' }}>
                                {{ $occasion->label() }}
                            </option>
                            @endforeach
                        </select>
                        @error('occasion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Weight --}}
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (grams)</label>
                        <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('weight') border-red-500 @enderror"
                            placeholder="0.00">
                        @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Inventory</h2>

                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Stock Quantity --}}
                    <div>
                        <label for="stock_in" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_in" id="stock_in" value="{{ old('stock_in', 0) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('stock_in') border-red-500 @enderror"
                            placeholder="0">
                        @error('stock_in')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Low Stock Threshold --}}
                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-2">Low Stock Alert</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('low_stock_threshold') border-red-500 @enderror"
                            placeholder="5">
                        <p class="mt-1 text-xs text-gray-500">Alert when stock reaches this level</p>
                        @error('low_stock_threshold')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SEO Settings --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">SEO Settings</h2>

                {{-- Meta Title --}}
                <div class="mb-5">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('meta_title') border-red-500 @enderror"
                        placeholder="SEO title for search engines">
                    @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Meta Description --}}
                <div class="mb-5">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('meta_description') border-red-500 @enderror"
                        placeholder="SEO description for search engines">{{ old('meta_description') }}</textarea>
                    @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tags') border-red-500 @enderror"
                        placeholder="e.g., summer, casual, cotton">
                    <p class="mt-1 text-xs text-gray-500">Separate tags with commas</p>
                    @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Status --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Status & Visibility</h2>

                {{-- Category --}}
                <div class="mb-5">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Status Checkboxes --}}
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active (visible to customers)</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">New Arrival</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Best Seller</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">On Sale</span>
                    </label>
                </div>
            </div>

            {{-- Product Images --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Product Images</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-600 mb-2">Drop images here or click to upload</p>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 5MB</p>
                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('images').click()"
                            class="mt-3 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            Choose Files
                        </button>
                    </div>
                    <div id="imagePreview" class="mt-4 grid grid-cols-2 gap-3"></div>
                    @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <button type="submit" id="submitBtn" class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:ring-blue-300 transition">
                    <i class="fas fa-plus mr-2"></i><span id="btnText">Save Product</span>
                    <i class="fas fa-spinner fa-spin ml-2 hidden" id="btnSpinner"></i>
                </button>
                <a href="{{ route('admin.products.index') }}" class="block w-full px-6 py-3 text-center text-gray-700 font-medium bg-gray-100 rounded-lg hover:bg-gray-200 transition mt-3">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';

        if (this.files) {
            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">Image ${index + 1}</span>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    const productForm = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    productForm.addEventListener('submit', function(e) {
        e.preventDefault();

        submitBtn.disabled = true;
        btnText.textContent = 'Saving...';
        btnSpinner.classList.remove('hidden');

        const formData = new FormData(productForm);

        fetch(productForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw {
                            status: response.status,
                            data: data
                        };
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);

                    productForm.reset();
                    document.getElementById('imagePreview').innerHTML = '';

                    setTimeout(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    }, 2000);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);

                if (error.status === 422 && error.data.errors) {
                    showToast('error', 'error.data.errors');
                } else {
                    const message = error.data?.message || 'An unexpected error occurred. Please try again.';
                    showToast('error', message);
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                btnText.textContent = 'Save Product';
                btnSpinner.classList.add('hidden');
            });
    });

    document.getElementById('name').addEventListener('input', function(e) {
        const name = e.target.value;
    });
</script>
@endpush

@endsection