@extends('mastercontrol.layout')

@section('title', 'Session Bookings')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">Session Bookings</h1>
            <p class="text-gray-500">View and manage all 1-on-1 session appointments.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form action="{{ route('mastercontrol.bookings') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by user name..." 
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 focus:ring-2 focus:ring-brand-500 outline-none transition-all w-full md:w-64"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Session Type</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-bold dark:text-white">{{ $booking->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm dark:text-gray-400">{{ $booking->sessionType->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="dark:text-white font-medium">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
                                <p class="text-gray-500 text-xs">{{ $booking->booking_time }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-sm dark:text-white">
                            ₹{{ $booking->amount }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $booking->status === 'completed' ? 'bg-green-500/10 text-green-500' : 'bg-orange-500/10 text-orange-500' }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No bookings found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
