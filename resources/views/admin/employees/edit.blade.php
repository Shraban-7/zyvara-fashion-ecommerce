@extends('admin.layouts.app')

@section('title', 'Edit Employee')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold text-secondary-800">
                Edit Employee
            </h3>

            <p class="text-sm text-secondary-500 mt-1">
                Update employee information
            </p>
        </div>

        <a href="{{ route('admin.employees.index') }}"
            class="px-4 py-2 bg-secondary-100 hover:bg-gray-200 text-secondary-700 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="bg-white border border-secondary-200 rounded-2xl shadow-sm p-6">
    
            <form action="{{ route('admin.employees.update', $employee->id) }}"
                method="POST">
    
                @csrf
                @method('PUT')
    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    
                    <div class="md:col-span-2">
                        <x-input
                            name="name"
                            type="text"
                            label="Employee Name *"
                            value="{{ old('name', $employee->name) }}"
                            placeholder="Enter employee name"
                            required />
                    </div>
    
                    <div>
                        <x-input
                            name="phone"
                            type="text"
                            label="Phone Number *"
                            value="{{ old('phone', $employee->phone) }}"
                            placeholder="01XXXXXXXXX"
                            required />
                    </div>
    
                    <div>
                        <x-input
                            name="email"
                            type="email"
                            label="Email Address"
                            value="{{ old('email', $employee->email) }}"
                            placeholder="employee@example.com" />
                    </div>
    
                    <div class="md:col-span-2">
                        <x-input
                            name="password"
                            type="password"
                            label="New Password"
                            placeholder="Leave empty to keep current password" />
    
                        <p class="mt-1 text-xs text-secondary-500">
                            Leave blank if you do not want to change password
                        </p>
                    </div>
    
                </div>
    
                <div class="mt-8 flex justify-end gap-3">
    
                    <a href="{{ route('admin.employees.index') }}"
                        class="px-5 py-2.5 bg-secondary-100 hover:bg-gray-200 text-secondary-700 rounded-lg transition">
                        Cancel
                    </a>
    
                    <button type="submit"
                        class="px-5 py-2.5 bg-primary hover:bg-primary-700 text-white rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-2"></i>Update Employee
                    </button>
    
                </div>
    
            </form>
    
        </div>
    </div>
@endsection