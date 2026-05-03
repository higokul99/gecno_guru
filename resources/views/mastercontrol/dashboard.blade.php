@extends('mastercontrol.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold dark:text-white">Dashboard Overview</h1>
        <p class="text-gray-500">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-950 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-500/10 rounded-xl text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13.732 4c-.77-1.333-2.694-1.333-3.464 0L1.732 18c-.77 1.333.192 3 1.732 3h17.072c1.54 0 2.502-1.667 1.732-3L13.732 4z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold dark:text-white">{{ $stats['total_users'] }}</h3>
            <p class="text-gray-500 text-sm font-medium">Total Registered Users</p>
        </div>

        <div class="bg-white dark:bg-gray-950 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-500/10 rounded-xl text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold dark:text-white">{{ $stats['total_bookings'] }}</h3>
            <p class="text-gray-500 text-sm font-medium">Total Session Bookings</p>
        </div>

        <div class="bg-white dark:bg-gray-950 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-50 dark:bg-green-500/10 rounded-xl text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold dark:text-white">{{ $stats['total_transactions'] }}</h3>
            <p class="text-gray-500 text-sm font-medium">Processed Transactions</p>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="font-bold text-lg dark:text-white">Recently Joined Professionals</h2>
            <a href="#" class="text-brand-500 text-sm font-bold hover:underline">View All Users</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-gray-400 tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-gray-400 tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-gray-400 tracking-wider">Joined Date</th>
                        <th class="px-6 py-4 text-xs uppercase font-bold text-gray-400 tracking-wider">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($stats['recent_users'] as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-500/10 text-brand-500 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <span class="font-medium dark:text-white">{{ $user->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $user->user_type === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                                {{ $user->user_type }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
