{{-- Review modal (shared) — expects $order --}}
<div id="reviewModal" class="fixed inset-0 bg-primary/60 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-surface-elevated rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-secondary-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-secondary-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-accent-50 flex items-center justify-center">
                    <i class="fas fa-pencil text-accent-600 text-sm"></i>
                </div>
                <h3 class="font-medium text-primary text-base">Write a review</h3>
            </div>
            <button type="button" onclick="closeReviewModal()" class="w-8 h-8 rounded-full border border-secondary-200 flex items-center justify-center text-secondary-400 hover:bg-light transition-colors">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('review.store') }}" method="POST" class="p-5">
            @csrf
            <input type="hidden" name="product_id" id="review_product_id">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="rating" id="ratingVal" value="0">

            <div class="flex items-center gap-3 p-3 bg-light rounded-xl border border-secondary-100 mb-5">
                <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center shrink-0"><i class="fas fa-box text-primary-400"></i></div>
                <div>
                    <p id="review_product_name" class="text-sm font-medium text-primary"></p>
                    <p class="text-xs text-secondary-300">Purchased item</p>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-medium text-secondary-400 uppercase tracking-wider mb-3">Your rating</label>
                <div class="flex items-center gap-1.5" id="starGroup">
                    @for ($i = 1; $i <= 5; $i++)
                        <span data-val="{{ $i }}" class="star text-4xl cursor-pointer select-none text-secondary-200 transition-transform hover:scale-110">☆</span>
                    @endfor
                    <span id="ratingLabel" class="text-xs text-secondary-400 ml-2"></span>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-medium text-secondary-400 uppercase tracking-wider mb-2">Your review</label>
                <textarea name="comment" rows="4" placeholder="Share your experience with this product..." class="w-full border border-secondary-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent resize-none text-primary placeholder-secondary-300 bg-surface-elevated"></textarea>
            </div>

            <button type="submit" class="w-full bg-primary text-surface-elevated py-3 rounded-xl text-sm font-medium flex items-center justify-center gap-2 hover:bg-primary-700 transition-colors shadow-sm">
                <i class="fas fa-paper-plane text-xs"></i> Submit review
            </button>
            <p class="text-center text-xs text-secondary-300 mt-3">Your review helps other shoppers make better decisions.</p>
        </form>
    </div>
</div>
