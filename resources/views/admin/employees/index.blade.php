@extends('admin.layouts.app')

@section('title', 'Employees')

@section('content')

    <div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-2xl font-bold text-secondary-800">Employees</h3>

            <a href="{{ route('admin.employees.create') }}"
                class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add Employee
            </a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="bg-white border rounded-xl p-4 mb-4">

            <div class="flex flex-col md:flex-row gap-3">

                {{-- SEARCH --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Employee by name or phone..."
                        class="w-full h-11 px-4 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                {{-- BUTTON GROUP --}}
                <div class="flex gap-2 md:items-end">

                    <button type="submit"
                        class="h-11 px-5 bg-primary text-white rounded-xl hover:bg-primary-700 transition font-medium flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>

                    <a href="{{ route('admin.employees.index') }}"
                        class="h-11 px-4 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>

                </div>

            </div>

        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-secondary-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-500 uppercase">
                                Employee
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-500 uppercase">
                                Phone
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-500 uppercase">
                                Email
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">

                        @forelse($employees as $employee)
                            <tr class="hover:bg-secondary-50 transition">

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-full bg-primary-100 flex items-center justify-center">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-secondary-800">
                                                {{ $employee->name }}
                                            </h4>

                                            <p class="text-sm text-secondary-500">
                                                STAFF
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-secondary-700">
                                    {{ $employee->phone }}
                                </td>

                                <td class="px-6 py-4 text-secondary-700">
                                    {{ $employee->email ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">

                                        <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                            class="text-primary hover:text-primary transition">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('admin.employees.destroy', $employee->id) }}', '{{ addslashes($employee->name) }}')"
                                            class="text-danger hover:text-danger transition">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-secondary-400">
                                    No employees found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-6 py-4 border-t bg-secondary-50">
                    {{ $employees->links() }}
                </div>
            @endif

        </div>

    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

        <div class="bg-white rounded-2xl max-w-md w-full p-6">

            <div class="text-center mb-6">

                <div class="w-16 h-16 bg-danger-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-danger text-2xl"></i>
                </div>

                <h3 class="text-xl font-bold text-primary mb-2">
                    Delete Employee?
                </h3>

                <p class="text-secondary-600">
                    Are you sure you want to delete
                    <span id="deleteEmployeeName" class="font-semibold text-secondary-800"></span>?
                    This action cannot be undone.
                </p>

            </div>

            <div class="flex gap-3">

                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition font-medium">
                    Cancel
                </button>

                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-danger text-white rounded-xl hover:bg-danger-700 transition font-medium">
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