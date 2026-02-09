@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Edit Product</h1>
            <p class="text-gray-500">Update product information</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Products
        </a>
    </div>
</div>

{{-- Form --}}
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Basic Information --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h2>

                {{-- Product Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                        placeholder="e.g., Men's Slim Fit Cotton Shirt">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SKU --}}
                <div class="mb-5">
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('sku') border-red-500 @enderror"
                        placeholder="e.g., SHIRT-BLU-M-001">
                    @error('sku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Short Description --}}
                <div class="mb-5">
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('short_description') border-red-500 @enderror"
                        placeholder="Brief description for product listing">{{ old('short_description', $product->short_description) }}</textarea>
                    @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Full Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                    <textarea name="description" id="description" rows="6"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                        placeholder="Detailed product description, features, and specifications">{{ old('description', $product->description) }}</textarea>
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
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('price') border-red-500 @enderror"
                            placeholder="0.00">
                        @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Compare Price --}}
                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-2">Compare Price (৳)</label>
                        <input type="number" step="0.01" name="compare_price" id="compare_price" value="{{ old('compare_price', $product->compare_price) }}"
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
                        <input type="number" step="0.01" name="cost_price" id="cost_price" value="{{ old('cost_price', $product->cost_price) }}"
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
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('brand') border-red-500 @enderror"
                            placeholder="e.g., Nike, Adidas">
                        @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Material --}}
                    <div>
                        <label for="material" class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                        <input type="text" name="material" id="material" value="{{ old('material', $product->material) }}"
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
                            <option value="{{ $fitType->value }}" {{ old('fit_type', $product->fit_type?->value) == $fitType->value ? 'selected' : '' }}>
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
                            <option value="{{ $pattern->value }}" {{ old('pattern', $product->pattern?->value) == $pattern->value ? 'selected' : '' }}>
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
                            <option value="{{ $occasion->value }}" {{ old('occasion', $product->occasion?->value) == $occasion->value ? 'selected' : '' }}>
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
                        <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $product->weight) }}"
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
                        <input type="number" name="stock_in" id="stock_in" value="{{ old('stock_in', $product->stock_in) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('stock_in') border-red-500 @enderror"
                            placeholder="0">
                        @error('stock_in')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Low Stock Threshold --}}
                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-2">Low Stock Alert</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('low_stock_threshold') border-red-500 @enderror"
                            placeholder="5">
                        <p class="mt-1 text-xs text-gray-500">Alert when stock reaches this level</p>
                        @error('low_stock_threshold')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Product Variants --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Product Variants</h2>
                    <button type="button" onclick="addVariant()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add Variant
                    </button>
                </div>

                <div id="variantsContainer" class="space-y-4">
                    @forelse($product->variants as $index => $variant)
                    <div class="variant-row p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">

                        <div class="grid md:grid-cols-6 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Size</label>
                                <select name="variants[{{ $index }}][size_id]" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Size</option>
                                    @foreach($sizes as $size)
                                    <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Color</label>
                                <select name="variants[{{ $index }}][color_id]" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Color</option>
                                    @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Price (৳)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="variants[{{ $index }}][price]"
                                    value="{{ $variant->price }}"
                                    required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="0.00">
                            </div>


                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                                <input type="text" name="variants[{{ $index }}][sku]" value="{{ $variant->sku }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Optional">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                                <input type="number" name="variants[{{ $index }}][stock_in]" value="{{ $variant->stock_in }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="0" min="0">
                            </div>

                            <div class="flex items-end">
                                <button type="button" onclick="removeVariant(this, {{ $variant->id }})"
                                    class="w-full px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No variants added yet. Click "Add Variant" to create one.</p>
                    @endforelse
                </div>

                <input type="hidden" name="delete_variants[]" id="deleteVariants" value="">
            </div>

            {{-- SEO Settings --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">SEO Settings</h2>

                {{-- Meta Title --}}
                <div class="mb-5">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
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
                        placeholder="SEO description for search engines">{{ old('meta_description', $product->meta_description) }}</textarea>
                    @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags', is_array($product->tags) ? implode(', ', $product->tags) : '') }}"
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
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active (visible to customers)</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">New Arrival</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Best Seller</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">On Sale</span>
                    </label>
                </div>
            </div>

            {{-- Product Images --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Product Images</h2>

                {{-- Existing Images --}}
                @if($product->images->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            <label class="absolute top-2 right-2 cursor-pointer">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="hidden delete-image-checkbox">
                                <span class="block w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Hover over image and click trash icon to delete</p>
                </div>
                @endif

                {{-- Upload New Images --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
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
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:ring-blue-300 transition">
                    <i class="fas fa-save mr-2"></i>Update Product
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
    let variantIndex = "{{ $product->variants->count() }}";
    const deleteVariantsArray = [];

    const sizes = @json($sizes);
    const colors = @json($colors);

    // Add new variant
    function addVariant() {
        const container = document.getElementById('variantsContainer');

        // Remove "no variants" message if it exists
        const noVariantsMsg = container.querySelector('p.text-gray-500');
        if (noVariantsMsg) {
            noVariantsMsg.remove();
        }

        const variantRow = document.createElement('div');
        variantRow.className = 'variant-row p-4 border border-gray-200 rounded-lg bg-gray-50';

        variantRow.innerHTML = `
            <div class="grid md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Size</label>
                    <select 
                        name="variants[${variantIndex}][size_id]" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Size</option>
                        ${sizes.map(size => `
                            <option value="${size.id}">${size.name}</option>
                        `).join('')}
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Color</label>
                    <select 
                        name="variants[${variantIndex}][color_id]" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Color</option>
                        ${colors.map(color => `
                            <option value="${color.id}">${color.name}</option>
                        `).join('')}
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Price (৳)</label>
                    <input 
                        type="number"
                        step="0.01"
                        name="variants[${variantIndex}][price]"
                        required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>


                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                    <input 
                        type="text"
                        name="variants[${variantIndex}][sku]"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Optional">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                    <input 
                        type="number"
                        name="variants[${variantIndex}][stock_in]"
                        value="0"
                        min="0"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-end">
                    <button 
                        type="button"
                        onclick="removeVariant(this)"
                        class="w-full px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(variantRow);
        variantIndex++;
    }

    // Remove variant
    function removeVariant(button, variantId = null) {
        if (variantId) {
            deleteVariantsArray.push(variantId);
            updateDeleteVariantsInput();
        }
        button.closest('.variant-row').remove();
    }

    // Update hidden input with variants to delete
    function updateDeleteVariantsInput() {
        const form = document.querySelector('form');
        // Remove existing delete_variants inputs
        form.querySelectorAll('input[name="delete_variants[]"]').forEach(input => input.remove());

        // Add new inputs
        deleteVariantsArray.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_variants[]';
            input.value = id;
            form.appendChild(input);
        });
    }

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
                    div.innerHTML = ` <
        img src = "${e.target.result}"
        class = "w-full h-32 object-cover rounded-lg border border-gray-200" >
        <
        span class = "absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded" > New $ {
            index + 1
        } < /span>
        `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Handle delete image checkbox clicks
    document.querySelectorAll('.delete-image-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const imageContainer = this.closest('.relative');
            if (this.checked) {
                imageContainer.classList.add('opacity-50');
                imageContainer.querySelector('img').classList.add('grayscale');
            } else {
                imageContainer.classList.remove('opacity-50');
                imageContainer.querySelector('img').classList.remove('grayscale');
            }
        });
    });
</script>
@endpush

@endsection