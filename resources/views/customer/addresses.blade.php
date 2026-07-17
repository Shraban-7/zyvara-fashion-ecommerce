@extends('customer.layout')
@section('title', 'Addresses')

@php $addresses = $addresses ?? collect(); @endphp

@section('dashboard-content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading text-2xl font-semibold text-primary">My Addresses</h2>
                <p class="text-sm text-secondary-500 mt-1">Manage your saved shipping & billing addresses.</p>
            </div>
            <button onclick="openAddressModal()" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                <i class="fas fa-plus"></i> <span class="hidden sm:inline">Add Address</span><span class="sm:hidden">Add</span>
            </button>
        </div>

        @if($addresses->count() > 0)
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach($addresses as $address)
                    <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 relative">
                        @if($address->is_default)
                            <span class="absolute top-4 right-4 inline-flex items-center gap-1 px-2 py-1 bg-accent-50 text-accent-700 rounded-full text-xs font-semibold"><i class="fas fa-star"></i> Default</span>
                        @endif
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-500"><i class="fas fa-{{ $address->type->value === 'home' ? 'home' : 'building' }}"></i></span>
                            <div>
                                <h4 class="font-semibold text-primary">{{ $address->name }}</h4>
                                <span class="text-xs capitalize px-2 py-0.5 bg-light rounded text-secondary-600">{{ $address->type->value }}</span>
                            </div>
                        </div>
                        <div class="text-sm text-secondary-500 space-y-1">
                            <p>{{ $address->address_line_1 }}</p>
                            @if($address->address_line_2)<p>{{ $address->address_line_2 }}</p>@endif
                            <p>{{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code) - {{ $address->postal_code }}@endif</p>
                            <p><i class="fas fa-phone mr-1"></i>{{ $address->phone }}</p>
                        </div>
                        <div class="flex items-center gap-2 pt-4 mt-3 border-t border-secondary-100">
                            <button onclick="openAddressModal({{ $address->id }})" class="flex-1 bg-light text-primary px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-50 transition">Edit</button>
                            @if(!$address->is_default)
                                <form action="{{ route('customer.addresses.default', $address) }}" method="POST" class="flex-1">@csrf
                                    <button class="w-full bg-light text-secondary-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-50 transition">Set Default</button>
                                </form>
                            @endif
                            <form action="{{ route('customer.addresses.delete', $address) }}" method="POST" class="flex-1">@csrf @method('DELETE')
                                <button onclick="return confirm('Delete this address?')" class="w-full bg-danger-50 text-danger-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-danger-100 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-light border border-secondary-100 flex items-center justify-center mx-auto mb-3"><i class="fas fa-address-book text-secondary-400 text-2xl"></i></div>
                <h3 class="font-heading text-lg font-semibold text-primary mb-1">No addresses saved</h3>
                <p class="text-sm text-secondary-500 mb-5">Add an address for faster checkout.</p>
                <button onclick="openAddressModal()" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition"><i class="fas fa-plus"></i> Add Address</button>
            </div>
        @endif

        @include('partials._address-modal')
    </div>

    @php
        $addressJson = $addresses->mapWithKeys(function ($a) {
            return [$a->id => [
                'name' => $a->name, 'phone' => $a->phone,
                'address_line_1' => $a->address_line_1, 'address_line_2' => $a->address_line_2,
                'city' => $a->city, 'state' => $a->state, 'postal_code' => $a->postal_code,
                'type' => $a->type->value, 'is_default' => $a->is_default,
            ]];
        });
    @endphp

    @push('scripts')
        <script>
            window.__addressData = @json($addressJson);
        </script>
    @endpush
@endsection
