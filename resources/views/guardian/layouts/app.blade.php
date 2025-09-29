<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Panel Wali Murid' }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background-color: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
        }
        .sidebar-link.active {
            background-color: #0ea5e9;
            color: white;
            box-shadow: 0 4px 14px 0 rgba(14, 165, 233, 0.3);
        }
        .sidebar-link.active .sidebar-icon {
            color: white;
        }
        .sidebar-mobile {
            transition: transform 0.3s ease-in-out;
        }
        .mobile-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        .mobile-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-primary-50">

    <div class="antialiased bg-gray-50">
        <nav class="bg-white border-b border-gray-200 px-4 py-2.5 fixed left-0 right-0 top-0 z-50">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex justify-start items-center">
                    <button id="mobileMenuBtn" aria-controls="sidebar" type="button" class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <a href="{{ route('guardian.dashboard') }}" class="flex items-center justify-between mr-4">
                        <div class="h-8 w-8 mr-3 bg-teal-600 rounded-lg flex items-center justify-center text-white font-bold">W</div>
                        <span class="self-center text-2xl font-semibold whitespace-nowrap">Panel Wali</span>
                    </a>
                </div>
                <div class="flex items-center lg:order-2">
                    @auth
                    <div x-data="{ dropdownOpen: false }" class="relative" x-cloak>
                        <button @click="dropdownOpen = !dropdownOpen" type="button" class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                        <!-- Dropdown menu -->
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 z-50 my-4 w-56 text-base list-none bg-white rounded-lg shadow-lg" id="user-dropdown">
                            <div class="px-4 py-3">
                                <span class="block text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                                <span class="block text-sm text-gray-500 truncate">{{ Auth::user()->guardian_phone }}</span>
                            </div>
                            <ul class="py-1" aria-labelledby="user-menu-button">
                                <li>
                                    <a href="{{ route('guardian.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                                </li>
                            </ul>
                             <ul class="py-1" aria-labelledby="user-menu-button">
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                                    <form id="logout-form" action="{{ route('guardian.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 lg:translate-x-0" aria-label="Sidebar">
            <div class="px-3 py-4 overflow-y-auto h-full bg-white">
                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="{{ route('guardian.dashboard') }}" class="sidebar-link flex items-center p-3 rounded-lg group {{ request()->routeIs('guardian.dashboard') || request()->routeIs('guardian.students.show') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-primary-500 sidebar-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z"></path></svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guardian.profile.show') }}" class="sidebar-link flex items-center p-3 rounded-lg group {{ request()->routeIs('guardian.profile.show') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-primary-500 sidebar-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                            <span class="ms-3">Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 z-30 bg-gray-900/50 opacity-0 pointer-events-none lg:hidden"></div>

        <div class="p-4 lg:ml-64">
            <div class="mt-14">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('opacity-0');
                overlay.classList.toggle('pointer-events-none');
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when clicking links on mobile
            const sidebarLinks = sidebar.querySelectorAll('a[href]');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024 && !sidebar.classList.contains('-translate-x-full')) {
                        toggleSidebar();
                    }
                });
            });
        });
    </script>
</body>
</html>

