<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Panel Siswa' }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        }

        /* Sidebar animations */
        .sidebar-link {
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar-link:hover::before {
            left: 100%;
        }

        .sidebar-link:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(99, 102, 241, 0.15));
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: white;
            border-radius: 2px 0 0 2px;
        }

        /* Mobile sidebar */
        @media (max-width: 1023px) {
            .mobile-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .mobile-sidebar.open {
                transform: translateX(0);
            }
        }

        @media (min-width: 1024px) {
            .mobile-sidebar {
                transform: translateX(0) !important;
                position: relative !important;
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Glass effect */
        .glass-effect {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Header animation */
        .header-fade-in {
            animation: fadeInDown 0.6s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Content animation */
        .content-fade-in {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .sidebar-mobile-overlay {
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 antialiased">
    <div class="flex h-screen bg-gradient-to-br from-slate-50 to-slate-100">

        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden sidebar-mobile-overlay"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:relative lg:translate-x-0 w-72 bg-gradient-to-b from-slate-800 via-slate-900 to-slate-800 text-white flex-shrink-0 flex flex-col z-50 lg:z-auto mobile-sidebar shadow-2xl lg:shadow-none">

            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-700 text-xl font-bold flex-shrink-0 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-indigo-700/20"></div>
                <div class="relative flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-lg tracking-wide">Panel Siswa</span>
                </div>

                <!-- Close button for mobile -->
                <button id="closeSidebar" class="absolute right-4 lg:hidden p-2 hover:bg-white/10 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 flex-1 px-4 custom-scrollbar overflow-y-auto">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('student.dashboard') }}" class="sidebar-link flex items-center py-4 px-4 rounded-xl transition-all duration-300 text-white group {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium">Dashboard</div>
                            <div class="text-slate-300 text-xs mt-1">Ringkasan aktivitas</div>
                        </div>
                    </a>

                    <!-- Attendance History -->
                    <a href="{{ route('student.attendances.index') }}" class="sidebar-link flex items-center py-4 px-4 rounded-xl transition-all duration-300 text-white group {{ request()->routeIs('student.attendances.index') ? 'active' : '' }}">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium">Riwayat Absensi</div>
                            <div class="text-slate-300 text-xs mt-1">Data kehadiran Anda</div>
                        </div>
                    </a>

                    <!-- Permits -->
                    <a href="{{ route('student.permits.index') }}" class="sidebar-link flex items-center py-4 px-4 rounded-xl transition-all duration-300 text-white group {{ request()->routeIs('student.permits.*') ? 'active' : '' }}">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium">Pengajuan Izin</div>
                            <div class="text-slate-300 text-xs mt-1">Kelola izin Anda</div>
                        </div>
                    </a>
                </div>
            </nav>

            <!-- Profile Section at Bottom -->
            <div class="p-4 border-t border-slate-700/50">
                <a href="{{ route('student.profile.show') }}" class="sidebar-link flex items-center py-4 px-4 rounded-xl transition-all duration-300 text-white group {{ request()->routeIs('student.profile.show') ? 'active' : '' }}">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center mr-4 group-hover:shadow-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">Profil Saya</div>
                        <div class="text-slate-300 text-xs mt-1">Pengaturan akun</div>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">

            <!-- Top Header -->
            <header class="h-20 bg-white/80 glass-effect border-b border-slate-200/60 flex items-center justify-between px-6 lg:px-8 shadow-sm header-fade-in relative z-30">

                <!-- Mobile Menu Button & Page Title -->
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuBtn" class="lg:hidden p-2 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-xl font-bold text-slate-800">{{ $pageTitle ?? 'Dashboard' }}</h1>
                        <p class="text-sm text-slate-500 mt-1">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                    <!-- Notification Bell -->
                    <button class="relative p-2 hover:bg-slate-100 rounded-xl transition-colors group">
                        <svg class="w-6 h-6 text-slate-600 group-hover:text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:block text-right">
                            <div class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-slate-500">Siswa Aktif</div>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-medium">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('student.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-red-50 text-slate-700 hover:text-red-600 rounded-xl transition-all duration-200 group">
                            <svg class="w-4 h-4 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-sm font-medium hidden sm:block">Keluar</span>
                        </button>
                    </form>
                    @endauth
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto custom-scrollbar content-fade-in">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const closeSidebar = document.getElementById('closeSidebar');

            function openSidebar() {
                sidebar.classList.add('open');
                mobileOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebarFn() {
                sidebar.classList.remove('open');
                mobileOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            mobileMenuBtn.addEventListener('click', openSidebar);
            mobileOverlay.addEventListener('click', closeSidebarFn);
            closeSidebar.addEventListener('click', closeSidebarFn);

            // Close sidebar on window resize if it's large screen
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebarFn();
                }
            });
        });
    </script>
</body>
</html>
