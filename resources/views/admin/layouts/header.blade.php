<header class="admin-header h-16 flex items-center justify-between px-6 sticky top-0 z-40">
    {{-- Menu Toggle Button --}}
    <button id="sidebarToggle" class="group flex items-center justify-center w-10 h-10 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 focus:outline-none">
        <i data-lucide="align-left" class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"></i>
    </button>

    {{-- Header Actions --}}
    <div class="flex items-center gap-2">

        {{-- Notifications Dropdown --}}
        @php
            $unreadNotificationsCount = \App\Models\Notification::where('user_id', auth()->id())->unread()->count();
        @endphp
        <div class="relative" x-data="notificationDropdown()" x-init="init()" @click.away="open = false">
            <button @click="open = !open"
                    class="header-action-btn relative p-2.5 rounded-xl transition focus:outline-none"
                    title="Notifications">
                <i class="fas fa-bell text-lg"></i>
                <span x-show="unreadCount > 0"
                      class="absolute top-1 right-1 min-w-[15px] h-3.5 px-0.5 bg-red-500 text-white text-[8px] font-bold border border-white rounded-full flex items-center justify-center"
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
                 class="dropdown-menu absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">

                {{-- Header --}}
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-sm">Notifications</span>
                    <span x-show="unreadCount > 0"
                          class="text-[10px] font-bold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full"
                          x-text="`${unreadCount} new`">
                    </span>
                </div>

                {{-- List --}}
                <div class="divide-y divide-slate-100 max-h-[320px] overflow-y-auto">
                    <template x-for="item in notifications" :key="item.id">
                        <a :href="item.action_url"
                           class="flex gap-3 p-3.5 hover:bg-slate-50 transition relative"
                           :class="item.is_unread ? 'bg-indigo-50/30' : ''">
                            <div x-show="item.is_unread"
                                 class="absolute left-1.5 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-indigo-600 rounded-full">
                            </div>
                            <div class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-xs"
                                 :class="`bg-${item.color}-50 text-${item.color}-600`">
                                <i class="fas" :class="item.icon"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate" x-text="item.title"></p>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 leading-relaxed" x-text="item.message"></p>
                                <p class="text-[10px] text-slate-400 mt-1 font-semibold" x-text="item.time"></p>
                            </div>
                        </a>
                    </template>

                    <div x-show="notifications.length === 0" class="p-8 text-center text-xs text-slate-400">
                        No notifications available
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                    <a href="{{ route('admin.notifications.index') }}"
                       class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        See all notifications
                    </a>
                </div>
            </div>
        </div>

        {{-- View Site --}}
        <a href="{{ route('home') }}" target="_blank"
            class="hidden sm:flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
            <i class="fas fa-external-link-alt text-xs"></i>
            <span>View Site</span>
        </a>

        {{-- Divider --}}
        <div class="hidden sm:block w-px h-8 bg-slate-200 mx-1"></div>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 hover:bg-slate-50 rounded-xl transition">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-500 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md shadow-indigo-500/20">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-slate-700 leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-400 leading-tight">Administrator</p>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-400 hidden sm:block"></i>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                class="dropdown-menu absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl py-2 z-50">
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <div class="py-1.5">
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition rounded-lg mx-1.5">
                        <i class="fas fa-user-circle w-4 text-slate-400"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition rounded-lg mx-1.5">
                        <i class="fas fa-cog w-4 text-slate-400"></i>
                        <span>Account Settings</span>
                    </a>
                </div>
                <div class="border-t border-slate-100 my-1"></div>
                <div class="py-1">
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition w-full text-left rounded-lg mx-1.5">
                            <i class="fas fa-sign-out-alt w-4"></i>
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