@extends('admin.layouts.app')
@section('title', 'Testimonials')

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Testimonials</h1>
            <p class="text-sm text-gray-600">Customer reviews shown in the homepage testimonials section.</p>
            @if(isset($pendingCount) && $pendingCount)
            <span class="inline-flex items-center mt-1 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-full">
                {{ $pendingCount }} pending approval
            </span>
            @endif
        </div>
        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Testimonial
        </button>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Review</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($testimonials as $t)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-slate-800 text-white flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($t->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $t->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $t->location ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $t->quote }}</p>
                        </td>
                        <td class="px-6 py-4 text-amber-500 text-sm">
                            {{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $t->sort_order }}</td>
                        <td class="px-6 py-4">
                            @if(!$t->is_approved)
                            <span class="flex items-center text-amber-600 text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                            </span>
                            @elseif($t->is_active)
                            <span class="flex items-center text-green-600 text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600 mr-1.5"></span> Active
                            </span>
                            @else
                            <span class="flex items-center text-gray-400 text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400 mr-1.5"></span> Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-1">
                                @if(!$t->is_approved)
                                <form action="{{ route('admin.testimonials.approve', $t->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-green-600 hover:bg-green-50 rounded-md transition-all" title="Approve">
                                        <i class="fa-solid fa-check text-base"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.testimonials.reject', $t->id) }}" method="POST" onsubmit="return confirm('Reject and delete this submission?')">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-md transition-all" title="Reject">
                                        <i class="fa-solid fa-xmark text-base"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button"
                                    onclick="openEditModal({{ $t->id }}, '{{ addslashes($t->name) }}', '{{ addslashes($t->location) }}', '{{ addslashes($t->quote) }}', {{ $t->rating }}, {{ $t->sort_order }}, {{ $t->is_active ? 'true' : 'false' }})"
                                    class="w-8 h-8 flex items-center justify-center text-indigo-600 hover:bg-indigo-50 rounded-md transition-all" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </button>
                                <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-md transition-all" title="Delete">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No testimonials yet. Click "Add Testimonial" to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeCreateModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Add Testimonial</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <x-input name="name" type="text" label="Customer Name *" required />
                        <x-input name="location" type="text" label="Location" placeholder="e.g. Dhaka" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Review *</label>
                            <textarea name="quote" rows="3" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                                <select name="rating" class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                                </select>
                            </div>
                            <x-input name="sort_order" type="number" value="0" label="Sort Order" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Avatar (optional)</label>
                            <input type="file" name="avatar" accept="image/*" class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeEditModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Edit Testimonial</h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <x-input name="name" type="text" label="Customer Name *" id="edit_name" required />
                        <x-input name="location" type="text" label="Location" id="edit_location" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Review *</label>
                            <textarea name="quote" id="edit_quote" rows="3" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                                <select name="rating" id="edit_rating" class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                                </select>
                            </div>
                            <x-input name="sort_order" type="number" label="Sort Order" id="edit_sort_order" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Avatar (optional)</label>
                            <input type="file" name="avatar" accept="image/*" class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
    function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    function openEditModal(id, name, location, quote, rating, sortOrder, isActive) {
        document.getElementById('editForm').action = '{{ url("admin/testimonials") }}/' + id + '/update';
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_location').value = location || '';
        document.getElementById('edit_quote').value = quote;
        document.getElementById('edit_rating').value = rating;
        document.getElementById('edit_sort_order').value = sortOrder;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editModal').classList.remove('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); }
    });
</script>
@endpush
@endsection
