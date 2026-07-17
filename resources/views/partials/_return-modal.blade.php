{{-- Return / Exchange modal (shared) — expects $order --}}
<div id="returnModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-primary/50 p-4">
    <div class="bg-surface-elevated rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-secondary-100 px-6 py-4">
            <h3 class="text-lg font-bold text-primary">Return / Exchange</h3>
            <button type="button" onclick="closeReturnModal()" class="text-secondary-400 hover:text-primary"><i class="fas fa-xmark text-lg"></i></button>
        </div>

        <form id="returnForm" method="POST" action="{{ route('orders.returns.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="order_item_id" id="return_order_item_id">

            <div id="returnAlert" class="hidden rounded-lg px-4 py-3 text-sm"></div>

            <div>
                <label class="block text-sm font-medium text-secondary-600 mb-1.5">Type</label>
                <div class="flex gap-3">
                    <label class="flex-1">
                        <input type="radio" name="type" value="return" class="peer sr-only" checked>
                        <span class="block cursor-pointer rounded-xl border border-secondary-200 px-4 py-3 text-center text-sm font-medium text-secondary-600 peer-checked:border-primary peer-checked:bg-primary-50 peer-checked:text-primary transition">Return (Refund)</span>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="type" value="exchange" class="peer sr-only">
                        <span class="block cursor-pointer rounded-xl border border-secondary-200 px-4 py-3 text-center text-sm font-medium text-secondary-600 peer-checked:border-primary peer-checked:bg-primary-50 peer-checked:text-primary transition">Exchange</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary-600 mb-1.5">Reason</label>
                <select name="reason" class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                    <option value="wrong_size">Wrong size</option>
                    <option value="damaged">Item damaged</option>
                    <option value="not_as_described">Not as described</option>
                    <option value="changed_mind">Changed my mind</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div id="variantField" class="hidden">
                <label class="block text-sm font-medium text-secondary-600 mb-1.5">New Size / Color</label>
                <select name="requested_variant_id" id="requested_variant_id" class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                    <option value="">Select an option…</option>
                </select>
                <p id="variantHint" class="text-xs text-secondary-400 mt-1"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary-600 mb-1.5">Additional details</label>
                <textarea name="reason_note" rows="3" class="w-full px-3 py-2 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent" placeholder="Tell us more…"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary-600 mb-1.5">Photos (optional, up to 5)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm">
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-surface-elevated transition hover:bg-primary-700 shadow-sm">
                Submit Request
            </button>
        </form>
    </div>
</div>
