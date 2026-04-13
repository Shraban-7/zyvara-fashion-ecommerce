@extends('admin.layouts.app')

@section('title', 'Expenses')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Expenses</h1>
            <p class="text-sm text-gray-500 mt-1">Track and manage all business expenses</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAddModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span class="hidden sm:inline">Add Expense</span>
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-receipt text-blue-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Expenses</p>
                <p class="text-xl font-bold text-gray-900">{{ money($totalExpense) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-calendar-alt text-purple-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">This Month</p>
                <p class="text-xl font-bold text-gray-900">{{ money($monthlyExpense) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-tags text-orange-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Records</p>
                <p class="text-xl font-bold text-gray-900">{{ $expenses->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.expenses.index') }}">

            <div class="grid md:grid-cols-4 gap-4">

                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Category
                    </label>
                    <select name="category_id"
                        class="w-full h-11 px-4 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- From Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full h-11 px-4 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- To Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full h-11 px-4 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 h-11 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium text-sm">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>

                    <a href="{{ route('admin.expenses.index') }}"
                        class="h-11 px-4 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expense
                            Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="expenseTableBody">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition" id="expense-row-{{ $expense->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($expense->category)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                        <i class="fas fa-tag text-xs"></i>
                                        {{ $expense->category->name }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                        Uncategorized
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 max-w-xs truncate">{{ $expense->description ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-base font-bold text-gray-900">{{ money($expense->amount) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $expense->expense_date->format('M d, Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        onclick="openEditModal({{ $expense->id }}, {{ $expense->category_id ?? 'null' }}, '{{ addslashes($expense->category->name ?? '') }}', '{{ $expense->amount }}', '{{ $expense->expense_date }}', '{{ addslashes($expense->description ?? '') }}')"
                                        class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $expense->id }})"
                                        class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg transition"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">No expenses found</p>
                                    <p class="text-sm text-gray-500">Try adjusting your filters or add a new expense</p>
                                    <button onclick="openAddModal()"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm font-medium">
                                        <i class="fas fa-plus mr-2"></i>Add Expense
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>


    {{-- ======== ADD MODAL ======== --}}
    <div id="addModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Add Expense</h2>
                            <p class="text-xs text-gray-500">Fill in the details below</p>
                        </div>
                    </div>
                    <button onclick="closeAddModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-5">

                    {{-- Category: select2 style --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-2">Select an existing category or type a new name to create one.
                        </p>
                        <select id="addCategoryId" name="category_id"
                            class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm select2-category">
                            <option value="">— Select or type to create —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        {{-- Hidden input sent to server: numeric id OR typed name --}}
                        <input type="hidden" id="addCategoryValue" name="category_id_value">
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-sm">৳</span>
                            <input type="number" id="addAmount" step="0.01" min="0" placeholder="0.00"
                                class="w-full h-11 pl-8 pr-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <p class="text-xs text-red-500 mt-1 hidden" id="addAmountErr">Amount is required.</p>
                    </div>

                    {{-- Expense Date --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Expense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="addExpenseDate" value="{{ date('Y-m-d') }}"
                            class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-red-500 mt-1 hidden" id="addDateErr">Date is required.</p>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea id="addDescription" rows="3" placeholder="Optional notes about this expense..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                    </div>

                    {{-- Global error --}}
                    <div id="addErrorBox"
                        class="hidden bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3"></div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button onclick="closeAddModal()"
                        class="h-11 px-6 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium text-sm">
                        Cancel
                    </button>
                    <button onclick="submitAdd()" id="addSubmitBtn"
                        class="h-11 px-6 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Save Expense</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ======== EDIT MODAL ======== --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-orange-600 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Edit Expense</h2>
                            <p class="text-xs text-gray-500">Update the expense details</p>
                        </div>
                    </div>
                    <button onclick="closeEditModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-5">
                    <input type="hidden" id="editExpenseId">

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-2">Select an existing category or type a new name to create one.
                        </p>
                        <select id="editCategoryId"
                            class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm select2-category-edit">
                            <option value="">— Select or type to create —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-sm">৳</span>
                            <input type="number" id="editAmount" step="0.01" min="0" placeholder="0.00"
                                class="w-full h-11 pl-8 pr-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <p class="text-xs text-red-500 mt-1 hidden" id="editAmountErr">Amount is required.</p>
                    </div>

                    {{-- Expense Date --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Expense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="editExpenseDate"
                            class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-red-500 mt-1 hidden" id="editDateErr">Date is required.</p>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea id="editDescription" rows="3" placeholder="Optional notes..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                    </div>

                    <div id="editErrorBox"
                        class="hidden bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3"></div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button onclick="closeEditModal()"
                        class="h-11 px-6 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium text-sm">
                        Cancel
                    </button>
                    <button onclick="submitEdit()" id="editSubmitBtn"
                        class="h-11 px-6 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition font-medium text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update Expense</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ======== DELETE MODAL ======== --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash text-red-500 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Expense?</h3>
                <p class="text-sm text-gray-500 mb-6">This action cannot be undone. The record will be permanently removed.
                </p>
                <input type="hidden" id="deleteExpenseId">
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()"
                        class="flex-1 h-11 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium text-sm">
                        Cancel
                    </button>
                    <button onclick="submitDelete()" id="deleteSubmitBtn"
                        class="flex-1 h-11 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Yes, Delete</span>
                    </button>
                </div>
                <div id="deleteErrorBox"
                    class="hidden mt-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3"></div>
            </div>
        </div>
    </div>


    {{-- Toast --}}
    <div id="toast"
        class="fixed bottom-6 right-6 z-60 hidden items-center gap-3 px-5 py-3.5 rounded-2xl shadow-lg text-sm font-medium text-white min-w-55">
        <i id="toastIcon" class="fas"></i>
        <span id="toastMsg"></span>
    </div>


    @push('scripts')
        {{-- jQuery (if not already in layout) --}}
        {{--
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script> --}}

        {{-- Select2 --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <style>
            .select2-container {
                width: 100% !important;
            }

            /* Make Select2 match Tailwind inputs */
            .select2-container--default .select2-selection--single {
                height: 44px !important;
                border: 1px solid #d1d5db !important;
                border-radius: 0.75rem !important;
                padding: 0 12px !important;
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 44px !important;
                color: #111827;
                padding: 0 !important;
                font-size: 0.875rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 44px !important;
                right: 10px;
            }

            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3) !important;
                outline: none !important;
            }

            .select2-dropdown {
                border: 1px solid #d1d5db !important;
                border-radius: 0.75rem !important;
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #3b82f6 !important;
            }

            .select2-search--dropdown .select2-search__field {
                border-radius: 0.5rem !important;
                border: 1px solid #d1d5db !important;
                padding: 6px 10px;
                font-size: 0.875rem;
                outline: none;
            }
        </style>

        <script>
            const CSRF = '{{ csrf_token() }}';
            const storeUrl = '{{ route("admin.expenses.store") }}';
            const baseUrl = '{{ url("admin/expenses") }}';

            // -------- Select2 init --------
            function initSelect2(selector, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    tags: true,
                    allowClear: true,
                    width: '100%', // 🔥 CRITICAL FIX
                    dropdownParent: $('#addModal'), // 🔥 FIX MODAL CONTEXT
                    createTag: function (params) {
                        const term = $.trim(params.term);
                        if (!term) return null;
                        return {
                            id: term,
                            text: term + ' (new)',
                            newTag: true
                        };
                    }
                });
            }

            $(document).ready(function () {
                initSelect2('#addCategoryId', '— Select or type to create —');
                initSelect2('#editCategoryId', '— Select or type to create —');
            });

            // -------- Toast --------


            // -------- Helpers --------
            function setLoading(btnId, loading) {
                const btn = $(`#${btnId}`);
                if (loading) {
                    btn.prop('disabled', true).find('span').text('Please wait...');
                    btn.find('i').attr('class', 'fas fa-spinner fa-spin');
                } else {
                    btn.prop('disabled', false);
                }
            }

            function getCategoryValue(selectId) {
                const val = $(`#${selectId}`).val();
                return val ?? '';
            }

            // -------- ADD MODAL --------
            function openAddModal() {
                $('#addModal').removeClass('hidden');
                $('body').css('overflow', 'hidden');
            }
            function closeAddModal() {
                $('#addModal').addClass('hidden');
                $('body').css('overflow', '');
                // Reset
                $('#addCategoryId').val('').trigger('change');
                $('#addAmount').val('');
                $('#addExpenseDate').val('{{ date("Y-m-d") }}');
                $('#addDescription').val('');
                $('#addAmountErr, #addDateErr, #addErrorBox').addClass('hidden');
            }

            function submitAdd() {
                // Validate
                let valid = true;
                if (!$('#addAmount').val()) { $('#addAmountErr').removeClass('hidden'); valid = false; } else { $('#addAmountErr').addClass('hidden'); }
                if (!$('#addExpenseDate').val()) { $('#addDateErr').removeClass('hidden'); valid = false; } else { $('#addDateErr').addClass('hidden'); }
                if (!valid) return;

                const categoryVal = getCategoryValue('addCategoryId');

                setLoading('addSubmitBtn', true);
                $('#addErrorBox').addClass('hidden');

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: {
                        _token: CSRF,
                        category_id: categoryVal,
                        amount: $('#addAmount').val(),
                        expense_date: $('#addExpenseDate').val(),
                        description: $('#addDescription').val(),
                    },
                    success: function (res) {
                        setLoading('addSubmitBtn', false);
                        closeAddModal();
                        window.showSuccess('Expense added successfully!');
                        setTimeout(() => location.reload(), 800);
                    },
                    error: function (xhr) {
                        setLoading('addSubmitBtn', false);
                        const errors = xhr.responseJSON?.errors;
                        const msg = errors
                            ? Object.values(errors).flat().join(' ')
                            : (xhr.responseJSON?.message ?? 'Something went wrong.');

                        window.showError(msg);
                    }
                });
            }

            // -------- EDIT MODAL --------
            function openEditModal(id, categoryId, categoryName, amount, expenseDate, description) {
                $('#editExpenseId').val(id);
                $('#editAmount').val(amount);
                $('#editExpenseDate').val(expenseDate);
                $('#editDescription').val(description);

                // Set Select2 value
                if (categoryId) {
                    $('#editCategoryId').val(categoryId).trigger('change');
                } else {
                    $('#editCategoryId').val('').trigger('change');
                }

                $('#editModal').removeClass('hidden');
                $('body').css('overflow', 'hidden');
            }
            function closeEditModal() {
                $('#editModal').addClass('hidden');
                $('body').css('overflow', '');
                $('#editAmountErr, #editDateErr, #editErrorBox').addClass('hidden');
            }

            function submitEdit() {
                let valid = true;
                if (!$('#editAmount').val()) { $('#editAmountErr').removeClass('hidden'); valid = false; } else { $('#editAmountErr').addClass('hidden'); }
                if (!$('#editExpenseDate').val()) { $('#editDateErr').removeClass('hidden'); valid = false; } else { $('#editDateErr').addClass('hidden'); }
                if (!valid) return;

                const id = $('#editExpenseId').val();
                const categoryVal = getCategoryValue('editCategoryId');

                setLoading('editSubmitBtn', true);
                $('#editErrorBox').addClass('hidden');

                $.ajax({
                    url: `${baseUrl}/${id}`,
                    method: 'POST',
                    data: {
                        _token: CSRF,
                        _method: 'PUT',
                        category_id: categoryVal,
                        amount: $('#editAmount').val(),
                        expense_date: $('#editExpenseDate').val(),
                        description: $('#editDescription').val(),
                    },
                    success: function (res) {
                        setLoading('editSubmitBtn', false);
                        closeEditModal();
                        window.showSuccess('Expense updated successfully!');
                        setTimeout(() => location.reload(), 800);
                    },
                    error: function (xhr) {
                        setLoading('editSubmitBtn', false);
                        const errors = xhr.responseJSON?.errors;
                        const msg = errors
                            ? Object.values(errors).flat().join(' ')
                            : (xhr.responseJSON?.message ?? 'Something went wrong.');
                        window.showError(msg);
                    }
                });
            }

            // -------- DELETE MODAL --------
            function openDeleteModal(id) {
                $('#deleteExpenseId').val(id);
                $('#deleteModal').removeClass('hidden');
                $('body').css('overflow', 'hidden');
            }
            function closeDeleteModal() {
                $('#deleteModal').addClass('hidden');
                $('body').css('overflow', '');
                $('#deleteErrorBox').addClass('hidden');
            }

            function submitDelete() {
                const id = $('#deleteExpenseId').val();

                setLoading('deleteSubmitBtn', true);
                $('#deleteErrorBox').addClass('hidden');

                $.ajax({
                    url: `${baseUrl}/${id}`,
                    method: 'POST',
                    data: {
                        _token: CSRF,
                        _method: 'DELETE',
                    },
                    success: function (res) {
                        setLoading('deleteSubmitBtn', false);
                        closeDeleteModal();
                        window.location.reload();
                    },
                    error: function (xhr) {
                        setLoading('deleteSubmitBtn', false);
                        const msg = xhr.responseJSON?.message ?? 'Something went wrong.';
                        window.showError(msg);
                    }
                });
            }

            // -------- Escape key --------
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                    closeDeleteModal();
                }
            });
        </script>
    @endpush

@endsection