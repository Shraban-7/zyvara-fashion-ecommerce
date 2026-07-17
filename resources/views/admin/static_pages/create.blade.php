@extends('admin.layouts.app')
@section('title', 'Static Pages')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-secondary-800">Create Static Page</h3>
            <p class="mt-1 text-sm text-secondary-500">Create content pages with SEO metadata and footer position.</p>
        </div>
        <a href="{{ route('admin.static_pages.index') }}"
            class="inline-flex items-center justify-center rounded-lg bg-secondary-100 px-4 py-2.5 font-medium text-secondary-700 transition hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Pages
        </a>
    </div>

    <form id="staticPageForm" action="{{ route('admin.static_pages.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-secondary-200 bg-white p-6 shadow-sm">
            <h4 class="text-lg font-semibold text-secondary-800">Basic Information</h4>
            <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-secondary-700">Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200"
                        placeholder="Example: About Us">
                </div>

                <div class="md:col-span-2">
                    <label for="slug" class="mb-1.5 block text-sm font-semibold text-secondary-700">Slug <span class="text-danger">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200"
                        placeholder="about-us">
                    <p class="mt-1 text-xs text-secondary-500">This will be used in page URL: /pages/your-slug</p>
                </div>

                <div>
                    <label for="sort_order" class="mb-1.5 block text-sm font-semibold text-secondary-700">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200">
                </div>

                <div>
                    <label for="footer_position" class="mb-1.5 block text-sm font-semibold text-secondary-700">Footer Position</label>
                    <input type="number" id="footer_position" name="footer_position" value="{{ old('footer_position', 1) }}" min="1"
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200">
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-secondary-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                            class="rounded border-secondary-300 text-primary focus:ring-primary">
                        Active Page
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-secondary-200 bg-white p-6 shadow-sm">
            <h4 class="text-lg font-semibold text-secondary-800">Page Content</h4>
            <div class="mt-5">
                <label for="content_editor" class="mb-1.5 block text-sm font-semibold text-secondary-700">Content</label>
                <textarea id="content" name="content" class="hidden">{{ old('content') }}</textarea>
                <div id="content_editor" class="quill-editor bg-white"></div>
            </div>
        </div>

        <div class="rounded-2xl border border-secondary-200 bg-white p-6 shadow-sm">
            <h4 class="text-lg font-semibold text-secondary-800">SEO Metadata</h4>
            <div class="mt-5 grid grid-cols-1 gap-5">
                <div>
                    <label for="meta_title" class="mb-1.5 block text-sm font-semibold text-secondary-700">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200"
                        placeholder="SEO title for search engines">
                </div>

                <div>
                    <label for="meta_description" class="mb-1.5 block text-sm font-semibold text-secondary-700">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="4"
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200"
                        placeholder="Short description for search results">{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label for="meta_keywords" class="mb-1.5 block text-sm font-semibold text-secondary-700">Meta Keywords</label>
                    <textarea id="meta_keywords" name="meta_keywords" rows="3"
                        class="w-full rounded-lg border border-secondary-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-200"
                        placeholder="keyword1, keyword2, keyword3">{{ old('meta_keywords') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
            <a href="{{ route('admin.static_pages.index') }}"
                class="rounded-lg bg-secondary-100 px-5 py-2.5 font-medium text-secondary-700 transition hover:bg-gray-200">Cancel</a>
            <button type="submit"
                class="rounded-lg bg-primary px-5 py-2.5 font-medium text-white transition hover:bg-primary-700">
                <i class="fas fa-save mr-2"></i>Save Page
            </button>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    (function() {
        const form = document.getElementById('staticPageForm');
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const contentInput = document.getElementById('content');
        let slugManuallyEdited = !!slugInput.value;

        function slugify(value) {
            return value
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        slugInput.addEventListener('input', function() {
            slugManuallyEdited = true;
        });

        titleInput.addEventListener('input', function() {
            if (!slugManuallyEdited) {
                slugInput.value = slugify(titleInput.value);
            }
        });

        const quill = new Quill('#content_editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    [{
                        align: []
                    }],
                    ['link', 'image', 'blockquote', 'code-block'],
                    ['clean']
                ]
            },
            placeholder: 'Write your page content here...'
        });

        quill.root.style.minHeight = '320px';

        quill.root.innerHTML = contentInput.value || '';

        form.addEventListener('submit', function() {
            contentInput.value = quill.root.innerHTML;
        });
    })();
</script>
@endpush

@endsection