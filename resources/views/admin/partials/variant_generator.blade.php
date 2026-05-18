<style>
    /* Custom Multiselect Styles */
    .custom-multiselect {
        position: relative;
    }

    .multiselect-trigger {
        min-height: 46px;
        border-radius: 14px;
        border: 2px solid #e5e7eb;
        padding: 8px 12px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .multiselect-trigger:hover {
        border-color: #d1d5db;
    }

    .multiselect-trigger.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .multiselect-selected {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        flex: 1;
    }

    .multiselect-tag {
        background: linear-gradient(135deg, #3b82f6 0%, #3b82f6 100%);
        color: white;
        border-radius: 10px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        animation: slideIn 0.2s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .multiselect-tag-remove {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transition: background 0.2s;
    }

    .multiselect-tag-remove:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .multiselect-placeholder {
        color: #9ca3af;
        font-size: 14px;
    }

    .multiselect-arrow {
        color: #6b7280;
        transition: transform 0.2s;
    }

    .multiselect-trigger.active .multiselect-arrow {
        transform: rotate(180deg);
    }

    .multiselect-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        animation: dropdownSlide 0.2s ease;
    }

    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .multiselect-dropdown.active {
        display: block;
    }

    .multiselect-search {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        sticky: top;
        background: white;
    }

    .multiselect-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    .multiselect-search input:focus {
        border-color: #3b82f6;
    }

    .multiselect-options {
        padding: 4px;
    }

    .multiselect-option {
        padding: 10px 12px;
        cursor: pointer;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.15s;
        font-size: 14px;
    }

    .multiselect-option:hover {
        background: #f3f4f6;
    }

    .multiselect-option.selected {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 500;
    }

    .multiselect-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .multiselect-option.selected .multiselect-checkbox {
        background: linear-gradient(135deg, #3b82f6 0%, #3b82f6 100%);
        border-color: #3b82f6;
    }

    .multiselect-checkbox i {
        color: white;
        font-size: 10px;
        display: none;
    }

    .multiselect-option.selected .multiselect-checkbox i {
        display: block;
    }

    .multiselect-no-results {
        padding: 20px;
        text-align: center;
        color: #9ca3af;
        font-size: 14px;
    }

    /* Scrollbar Styles */
    .multiselect-dropdown::-webkit-scrollbar {
        width: 8px;
    }

    .multiselect-dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .multiselect-dropdown::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .multiselect-dropdown::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Variant Card Animation */
    .variant-card {
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm" id="variantGenerator">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Product Variant Generator</h2>
            </div>
        </div>

        <div class="p-6 pt-0">
            <!-- Selectors -->
            <div class="grid md:grid-cols-3 gap-5 mb-6">

                <!-- Sizes -->
                <div>
                    <div class="custom-multiselect" id="sizeMultiselect">

                        <div class="multiselect-trigger">
                            <div class="multiselect-selected">
                                <span class="multiselect-placeholder">
                                    Select sizes...
                                </span>
                            </div>

                            <i class="fas fa-chevron-down multiselect-arrow"></i>
                        </div>

                        <div class="multiselect-dropdown">

                            <div class="multiselect-search">
                                <input type="text" placeholder="Search sizes..." class="multiselect-search-input">
                            </div>

                            <div class="multiselect-options">

                                @foreach($sizes as $size)
                                    <div class="multiselect-option" data-id="{{ $size->id }}" data-name="{{ $size->name }}">

                                        <div class="multiselect-checkbox">
                                            <i class="fas fa-check"></i>
                                        </div>

                                        <span>{{ $size->name }}</span>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colors -->
                <div>
                    <div class="custom-multiselect" id="colorMultiselect">

                        <div class="multiselect-trigger">
                            <div class="multiselect-selected">
                                <span class="multiselect-placeholder">
                                    Select colors...
                                </span>
                            </div>

                            <i class="fas fa-chevron-down multiselect-arrow"></i>
                        </div>

                        <div class="multiselect-dropdown">

                            <div class="multiselect-search">
                                <input type="text" placeholder="Search colors..." class="multiselect-search-input">
                            </div>

                            <div class="multiselect-options">

                                @foreach($colors as $color)
                                    <div class="multiselect-option" data-id="{{ $color->id }}"
                                        data-name="{{ $color->name }}">

                                        <div class="multiselect-checkbox">
                                            <i class="fas fa-check"></i>
                                        </div>

                                        <span>{{ $color->name }}</span>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-end mb-1">
                    <button type="button" id="generateVariantsBtn"
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-layer-group mr-2"></i>
                        Generate Variants
                    </button>
                </div>
            </div>

            <!-- Variants Container -->
            <div id="variantsContainer"
                class="space-y-4 max-h-[350px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">

                @if(isset($product) && $product->variants->count() > 0)

                    @foreach($product->variants as $i => $variant)

                        <div class="variant-card p-5 border border-gray-200 rounded-2xl bg-gray-50"
                            data-id="{{ $variant->id }}">

                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                            <input type="hidden" name="variants[{{ $i }}][size_id]" value="{{ $variant->size_id }}">
                            <input type="hidden" name="variants[{{ $i }}][color_id]" value="{{ $variant->color_id }}">

                            <div class="flex items-center justify-between">

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">
                                        {{ $variant->size->name ?? '' }} / {{ $variant->color->name ?? '' }}
                                    </h3>
                                </div>

                                <button type="button"
                                    class="removeVariantBtn inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-100 rounded-xl hover:bg-red-200 transition">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>

                            <div class="grid md:grid-cols-4 gap-4">

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Size</label>
                                    <input type="text" value="{{ $variant->size->name ?? '' }}" readonly
                                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-xl bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Color</label>
                                    <input type="text" value="{{ $variant->color->name ?? '' }}" readonly
                                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-xl bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                                    <input type="number" name="variants[{{ $i }}][price]" value="{{ $variant->price }}"
                                        class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-xl">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                                    <input type="text" name="variants[{{ $i }}][sku]" value="{{ $variant->sku }}"
                                        class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-xl">
                                </div>

                            </div>
                        </div>

                    @endforeach

                @else

                    <div id="empty-variant"
                        class="text-sm text-gray-500 text-center py-10 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50">
                        Click "Generate Variants" to create combinations.
                    </div>

                @endif

            </div>

            <!-- deleted tracking -->
            <input type="hidden" name="deleted_variants[]" id="deletedVariants">
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        class CustomMultiselect {

            constructor(element) {
                this.element = element;
                this.trigger = element.querySelector('.multiselect-trigger');
                this.dropdown = element.querySelector('.multiselect-dropdown');
                this.selectedContainer = element.querySelector('.multiselect-selected');
                this.optionsContainer = element.querySelector('.multiselect-options');
                this.searchInput = element.querySelector('.multiselect-search-input');

                this.selectedItems = [];

                this.init();
            }

            init() {
                this.bindEvents();
            }

            bindEvents() {

                this.trigger.addEventListener('click', (e) => {
                    e.stopPropagation();

                    document.querySelectorAll('.multiselect-dropdown.active').forEach(el => {
                        if (el !== this.dropdown) el.classList.remove('active');
                    });

                    document.querySelectorAll('.multiselect-trigger.active').forEach(el => {
                        if (el !== this.trigger) el.classList.remove('active');
                    });

                    this.dropdown.classList.toggle('active');
                    this.trigger.classList.toggle('active');
                });

                this.searchInput.addEventListener('input', (e) => {
                    const value = e.target.value.toLowerCase();

                    this.optionsContainer.querySelectorAll('.multiselect-option').forEach(option => {
                        const text = option.dataset.name.toLowerCase();
                        option.style.display = text.includes(value) ? 'flex' : 'none';
                    });
                });

                this.optionsContainer.addEventListener('click', (e) => {

                    const option = e.target.closest('.multiselect-option');
                    if (!option) return;

                    const item = {
                        id: option.dataset.id,
                        name: option.dataset.name
                    };

                    if (option.classList.contains('selected')) {

                        option.classList.remove('selected');
                        this.selectedItems = this.selectedItems.filter(i => i.id !== item.id);

                    } else {

                        option.classList.add('selected');
                        this.selectedItems.push(item);
                    }

                    this.renderSelected();
                });

                this.selectedContainer.addEventListener('click', (e) => {

                    const removeBtn = e.target.closest('.multiselect-tag-remove');
                    if (!removeBtn) return;

                    e.stopPropagation();

                    const tag = removeBtn.closest('.multiselect-tag');
                    const id = tag.dataset.id;

                    this.selectedItems = this.selectedItems.filter(item => item.id != id);

                    this.optionsContainer
                        .querySelector(`[data-id="${id}"]`)
                        ?.classList.remove('selected');

                    this.renderSelected();
                });

                document.addEventListener('click', (e) => {
                    if (!this.element.contains(e.target)) {
                        this.dropdown.classList.remove('active');
                        this.trigger.classList.remove('active');
                    }
                });
            }

            renderSelected() {

                if (this.selectedItems.length === 0) {
                    this.selectedContainer.innerHTML = `
                    <span class="multiselect-placeholder">Select options...</span>
                `;
                    return;
                }

                this.selectedContainer.innerHTML = this.selectedItems.map(item => `
                <div class="multiselect-tag" data-id="${item.id}">
                    <span>${item.name}</span>
                    <span class="multiselect-tag-remove">
                        <i class="fas fa-times"></i>
                    </span>
                </div>
            `).join('');
            }

            getSelected() {
                return this.selectedItems;
            }

            clear() {
                this.selectedItems = [];

                this.optionsContainer.querySelectorAll('.multiselect-option')
                    .forEach(option => option.classList.remove('selected'));

                this.renderSelected();
            }
        }

        const sizeMultiselect = new CustomMultiselect(document.getElementById('sizeMultiselect'));
        const colorMultiselect = new CustomMultiselect(document.getElementById('colorMultiselect'));

        const generateVariantsBtn = document.getElementById("generateVariantsBtn");
        const variantsContainer = document.getElementById("variantsContainer");

        generateVariantsBtn.addEventListener("click", function () {

            const selectedSizes = sizeMultiselect.getSelected();
            const selectedColors = colorMultiselect.getSelected();

            if (selectedSizes.length === 0 || selectedColors.length === 0) {
                return;
            }

            const variantsContainer = document.getElementById("variantsContainer");

            const existingKeys = new Set();

            document.querySelectorAll('.variant-card').forEach(card => {
                const sizeId = card.querySelector('input[name*="[size_id]"]')?.value;
                const colorId = card.querySelector('input[name*="[color_id]"]')?.value;

                if (sizeId && colorId) {
                    existingKeys.add(`${sizeId}_${colorId}`);
                }
            });

            let html = '';
            let index = document.querySelectorAll('.variant-card').length;

            const productSku = document.querySelector('[name="sku"]')?.value || '';

            selectedSizes.forEach(size => {
                selectedColors.forEach(color => {

                    const key = `${size.id}_${color.id}`;

                    if (existingKeys.has(key)) {
                        return;
                    }

                    existingKeys.add(key);

                    html += `
                <div class="variant-card p-5 border border-gray-200 rounded-2xl bg-gray-50">

                    <input type="hidden" name="variants[${index}][size_id]" value="${size.id}">
                    <input type="hidden" name="variants[${index}][color_id]" value="${color.id}">

                    <div class="flex items-center justify-between mb-5">

                        <div>
                            <h3 class="text-sm font-bold text-gray-900">
                                ${size.name} / ${color.name}
                            </h3>
                        </div>

                        <button type="button"
                            class="removeVariantBtn w-9 h-9 text-red-600 bg-red-100 rounded-xl hover:bg-red-200 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="grid md:grid-cols-4 gap-4">

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Size</label>
                            <input type="text" value="${size.name}" readonly
                                class="w-full px-3 py-2 text-sm border rounded-xl bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Color</label>
                            <input type="text" value="${color.name}" readonly
                                class="w-full px-3 py-2 text-sm border rounded-xl bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                            <input type="number" step="0.01"
                                name="variants[${index}][price]"
                                value="${productPrice}"
                                class="w-full px-3 py-2 text-sm border rounded-xl"
                                placeholder="0.00">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text"
                                name="variants[${index}][sku]"
                                class="w-full px-3 py-2 text-sm border rounded-xl">
                        </div>

                    </div>
                </div>
            `;

                    index++;
                });
            });

            variantsContainer.insertAdjacentHTML('beforeend', html);
        });

        variantsContainer.addEventListener("click", function (e) {

            const btn = e.target.closest(".removeVariantBtn");

            if (btn) {
                btn.closest(".variant-card").remove();
            }
        });

    });
</script>