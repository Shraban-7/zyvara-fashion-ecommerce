@extends('customer.layout')
@section('title', 'My Reviews')

@section('dashboard-content')
    <div class="space-y-6">
        <div>
            <h2 class="font-heading text-2xl font-semibold text-primary">My Reviews</h2>
            <p class="text-sm text-secondary-500 mt-1">Reviews you've written and products waiting for your feedback.</p>
        </div>

        {{-- Pending reviews (delivered, not yet reviewed) --}}
        @php $pending = $pending_reviews ?? collect(); @endphp
        @if($pending->count() > 0)
            <section class="bg-accent-50/60 border border-accent-100 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-8 h-8 rounded-full bg-accent text-primary flex items-center justify-center">
                        <i class="fas fa-pen-to-square text-sm"></i>
                    </span>
                    <h3 class="font-heading text-lg font-semibold text-primary">Pending Reviews</h3>
                    <span class="ml-auto text-xs font-medium text-accent-700 bg-accent-100 px-2.5 py-1 rounded-full">{{ $pending->count() }}</span>
                </div>

                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach($pending as $item)
                        <div class="flex items-center gap-3 bg-surface-elevated rounded-xl border border-secondary-100 p-3">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-light border border-secondary-100 shrink-0">
                                @if($item->product_image)
                                    <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-secondary-400"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-primary truncate">{{ $item->product_name }}</p>
                                <p class="text-xs text-secondary-400">Delivered {{ $item->delivered_at ?? 'recently' }}</p>
                            </div>
                            <button type="button" onclick="openReviewModal({{ $item->product_id }}, '{{ addslashes($item->product_name) }}', {{ $item->order_id ?? 'null' }})"
                                class="shrink-0 inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-2 rounded-lg text-xs font-semibold hover:bg-primary-700 transition">
                                <i class="fas fa-star"></i> Write
                            </button>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Written reviews --}}
        @php $reviews = $reviews ?? collect(); @endphp
        @if($reviews->count() > 0)
            <section class="space-y-4">
                @foreach($reviews as $review)
                    <article class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-24 h-24 rounded-xl overflow-hidden bg-light border border-secondary-100 shrink-0">
                                @if($review->product && $review->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}" alt="{{ $review->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-secondary-400"><i class="fas fa-image text-2xl"></i></div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $review->product->slug ?? '#') }}" class="font-medium text-primary hover:text-accent-600 transition truncate block">{{ $review->product->name ?? 'Product' }}</a>
                                        <p class="text-xs text-secondary-400 mt-0.5">Reviewed on {{ $review->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-0.5 shrink-0" aria-label="Rated {{ $review->rating }} out of 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-accent' : 'text-secondary-200' }} text-sm"></i>
                                        @endfor
                                    </div>
                                </div>

                                @if($review->comment)
                                    <p class="text-sm text-secondary-600 mt-2">{{ $review->comment }}</p>
                                @endif

                                @if($review->images->count() > 0)
                                    <div class="flex gap-2 mt-3">
                                        @foreach($review->images as $image)
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Review photo"
                                                class="w-14 h-14 rounded-lg object-cover cursor-pointer hover:opacity-75 transition border border-secondary-100"
                                                onclick="viewImage('{{ asset('storage/' . $image->image_path) }}')">
                                        @endforeach
                                    </div>
                                @endif

                                @if($review->admin_response)
                                    <div class="bg-primary-50 border-l-2 border-primary rounded-lg px-3 py-2 mt-3">
                                        <p class="text-xs font-semibold text-primary mb-0.5"><i class="fas fa-reply mr-1"></i> Store Response</p>
                                        <p class="text-sm text-secondary-600">{{ $review->admin_response }}</p>
                                    </div>
                                @endif

                                <div class="flex items-center gap-3 mt-3">
                                    <button type="button" onclick="openReviewModal({{ $review->product_id }}, '{{ addslashes($review->product->name ?? '') }}', {{ $review->order_id ?? 'null' }}, {{ $review->id }})"
                                        class="text-xs font-medium text-primary-500 hover:text-primary-700 transition inline-flex items-center gap-1">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('customer.reviews.destroy', $review->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Delete this review?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-danger-600 hover:text-danger-700 transition inline-flex items-center gap-1">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach

                <div class="flex justify-center">
                    {{ $reviews->links() }}
                </div>
            </section>
        @else
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-12 text-center">
                <div class="w-20 h-20 rounded-full bg-light border border-secondary-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-secondary-400 text-3xl"></i>
                </div>
                <h3 class="font-heading text-xl font-semibind text-primary mb-1">No Reviews Yet</h3>
                <p class="text-sm text-secondary-500 mb-6">Share your experience by reviewing products you've purchased.</p>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-6 py-3 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fas fa-bag-shopping"></i> View Your Orders
                </a>
            </div>
        @endif
    </div>

    {{-- Image viewer --}}
    <div id="imageViewerModal" class="fixed inset-0 bg-primary/90 z-50 hidden items-center justify-center p-4" onclick="closeImageViewer()">
        <button class="absolute top-4 right-4 text-surface-elevated text-3xl hover:text-secondary-300" aria-label="Close"><i class="fas fa-xmark"></i></button>
        <img id="viewerImage" src="" alt="Review image" class="max-w-full max-h-full object-contain rounded-lg">
    </div>

    @push('scripts')
        <script>
            function viewImage(url) {
                document.getElementById('viewerImage').src = url;
                document.getElementById('imageViewerModal').classList.remove('hidden');
                document.getElementById('imageViewerModal').classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeImageViewer() {
                document.getElementById('imageViewerModal').classList.add('hidden');
                document.getElementById('imageViewerModal').classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
            // openReviewModal is defined globally in orders/show.blade.php (shared review modal).
            // If not present, this no-op keeps the button from throwing.
            if (typeof window.openReviewModal !== 'function') {
                window.openReviewModal = function () { alert('Review form will open here.'); };
            }
        </script>
    @endpush
@endsection
