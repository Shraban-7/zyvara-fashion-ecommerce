{{--
    Shared Account Sidebar
    Used by customer/layout.blade.php. Shows a vertical sidebar on desktop (lg+)
    and a horizontal scrollable tab strip on mobile/tablet.
    Active link is highlighted in the gold accent.
--}}
@php
    $user = auth()->user();
    $segments = [
        [
            'route' => 'customer.dashboard',
            'is'    => ['customer.dashboard'],
            'icon'  => 'fa-grip',
            'label' => 'Overview',
        ],
        [
            'route' => 'orders.index',
            'is'    => ['orders.index', 'orders.show'],
            'icon'  => 'fa-bag-shopping',
            'label' => 'My Orders',
        ],
        [
            'route' => 'customer.wishlist',
            'is'    => ['customer.wishlist'],
            'icon'  => 'fa-heart',
            'label' => 'Wishlist',
        ],
        [
            'route' => 'customer.reviews',
            'is'    => ['customer.reviews'],
            'icon'  => 'fa-star',
            'label' => 'My Reviews',
        ],
        [
            'route' => 'customer.profile',
            'is'    => ['customer.profile'],
            'icon'  => 'fa-user-gear',
            'label' => 'Profile Settings',
        ],
        [
            'route' => 'customer.addresses',
            'is'    => ['customer.addresses'],
            'icon'  => 'fa-address-book',
            'label' => 'Addresses',
        ],
    ];

    // Add a track-order link only when an order is being viewed (detail page can inject it)
    if (isset($trackOrderNumber)) {
        $segments[] = [
            'route'  => 'orders.show',
            'is'     => ['orders.show'],
            'icon'   => 'fa-truck-fast',
            'label'  => 'Track Order',
            'params' => ['order' => $trackOrderNumber],
        ];
    }

    $activeRoute = null;
    foreach ($segments as $seg) {
        if (request()->routeIs($seg['is'])) { $activeRoute = $seg['label']; break; }
    }
@endphp

{{-- Desktop sidebar --}}
<aside class="hidden lg:block lg:w-64 flex-shrink-0">
    <nav aria-label="Account navigation"
        class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm overflow-hidden sticky top-28">
        {{-- Profile card --}}
        <div class="flex items-center gap-3 p-5 bg-primary text-surface-elevated">
            <div class="w-12 h-12 rounded-full bg-surface-elevated flex items-center justify-center font-heading font-bold text-lg text-primary shadow-sm shrink-0">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="font-semibold truncate leading-tight">{{ $user->name }}</p>
                <p class="text-xs text-primary-100/80 truncate">{{ $user->email }}</p>
            </div>
        </div>

        <ul class="p-2 space-y-1">
            @foreach ($segments as $seg)
                @php
                    $active = request()->routeIs($seg['is']);
                    $href = isset($seg['params'])
                        ? route($seg['route'], $seg['params'])
                        : route($seg['route']);
                @endphp
                <li>
                    <a href="{{ $href }}"
                        aria-current="{{ $active ? 'page' : 'false' }}"
                        class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                            {{ $active
                                ? 'bg-accent-50 text-accent-700 font-semibold'
                                : 'text-secondary-600 hover:bg-light hover:text-primary' }}">
                        <span class="w-5 text-center {{ $active ? 'text-accent-600' : 'text-secondary-400 group-hover:text-primary-500' }}">
                            <i class="fas {{ $seg['icon'] }}"></i>
                        </span>
                        <span>{{ $seg['label'] }}</span>
                        @if ($active)
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-accent-500"></span>
                        @endif
                    </a>
                </li>
            @endforeach

            <li class="pt-1 mt-1 border-t border-secondary-100">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-danger-600 hover:bg-danger-50 transition-colors">
                        <span class="w-5 text-center"><i class="fas fa-arrow-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

{{-- Mobile / tablet horizontal tab strip --}}
<nav aria-label="Account navigation (mobile)"
    class="lg:hidden -mx-4 px-4 mb-5 sticky top-0 z-30 bg-light/95 backdrop-blur py-3 border-b border-secondary-100">
    <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-1" role="tablist">
        @foreach ($segments as $seg)
            @php
                $active = request()->routeIs($seg['is']);
                $href = isset($seg['params'])
                    ? route($seg['route'], $seg['params'])
                    : route($seg['route']);
            @endphp
            <a href="{{ $href }}"
                role="tab"
                aria-selected="{{ $active ? 'true' : 'false' }}"
                class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium border transition-colors
                    {{ $active
                        ? 'bg-accent text-primary border-accent shadow-sm'
                        : 'bg-surface-elevated text-secondary-600 border-secondary-100 hover:border-primary-200' }}">
                <i class="fas {{ $seg['icon'] }}"></i>
                <span>{{ $seg['label'] }}</span>
            </a>
        @endforeach
        <form action="{{ route('auth.logout') }}" method="POST" class="shrink-0">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium border border-secondary-100 bg-surface-elevated text-danger-600">
                <i class="fas fa-arrow-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>
