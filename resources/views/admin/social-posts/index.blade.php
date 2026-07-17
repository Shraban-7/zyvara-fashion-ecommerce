@extends('admin.layouts.app')
@section('title', 'Social Feed')

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary-800">Social Feed</h1>
            <p class="text-sm text-secondary-600">Manage the Instagram & Facebook posts shown on the homepage social strip.</p>
        </div>
        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Post
        </button>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-secondary-500">
                <thead class="text-xs text-secondary-700 uppercase bg-secondary-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Preview</th>
                        <th class="px-6 py-4">Platform</th>
                        <th class="px-6 py-4">Caption</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($posts as $post)
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="h-14 w-14 rounded-lg overflow-hidden border border-secondary-200 bg-secondary-100">
                                <img src="{{ storage_url($post->image) }}" class="h-full w-full object-cover" alt="{{ $post->caption }}">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-secondary-700">
                                <i class="{{ $post->platform_icon }}"></i> {{ $post->platform_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-secondary-800">{{ $post->caption ?? '—' }}</div>
                            @if($post->post_url)<div class="text-xs text-primary truncate max-w-xs">{{ $post->post_url }}</div>@endif
                        </td>
                        <td class="px-6 py-4 text-secondary-600 font-mono text-xs">{{ $post->sort_order }}</td>
                        <td class="px-6 py-4">
                            @if($post->is_active)
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
                                <button type="button"
                                    onclick="openEditModal({{ $post->id }}, '{{ $post->platform }}', '{{ addslashes($post->caption) }}', '{{ addslashes($post->post_url) }}', {{ $post->sort_order }}, {{ $post->is_active ? 'true' : 'false' }})"
                                    class="w-8 h-8 flex items-center justify-center text-primary hover:bg-accent-50 rounded-md transition-all" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </button>
                                <form action="{{ route('admin.social-posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-danger hover:bg-danger-50 rounded-md transition-all" title="Delete">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-secondary-400 italic">No social posts yet. Click "Add Post" to get started.</td>
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
            <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Add Social Post</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form action="{{ route('admin.social-posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Platform *</label>
                            <select name="platform" required class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Caption</label>
                            <input type="text" name="caption" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition" placeholder="e.g. #OOTD">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Post URL</label>
                            <input type="url" name="post_url" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition" placeholder="https://instagram.com/p/...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Image *</label>
                            <input type="file" name="image" accept="image/*" required class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                        </div>
                        <x-input name="sort_order" type="number" value="0" label="Sort Order" />
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
            <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Edit Social Post</h3>
                    <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Platform *</label>
                            <select name="platform" id="edit_platform" required class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Caption</label>
                            <input type="text" name="caption" id="edit_caption" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Post URL</label>
                            <input type="url" name="post_url" id="edit_post_url" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Image (leave empty to keep)</label>
                            <input type="file" name="image" accept="image/*" class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                        </div>
                        <x-input name="sort_order" type="number" label="Sort Order" id="edit_sort_order" />
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

    function openEditModal(id, platform, caption, postUrl, sortOrder, isActive) {
        document.getElementById('editForm').action = '{{ url("admin/social-posts") }}/' + id + '/update';
        document.getElementById('edit_platform').value = platform;
        document.getElementById('edit_caption').value = caption || '';
        document.getElementById('edit_post_url').value = postUrl || '';
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
