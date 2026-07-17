{{-- Address modal (shared) — add/edit. JS hooks: openAddressModal(id?) --}}
<div id="addressModal" class="fixed inset-0 bg-primary/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-surface-elevated rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-secondary-100 flex items-center justify-between sticky top-0 bg-surface-elevated">
            <h3 id="addressModalTitle" class="text-lg font-bold text-primary">Add New Address</h3>
            <button onclick="closeAddressModal()" class="text-secondary-400 hover:text-primary text-2xl" aria-label="Close"><i class="fas fa-xmark"></i></button>
        </div>

        <form id="addressForm" method="POST" action="{{ route('customer.addresses.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="addressMethod" value="POST">
            <input type="hidden" name="address_id" id="addressId" value="">

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">Address Type</label>
                    <div class="flex gap-3">
                        @foreach(['home' => 'fa-home', 'office' => 'fa-building'] as $val => $icon)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="type" value="{{ $val }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                <div class="px-4 py-3 border-2 border-secondary-200 rounded-xl peer-checked:border-accent peer-checked:bg-accent-50 flex items-center justify-center gap-2 text-sm font-medium text-secondary-600 peer-checked:text-primary transition">
                                    <i class="fas {{ $icon }}"></i> {{ ucfirst($val) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addr_name" required class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="addr_phone" required class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">Address Line 1 <span class="text-danger">*</span></label>
                    <input type="text" name="address_line_1" id="addr_line1" required placeholder="House, street, area" class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">Address Line 2</label>
                    <input type="text" name="address_line_2" id="addr_line2" placeholder="Apartment, suite (optional)" class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" id="addr_city" required class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">State/Province</label>
                    <input type="text" name="state" id="addr_state" class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-secondary-600 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" id="addr_postal" class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-accent">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" id="addr_default" value="1" class="w-5 h-5 text-accent border-secondary-300 rounded focus:ring-accent">
                        <span class="text-sm font-medium text-secondary-600">Set as default address</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-secondary-100">
                <button type="button" onclick="closeAddressModal()" class="flex-1 bg-secondary-100 text-secondary-700 px-6 py-3 rounded-xl font-medium hover:bg-secondary-200 transition">Cancel</button>
                <button type="submit" class="flex-1 bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition">Save Address</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        function openAddressModal(id) {
            const form = document.getElementById('addressForm');
            const title = document.getElementById('addressModalTitle');
            if (id) {
                title.textContent = 'Edit Address';
                document.getElementById('addressMethod').value = 'PUT';
                form.action = '{{ url('addresses') }}/' + id;
                // Prefill via data attributes set by the edit button (server-injected for demo)
                const data = window.__addressData && window.__addressData[id];
                if (data) {
                    document.getElementById('addr_name').value = data.name || '';
                    document.getElementById('addr_phone').value = data.phone || '';
                    document.getElementById('addr_line1').value = data.address_line_1 || '';
                    document.getElementById('addr_line2').value = data.address_line_2 || '';
                    document.getElementById('addr_city').value = data.city || '';
                    document.getElementById('addr_state').value = data.state || '';
                    document.getElementById('addr_postal').value = data.postal_code || '';
                    document.getElementById('addr_default').checked = !!data.is_default;
                    form.querySelector(`input[name="type"][value="${data.type}"]`).checked = true;
                }
            } else {
                title.textContent = 'Add New Address';
                document.getElementById('addressMethod').value = 'POST';
                form.action = '{{ route('customer.addresses.store') }}';
                form.reset();
            }
            document.getElementById('addressModal').classList.replace('hidden', 'flex');
            document.body.style.overflow = 'hidden';
        }
        function closeAddressModal() {
            document.getElementById('addressModal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }
        document.getElementById('addressModal').addEventListener('click', function (e) {
            if (e.target === this) closeAddressModal();
        });
    </script>
@endpush
