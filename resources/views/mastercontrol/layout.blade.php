<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Master Control Dashboard') | GecnoGuru</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecf3ff',
                            100: '#dde9ff',
                            200: '#c2d6ff',
                            300: '#9cb9ff',
                            400: '#7592ff',
                            500: '#465fff',
                            600: '#3641f5',
                            700: '#2a31d8',
                            800: '#252dae',
                            900: '#262e89',
                            950: '#161950',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body
    x-data="{ darkMode: false, sidebarToggle: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')) || false; $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
    class="bg-gray-50 text-gray-800 dark:text-white/90"
>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
            class="fixed left-0 top-0 z-50 flex h-screen w-64 flex-col overflow-y-hidden border-r border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950 lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out"
        >
            <div class="flex items-center justify-between px-6 py-8">
                <a href="{{ route('mastercontrol.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ media_url('landing/assets/images/gecnoguru-favicon.jpg') }}" alt="Logo" class="h-10 w-10 rounded-lg" />
                    <span class="text-xl font-bold dark:text-white">MasterControl</span>
                </a>
            </div>

            <nav class="flex flex-col overflow-y-auto px-4 py-4 space-y-2">
                <a href="{{ route('mastercontrol.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('mastercontrol.dashboard') ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('mastercontrol.users') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('mastercontrol.users*') ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13.732 4c-.77-1.333-2.694-1.333-3.464 0L1.732 18c-.77 1.333.192 3 1.732 3h17.072c1.54 0 2.502-1.667 1.732-3L13.732 4z"></path></svg>
                    Users
                </a>
                <a href="{{ route('mastercontrol.bookings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('mastercontrol.bookings') ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Bookings
                </a>
                @if(Auth::user()->user_type === 'admin')
                <a href="{{ route('mastercontrol.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('mastercontrol.transactions') ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Transactions
                </a>
                @endif
            </nav>

            <div class="mt-auto p-4 border-t border-gray-100 dark:border-gray-800">
                <form action="{{ route('mastercontrol.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900">
            <!-- Header -->
            <header class="sticky top-0 z-40 flex w-full bg-white dark:bg-gray-950 border-b border-gray-100 dark:border-gray-800">
                <div class="flex flex-grow items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4 lg:hidden">
                        <button @click="sidebarToggle = !sidebarToggle" class="text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-4 ml-auto">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </button>
                        
                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200 dark:border-gray-800">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">Super Admin</p>
                            </div>
                            <img src="{{ media_url('landing/assets/images/user/owner.jpg') }}" alt="Admin" class="h-10 w-10 rounded-full border-2 border-brand-500" />
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
