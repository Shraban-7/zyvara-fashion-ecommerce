@extends('layouts.app')
@section('title', 'Update Product Categories')
@section('content')


<div class="max-w-7xl mx-auto p-6">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-bold">
                Products Without Category
            </h2>

            <p class="text-sm text-gray-500">
                Assign Category → Subcategory → Sub-subcategory
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3">Image</th>
                        <th class="text-left px-4 py-3">Product</th>
                        <th class="text-left px-4 py-3">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $product->thumbnail }}" class="w-16 h-16 rounded-lg object-cover border">
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $product->name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <button
                                type="button"
                                class="openModal bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}">
                                Set Category
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Backdrop --}}
<div
    id="categoryModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
        <form method="POST" id="categoryForm">
            @csrf
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">Assign Category</h3>
                        <p id="productName" class="text-sm text-gray-500"></p>
                    </div>
                    <button type="button" id="closeModal" class="text-gray-500 hover:text-red-500 text-xl">✕</button>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label class="block mb-2 text-sm font-medium">Category</label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full border rounded-lg px-3 py-2"
                        required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium">Subcategory</label>
                    <select
                        id="subcategory_id"
                        name="subcategory_id"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select Subcategory</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium">Sub Subcategory</label>
                    <select id="sub_subcategory_id" name="sub_subcategory_id" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select Sub Subcategory</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button" id="cancelModal" class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Update Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    const categories = @json($categories);

    const modal = document.getElementById('categoryModal');

    document.querySelectorAll('.openModal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('productName').innerText = this.dataset.name;
            document.getElementById('categoryForm').action = "{{ url('/products/update-category') }}/" + this.dataset.id;
            document.getElementById('category_id').value = '';
            document.getElementById('subcategory_id').innerHTML = '<option value="">Select Subcategory</option>';
            document.getElementById('sub_subcategory_id').innerHTML = '<option value="">Select Sub Subcategory</option>';
            modal.classList.remove('hidden');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    document.getElementById('closeModal').addEventListener('click', closeModal);
    document.getElementById('cancelModal').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const category = categories.find(item => item.id == categoryId);
        let html = '<option value="">Select Subcategory</option>';
        if (category) {
            category.children.forEach(sub => {
                html += `<option value="${sub.id}">${sub.name}</option>`;
            });
        }

        document.getElementById('subcategory_id').innerHTML = html;
        document.getElementById('sub_subcategory_id').innerHTML = '<option value="">Select Sub Subcategory</option>';
    });

    document.getElementById('subcategory_id').addEventListener('change', function() {
        const categoryId = document.getElementById('category_id').value;
        const subCategoryId = this.value;
        const category = categories.find(item => item.id == categoryId);
        let subCategory = null;
        if (category) {
            subCategory = category.children.find(item => item.id == subCategoryId);
        }

        let html = '<option value="">Select Sub Subcategory</option>';
        if (subCategory) {
            subCategory.children.forEach(subsub => {
                html += `<option value="${subsub.id}">${subsub.name}</option>`;
            });
        }

        document.getElementById('sub_subcategory_id').innerHTML = html;
    });
</script>

@endsection