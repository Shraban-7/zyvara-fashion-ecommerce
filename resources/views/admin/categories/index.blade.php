@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Categories</h1>
            <p class="text-sm text-secondary-500 mt-1">Manage your 3-level category hierarchy</p>

            {{-- Breadcrumb --}}
            <nav class="mt-2 flex items-center flex-wrap gap-1 text-sm">
                <a href="{{ route('admin.categories.index') }}"
                    class="text-secondary-400 hover:text-accent transition {{ $breadcrumb->isEmpty() ? 'font-semibold text-primary' : '' }}">
                    Categories
                </a>
                @foreach($breadcrumb as $crumb)
                    <span class="text-secondary-300">/</span>
                    @if($loop->last)
                        <span class="font-semibold text-primary">{{ $crumb->name }}</span>
                    @else
                        <a href="{{ route('admin.categories.index', ['parent' => $crumb->id]) }}"
                            class="text-secondary-400 hover:text-accent transition">{{ $crumb->name }}</a>
                    @endif
                @endforeach
            </nav>
        </div>

        <button type="button" onclick="openCreateModal({{ $parent ? $parent->id : 'null' }})"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-700 transition shadow-sm">
            <i class="fas fa-plus"></i>
            {{ $parent ? 'Add ' . ($parent->level == 0 ? 'Subcategory' : 'Sub-subcategory') : 'Add Category' }}
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-success-50 border border-success-200 px-4 py-3 text-sm text-success-700">{{ session('success') }}</div>
    @endif

    {{-- Card grid --}}
    @if($categories->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($categories as $cat)
                @php
                    $productCount = $cat->products_count + $cat->sub_cat_products_count + $cat->sub_sub_cat_products_count;
                    $subCount = $cat->children_count;
                    $canDrill = $subCount > 0 || $cat->canHaveChildren();
                @endphp
                <div class="group relative bg-surface-elevated rounded-2xl border border-secondary-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                    {{-- Card body (click to drill down) --}}
                    @if($canDrill)
                        <a href="{{ route('admin.categories.index', ['parent' => $cat->id]) }}" class="block p-5 flex-1">
                    @else
                        <div class="block p-5 flex-1">
                    @endif
                            <div class="flex items-center justify-center h-28 mb-4 rounded-xl bg-secondary-100 overflow-hidden">
                                @if($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="h-full w-full object-cover">
                                @else
                                    <i class="{{ $cat->icon ?? 'fas fa-image' }} text-3xl text-secondary-400"></i>
                                @endif
                            </div>
                            <h3 class="font-semibold text-secondary-800 text-center truncate">{{ $cat->name }}</h3>
                            <div class="mt-2 flex items-center justify-center gap-2 text-xs">
                                <span class="rounded-full bg-secondary-100 text-secondary-600 px-2.5 py-1 font-medium">
                                    {{ $productCount }} {{ Str::plural('product', $productCount) }}
                                </span>
                                @if($subCount > 0)
                                    <span class="rounded-full bg-accent-50 text-accent-700 px-2.5 py-1 font-medium">
                                        {{ $subCount }} sub{{ Str::plural('category', $subCount) }}
                                    </span>
                                @endif
                            </div>
                            @if(!$cat->is_active)
                                <span class="mt-2 block text-center text-[11px] font-semibold text-danger-600">Inactive</span>
                            @endif
                    @if($canDrill)</a>@else</div>@endif

                    {{-- Hover actions --}}
                    <div class="absolute top-2 right-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                        @if($cat->canHaveChildren())
                            <button type="button" onclick="openCreateModal({{ $cat->id }})" title="Add subcategory"
                                class="w-8 h-8 grid place-items-center rounded-lg bg-white/90 border border-secondary-200 text-accent hover:bg-accent hover:text-white transition shadow-sm">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        @endif
                        <button type="button" onclick="openEditModal(
                                {{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->parent_id ?? 'null' }},
                                '{{ $cat->icon }}', {{ $cat->sort_order }},
                                {{ $cat->is_active ? 'true' : 'false' }}, {{ $cat->is_featured ? 'true' : 'false' }},
                                '{{ $cat->image }}')" title="Edit"
                            class="w-8 h-8 grid place-items-center rounded-lg bg-white/90 border border-secondary-200 text-primary hover:bg-primary hover:text-white transition shadow-sm">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Delete &quot;{{ addslashes($cat->name) }}&quot;? Its subcategories will move to the top level.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete"
                                class="w-8 h-8 grid place-items-center rounded-lg bg-white/90 border border-secondary-200 text-danger hover:bg-danger hover:text-white transition shadow-sm">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @else
        <div class="bg-surface-elevated rounded-2xl border border-secondary-200 p-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-secondary-100 text-secondary-400">
                <i class="fas fa-folder-open text-xl"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-secondary-800">No categories here yet</h3>
            <p class="mt-1 text-secondary-500">
                @if($parent)
                    This category has no subcategories. Add one to build out your hierarchy.
                @else
                    Create your first top-level category to get started.
                @endif
            </p>
            <button type="button" onclick="openCreateModal({{ $parent ? $parent->id : 'null' }})"
                class="inline-flex items-center gap-2 mt-4 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
    @endif
</div>

{{-- Create Category Modal --}}
<div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="closeCreateModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>

        <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-primary">Add New Category</h3>
                <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="createForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="parent_id" id="create_parent_id">
                <div class="space-y-4">
                    <div>
                        <x-input name="name" type="text" label="Category Name *" placeholder="e.g. Women's Clothing" required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Parent Category</label>
                        <select id="create_parent_select" name="parent_id_display" disabled
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 bg-secondary-50 shadow-sm text-secondary-600">
                        </select>
                        <p id="create_parent_hint" class="mt-1 text-xs text-secondary-500"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Category Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            onchange="previewCreateImage(event)">
                        <div id="createImagePreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-secondary-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input name="icon" type="text" label="Icon Class" placeholder="fas fa-tag" required />
                            <p class="mt-1 text-xs text-secondary-500">FontAwesome icon class</p>
                        </div>
                        <div>
                            <x-input name="sort_order" type="number" value="0" label="Sort Order" required />
                        </div>
                    </div>

                    <div class="flex items-center gap-6 pt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" checked
                                class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured"
                                class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Featured</span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition">
                        <i class="fas fa-save mr-2"></i>Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="closeEditModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>

        <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-primary">Edit Category</h3>
                <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-input name="name" type="text" label="Category Name *" id="edit_name"
                            placeholder="e.g. Women's Clothing" required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Parent Category</label>
                        <select id="edit_parent_id" name="parent_id"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                            <option value="">None (Root Category)</option>
                        </select>
                        <p class="mt-1 text-xs text-secondary-500">A sub-subcategory cannot have children; only valid parents are shown.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Category Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            onchange="previewEditImage(event)">
                        <div id="editImagePreview" class="mt-3">
                            <img id="edit_image_preview" src="" alt="Preview"
                                class="w-24 h-24 object-cover rounded-lg border border-secondary-300">
                        </div>
                        <p class="mt-1 text-xs text-secondary-500">Leave empty to keep current image</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input name="icon" type="text" label="Icon Class" id="edit_icon" required />
                            <p class="mt-1 text-xs text-secondary-500">FontAwesome icon class</p>
                        </div>
                        <div>
                            <x-input name="sort_order" type="number" id="edit_sort_order" label="Sort Order" required />
                        </div>
                    </div>

                    <div class="flex items-center gap-6 pt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit_is_active" name="is_active"
                                class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit_is_featured" name="is_featured"
                                class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Featured</span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition">
                        <i class="fas fa-save mr-2"></i>Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Pre-built option list (id => {name, level}) from the server.
    @php
        $categoryOptionsData = $parentOptions->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'level' => $c->level,
                'parent_id' => $c->parent_id,
            ];
        })->values();
    @endphp
    const categoryOptions = @json($categoryOptionsData);
    const maxDepth = {{ \App\Models\Category::MAX_DEPTH }};

    function indent(level) {
        return level > 0 ? '└─ '.repeat(level) : '';
    }

    function openCreateModal(presetParentId) {
        const select = document.getElementById('create_parent_select');
        const hidden = document.getElementById('create_parent_id');
        const hint = document.getElementById('create_parent_hint');
        select.innerHTML = '';

        if (presetParentId) {
            const p = categoryOptions.find(c => c.id === presetParentId);
            hidden.value = presetParentId;
            select.innerHTML = `<option>${indent(p.level)}${p.name} (current)</option>`;
            const childLevel = (p.level ?? 0) + 1;
            hint.textContent = `This will be created as a ${childLevel === 1 ? 'subcategory' : 'sub-subcategory'} of "${p.name}".`;
        } else {
            hidden.value = '';
            select.innerHTML = `<option>None (Root Category)</option>`;
            hint.textContent = 'This will be a top-level category.';
        }

        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createForm').reset();
    }

    function openEditModal(id, name, parentId, icon, sortOrder, isActive, isFeatured, image) {
        const form = document.getElementById('editForm');
        form.action = '{{ route("admin.categories.index") }}/' + id + '/update';
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_sort_order').value = sortOrder;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('edit_is_featured').checked = isFeatured;

        // Build the parent <select> with only valid options:
        //  - depth < maxDepth (so the resulting category stays <= level 2)
        //  - not the category itself
        //  - not one of its descendants (no cycles)
        const select = document.getElementById('edit_parent_id');
        select.innerHTML = '<option value="">None (Root Category)</option>';

        const descendants = new Set();
        (function collect(pid) {
            categoryOptions.filter(c => c.parent_id === pid).forEach(c => {
                descendants.add(c.id);
                collect(c.id);
            });
        })(id);

        categoryOptions
            .filter(c => c.id !== id && !descendants.has(c.id) && (c.level ?? 0) < maxDepth)
            .sort((a, b) => (a.level - b.level) || a.name.localeCompare(b.name))
            .forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = indent(c.level) + c.name;
                if (c.id == parentId) opt.selected = true;
                select.appendChild(opt);
            });

        const preview = document.getElementById('editImagePreview');
        const previewImg = document.getElementById('edit_image_preview');
        if (image) {
            previewImg.src = '{{ asset("storage") }}/' + image;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });

    function previewCreateImage(event) {
        const preview = document.getElementById('createImagePreview');
        const img = preview.querySelector('img');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; preview.classList.remove('hidden'); };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }

    function previewEditImage(event) {
        const img = document.getElementById('edit_image_preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                document.getElementById('editImagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush

@endsection
