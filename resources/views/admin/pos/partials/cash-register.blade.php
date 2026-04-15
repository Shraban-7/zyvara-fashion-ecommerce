<div id="openRegisterModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-5">

        <h2 class="text-lg font-semibold mb-4">
            💰 Cash Register
        </h2>

        <form action="{{ route('admin.cashRegister.open') }}" method="POST">
            @csrf

            <label class="block text-sm font-medium mb-1">
                Opening Cash Amount
            </label>

            <div class="flex">
                <span class="px-3 flex items-center bg-gray-100 border border-r-0 rounded-l-lg">
                    {{ currency('symbol') }}
                </span>

                <input type="number" name="opening_amount" step="0.01" min="0" required
                    class="w-full border rounded-r-lg px-3 py-2" placeholder="Enter opening cash">
            </div>

            <button class="w-full mt-4 bg-blue-600 text-white py-2 rounded-lg">
                Save
            </button>
        </form>

    </div>
</div>

@if($cashRegister)
    <div id="closeRegisterModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-xl shadow-lg">

            <div class="flex justify-between items-start border-b px-5 py-4">

                <div>
                    <h2 class="font-semibold">Close Cash Register</h2>

                    <div class="text-xs text-gray-500 mt-1 flex gap-2">
                        <span class="px-2 py-0.5 bg-gray-100 rounded">
                            #{{ $cashRegister->id }}
                        </span>

                        <span>
                            Opened: {{ $cashRegister->created_at->format('h:i A') }}
                        </span>

                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded">
                            Active
                        </span>
                    </div>
                </div>

                <button type="button" id="closeCloseBtn" class="text-gray-500 hover:text-red-500 text-lg">
                    ✕
                </button>

            </div>

            <form method="POST" action="{{ route('admin.cashRegister.close', $cashRegister->id) }}">
                @csrf

                <input type="hidden" name="register_id" value="{{ $cashRegister->id }}">

                <div class="p-5 space-y-4">

                    <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-2">

                        <div class="flex justify-between">
                            <span>Opening Cash</span>
                            <strong>{{ money($cashRegisterData['opening_amount']) }}</strong>
                        </div>

                        <div class="flex justify-between text-green-600">
                            <span>Sales</span>
                            <strong>+ {{ money($cashRegisterData['sales_amount']) }}</strong>
                        </div>

                        <div class="flex justify-between text-red-500">
                            <span>Expenses</span>
                            <strong>- {{ money($cashRegisterData['expense']) }}</strong>
                        </div>

                        <div class="flex justify-between text-red-500">
                            <span>Sales Returns</span>
                            <strong>- {{ money($cashRegisterData['sales_returns']) }}</strong>
                        </div>

                        <hr>

                        @php
                            $expected = $cashRegisterData['opening_amount']
                                + $cashRegisterData['sales_amount']
                                - $cashRegisterData['expense']
                                - $cashRegisterData['sales_returns'];
                        @endphp

                        <div class="flex justify-between font-semibold">
                            <span>Expected Cash</span>
                            <span class="text-blue-600">
                                {{ money($expected) }}
                            </span>
                        </div>

                    </div>

                    {{-- ✅ CONDITION INPUT --}}
                    @if (!$cashRegister->closed_at)
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Enter Closing Cash Amount
                        </label>

                        <div class="flex">
                            <span class="px-3 flex items-center bg-gray-100 border border-r-0 rounded-l-lg">
                                {{ currency('symbol') }}
                            </span>

                            <input type="number" name="closing_amount"
                                step="0.01" min="0" required
                                class="w-full border rounded-r-lg px-3 py-2"
                                placeholder="Counted cash">
                        </div>
                    </div>
                    @endif

                </div>

                <div class="flex justify-end gap-2 border-t px-5 py-3">

                    <button type="button" id="cancelCloseBtn" class="px-4 py-2 border rounded-lg">
                        Cancel
                    </button>

                    {{-- ✅ CONDITION BUTTON --}}
                    <button class="px-4 py-2 rounded-lg text-white
                {{ $cashRegister->closed_at
            ? 'bg-yellow-600 hover:bg-yellow-700'
            : 'bg-blue-600 hover:bg-blue-700' }}">

                        {{ $cashRegister->closed_at ? 'Reopen Register' : 'Close Register' }}

                    </button>

                </div>

            </form>

        </div>
    </div>
@endif