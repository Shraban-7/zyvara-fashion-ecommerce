@extends('admin.layouts.app')
@section('title', 'Banners')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Banners & Sliders</h1>
        <p class="text-sm text-gray-600">Manage homepage hero sliders and promotional banners</p>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Preview</th>
                        <th class="px-6 py-4">Banner Details</th>
                        <th class="px-6 py-4">Position</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="h-16 w-28 rounded overflow-hidden border border-gray-200 bg-gray-100">
                                <img src="{{ asset('storage/' . $banner->image) }}"
                                    class="h-full w-full object-cover"
                                    alt="{{ $banner->title }}">
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $banner->title }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1">{{ $banner->subtitle ?? 'No subtitle' }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-50 text-blue-700 border border-blue-100">
                                {{ strtoupper($banner->position->value) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @if($banner->is_active)
                            <span class="flex items-center text-green-600 text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600 mr-1.5"></span> Active
                            </span>
                            @else
                            <span class="flex items-center text-gray-400 text-xs font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400 mr-1.5"></span> Inactive
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-600 font-mono text-xs">
                            {{ $banner->sort_order }}
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-1">
                                <a href=""
                                    class="w-8 h-8 flex items-center justify-center text-indigo-600 hover:bg-indigo-50 rounded-md transition-all"
                                    title="Edit Banner">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </a>

                                <form action="" method="POST" onsubmit="return confirm('Delete this banner?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-md transition-all"
                                        title="Delete">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                            No banners found. Click "Add New" to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $banners->links() }}
        </div>
        @endif
    </div>
</div>
@endsection