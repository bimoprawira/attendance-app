<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Attendance App'))</title>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @auth
            <!-- Navigation -->
            <nav id="main-navbar" class="sticky top-0 z-50 transition-all duration-300 bg-white/90 shadow-lg border-b border-white/40">
                <div class="w-full px-0">
                    <div class="relative flex items-center h-20 w-full">
                        <!-- App Name (Logo) Flush Left -->
                        <div class="absolute left-0 top-0 h-full flex items-center" style="padding-left:0;">
                            <a href="{{ Auth::guard('admin')->check() ? route('admin.dashboard') : route('dashboard') }}"
                               class="text-2xl font-extrabold text-indigo-600 tracking-tight flex items-center gap-2 ml-2 sm:ml-4">
                                <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" fill="#6366f1"/></svg>
                                ABSEN.IN
                            </a>
                        </div>
                        <!-- Navigation Links Center -->
                        <div class="flex-1 flex justify-center">
                            <div class="hidden md:flex space-x-8">
                                @if(Auth::guard('admin')->check())
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.employees') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.employees') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Karyawan
                                    </a>
                                    <a href="{{ route('admin.attendance.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.attendance.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Kehadiran
                                    </a>
                                    <a href="{{ route('admin.leaves.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.leaves.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Permintaan Cuti
                                    </a>
                                    <a href="{{ route('admin.gaji.index') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.gaji.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Gaji Karyawan
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('presence.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('presence.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Kehadiran
                                    </a>
                                    <a href="{{ route('leaves.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('leaves.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Pengajuan Cuti
                                    </a>
                                    <a href="{{ route('gaji.index') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('gaji.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-indigo-700 hover:border-gray-300' }} text-base font-medium transition">
                                        Gaji
                                    </a>
                                @endif
                            </div>
                        </div>
                        <!-- User Menu Flush Right -->
                        <div class="absolute right-0 top-0 h-full flex items-center" style="padding-right:0;">
                            @if(!Auth::guard('admin')->check())
                                <div class="flex items-center mr-4">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-bold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="ml-3 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                </div>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-2 text-base font-medium rounded-lg text-white bg-gray-800 hover:bg-black focus:outline-none transition mr-2 sm:mr-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <script>
                // Sticky navbar with glassmorphism effect and shadow on scroll
                document.addEventListener('DOMContentLoaded', function() {
                    const navbar = document.getElementById('main-navbar');
                    // Set initial state
                    navbar.classList.add('bg-white/90', 'shadow-lg');
                    navbar.classList.remove('bg-white/60', 'backdrop-blur-lg');
                    window.addEventListener('scroll', function() {
                        if (window.scrollY > 10) {
                            navbar.classList.remove('bg-white/90', 'shadow-lg');
                            navbar.classList.add('bg-white/60', 'backdrop-blur-lg');
                        } else {
                            navbar.classList.add('bg-white/90', 'shadow-lg');
                            navbar.classList.remove('bg-white/60', 'backdrop-blur-lg');
                        }
                    });
                });
            </script>
        @endauth

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
