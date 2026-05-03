@extends('mastercontrol.layout')

@section('title', 'Professional Details')

@section('content')
<div class="space-y-6" x-data="{ showDeleteModal: false }">
    <!-- Back & Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('mastercontrol.users') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-brand-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to User List
        </a>
        
        <div class="flex gap-3">
            @if(Auth::user()->user_type === 'admin')
            <!-- Block Action -->
            <form action="{{ route('mastercontrol.users.toggle-status', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-sm transition-all {{ $user->status == 0 ? 'bg-orange-500/10 text-orange-600 hover:bg-orange-500 hover:text-white' : 'bg-green-500/10 text-green-600 hover:bg-green-500 hover:text-white' }}">
                    @if($user->status == 0)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Block Professional
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Unblock Professional
                    @endif
                </button>
            </form>

            <!-- Delete Action -->
            <button 
                @click="showDeleteModal = true"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/10 text-red-600 font-bold text-sm hover:bg-red-500 hover:text-white transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Permanently Delete Data
            </button>
            @else
            <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-xl text-xs font-bold uppercase tracking-widest">Read Only Mode</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Basic Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 p-8 text-center shadow-sm">
                <div class="w-24 h-24 rounded-3xl bg-brand-500/10 text-brand-500 flex items-center justify-center font-bold text-3xl mx-auto mb-4 border-4 border-white dark:border-gray-900 shadow-xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-2xl font-bold dark:text-white">{{ $user->name }}</h2>
                <p class="text-gray-500 mb-6">{{ $user->email }}</p>
                
                <div class="grid grid-cols-2 gap-4 py-6 border-t border-gray-100 dark:border-gray-800">
                    <div class="text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                        @if($user->status == 0)
                            <span class="text-green-500 font-bold">Active</span>
                        @else
                            <span class="text-red-500 font-bold">Blocked</span>
                        @endif
                    </div>
                    <div class="text-center border-l border-gray-100 dark:border-gray-800">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Role</p>
                        <span class="dark:text-white font-bold capitalize">{{ $user->user_type }}</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-800 text-left space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Member Since</span>
                        <span class="font-medium dark:text-gray-300">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Last Updated</span>
                        <span class="font-medium dark:text-gray-300">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Activity Data -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bookings -->
            <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-bold text-lg dark:text-white">Session Bookings</h3>
                    <span class="px-3 py-1 bg-brand-500/10 text-brand-500 rounded-full text-xs font-bold">{{ $user->bookings->count() }} Total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-4">Session Type</th>
                                <th class="px-8 py-4">Date & Time</th>
                                <th class="px-8 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($user->bookings as $booking)
                            <tr>
                                <td class="px-8 py-4 font-bold text-sm dark:text-white">{{ $booking->sessionType->name }}</td>
                                <td class="px-8 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ $booking->booking_time }}
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $booking->status === 'completed' ? 'bg-green-500/10 text-green-500' : 'bg-orange-500/10 text-orange-500' }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-8 text-center text-gray-500 text-sm">No bookings found for this user.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transactions -->
            <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-bold text-lg dark:text-white">Transaction History</h3>
                    <span class="px-3 py-1 bg-green-500/10 text-green-600 rounded-full text-xs font-bold">{{ $user->transactions->count() }} Total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-4">Merchant ID</th>
                                <th class="px-8 py-4">Amount</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($user->transactions as $transaction)
                            <tr>
                                <td class="px-8 py-4 text-xs font-mono text-gray-500">{{ $transaction->merchant_transaction_id }}</td>
                                <td class="px-8 py-4 font-bold text-sm dark:text-white">₹{{ $transaction->amount }}</td>
                                <td class="px-8 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-8 text-center text-gray-500 text-sm">No transaction records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="showDeleteModal = false"
    >
        <div class="bg-white dark:bg-gray-950 w-full max-w-md rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 p-8 transform transition-all">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-500/10 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L1.732 18c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            
            <h3 class="text-xl font-bold text-center dark:text-white mb-2">Delete Full User Data?</h3>
            <p class="text-center text-gray-500 mb-8">
                Are you sure you want to delete <span class="font-bold text-gray-800 dark:text-gray-200">{{ $user->name }}</span>? This action will permanently remove their profile, bookings, and transaction history.
            </p>

            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 py-3 rounded-xl bg-gray-100 dark:bg-gray-800 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('mastercontrol.users.delete', $user->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 shadow-lg shadow-red-500/20 transition-colors">
                        Yes, Delete All
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
