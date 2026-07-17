@extends('admin.layouts.app')
@section('title', 'Static Pages')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-secondary-800">Static Pages</h3>
            <p class="mt-1 text-sm text-secondary-500">Manage title, slug, SEO and footer order for static pages.</p>
        </div>
        <a href="{{ route('admin.static_pages.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 font-medium text-white transition hover:bg-primary-700">
            <i class="fas fa-plus mr-2"></i>Create Page
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-secondary-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-secondary-600">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-secondary-600">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-secondary-600">Sort</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-secondary-600">Footer Position</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-secondary-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-secondary-600">Updated</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-secondary-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pages as $page)
                    <tr class="hover:bg-secondary-50/80">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-secondary-800">{{ $page->title }}</p>
                            @if($page->meta_title)
                            <p class="mt-1 line-clamp-1 text-xs text-secondary-500">SEO: {{ $page->meta_title }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-secondary-600">/{{ $page->slug }}</td>
                        <td class="px-4 py-3 text-sm text-secondary-600">{{ $page->sort_order }}</td>
                        <td class="px-4 py-3 text-sm text-secondary-600">{{ $page->footer_position }}</td>
                        <td class="px-4 py-3">
                            @if($page->is_active)
                            <span class="inline-flex rounded-full bg-success-100 px-2.5 py-1 text-xs font-semibold text-success">Active</span>
                            @else
                            <span class="inline-flex rounded-full bg-danger-100 px-2.5 py-1 text-xs font-semibold text-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-secondary-600">{{ $page->updated_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('static_page.show', $page->slug) }}" target="_blank"
                                    class="rounded-lg border border-secondary-200 px-3 py-1.5 text-xs font-medium text-secondary-700 transition hover:bg-secondary-100">
                                    View
                                </a>
                                <a href="{{ route('admin.static_pages.edit', $page->id) }}"
                                    class="rounded-lg border border-primary-100 bg-primary-50 px-3 py-1.5 text-xs font-medium text-primary transition hover:bg-primary-100">
                                    Edit
                                </a>
                                <form action="{{ route('admin.static_pages.delete', $page->id) }}" method="POST" onsubmit="return confirm('Delete this page?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-lg border border-danger-100 bg-danger-50 px-3 py-1.5 text-xs font-medium text-danger transition hover:bg-danger-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-14 text-center">
                            <div class="mx-auto max-w-sm">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-secondary-100 text-secondary-400">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <p class="font-semibold text-secondary-700">No static pages found</p>
                                <p class="mt-1 text-sm text-secondary-500">Create your first page to start managing footer links and SEO pages.</p>
                                <a href="{{ route('admin.static_pages.create') }}"
                                    class="mt-4 inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
                                    <i class="fas fa-plus mr-2"></i>Create Page
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection