@php
    // Build a breadcrumb from the current admin route name.
    $routeName = request()->route()?->getName() ?? 'admin.dashboard';
    $segments = collect(explode('.', $routeName))
        ->reject(fn ($s) => $s === 'admin')
        ->toArray();

    $labelMap = [
        'dashboard' => 'Dashboard',
        'products' => 'Products',
        'create' => 'Create',
        'edit' => 'Edit',
        'store' => 'Store',
        'update' => 'Update',
        'manage-stock' => 'Manage Stock',
        'stock-history' => 'Stock History',
        'printBarcode' => 'Print Barcode',
        'categories' => 'Categories',
        'brands' => 'Brands',
        'orders' => 'Orders',
        'show' => 'Details',
        'index' => '',
        'pos' => 'Point of Sale',
        'sales' => 'POS Sales',
        'saleReturns' => 'Returns',
        'expenses' => 'Expenses',
        'reports' => 'Reports',
        'overview' => 'Overview',
        'financial' => 'Financial',
        'sales' => 'Sales',
        'customers' => 'Customers',
        'cashRegisters' => 'Cash Registers',
        'flash-sales' => 'Flash Sales',
        'banners' => 'Banners',
        'home-sections' => 'Homepage Sections',
        'testimonials' => 'Testimonials',
        'social-posts' => 'Social Feed',
        'reviews' => 'Reviews',
        'activity-logs' => 'Activity Logs',
        'static_pages' => 'Static Pages',
        'settings' => 'Settings',
        'employees' => 'Employees',
        'notifications' => 'Notifications',
    ];

    $crumbs = collect($segments)
        ->map(fn ($s) => $labelMap[$s] ?? Str::title(str_replace(['-', '_'], ' ', $s)))
        ->filter(fn ($s) => $s !== '');
@endphp

<header class="admin-header h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40 gap-3">
    {{-- Left: toggle + breadcrumb --}}
    <div class="flex items-center gap-3 min-w-0">
        <button id="sidebarToggle" aria-label="Toggle sidebar"
            class="group flex items-center justify-center w-10 h-10 rounded-xl text-secondary-500 hover:text-primary hover:bg-secondary-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-accent/40">
            <i data-lucide="panel-left" class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"></i>
        </button>

        {{-- Breadcrumb (hidden on small screens) --}}
        <nav aria-label="Breadcrumb" class="hidden md:flex items-center gap-1.5 min-w-0 text-sm">
            <a href="{{ route('admin.dashboard') }}"
                class="text-secondary-500 hover:text-primary transition shrink-0">
                <i data-lucide="home" class="w-4 h-4"></i>
            </a>
            @foreach ($crumbs as $crumb)
                <span class="text-secondary-300 shrink-0">/</span>
                <span class="font-medium text-primary truncate">{{ $crumb }}</span>
            @endforeach
        </nav>
    </div>

    {{-- Right: search + notifications + profile --}}
    <div class="flex items-center gap-2 sm:gap-3">

        {{-- Quick Search --}}
        <form action="{{ route('admin.products.index') }}" method="GET"
            class="hidden lg:flex items-center relative group">
            <i data-lucide="search"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-secondary-400 pointer-events-none"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search products…"
                aria-label="Search products"
                class="w-56 xl:w-72 pl-9 pr-3 py-2 text-sm rounded-xl border border-secondary-200 bg-secondary-50 text-primary placeholder-secondary-400 transition focus:bg-white focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
        </form>

        {{-- Notifications Dropdown --}}
        @php
            $unreadNotificationsCount = \App\Models\Notification::where('user_id', auth()->id())->unread()->count();
        @endphp
        <div class="relative" x-data="notificationDropdown()" x-init="init()" @click.away="open = false">
            <button @click="open = !open"
                    class="header-action-btn relative p-2.5 rounded-xl transition focus:outline-none"
                    title="Notifications" aria-label="Notifications">
                <i data-lucide="bell" class="w-[18px] h-[18px]"></i>
                <span x-show="unreadCount > 0"
                      class="absolute top-1 right-1 min-w-[15px] h-3.5 px-0.5 bg-danger text-white text-[8px] font-bold border border-white rounded-full flex items-center justify-center"
                      x-text="unreadCount">
                </span>
            </button>

            {{-- Dropdown --}}
            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="dropdown-menu absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-secondary-200 overflow-hidden z-50">

                {{-- Header --}}
                <div class="px-4 py-3 bg-secondary-50 border-b border-secondary-100 flex items-center justify-between">
                    <span class="font-bold text-primary text-sm">Notifications</span>
                    <span x-show="unreadCount > 0"
                          class="text-[10px] font-bold bg-accent-100 text-accent px-2 py-0.5 rounded-full"
                          x-text="`${unreadCount} new`">
                    </span>
                </div>

                {{-- List --}}
                <div class="divide-y divide-secondary-100 max-h-[320px] overflow-y-auto">
                    <template x-for="item in notifications" :key="item.id">
                        <a :href="item.action_url"
                           class="flex gap-3 p-3.5 hover:bg-secondary-50 transition relative"
                           :class="item.is_unread ? 'bg-accent-50/40' : ''">
                            <div x-show="item.is_unread"
                                 class="absolute left-1.5 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-accent rounded-full">
                            </div>
                            <div class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-xs"
                                 :class="`bg-${item.color}-50 text-${item.color}-600`">
                                <i class="fas" :class="item.icon"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-primary truncate" x-text="item.title"></p>
                                <p class="text-xs text-secondary-500 mt-0.5 line-clamp-2 leading-relaxed" x-text="item.message"></p>
                                <p class="text-[10px] text-secondary-400 mt-1 font-semibold" x-text="item.time"></p>
                            </div>
                        </a>
                    </template>

                    <div x-show="notifications.length === 0" class="p-8 text-center text-xs text-secondary-400">
                        No notifications available
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-3 bg-secondary-50 border-t border-secondary-100 text-center">
                    <a href="{{ route('admin.notifications.index') }}"
                       class="text-xs font-bold text-accent hover:text-primary transition">
                        See all notifications
                    </a>
                </div>
            </div>
        </div>

        {{-- View Site --}}
        <a href="{{ route('home') }}" target="_blank"
            class="hidden sm:flex items-center gap-2 px-3 sm:px-4 py-2 text-sm font-semibold text-secondary-600 hover:text-primary hover:bg-secondary-100 rounded-xl transition">
            <i data-lucide="external-link" class="text-xs"></i>
            <span class="hidden sm:inline">View Site</span>
        </a>

        {{-- Divider --}}
        <div class="hidden sm:block w-px h-8 bg-secondary-200 mx-1"></div>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" aria-label="Account menu"
                class="flex items-center gap-2.5 p-1.5 hover:bg-secondary-100 rounded-xl transition focus:outline-none focus:ring-2 focus:ring-accent/40">
                <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-primary leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-secondary-400 leading-tight">Administrator</p>
                </div>
                <i data-lucide="chevron-down" class="text-[10px] text-secondary-400 hidden sm:block"></i>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                class="dropdown-menu absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl py-2 z-50">
                <div class="px-4 py-3 border-b border-secondary-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white text-sm font-bold">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-primary truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-secondary-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
                <div class="py-1.5">
                    <a href="{{ route('admin.settings.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-600 hover:bg-secondary-50 hover:text-primary transition rounded-lg mx-1.5">
                        <i data-lucide="settings" class="w-4 text-secondary-400"></i>
                        <span>Settings</span>
                    </a>
                </div>
                <div class="border-t border-secondary-100 my-1"></div>
                <div class="py-1">
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-danger hover:bg-danger-50 transition w-full text-left rounded-lg mx-1.5">
                            <i data-lucide="log-out" class="w-4"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function notificationDropdown() {
        return {
            open: false,
            unreadCount: {{ $unreadNotificationsCount }},
            notifications: [],
            init() {
                this.fetchNotifications();
                setInterval(() => this.fetchNotifications(), 5000);
            },
            fetchNotifications() {
                fetch('{{ route("admin.notifications.poll") }}')
                    .then(r => r.json())
                    .then(data => {
                        if (data.unread_count > this.unreadCount) this.playBeep();
                        this.unreadCount = data.unread_count;
                        this.notifications = data.notifications;
                    })
                    .catch(err => console.log('Polling error:', err));
            },
            playBeep() {
                const audio = new Audio('{{ asset("assets/audio/notification.mp3") }}');
                audio.play().catch(() => {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.frequency.value = 880;
                        gain.gain.setValueAtTime(0.08, ctx.currentTime);
                        osc.start();
                        osc.stop(ctx.currentTime + 0.12);
                    } catch (e) { console.log('Audio error:', e); }
                });
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
