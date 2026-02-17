@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
<div x-data="{ showModal: false }">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Categories</h2>
            <p class="text-sm text-gray-500">Manage your product hierarchy and visibility</p>
        </div>

        <button @click="showModal = true" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add New Category
        </button>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Sort</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Featured</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $category->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($category->icon)
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 mr-3">
                                    <i class="{{ $category->icon }} text-lg"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border">
                                {{ $category->parent ? $category->parent->name : 'Root' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $category->sort_order }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($category->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"> Active </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> Inactive </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->is_featured)
                            <span class="text-amber-500" title="Featured Category">
                                <i class="fas fa-star"></i>
                            </span>
                            @else
                            <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <div class="flex justify-end gap-2">
                                <div x-data="{ editModal{{ $category->id }}: false }">
                                    <button @click="editModal{{ $category->id }} = true" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-md transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <div x-show="editModal{{ $category->id }}" class="fixed inset-0 z-50 overflow-y-auto text-left" style="display: none;">
                                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                                            {{-- 1. Backdrop with Smooth Fade --}}
                                            <div x-show="editModal{{ $category->id }}"
                                                x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                @click="editModal{{ $category->id }} = false"
                                                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

                                            {{-- This span centers the modal --}}
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                            {{-- 2. Modal Content with Smooth Slide/Scale --}}
                                            <div x-show="editModal{{ $category->id }}"
                                                x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">

                                                <div class="flex items-center justify-between mb-6">
                                                    <h3 class="text-lg font-bold text-gray-900">Edit Category: {{ $category->name }}</h3>
                                                    <button @click="editModal{{ $category->id }} = false" class="text-gray-400 hover:text-gray-500 text-2xl">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="space-y-4">
                                                        {{-- Form fields go here (keeping same as your original) --}}
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Category Name</label>
                                                            <input type="text" name="name" value="{{ $category->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                                                            <select name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                                <option value="">None (Root)</option>
                                                                @foreach($categories as $parentOption)
                                                                @if($parentOption->id != $category->id)
                                                                <option value="{{ $parentOption->id }}" {{ $category->parent_id == $parentOption->id ? 'selected' : '' }}>
                                                                    {{ $parentOption->name }}
                                                                </option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Icon Class</label>
                                                                <input type="text" name="icon" value="{{ $category->icon }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                                                <input type="number" name="sort_order" value="{{ $category->sort_order }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-6 pt-2">
                                                            <label class="inline-flex items-center">
                                                                <input type="checkbox" name="is_active" {{ $category->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                                                                <span class="ml-2 text-sm text-gray-600">Active</span>
                                                            </label>
                                                            <label class="inline-flex items-center">
                                                                <input type="checkbox" name="is_featured" {{ $category->is_featured ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                                                                <span class="ml-2 text-sm text-gray-600">Featured</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="mt-8 flex justify-end gap-3">
                                                        <button type="button" @click="editModal{{ $category->id }} = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">Cancel</button>
                                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-sm transition">Update Category</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-md transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                            No categories found in the database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            {{-- Background Backdrop --}}
            <div x-show="showModal" @click="showModal = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

            {{-- This span centers the modal --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Content --}}
            <div x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Add New Category</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="e.g. Electronics">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                            <select name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">None (Root)</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Icon Class</label>
                                <input type="text" name="icon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="fas fa-tag">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                <input type="number" name="sort_order" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex items-center gap-6 pt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_featured" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Featured</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-sm">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection