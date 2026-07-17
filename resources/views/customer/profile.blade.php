@extends('customer.layout')
@section('title', 'Profile Settings')

@php
    $addresses = $addresses ?? collect();
    $user = $user ?? auth()->user();
@endphp

@section('dashboard-content')
    <div class="space-y-6">
        {{-- Profile header with avatar --}}
        <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-6 flex items-center gap-5">
            <div class="relative group">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-light border border-secondary-100 flex items-center justify-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="font-heading text-2xl font-bold text-primary">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <label class="absolute inset-0 rounded-full bg-primary/40 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition text-surface-elevated">
                    <i class="fas fa-camera"></i>
                    <input type="file" name="avatar" form="profileForm" accept="image/*" class="hidden">
                </label>
            </div>
            <div>
                <h2 class="font-heading text-xl font-semibold text-primary">{{ $user->name }}</h2>
                <p class="text-sm text-secondary-500">{{ $user->email }}</p>
                <p class="text-xs text-secondary-400 mt-1">Member since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-secondary-100">
            <div class="flex gap-1 overflow-x-auto hide-scrollbar" role="tablist">
                @foreach(['info' => 'Personal Info', 'password' => 'Password', 'addresses' => 'Addresses', 'notifications' => 'Notifications'] as $key => $label)
                    <button role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        onclick="switchTab(this, '{{ $key }}')"
                        class="tab-btn px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition -mb-px
                            {{ $loop->first ? 'border-accent text-primary' : 'border-transparent text-secondary-500 hover:text-primary' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Personal Info --}}
        <section id="tab-info" role="tabpanel" class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-secondary-100 bg-primary">
                <h3 class="text-base font-semibold text-surface-elevated flex items-center gap-2"><i class="fas fa-user"></i> Personal Information</h3>
            </div>
            <form id="profileForm" action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-semibold text-secondary-600 mb-2">Full Name <span class="text-danger-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent bg-light text-primary @error('name') border-danger-500 @enderror">
                    @error('name')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-secondary-600 mb-2">Email <span class="text-danger-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent bg-light text-primary @error('email') border-danger-500 @enderror">
                    @error('email')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-secondary-600 mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="01712345678"
                        class="block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent bg-light text-primary @error('phone') border-danger-500 @enderror">
                    @error('phone')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition">
                        <i class="fas fa-floppy-disk"></i> Save Changes
                    </button>
                </div>
            </form>
        </section>

        {{-- Password --}}
        <section id="tab-password" role="tabpanel" class="hidden bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-secondary-100 bg-primary">
                <h3 class="text-base font-semibold text-surface-elevated flex items-center gap-2"><i class="fas fa-lock"></i> Change Password</h3>
            </div>
            <form action="{{ route('customer.password.update') }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-secondary-600 mb-2">Current Password <span class="text-danger-500">*</span></label>
                    <input type="password" name="current_password" id="current_password" required
                        class="block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent bg-light text-primary @error('current_password') border-danger-500 @enderror">
                    @error('current_password')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-semibold text-secondary-600 mb-2">New Password <span class="text-danger-500">*</span></label>
                    <input type="password" name="new_password" id="new_password" required minlength="8"
                        class="block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent bg-light text-primary @error('new_password') border-danger-500 @enderror">
                    @error('new_password')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-secondary-400">At least 8 characters.</p>
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-secondary-600 mb-2">Confirm New Password <span class="text-danger-500">*</span></label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required minlength="8"
                        class="block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-accent/30 focus:border-accent bg-light text-primary">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition">
                        <i class="fas fa-shield-halved"></i> Update Password
                    </button>
                </div>
            </form>
        </section>

        {{-- Addresses (inline) --}}
        <section id="tab-addresses" role="tabpanel" class="hidden space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading text-lg font-semibold text-primary">Saved Addresses</h3>
                <button onclick="openAddressModal()" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fas fa-plus"></i> Add Address
                </button>
            </div>

            @if($addresses->count() > 0)
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($addresses as $address)
                        <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 relative">
                            @if($address->is_default)
                                <span class="absolute top-4 right-4 inline-flex items-center gap-1 px-2 py-1 bg-accent-50 text-accent-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-star"></i> Default
                                </span>
                            @endif
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-500">
                                    <i class="fas fa-{{ $address->type->value === 'home' ? 'home' : 'building' }}"></i>
                                </span>
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
                                    <form action="{{ route('customer.addresses.default', $address) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button class="w-full bg-light text-secondary-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-50 transition">Set Default</button>
                                    </form>
                                @endif
                                <form action="{{ route('customer.addresses.delete', $address) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Delete this address?')" class="w-full bg-danger-50 text-danger-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-danger-100 transition">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-light border border-secondary-100 flex items-center justify-center mx-auto mb-3"><i class="fas fa-address-book text-secondary-400 text-2xl"></i></div>
                    <h4 class="font-semibold text-primary mb-1">No addresses saved</h4>
                    <p class="text-sm text-secondary-500 mb-4">Add an address for faster checkout.</p>
                    <button onclick="openAddressModal()" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition"><i class="fas fa-plus"></i> Add Address</button>
                </div>
            @endif

            @include('partials._address-modal')
        </section>

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

        {{-- Notifications --}}
        <section id="tab-notifications" role="tabpanel" class="hidden bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-6 space-y-1">
            <h3 class="font-heading text-lg font-semibold text-primary mb-4">Notification Preferences</h3>
            @foreach([
                'order_email' => ['Order updates (email)', 'Shipping & delivery alerts', 'fa-box'],
                'promo_email' => ['Promotions (email)', 'Sales, new arrivals & offers', 'fa-tag'],
                'order_sms'   => ['Order updates (SMS)', 'Critical status changes via SMS', 'fa-comment-sms'],
            ] as $key => $meta)
                <label class="flex items-center justify-between gap-4 py-4 border-b border-secondary-100 last:border-0 cursor-pointer">
                    <span class="flex items-center gap-3">
                        <i class="fas {{ $meta[2] }} text-accent-600 w-5 text-center"></i>
                        <span>
                            <span class="block text-sm font-medium text-primary">{{ $meta[0] }}</span>
                            <span class="block text-xs text-secondary-400">{{ $meta[1] }}</span>
                        </span>
                    </span>
                    <input type="checkbox" name="notifications[{{ $key }}]" value="1"
                        {{ ($prefs[$key] ?? true) ? 'checked' : '' }}
                        class="sr-only peer">
                    <span class="w-11 h-6 rounded-full bg-secondary-200 peer-checked:bg-accent relative transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-surface-elevated after:transition-transform peer-checked:after:translate-x-5"></span>
                </label>
            @endforeach
            <div class="flex justify-end pt-2">
                <button class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-6 py-3 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fas fa-floppy-disk"></i> Save Preferences
                </button>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            function switchTab(btn, key) {
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.setAttribute('aria-selected', 'false');
                    b.className = 'tab-btn px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition -mb-px border-transparent text-secondary-500 hover:text-primary';
                });
                btn.setAttribute('aria-selected', 'true');
                btn.className = 'tab-btn px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition -mb-px border-accent text-primary';
                document.querySelectorAll('[role="tabpanel"]').forEach(p => p.classList.add('hidden'));
                document.getElementById('tab-' + key).classList.remove('hidden');
            }
        </script>
    @endpush
@endsection
