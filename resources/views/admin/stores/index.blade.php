@extends('admin.layouts.app')
@section('title', 'Stores')

@section('content')

<div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary-800">Stores & Showrooms</h1>
            <p class="text-sm text-secondary-600">Manage physical locations shown on the storefront</p>
        </div>

        <button onclick="openCreateModal()"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Store
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
                        <th class="px-6 py-4">Store</th>
                        <th class="px-6 py-4">City</th>
                        <th class="px-6 py-4">Flagship</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($stores as $store)
                        <tr class="hover:bg-secondary-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg overflow-hidden border border-secondary-200 bg-secondary-100 flex-shrink-0">
                                        @if($store->image)
                                            <img src="{{ asset('storage/' . $store->image) }}" class="h-full w-full object-cover" alt="{{ $store->name }}">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-secondary-400"><i class="fas fa-store"></i></div>
                                        @endif
                                    </div>
                                    <div class="font-bold text-primary">{{ $store->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-secondary-600">{{ $store->city }}</td>
                            <td class="px-6 py-4">
                                @if($store->is_flagship)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded bg-accent-50 text-accent-700 border border-accent-100">
                                        <i class="fas fa-star text-[10px]"></i> Flagship
                                    </span>
                                @else
                                    <span class="text-secondary-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($store->is_active)
                                    <span class="flex items-center text-success text-xs font-medium">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success mr-1.5"></span> Active
                                    </span>
                                @else
                                    <span class="flex items-center text-secondary-400 text-xs font-medium">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400 mr-1.5"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-secondary-600">{{ $store->display_order }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button type="button" onclick="openEditModal(
                                        {{ $store->id }},
                                        '{{ addslashes($store->name) }}',
                                        '{{ addslashes($store->address_line1) }}',
                                        '{{ addslashes($store->address_line2 ?? '') }}',
                                        '{{ addslashes($store->city) }}',
                                        '{{ addslashes($store->state ?? '') }}',
                                        '{{ addslashes($store->postal_code ?? '') }}',
                                        '{{ addslashes($store->country ?? '') }}',
                                        '{{ $store->latitude ?? '' }}',
                                        '{{ $store->longitude ?? '' }}',
                                        '{{ addslashes($store->phone ?? '') }}',
                                        '{{ addslashes($store->email ?? '') }}',
                                        '{{ addslashes(json_encode($store->opening_hours ?? [])) }}',
                                        '{{ $store->google_maps_url ?? '' }}',
                                        {{ $store->is_flagship ? 'true' : 'false' }},
                                        {{ $store->is_active ? 'true' : 'false' }},
                                        {{ $store->display_order }},
                                        '{{ $store->image }}'
                                    )"
                                    class="text-primary text-sm hover:underline mr-3">Edit</button>
                                <form action="{{ route('admin.stores.delete', $store->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this store?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger text-sm hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-secondary-400">No stores yet. Click "Add Store" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-secondary-100">
            {{ $stores->links() }}
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="closeCreateModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
        <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-primary">Add Store</h3>
                <button type="button" onclick="closeCreateModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
            </div>

            <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2"><x-input name="name" type="text" label="Store Name *" placeholder="e.g. Downtown Flagship" required /></div>
                    <div><x-input name="address_line1" type="text" label="Address Line 1 *" required /></div>
                    <div><x-input name="address_line2" type="text" label="Address Line 2" /></div>
                    <div><x-input name="city" type="text" label="City *" required /></div>
                    <div><x-input name="state" type="text" label="State / Province" /></div>
                    <div><x-input name="postal_code" type="text" label="Postal Code" /></div>
                    <div><x-input name="country" type="text" label="Country" /></div>
                    <div><x-input name="phone" type="text" label="Phone" /></div>
                    <div><x-input name="email" type="email" label="Email" /></div>
                    <div><x-input name="latitude" type="text" label="Latitude (optional)" placeholder="23.7808" /></div>
                    <div><x-input name="longitude" type="text" label="Longitude (optional)" placeholder="90.4124" /></div>
                    <div class="md:col-span-2"><x-input name="google_maps_url" type="url" label="Google Maps URL (optional)" placeholder="https://maps.google.com/?q=..." /></div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Opening Hours (format HH:MM-HH:MM, or leave blank for Closed)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'] as $k => $label)
                                <div>
                                    <label class="text-xs text-secondary-500">{{ $label }}</label>
                                    <input type="text" name="opening_hours[{{ $k }}]" id="create_oh_{{ $k }}"
                                        class="mt-1 block w-full px-3 py-2 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                                        placeholder="10:00-20:00">
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-1 text-xs text-secondary-500">Leave a day blank to mark it Closed.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Store Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            onchange="previewCreateImage(event)">
                        <div id="createImagePreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="max-h-32 rounded-lg border border-secondary-300">
                        </div>
                    </div>

                    <div><x-input name="display_order" type="number" value="0" label="Display Order" required /></div>
                    <div class="flex items-end gap-6 pb-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_flagship" class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Flagship</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" checked class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Save Store</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="closeEditModal()" class="fixed inset-0 bg-secondary-500/75 transition-opacity"></div>
        <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle bg-white shadow-xl rounded-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-primary">Edit Store</h3>
                <button type="button" onclick="closeEditModal()" class="text-secondary-400 hover:text-secondary-500 transition"><i class="fas fa-times text-xl"></i></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2"><x-input name="name" type="text" id="edit_name" label="Store Name *" required /></div>
                    <div><x-input name="address_line1" type="text" id="edit_address_line1" label="Address Line 1 *" required /></div>
                    <div><x-input name="address_line2" type="text" id="edit_address_line2" label="Address Line 2" /></div>
                    <div><x-input name="city" type="text" id="edit_city" label="City *" required /></div>
                    <div><x-input name="state" type="text" id="edit_state" label="State / Province" /></div>
                    <div><x-input name="postal_code" type="text" id="edit_postal_code" label="Postal Code" /></div>
                    <div><x-input name="country" type="text" id="edit_country" label="Country" /></div>
                    <div><x-input name="phone" type="text" id="edit_phone" label="Phone" /></div>
                    <div><x-input name="email" type="email" id="edit_email" label="Email" /></div>
                    <div><x-input name="latitude" type="text" id="edit_latitude" label="Latitude (optional)" /></div>
                    <div><x-input name="longitude" type="text" id="edit_longitude" label="Longitude (optional)" /></div>
                    <div class="md:col-span-2"><x-input name="google_maps_url" type="url" id="edit_google_maps_url" label="Google Maps URL (optional)" /></div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Opening Hours (format HH:MM-HH:MM, or leave blank for Closed)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="edit_hours_grid">
                            @foreach(['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'] as $k => $label)
                                <div>
                                    <label class="text-xs text-secondary-500">{{ $label }}</label>
                                    <input type="text" name="opening_hours[{{ $k }}]" id="edit_oh_{{ $k }}"
                                        class="mt-1 block w-full px-3 py-2 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                                        placeholder="10:00-20:00">
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-1 text-xs text-secondary-500">Leave a day blank to mark it Closed.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Store Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full px-4 py-2.5 rounded-lg border border-secondary-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary transition"
                            onchange="previewEditImage(event)">
                        <div id="editImagePreview" class="mt-3">
                            <img id="edit_image_preview" src="" alt="Preview" class="max-h-32 rounded-lg border border-secondary-300">
                        </div>
                        <p class="mt-1 text-xs text-secondary-500">Leave empty to keep current image</p>
                    </div>

                    <div><x-input name="display_order" type="number" id="edit_display_order" label="Display Order" required /></div>
                    <div class="flex items-end gap-6 pb-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit_is_flagship" name="is_flagship" class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Flagship</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit_is_active" name="is_active" class="rounded border-secondary-300 text-primary shadow-sm focus:ring-primary focus:ring-2">
                            <span class="ml-2 text-sm text-secondary-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 text-sm font-medium text-secondary-700 bg-secondary-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-700 shadow-sm transition"><i class="fas fa-save mr-2"></i>Update Store</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const DAYS = ['mon','tue','wed','thu','fri','sat','sun'];

    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createModal').querySelector('form').reset();
    }

    function openEditModal(id, name, address1, address2, city, state, postal, country, lat, lng, phone, email, hoursJson, mapsUrl, isFlagship, isActive, order, image) {
        const form = document.getElementById('editForm');
        form.action = '{{ route("admin.stores.index") }}/' + id + '/update';

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_address_line1').value = address1;
        document.getElementById('edit_address_line2').value = address2;
        document.getElementById('edit_city').value = city;
        document.getElementById('edit_state').value = state;
        document.getElementById('edit_postal_code').value = postal;
        document.getElementById('edit_country').value = country;
        document.getElementById('edit_latitude').value = lat;
        document.getElementById('edit_longitude').value = lng;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_google_maps_url').value = mapsUrl;
        document.getElementById('edit_display_order').value = order;
        document.getElementById('edit_is_flagship').checked = isFlagship;
        document.getElementById('edit_is_active').checked = isActive;

        let hours = {};
        try { hours = JSON.parse(hoursJson || '{}'); } catch (e) { hours = {}; }
        DAYS.forEach(d => { document.getElementById('edit_oh_' + d).value = hours[d] && hours[d] !== 'closed' ? hours[d] : ''; });

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

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); }
    });

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
