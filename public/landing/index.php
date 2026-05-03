<?php
/**
 * GecnoGuru — Standalone Auth Page (Login/Register)
 * ────────────────────────────────────────────────
 * This page replaces the old landing page as the main entry point.
 * It uses a premium template structure for a premium UI.
 */

define('APP_PATH', '/main/prod');
define('SITE_NAME', 'GecnoGuru Career Portal');
define('WHATSAPP', 'https://wa.me/918547349691');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign In | GecnoGuru Career Portal</title>
    <link rel="icon" type="image/jpg" href="assets/images/gecnoguru-favicon.jpg">
    
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
                        },
                        error: {
                            500: '#f04438',
                        }
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    fontSize: {
                        'title-sm': ['30px', '38px'],
                        'title-md': ['36px', '44px'],
                    },
                    boxShadow: {
                        'theme-xs': '0px 1px 2px 0px rgba(16, 24, 40, 0.05)',
                    }
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
        authMode: 'signin',
        showPassword: false
    }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
        setTimeout(() => loaded = false, 500);
    "
    :class="{'dark bg-gray-900': darkMode === true}"
    class="bg-gray-50"
>
    <!-- ===== Preloader Start ===== -->
    <div
        x-show="loaded"
        class="fixed left-0 top-0 z-[9999] flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
    >
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="relative min-h-screen flex flex-col justify-center lg:flex-row overflow-hidden">
        
        <!-- Form Section -->
        <div class="flex flex-col flex-1 w-full lg:w-1/2 p-6 sm:p-12 z-10 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800">
            <div class="w-full max-w-md mx-auto">
                <!-- Branding / Back Link -->
                <!-- Form Section -->

                <!-- SIGN IN FORM -->
                <div x-show="authMode === 'signin'" x-cloak x-transition>
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">GecnoGuru Career Portal</h1>
                        <p class="text-gray-500 dark:text-gray-400">Welcome back! Please enter your details.</p>
                    </div>

                    <form action="<?= APP_PATH ?>/login" method="POST" class="space-y-6">
                        <!-- CSRF Token (since it's PHP, we might need to handle this manually if session exists) -->
                        <input type="hidden" name="_token" value="<?= isset($_COOKIE['XSRF-TOKEN']) ? $_COOKIE['XSRF-TOKEN'] : '' ?>">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Email*</label>
                            <input type="email" name="email" required placeholder="name@company.com" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all" />
                        </div>

                        <div>
                            <div class="flex justify-between mb-1.5">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Password*</label>
                                <a href="#" class="text-sm text-brand-500 hover:text-brand-600 font-medium">Forgot password?</a>
                            </div>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showPassword" class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    <svg x-show="showPassword" class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26a4 4 0 015.656 5.657l-5.656-5.657zm4.35 9.124l-3.32-3.32a4 4 0 004.764-4.764l3.32 3.32a9.977 9.977 0 01-4.764 4.764zm-7.662-7.662L3.06 6.417A9.974 9.974 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.664-.105 2.447-.305L10.74 15.01a4 4 0 01-6.104-6.104z" clip-rule="evenodd"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="remember" class="w-4 h-4 text-brand-500 border-gray-300 rounded focus:ring-brand-500" />
                            <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Keep me logged in</label>
                        </div>

                        <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3.5 rounded-lg transition-colors shadow-lg shadow-brand-500/20">
                            Sign In
                        </button>
                    </form>

                    <div class="mt-8 text-center pt-6 border-t border-gray-100 dark:border-gray-800">
                        <p class="text-gray-600 dark:text-gray-400">
                            Don't have an account? 
                            <button @click="authMode = 'signup'" class="text-brand-500 hover:underline font-bold">Sign Up Free</button>
                        </p>
                    </div>
                </div>

                <!-- SIGN UP FORM -->
                <div x-show="authMode === 'signup'" x-cloak x-transition>
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">GecnoGuru Career Portal</h1>
                        <p class="text-gray-500 dark:text-gray-400">Start your career journey with GecnoGuru Career Portal.</p>
                    </div>

                    <form action="<?= APP_PATH ?>/register" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Full Name*</label>
                            <input type="text" name="name" required placeholder="John Doe" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Email*</label>
                            <input type="email" name="email" required placeholder="john@example.com" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Password*</label>
                                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Confirm Password*</label>
                                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all" />
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3.5 rounded-lg transition-colors shadow-lg shadow-brand-500/20">
                            Create Account
                        </button>
                    </form>

                    <div class="mt-8 text-center pt-6 border-t border-gray-100 dark:border-gray-800">
                        <p class="text-gray-600 dark:text-gray-400">
                            Already have an account? 
                            <button @click="authMode = 'signin'" class="text-brand-500 hover:underline font-bold">Sign In</button>
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Visual / Graphic Section -->
        <div class="hidden lg:flex flex-col flex-1 bg-brand-950 items-center justify-center relative">
            <!-- Decorative Grid Shapes (using the copied assets) -->
            <div class="absolute right-0 top-0 -z-1 w-full max-w-[450px]">
                <img src="assets/images/shape/grid-01.svg" alt="grid" class="opacity-20" />
            </div>
            <div class="absolute bottom-0 left-0 -z-1 w-full max-w-[450px] rotate-180">
                <img src="assets/images/shape/grid-01.svg" alt="grid" class="opacity-20" />
            </div>
            
            <div class="z-10 text-center max-w-md px-12">
                <div class="mb-8 text-center">
                    <img src="assets/images/gecnoguru-favicon.jpg" alt="Logo" class="mx-auto h-16 w-16 rounded-2xl shadow-lg border border-white/10" />
                </div>
                <h2 class="text-4xl font-bold text-white mb-4">Master Your Career with AI</h2>
                <p class="text-brand-200 text-lg">Join GecnoGuru Career Portal and access premium tools for resumes, portfolios, and expert career coaching.</p>
                
                <!-- 
                <div class="mt-12 flex justify-center space-x-4">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-brand-950" src="assets/images/user/user-01.jpg" alt="user" />
                        <img class="w-10 h-10 rounded-full border-2 border-brand-950" src="assets/images/user/user-02.jpg" alt="user" />
                        <img class="w-10 h-10 rounded-full border-2 border-brand-950" src="assets/images/user/user-03.jpg" alt="user" />
                    </div>
                    <div class="text-white text-sm">
                        <span class="block font-bold">500+ Professionals</span>
                        <span class="text-brand-300">already joined this week</span>
                    </div>
                </div>
                -->
                <!-- FUTURE_USE: Social proof section with user avatars and join count -->
            </div>

            <!-- Dark Mode Toggle -->
            <button
                @click="darkMode = !darkMode"
                class="absolute bottom-10 right-10 flex h-14 w-14 items-center justify-center rounded-full bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-xl hover:scale-110 transition-transform"
            >
                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
        </div>
    </div>
    <!-- ===== Page Wrapper End ===== -->
</body>
</html>
