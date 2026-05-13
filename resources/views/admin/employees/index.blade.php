@extends('admin.layouts.app')

@section('title', 'Employees')

@section('content')

    <div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Employees</h3>

            <a href="{{ route('admin.employees.create') }}"
                class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add Employee
            </a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="bg-white border rounded-xl p-4 mb-4">

            <input type="hidden" name="tab" value="pos">

            <div class="flex flex-col md:flex-row gap-3">

                {{-- SEARCH --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Employee by name or phone..."
                        class="w-full h-11 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- BUTTON GROUP --}}
                <div class="flex gap-2 md:items-end">

                    <button type="submit"
                        class="h-11 px-5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>

                    <a href="{{ route('admin.employees.index') }}"
                        class="h-11 px-4 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>

                </div>

            </div>

        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Employee
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Phone
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Email
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">

                        @forelse($employees as $employee)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-gray-800">
                                                {{ $employee->name }}
                                            </h4>

                                            <p class="text-sm text-gray-500">
                                                STAFF
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $employee->phone }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $employee->email ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">

                                        <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                            class="text-blue-600 hover:text-blue-800 transition">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('admin.employees.destroy', $employee->id) }}', '{{ addslashes($employee->name) }}')"
                                            class="text-red-500 hover:text-red-700 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                    No employees found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $employees->links() }}
                </div>
            @endif

        </div>

    </div>

    {{-- Delete Confirmation Modal --}}
    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">

        <div class="bg-white rounded-2xl max-w-md w-full p-6">

            <div class="text-center mb-6">

                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    Delete Employee?
                </h3>

                <p class="text-gray-600">
                    Are you sure you want to delete
                    <span id="deleteEmployeeName" class="font-semibold text-gray-800"></span>?
                    This action cannot be undone.
                </p>

            </div>

            <div class="flex gap-3">

                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                    Cancel
                </button>

                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium">
                        Delete
                    </button>
                </form>

            </div>

        </div>

    </div>


    @push('scripts')
        <script>
            function openDeleteModal(action, employeeName) {
                document.getElementById('deleteForm').action = action;
                document.getElementById('deleteEmployeeName').innerText = employeeName;

                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        </script>
    @endpush
@endsection