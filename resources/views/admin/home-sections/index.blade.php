@extends('admin.layouts.app')
@section('title', 'Homepage Sections')

@php
$labels = [
    'hero' => 'Hero Slider',
    'categories' => 'Featured Categories',
    'flash_sale' => 'Flash Sale',
    'new_arrivals' => 'New Arrivals',
    'trending' => 'Trending Now',
    'best_selling' => 'Best Selling',
    'on_sale' => 'On Sale',
    'featured' => 'Featured Products',
    'bento_events' => 'Bento / Events Grid',
    'testimonials' => 'Testimonials',
    'festive_banner' => 'Festive Banner',
    'mens_collection' => "Men's Collection",
    'ladies_collection' => "Ladies' Collection",
    'our_brands' => 'Our Brands',
    'why_us' => 'Why Choose Us',
    'showroom' => 'Showroom',
    'newsletter' => 'Newsletter',
    'social_feed' => 'Social Feed',
];
@endphp

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary-800">Homepage Sections</h1>
            <p class="text-sm text-secondary-600">Control which sections appear on the homepage, their order, and their headings. Drag rows to reorder.</p>
        </div>

        @if($availableKeys->isNotEmpty())
        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Section
        </button>
        @endif
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-secondary-500">
                <thead class="text-xs text-secondary-700 uppercase bg-secondary-50 border-b">
                    <tr>
                        <th class="px-4 py-4 w-10"></th>
                        <th class="px-6 py-4">Section</th>
                        <th class="px-6 py-4">Title / Subtitle</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="sectionsBody" class="divide-y divide-gray-200">
                    @forelse($sections as $section)
                    <tr class="hover:bg-secondary-50 transition-colors" data-id="{{ $section->id }}">
                        <td class="px-4 py-4 text-secondary-300 cursor-move drag-handle text-center">
                            <i class="fas fa-grip-vertical"></i>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-primary">{{ $labels[$section->section_key] ?? $section->section_key }}</div>
                            <div class="text-xs text-secondary-400 font-mono">{{ $section->section_key }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-secondary-800">{{ $section->title ?? '—' }}</div>
                            <div class="text-xs text-secondary-500 line-clamp-1">{{ $section->subtitle ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-secondary-600 font-mono text-xs">{{ $section->item_limit }}</td>
                        <td class="px-6 py-4">
                            <button type="button"
                                onclick="toggleStatus({{ $section->id }}, this)"
                                class="toggle-btn inline-flex items-center text-xs font-medium {{ $section->is_visible ? 'text-success' : 'text-secondary-400' }}">
                                <span class="h-1.5 w-1.5 rounded-full mr-1.5 {{ $section->is_visible ? 'bg-success' : 'bg-gray-400' }}"></span>
                                <span class="toggle-label">{{ $section->is_visible ? 'Visible' : 'Hidden' }}</span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-1">
                                <button type="button"
                                    onclick="openEditModal({{ $section->id }}, '{{ addslashes($labels[$section->section_key] ?? $section->section_key) }}', '{{ addslashes($section->title) }}', '{{ addslashes($section->eyebrow) }}', '{{ addslashes($section->subtitle) }}', {{ $section->item_limit }}, {{ $section->is_visible ? 'true' : 'false' }})"
                                    class="w-8 h-8 flex items-center justify-center text-primary hover:bg-accent-50 rounded-md transition-all"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </button>
                                <form action="{{ route('admin.home-sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Remove this section from the homepage?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-danger hover:bg-danger-50 rounded-md transition-all"
                                        title="Remove">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-secondary-400 italic">
                            No sections configured yet. Click "Add Section" to build your homepage.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Section Modal --}}
    <div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeCreateModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
            <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Add Section</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.home-sections.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Section Type *</label>
                            <select name="section_key" required
                                class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition">
                                @foreach($availableKeys as $key)
                                <option value="{{ $key }}">{{ $labels[$key] ?? $key }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-input name="title" type="text" label="Heading (optional)" placeholder="e.g. New Arrivals" />
                        <x-input name="eyebrow" type="text" label="Eyebrow / Accent label (optional)" placeholder="e.g. Just Dropped" />
                        <x-input name="subtitle" type="text" label="Subheading (optional)" placeholder="e.g. Fresh drops this week" />
                        <x-input name="item_limit" type="number" value="10" label="Item Limit *" required />
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_visible" checked
                                class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Visible on homepage</span>
                        </label>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition">
                            <i class="fas fa-save mr-2"></i>Add Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Section Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeEditModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
            <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-primary">Edit <span id="edit_section_name"></span></h3>
                    <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <x-input name="title" type="text" label="Heading (optional)" id="edit_title" />
                        <x-input name="eyebrow" type="text" label="Eyebrow / Accent label (optional)" id="edit_eyebrow" />
                        <x-input name="subtitle" type="text" label="Subheading (optional)" id="edit_subtitle" />
                        <x-input name="item_limit" type="number" label="Item Limit *" id="edit_item_limit" required />
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_visible" id="edit_is_visible"
                                class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Visible on homepage</span>
                        </label>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition">
                            <i class="fas fa-save mr-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const CSRF = '{{ csrf_token() }}';

    function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
    function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    function openEditModal(id, name, title, eyebrow, subtitle, itemLimit, isVisible) {
        document.getElementById('editForm').action = '{{ url("admin/home-sections") }}/' + id + '/update';
        document.getElementById('edit_section_name').textContent = name;
        document.getElementById('edit_title').value = title || '';
        document.getElementById('edit_eyebrow').value = eyebrow || '';
        document.getElementById('edit_subtitle').value = subtitle || '';
        document.getElementById('edit_item_limit').value = itemLimit;
        document.getElementById('edit_is_visible').checked = isVisible;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function toggleStatus(id, btn) {
        fetch('{{ url("admin/home-sections") }}/' + id + '/toggle-status', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const dot = btn.querySelector('span');
            const label = btn.querySelector('.toggle-label');
            if (data.is_visible) {
                btn.classList.remove('text-secondary-400'); btn.classList.add('text-success');
                dot.classList.remove('bg-gray-400'); dot.classList.add('bg-success');
                label.textContent = 'Visible';
            } else {
                btn.classList.remove('text-success'); btn.classList.add('text-secondary-400');
                dot.classList.remove('bg-success'); dot.classList.add('bg-gray-400');
                label.textContent = 'Hidden';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const body = document.getElementById('sectionsBody');
        if (body && window.Sortable) {
            Sortable.create(body, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function() {
                    const order = Array.from(body.querySelectorAll('tr[data-id]')).map(r => r.dataset.id);
                    fetch('{{ route("admin.home-sections.reorder") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ order })
                    });
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); }
    });
</script>
@endpush
@endsection
