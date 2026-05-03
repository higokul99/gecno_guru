@extends('mastercontrol.layout')

@section('title', 'Transactions')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">Transaction Logs</h1>
            <p class="text-gray-500">Full history of all PhonePe payments.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form action="{{ route('mastercontrol.transactions') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search Merchant ID..." 
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 focus:ring-2 focus:ring-brand-500 outline-none transition-all w-full md:w-64"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Merchant ID</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-bold dark:text-white">{{ $transaction->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-gray-500">{{ $transaction->merchant_transaction_id }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-sm dark:text-white">
                            ₹{{ $transaction->amount }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 text-right">
                            {{ $transaction->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No transactions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
