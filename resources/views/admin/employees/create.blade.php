@extends('admin.layouts.app')

@section('title', 'Create Employee')

@section('content')


    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">
                Add Employee
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Create a new staff account
            </p>
        </div>

        <a href="{{ route('admin.employees.index') }}"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <form action="{{ route('admin.employees.store') }}" method="POST">
    
                @csrf
    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    
                    <div class="md:col-span-2">
                        <x-input
                            name="name"
                            type="text"
                            label="Employee Name *"
                            placeholder="Enter employee name"
                            required />
                    </div>
    
                    <div>
                        <x-input
                            name="phone"
                            type="text"
                            label="Phone Number *"
                            placeholder="01XXXXXXXXX"
                            required />
                    </div>
    
                    <div>
                        <x-input
                            name="email"
                            type="email"
                            label="Email Address"
                            placeholder="employee@example.com" />
                    </div>
    
                    <div class="md:col-span-2">
                        <x-input
                            name="password"
                            type="password"
                            label="Password *"
                            placeholder="Enter password"
                            required />
                    </div>
    
                </div>
    
                <div class="mt-8 flex justify-end gap-3">
    
                    <a href="{{ route('admin.employees.index') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        Cancel
                    </a>
    
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-2"></i>Create Employee
                    </button>
    
                </div>
    
            </form>
        </div>
    </div>
@endsection