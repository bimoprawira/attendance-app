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
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ Auth::guard('admin')->check() ? route('admin.dashboard') : route('dashboard') }}"
                                   class="text-xl font-bold text-blue-600">
                                    Aplikasi Kehadiran
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                @if(Auth::guard('admin')->check())
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Beranda
                                    </a>
                                    <a href="{{ route('admin.employees') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.employees') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Karyawan
                                    </a>
                                    <a href="{{ route('admin.attendance.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.attendance.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Kehadiran
                                    </a>
                                    <a href="{{ route('admin.leaves.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.leaves.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Permintaan Cuti
                                    </a>
                                    <a href="{{ route('admin.gaji.index') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.gaji.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Gaji
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Beranda
                                    </a>
                                    <a href="{{ route('presence.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('presence.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Kehadiran
                                    </a>
                                    <a href="{{ route('leaves.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('leaves.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Permintaan Cuti
                                    </a>
                                    <a href="{{ route('gaji.index') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('gaji.index') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Gaji
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center">
                            @if(!Auth::guard('admin')->check())
                                <div class="flex items-center mr-4">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-medium">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="ml-3 text-gray-700">{{ Auth::user()->name }}</span>
                                </div>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none transition duration-150 ease-in-out">
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
        @endauth

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
