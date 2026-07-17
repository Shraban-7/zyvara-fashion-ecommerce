@extends('admin.layouts.app')
@section('title', 'Testimonials')

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary-800">Testimonials</h1>
            <p class="text-sm text-secondary-600">Customer reviews shown in the homepage testimonials section.</p>
            @if(isset($pendingCount) && $pendingCount)
            <span class="inline-flex items-center mt-1 text-xs font-semibold text-warning bg-warning-50 border border-warning-100 px-2.5 py-1 rounded-full">
                {{ $pendingCount }} pending approval
            </span>
            @endif
        </div>
        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Testimonial
        </button>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-secondary-500">
                <thead class="text-xs text-secondary-700 uppercase bg-secondary-50 border-b">
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
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-secondary-800 text-white flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($t->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-primary">{{ $t->name }}</div>
                                    <div class="text-xs text-secondary-400">{{ $t->location ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <p class="text-sm text-secondary-600 line-clamp-2">{{ $t->quote }}</p>
                        </td>
                        <td class="px-6 py-4 text-warning text-sm">
                            {{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}
                        </td>
                        <td class="px-6 py-4 text-secondary-600 font-mono text-xs">{{ $t->sort_order }}</td>
                        <td class="px-6 py-4">
                            @if(!$t->is_approved)
                            <span class="flex items-center text-warning text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-warning-500 mr-1.5"></span> Pending
                            </span>
                            @elseif($t->is_active)
                            <span class="flex items-center text-success text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-success mr-1.5"></span> Active
                            </span>
                            @else
                            <span class="flex items-center text-secondary-400 text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400 mr-1.5"></span> Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-1">
                                @if(!$t->is_approved)
                                <form action="{{ route('admin.testimonials.approve', $t->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-success hover:bg-success-50 rounded-md transition-all" title="Approve">
                                        <i class="fa-solid fa-check text-base"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.testimonials.reject', $t->id) }}" method="POST" onsubmit="return confirm('Reject and delete this submission?')">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-danger hover:bg-danger-50 rounded-md transition-all" title="Reject">
                                        <i class="fa-solid fa-xmark text-base"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button"
                                    onclick="openEditModal({{ $t->id }}, '{{ addslashes($t->name) }}', '{{ addslashes($t->location) }}', '{{ addslashes($t->quote) }}', {{ $t->rating }}, {{ $t->sort_order }}, {{ $t->is_active ? 'true' : 'false' }})"
                                    class="w-8 h-8 flex items-center justify-center text-primary hover:bg-accent-50 rounded-md transition-all" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </button>
                                <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-danger hover:bg-danger-50 rounded-md transition-all" title="Delete">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-secondary-400 italic">No testimonials yet. Click "Add Testimonial" to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeCreateModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
            <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Add Testimonial</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <x-input name="name" type="text" label="Customer Name *" required />
                        <x-input name="location" type="text" label="Location" placeholder="e.g. Dhaka" />
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Review *</label>
                            <textarea name="quote" rows="3" required class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Rating *</label>
                                <select name="rating" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                                </select>
                            </div>
                            <x-input name="sort_order" type="number" value="0" label="Sort Order" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Avatar (optional)</label>
                            <input type="file" name="avatar" accept="image/*" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                        </div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" checked class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeEditModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
            <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Edit Testimonial</h3>
                    <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <x-input name="name" type="text" label="Customer Name *" id="edit_name" required />
                        <x-input name="location" type="text" label="Location" id="edit_location" />
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Review *</label>
                            <textarea name="quote" id="edit_quote" rows="3" required class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Rating *</label>
                                <select name="rating" id="edit_rating" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                                </select>
                            </div>
                            <x-input name="sort_order" type="number" label="Sort Order" id="edit_sort_order" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Avatar (optional)</label>
                            <input type="file" name="avatar" accept="image/*" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                        </div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Update</button>
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
