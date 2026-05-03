<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Secure Checkout | GecnoGuru Career Portal</title>
    <link rel="icon" type="image/jpg" href="/landing/assets/images/gecnoguru-favicon.jpg">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

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
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body
    x-data="{ 
        loaded: true, 
        darkMode: false, 
        sidebarToggle: false,
        userDropdown: false,
        page: 'sessions'
    }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
        setTimeout(() => loaded = false, 500);
    "
    :class="{'dark bg-gray-900': darkMode === true}"
    class="bg-gray-50 text-gray-800"
>
    <!-- Preloader -->
    <div x-show="loaded" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
    </div>

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside
            :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
            class="fixed left-0 top-0 z-[999] flex h-screen w-72 flex-col overflow-y-hidden bg-white dark:bg-gray-950 border-r border-gray-100 dark:border-gray-800 transition-transform duration-300 lg:static lg:translate-x-0"
        >
            <div class="flex items-center justify-start px-8 py-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="/landing/assets/images/gecnoguru-favicon.jpg" alt="Logo" class="h-8 w-8 rounded-lg flex-shrink-0" />
                    <span class="ml-2 font-bold text-lg dark:text-white">GecnoGuru</span>
                </a>
                <button @click="sidebarToggle = false" class="lg:hidden ml-auto text-gray-500 hover:text-brand-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <!-- Sidebar Content -->
            <div class="flex flex-col overflow-y-auto no-scrollbar">
                <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6">
                    <ul class="flex flex-col gap-1.5">
                        <li>
                            <a href="{{ route('home') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('whatsapp-redirect') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Build Resume
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('whatsapp-redirect') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Cover Letter
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('session-booking.index') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Book Session
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('whatsapp-redirect') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                Portfolio Website
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coming-soon') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Job Tracker
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coming-soon') }}" class="flex items-center gap-2.5 rounded-lg px-4 py-3 font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Land Dream Job
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            
            <header class="sticky top-0 z-[99] flex w-full bg-white dark:bg-gray-950 border-b border-gray-100 dark:border-gray-800 px-6 py-4">
                <div class="flex flex-grow items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarToggle = true" class="mr-4 lg:hidden text-gray-500 hover:text-brand-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <h1 class="text-xl font-bold dark:text-white">Secure Checkout</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- User Profile Dropdown -->
                        <div class="relative" @click.outside="userDropdown = false">
                            <button @click="userDropdown = !userDropdown" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                                <div class="w-10 h-10 rounded-full border-2 border-brand-500/20 bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden transition-colors">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                                <div class="hidden md:block text-left">
                                    <span class="block text-sm font-bold dark:text-white leading-tight">{{ Auth::user()->name }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="userDropdown" x-cloak x-transition class="absolute right-0 mt-3 w-48 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-xl py-2 z-[100]">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-brand-500 hover:text-white transition-colors">Profile Settings</a>
                                <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-6 md:p-8">
                <div class="max-w-2xl mx-auto">
                    
                    <div class="bg-white dark:bg-gray-950 rounded-3xl border border-gray-100 dark:border-gray-800 p-8 md:p-12 shadow-sm">
                        
                        <div class="text-center mb-10">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-brand-500/10 text-brand-500 mb-6 font-bold text-2xl">
                                1
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Final Step</h2>
                            <p class="text-gray-500">Review your booking and proceed to payment.</p>
                        </div>

                        @if(session('error'))
                            <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <div class="space-y-6 mb-10">
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800">
                                <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Booking Summary
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Service</span>
                                        <span class="font-bold dark:text-gray-300">{{ $booking->sessionType->name }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Date</span>
                                        <span class="font-bold dark:text-gray-300">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Time Slot</span>
                                        <span class="font-bold dark:text-gray-300">{{ $booking->booking_time }}</span>
                                    </div>
                                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-lg">
                                        <span class="font-bold dark:text-white">Amount Due</span>
                                        <span class="text-2xl font-black text-brand-500">₹{{ number_format($booking->amount, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('payment.initiate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="amount" value="{{ $booking->amount }}">
                            
                            <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-5 rounded-2xl transition-all shadow-xl shadow-brand-500/20 flex items-center justify-center gap-3 active:scale-95 group">
                                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                Pay Now
                            </button>
                        </form>
                        
                        <div class="mt-8 flex items-center justify-center gap-6 opacity-40 grayscale hover:grayscale-0 transition-all">
                             <!-- Payment Partner Icons Placeholder -->
                             <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Secure Payment Powered by PhonePe</span>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('session-booking.index') }}" class="text-sm font-bold text-gray-500 hover:text-brand-500 transition-colors">Cancel and go back</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
