@extends('admin.layouts.app')
@section('title', 'Events')

@section('content')

<div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary-800">Festival / Running Events</h1>
            <p class="text-sm text-secondary-600">Events with an active status and current date range appear on the homepage bento grid automatically.</p>
        </div>

        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Event
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-success-50 border border-success-200 px-4 py-3 text-sm text-success-700 mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-secondary-500">
                <thead class="text-xs text-secondary-700 uppercase bg-secondary-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Preview</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4">Date Range</th>
                        <th class="px-6 py-4">Renders As</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($events as $event)
                        @php
                            $statusKey = $event->statusKey();
                            $activeCount = \App\Models\Event::active()->count();
                            $willRender = $event->isCurrentlyVisible();
                            $sizeHint = $willRender
                                ? (($activeCount === 1) ? 'Large hero tile' : ($event->priority > 0 && $event->priority >= ($events->max('priority') ?? 0) ? 'Large featured tile' : 'Small / medium tile'))
                                : 'Not shown (status: ' . $statusKey . ')';
                        @endphp
                        <tr class="hover:bg-secondary-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-16 w-28 rounded overflow-hidden border border-secondary-200 bg-secondary-100">
                                    @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" class="h-full w-full object-cover" alt="{{ $event->title }}">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-secondary-400"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-primary">{{ $event->title }}</div>
                                <div class="text-xs text-secondary-500 line-clamp-1">{{ $event->subtitle ?? 'No subtitle' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-secondary-600">{{ $event->priority }}</td>
                            <td class="px-6 py-4 text-xs text-secondary-600 whitespace-nowrap">
                                {{ $event->start_date?->format('M d') ?? '—' }}
                                <span class="text-secondary-400">→</span>
                                {{ $event->end_date?->format('M d') ?? 'ongoing' }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="inline-flex px-2 py-1 rounded bg-secondary-50 text-secondary-600 border border-secondary-200">{{ $sizeHint }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @include('admin.events._status-badge', ['key' => $statusKey])
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex justify-end items-center gap-1">
                                    <button type="button"
                                        onclick="openEditModal(
                                            {{ $event->id }},
                                            '{{ addslashes($event->title) }}',
                                            '{{ addslashes($event->subtitle ?? '') }}',
                                            '{{ addslashes($event->link_url ?? '') }}',
                                            '{{ addslashes($event->badge_text ?? '') }}',
                                            {{ $event->priority }},
                                            '{{ $event->start_date?->format('Y-m-d') }}',
                                            '{{ $event->end_date?->format('Y-m-d') }}',
                                            {{ $event->display_order }},
                                            {{ $event->is_active ? 'true' : 'false' }},
                                            '{{ $event->image }}'
                                        )"
                                        class="w-8 h-8 flex items-center justify-center text-primary hover:bg-accent-50 rounded-md transition-all" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-base"></i>
                                    </button>
                                    <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event?')">
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
                            <td colspan="7" class="px-6 py-12 text-center text-secondary-400 italic">No events yet. Click "Add Event" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Live mini preview of the homepage bento grid --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-secondary-800 mb-1">Homepage Bento Preview</h2>
        <p class="text-sm text-secondary-500 mb-4">Mirrors the storefront layout for the currently active events (updates after save).</p>
        @include('admin.events._bento-preview', ['cells' => \App\Services\BentoLayoutService::cached()])
    </div>
</div>

{{-- Create Modal --}}
<div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="closeCreateModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
        <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-primary">Add Event</h3>
                <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2"><x-input name="title" type="text" label="Event Title *" placeholder="e.g. Summer Sale" required /></div>
                    <div class="md:col-span-2"><x-input name="subtitle" type="text" label="Subtitle" placeholder="e.g. Up to 50% Off" /></div>
                    <div><x-input name="link_url" type="text" label="Link URL" placeholder="e.g. /shop? sale=summer" /></div>
                    <div><x-input name="badge_text" type="text" label="Badge Text" placeholder="e.g. Ends in 3 days" /></div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Priority *</label>
                        <input type="number" name="priority" value="0" min="0"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            required>
                        <p class="mt-1 text-xs text-secondary-500">Higher priority = larger cell / featured first.</p>
                    </div>
                    <div><x-input name="display_order" type="number" value="0" label="Display Order *" required /></div>

                    <div><x-input name="start_date" type="date" label="Start Date" /></div>
                    <div><x-input name="end_date" type="date" label="End Date" /></div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Image *</label>
                        <input type="file" name="image" accept="image/*" required
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            onchange="previewCreateImage(event)">
                        <div id="createImagePreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="max-h-32 rounded-lg border border-secondary-300">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" checked class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="closeEditModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
        <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-primary">Edit Event</h3>
                <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2"><x-input name="title" type="text" id="edit_title" label="Event Title *" required /></div>
                    <div class="md:col-span-2"><x-input name="subtitle" type="text" id="edit_subtitle" label="Subtitle" /></div>
                    <div><x-input name="link_url" type="text" id="edit_link_url" label="Link URL" /></div>
                    <div><x-input name="badge_text" type="text" id="edit_badge_text" label="Badge Text" /></div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Priority *</label>
                        <input type="number" name="priority" id="edit_priority" min="0"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            required>
                    </div>
                    <div><x-input name="display_order" type="number" id="edit_display_order" label="Display Order *" required /></div>

                    <div><x-input name="start_date" type="date" id="edit_start_date" label="Start Date" /></div>
                    <div><x-input name="end_date" type="date" id="edit_end_date" label="End Date" /></div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            onchange="previewEditImage(event)">
                        <div id="editImagePreview" class="mt-3">
                            <img id="edit_image_preview" src="" alt="Preview" class="max-h-32 rounded-lg border border-secondary-300">
                        </div>
                        <p class="mt-1 text-xs text-secondary-500">Leave empty to keep current image</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Update Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
    function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }

    function openEditModal(id, title, subtitle, linkUrl, badgeText, priority, startDate, endDate, displayOrder, isActive, image) {
        const form = document.getElementById('editForm');
        form.action = '{{ route("admin.events.index") }}/' + id + '/update';
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_subtitle').value = subtitle || '';
        document.getElementById('edit_link_url').value = linkUrl || '';
        document.getElementById('edit_badge_text').value = badgeText || '';
        document.getElementById('edit_priority').value = priority;
        document.getElementById('edit_start_date').value = startDate || '';
        document.getElementById('edit_end_date').value = endDate || '';
        document.getElementById('edit_display_order').value = displayOrder;
        document.getElementById('edit_is_active').checked = isActive;

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
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    function previewCreateImage(event) {
        const preview = document.getElementById('createImagePreview');
        const img = preview.querySelector('img');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; preview.classList.remove('hidden'); };
            reader.readAsDataURL(file);
        } else { preview.classList.add('hidden'); }
    }
    function previewEditImage(event) {
        const img = document.getElementById('edit_image_preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; document.getElementById('editImagePreview').classList.remove('hidden'); };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); }
    });
</script>
@endpush

@endsection
