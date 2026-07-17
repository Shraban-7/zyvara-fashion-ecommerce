@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')

<div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-secondary-800">Categories</h2>
            <p class="text-sm text-secondary-500">Manage your product hierarchy and visibility</p>
        </div>

        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add New Category
        </button>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider text-center">Sort</th>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider text-center">Featured</th>
                        <th class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-secondary-600">{{ $category->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($category->icon)
                                <div class="flex-shrink-0 h-10 w-10 bg-secondary-100 rounded-lg flex items-center justify-center text-secondary-500 mr-3">
                                    <i class="{{ $category->icon }} text-lg"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-primary">{{ $category->name }}</div>
                                    <div class="text-xs text-secondary-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800 border">
                                {{ $category->parent ? $category->parent->name : 'Root' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-600 text-center">{{ $category->sort_order }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($category->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success"> Active </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger-100 text-danger"> Inactive </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->is_featured)
                            <span class="text-warning" title="Featured Category">
                                <i class="fas fa-star"></i>
                            </span>
                            @else
                            <span class="text-secondary-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->parent_id ?? 'null' }}, '{{ addslashes($category->icon ?? '') }}', {{ $category->sort_order }}, {{ $category->is_active ? 'true' : 'false' }}, {{ $category->is_featured ? 'true' : 'false' }})"
                                    class="text-primary hover:text-primary bg-accent-50 p-2 rounded-md transition-colors"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-danger hover:text-danger bg-danger-50 p-2 rounded-md transition-colors"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-secondary-500 italic">
                            No categories found in the database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="px-6 py-4 bg-secondary-50 border-t border-secondary-200">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    {{-- Add Category Modal --}}
    <div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background Backdrop --}}
            <div onclick="closeCreateModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>

            {{-- Modal Content --}}
            <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Add New Category</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-input name="name" type="text" label="Category Name *" placeholder="e.g. Men's Fashion" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Parent Category</label>
                            <select name="parent_id"
                                class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                <option value="">None (Root Category)</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input name="icon" type="text" label="Icon Class" placeholder="fas fa-tag" required />
                                <p class="mt-1 text-xs text-secondary-500">FontAwesome icon class</p>
                            </div>
                            <div>
                                <x-input name="sort_order" type="number" value="0" label="Sort Order" placeholder="fas fa-tag" required />
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
            {{-- Background Backdrop --}}
            <div onclick="closeEditModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>

            {{-- Modal Content --}}
            <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Edit Category</h3>
                    <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <x-input name="name" type="text" label="Category Name *" id="edit_name" placeholder="e.g. Men's Fashion" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Parent Category</label>
                            <select id="edit_parent_id" name="parent_id"
                                class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                <option value="">None (Root Category)</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
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
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(id, name, parentId, icon, sortOrder, isActive, isFeatured) {
        document.getElementById('editForm').action = '{{ route("admin.categories.index") }}/' + id + '/update';
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_parent_id').value = parentId || '';
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_sort_order').value = sortOrder;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('edit_is_featured').checked = isFeatured;

        // Hide the parent option that matches the current category
        const parentSelect = document.getElementById('edit_parent_id');
        Array.from(parentSelect.options).forEach(option => {
            if (option.value == id) {
                option.style.display = 'none';
            } else {
                option.style.display = 'block';
            }
        });

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });
</script>
@endpush

@endsection