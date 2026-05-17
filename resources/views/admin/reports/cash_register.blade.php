@extends('admin.layouts.app')

@section('title', 'Cash Registers Reports')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Cash Register Reports
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Monitor cashier opening and closing balances.
                </p>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Cashier
                            </th>

                            <th
                                class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Opening Cash
                            </th>

                            <th
                                class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Closing Cash
                            </th>

                            <th
                                class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Difference
                            </th>

                            <th
                                class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse ($cashRegisters as $cashRegister)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">

                                    {{ $cashRegister->opened_at->format('d M Y') }}
                                </td>

                                {{-- Cashier --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $cashRegister?->employee?->name }}
                                </td>

                                {{-- Opening --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-slate-700">

                                    {{ money($cashRegister->opening_amount) }}
                                </td>

                                {{-- Closing --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-slate-700">

                                    {{ money($cashRegister->closing_amount) }}
                                </td>

                                {{-- Difference --}}
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold">

                                    @if ($cashRegister->difference < 0)

                                        <span class="text-red-600">

                                            -{{ money(abs($cashRegister->difference)) }}
                                        </span>

                                    @else

                                        <span class="text-emerald-600">

                                            +{{ money($cashRegister->difference) }}
                                        </span>

                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    @if (!empty($cashRegister->closed_at))

                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">

                                            Closed
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">

                                            Not Closed
                                        </span>

                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="px-6 py-10 text-center text-sm text-slate-500">

                                    No cash register reports found.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-end">

            {{ $cashRegisters->links() }}
        </div>
    </div>

@endsection