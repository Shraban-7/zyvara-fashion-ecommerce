@php
    $isEdit = isset($flashSale);
    $selected = $isEdit
        ? $flashSale->products->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'price' => (float) $p->price,
            'sale_price' => $p->pivot->sale_price !== null ? (float) $p->pivot->sale_price : null,
            'image' => $p->primaryImage->first()?->image ? storage_url($p->primaryImage->first()->image) : null,
        ])->values()
        : collect();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="flashSaleForm(@js($selected))">
    {{-- Left: details --}}
    <div class="lg:col-span-1 space-y-5">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Details</h3>

            <x-input name="title" type="text" label="Title *" :value="old('title', $flashSale->title ?? '')" required />
            <x-input name="subtitle" type="text" label="Subtitle" :value="old('subtitle', $flashSale->subtitle ?? '')" placeholder="e.g. Weekend Blowout" />

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Starts At *</label>
                <input type="datetime-local" name="starts_at" required
                    value="{{ old('starts_at', isset($flashSale) ? $flashSale->starts_at->format('Y-m-d\TH:i') : '') }}"
                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ends At *</label>
                <input type="datetime-local" name="ends_at" required
                    value="{{ old('ends_at', isset($flashSale) ? $flashSale->ends_at->format('Y-m-d\TH:i') : '') }}"
                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <x-input name="display_order" type="number" label="Display Order" :value="old('display_order', $flashSale->display_order ?? 0)" />

            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" {{ old('is_active', $flashSale->is_active ?? true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-2">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>
    </div>

    {{-- Right: product picker --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">Products</h3>

            <div class="relative mb-4">
                <input type="text" x-model="search" @input.debounce.300ms="searchProducts()"
                    placeholder="Search products by name or SKU..."
                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">

                <div x-show="results.length" x-cloak
                    class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-72 overflow-y-auto">
                    <template x-for="p in results" :key="p.id">
                        <button type="button" @click="addProduct(p)"
                            class="flex items-center gap-3 w-full px-4 py-2.5 text-left hover:bg-gray-50 border-b border-gray-100 last:border-0">
                            <img :src="p.image || 'https://placehold.co/40x40?text=—'" class="h-9 w-9 rounded object-cover bg-gray-100">
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-800 truncate" x-text="p.name"></div>
                                <div class="text-xs text-gray-400" x-text="'SKU: ' + (p.sku || '—') + ' · ৳' + p.price"></div>
                            </div>
                            <i class="fas fa-plus text-blue-500 text-xs"></i>
                        </button>
                    </template>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-3 py-2">Product</th>
                            <th class="px-3 py-2 w-28">Regular</th>
                            <th class="px-3 py-2 w-36">Sale Price</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(p, idx) in selected" :key="p.id">
                            <tr class="border-b border-gray-100">
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <img :src="p.image || 'https://placehold.co/36x36?text=—'" class="h-8 w-8 rounded object-cover bg-gray-100">
                                        <span class="text-sm text-gray-800" x-text="p.name"></span>
                                    </div>
                                    <input type="hidden" name="products[]" :value="p.id">
                                </td>
                                <td class="px-3 py-2 text-gray-500" x-text="'৳' + p.price"></td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" min="0"
                                        :name="'sale_prices[' + p.id + ']'"
                                        x-model="p.sale_price"
                                        placeholder="Leave blank = regular"
                                        class="w-full px-2 py-1.5 rounded border border-gray-300 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <button type="button" @click="removeProduct(idx)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="!selected.length">
                            <td colspan="4" class="px-3 py-8 text-center text-gray-400 italic">No products added yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.flash-sales.index') }}"
                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</a>
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
                <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Update' : 'Create' }} Flash Sale
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function flashSaleForm(initial) {
        return {
            search: '',
            results: [],
            selected: initial || [],
            searchProducts() {
                if (this.search.trim().length < 1) { this.results = []; return; }
                fetch('{{ route("admin.flash-sales.search-products") }}?query=' + encodeURIComponent(this.search))
                    .then(r => r.json())
                    .then(data => {
                        const ids = this.selected.map(p => p.id);
                        this.results = data.filter(p => !ids.includes(p.id));
                    });
            },
            addProduct(p) {
                if (this.selected.find(x => x.id === p.id)) return;
                this.selected.push({ id: p.id, name: p.name, sku: p.sku, price: p.price, image: p.image, sale_price: null });
                this.results = [];
                this.search = '';
            },
            removeProduct(idx) {
                this.selected.splice(idx, 1);
            }
        };
    }
</script>
@endpush
