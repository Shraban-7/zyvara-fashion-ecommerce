<div class="bg-surface rounded-2xl p-5 md:p-6 border border-secondary-100 shadow-sm">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold shadow-sm">2
        </div>
        <h2 class="text-lg font-bold text-black">Payment Method</h2>
    </div>

    <div class="space-y-3">
        {{-- Cash on Delivery --}}
        <label class="payment-option block relative cursor-pointer">
            <input type="radio" name="payment_method" value="cod" class="sr-only peer" checked>
            <div
                class="p-4 border-2 border-secondary-200 rounded-xl peer-checked:border-primary peer-checked:bg-primary/5 transition hover:border-secondary-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-success-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-black">Cash on Delivery</h4>
                        <p class="text-xs text-secondary-500">Pay when you receive your order</p>
                    </div>
                    <div
                        class="w-5 h-5 border-2 border-secondary-300 rounded-full peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center flex-shrink-0 transition">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </label>

        {{-- bKash Payment --}}
        <label class="payment-option block relative cursor-pointer">
            <input type="radio" name="payment_method" value="bkash" class="sr-only peer">
            <div
                class="p-4 border-2 border-secondary-200 rounded-xl peer-checked:border-primary peer-checked:bg-primary/5 transition hover:border-secondary-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-secondary-50">
                        <img src="{{ asset('assets/images/bkash.png') }}" alt="bKash" class="h-8">
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-black">bKash Payment</h4>
                        <p class="text-xs text-secondary-500">Send money to our bKash merchant number</p>
                    </div>
                    <div
                        class="w-5 h-5 border-2 border-secondary-300 rounded-full peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center flex-shrink-0 transition">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </label>

        {{-- Nagad Payment --}}
        <label class="payment-option block relative cursor-pointer">
            <input type="radio" name="payment_method" value="nagad" class="sr-only peer">
            <div
                class="p-4 border-2 border-secondary-200 rounded-xl peer-checked:border-primary peer-checked:bg-primary/5 transition hover:border-secondary-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-secondary-50">
                        <img src="{{ asset('assets/images/nagad.png') }}" alt="Nagad" class="h-8">
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-black">Nagad Payment</h4>
                        <p class="text-xs text-secondary-500">Send money to our Nagad number</p>
                    </div>
                    <div
                        class="w-5 h-5 border-2 border-secondary-300 rounded-full peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center flex-shrink-0 transition">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </label>
    </div>

    {{-- bKash Payment Instructions --}}
    <div id="bkashInstructions"
        class="hidden mt-5 p-5 bg-primary-50 rounded-xl border border-primary-200">
        <h4 class="font-bold text-primary-800 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle text-primary-500"></i>
            bKash Payment Instructions
        </h4>
        <ol class="space-y-3 text-sm text-primary-900">
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">1</span>
                <span>Open your <strong>bKash App</strong> or dial <strong>*247#</strong></span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">2</span>
                <span>Select <strong>"Send Money"</strong></span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">3</span>
                <div>
                    <span>Enter our bKash Merchant Number:</span>
                    <div class="mt-2 flex items-center gap-2">
                        <code
                            class="bg-surface px-4 py-2 rounded-lg font-bold text-primary-700 text-lg border border-primary-200">{{ $bkashNumber }}</code>
                        <button type="button"
                            onclick="copyToClipboard('{{ str_replace('-', '', $bkashNumber) }}', this)"
                            class="p-2 hover:bg-primary-200 rounded-lg transition">
                            <i class="far fa-copy text-primary-600"></i>
                        </button>
                    </div>
                </div>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">4</span>
                <span>Enter the <strong>Total Amount</strong> shown in order summary</span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">5</span>
                <span>Add <strong>Reference:</strong> Your Phone Number</span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">6</span>
                <span>Enter your bKash <strong>PIN</strong> to confirm</span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">7</span>
                <span>Enter the <strong>Transaction ID (TrxID)</strong> below</span>
            </li>
        </ol>

        {{-- Quick Pay Button --}}
        <a href="https://shop.bkash.com/spinner-fashion{{ str_replace('-', '', $bkashNumber) }}/pay" target="_blank"
            class="mt-4 w-full bg-primary text-white py-3 rounded-xl font-semibold text-sm hover:bg-primary-700 transition flex items-center justify-center gap-2 shadow-lg shadow-primary-200/50">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/BKash-bKash-Logo.wine.svg/200px-BKash-bKash-Logo.wine.svg.png"
                alt="bKash" class="h-5 brightness-0 invert">
            Pay with bKash App
        </a>

        <div class="mt-4">
            <label class="block text-sm font-medium text-primary-800 mb-2">Transaction ID (TrxID) <span
                    class="text-danger-500">*</span></label>
            <input type="text" name="bkash_trx_id" placeholder="e.g., 8N72KS92JD"
                class="w-full h-12 px-4 border border-primary-300 rounded-xl text-sm focus:outline-none focus:border-primary-500 bg-surface transition uppercase"
                style="letter-spacing: 1px;">
            <p class="text-xs text-primary-600 mt-1">Enter the TrxID from your bKash confirmation SMS</p>
        </div>
    </div>

    {{-- Nagad Payment Instructions --}}
    <div id="nagadInstructions"
        class="hidden mt-5 p-5 bg-warning-50 rounded-xl border border-warning-200">
        <h4 class="font-bold text-warning-800 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle text-warning-500"></i>
            Nagad Payment Instructions
        </h4>
        <ol class="space-y-3 text-sm text-warning-900">
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">1</span>
                <span>Open your <strong>Nagad App</strong> or dial <strong>*167#</strong></span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">2</span>
                <span>Select <strong>"Send Money"</strong></span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">3</span>
                <div>
                    <span>Enter our Nagad Number:</span>
                    <div class="mt-2 flex items-center gap-2">
                        <code
                            class="bg-surface px-4 py-2 rounded-lg font-bold text-warning-700 text-lg border border-warning-200">{{ $nagadNumber }}</code>
                        <button type="button"
                            onclick="copyToClipboard('{{ str_replace('-', '', $nagadNumber) }}', this)"
                            class="p-2 hover:bg-warning-200 rounded-lg transition">
                            <i class="far fa-copy text-warning-600"></i>
                        </button>
                    </div>
                </div>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">4</span>
                <span>Enter the <strong>Total Amount</strong> shown in order summary</span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">5</span>
                <span>Add <strong>Reference:</strong> Your Phone Number</span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">6</span>
                <span>Enter your Nagad <strong>PIN</strong> to confirm</span>
            </li>
            <li class="flex gap-3">
                <span
                    class="w-6 h-6 bg-warning text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">7</span>
                <span>Enter the <strong>Transaction ID (TrxID)</strong> below</span>
            </li>
        </ol>

        <div class="mt-4">
            <label class="block text-sm font-medium text-warning-800 mb-2">Transaction ID (TrxID) <span
                    class="text-danger-500">*</span></label>
            <input type="text" name="nagad_trx_id" placeholder="e.g., N8K72J92"
                class="w-full h-12 px-4 border border-warning-300 rounded-xl text-sm focus:outline-none focus:border-warning-500 bg-surface transition uppercase"
                style="letter-spacing: 1px;">
            <p class="text-xs text-warning-600 mt-1">Enter the TrxID from your Nagad confirmation SMS</p>
        </div>
    </div>
</div>