<header class="admin-header h-16 flex items-center justify-between px-6 sticky top-0 z-40">
    {{-- Menu Toggle Button (All Screens) --}}
    <button id="sidebarToggle" class="group flex items-center justify-center w-10 h-10 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 focus:outline-none">
        <i data-lucide="align-left" class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"></i>
    </button>



    {{-- Header Actions --}}
    <div class="flex items-center gap-2">
        {{-- Notifications --}}
        <button class="header-action-btn p-2.5 rounded-xl transition">
            <i class="fas fa-bell text-lg"></i>
            <span class="notification-dot"></span>
        </button>

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

<style>
    [x-cloak] {
        display: none !important;
    }
</style>