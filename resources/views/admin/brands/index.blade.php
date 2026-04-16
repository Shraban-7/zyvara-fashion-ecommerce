@extends('admin.layouts.app')
@section('title', 'Brands')

@section('content')

    <div>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Brands</h3>

            <button onclick="openCreateModal()"
                class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg">
                <i class="fas fa-plus mr-2"></i> Add Brand
            </button>
        </div>

        {{-- Brand List --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($brands as $brand)
                <div class="bg-white rounded-xl shadow-sm border p-4 flex justify-between items-center">

                    <div class="flex items-center gap-3">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" class="w-12 h-12 rounded-lg object-cover border">
                        @else
                            <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded-lg">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif

                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $brand->name }}</h4>

                            <div class="flex flex-wrap items-center gap-2 mt-1">

                                @if($brand->own_brand)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-purple-100 text-purple-700">
                                        Own Brand
                                    </span>
                                @endif

                                @if($brand->is_active)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="space-x-3">
                        <button onclick="openEditModal(
                                        {{ $brand->id }},
                                        '{{ addslashes($brand->name) }}',
                                        '{{ $brand->logo }}',
                                        {{ $brand->own_brand ? 'true' : 'false' }},
                                        {{ $brand->is_active ? 'true' : 'false' }}
                                    )" class="text-blue-500 text-sm hover:underline">
                            Edit
                        </button>

                        <form action="{{ route('admin.brands.delete', $brand->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Delete this brand?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 text-sm hover:underline">Delete</button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

    </div>

    {{-- ================= CREATE MODAL ================= --}}
    <div id="createModal" class="fixed inset-0 hidden z-50">
        <div class="flex items-center justify-center min-h-screen bg-black/50">

            <div class="bg-white w-full max-w-md p-6 rounded-2xl">

                <h3 class="text-lg font-bold mb-4">Add Brand</h3>

                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4">

                        <x-input name="name" label="Brand Name" required />

                        <div>
                            <label class="block text-sm mb-1">Logo</label>
                            <input type="file" name="logo" class="w-full border rounded-lg px-3 py-2">
                        </div>

                        {{-- BOOLEAN CHECKBOX --}}
                        <div class="flex gap-5">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="own_brand" class="rounded">
                                Own Brand
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" checked class="rounded">
                                Active
                            </label>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- ================= EDIT MODAL ================= --}}
    <div id="editModal" class="fixed inset-0 hidden z-50">
        <div class="flex items-center justify-center min-h-screen bg-black/50">

            <div class="bg-white w-full max-w-md p-6 rounded-2xl">

                <h3 class="text-lg font-bold mb-4">Edit Brand</h3>

                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">

                        <x-input name="name" id="edit_name" label="Brand Name" required />

                        <div>
                            <label class="block text-sm mb-1">Logo</label>
                            <input type="file" name="logo" class="w-full border rounded-lg px-3 py-2">
                            <img id="edit_logo_preview" class="w-16 h-16 mt-2 rounded-lg hidden">
                        </div>

                        {{-- BOOLEAN CHECKBOX --}}
                        <div class="flex gap-5">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" id="edit_own_brand" name="own_brand">
                                Own Brand
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="checkbox" id="edit_is_active" name="is_active">
                                Active
                            </label>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- ================= SCRIPT ================= --}}
    @push('scripts')
        <script>

            function openCreateModal() {
                document.getElementById('createModal').classList.remove('hidden');
            }
            function closeCreateModal() {
                document.getElementById('createModal').classList.add('hidden');
            }

            function openEditModal(id, name, logo, ownBrand, isActive) {

                let updateUrl = "{{ route('admin.brands.update', ':id') }}";
                updateUrl = updateUrl.replace(':id', id);

                document.getElementById('editForm').action = updateUrl;

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_own_brand').checked = ownBrand;
                document.getElementById('edit_is_active').checked = isActive;

                const preview = document.getElementById('edit_logo_preview');

                if (logo) {
                    preview.src = '/storage/' + logo;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }

                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

        </script>
    @endpush

@endsection