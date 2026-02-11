<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ env('APP_NAME') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar-link:not(.active):hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu {
            animation: slideDown 0.2s ease-out;
        }

        .modal-active {
            overflow: hidden;
        }

        .modal-overlay {
            transition: opacity 0.15s ease;
        }

        .modal-container {
            transition: transform 0.15s ease, opacity 0.15s ease;
        }

        .hidden-modal {
            pointer-events: none;
            opacity: 0;
        }

        .hidden-modal .modal-container {
            transform: scale(0.95);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        @include('admin.layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.layouts.header')

            <main class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        sidebarToggle?.addEventListener('click', openSidebar);
        sidebarClose?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Close sidebar on window resize if in desktop mode
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });

        window.toggleModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            if (modal.classList.contains('hidden-modal')) {
                modal.classList.remove('hidden-modal');
                document.body.classList.add('modal-active');
            } else {
                modal.classList.add('hidden-modal');
                document.body.classList.remove('modal-active');
            }
        };

        document.addEventListener('click', function(e) {
            const closeBtn = e.target.closest('.modal-overlay .close');
            if (!closeBtn) return;

            const modal = closeBtn.closest('.modal-overlay');
            if (!modal) return;

            modal.classList.add('hidden-modal');
            document.body.classList.remove('modal-active');
        });
    </script>

    @stack('scripts')

    @include('partials.toast')
</body>

</html>