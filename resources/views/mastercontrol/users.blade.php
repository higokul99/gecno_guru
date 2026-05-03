@extends('mastercontrol.layout')

@section('title', 'User Management')

@section('content')
<div class="space-y-6" x-data="{ 
    showDeleteModal: false, 
    userToDelete: null, 
    userNameToDelete: '',
    confirmDelete(id, name) {
        this.userToDelete = id;
        this.userNameToDelete = name;
        this.showDeleteModal = true;
    }
}">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">User Management</h1>
            <p class="text-gray-500">Manage, block, or delete professionals from the platform.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form action="{{ route('mastercontrol.users') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search users..." 
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 focus:ring-2 focus:ring-brand-500 outline-none transition-all w-full md:w-64"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Professional</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Joined Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-500/10 text-brand-500 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('mastercontrol.users.show', $user->id) }}" class="font-bold dark:text-white hover:text-brand-500 transition-colors">{{ $user->name }}</a>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status == 0)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-green-500/10 text-green-500">Active</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-500/10 text-red-500">Blocked</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm dark:text-gray-400 capitalize">{{ $user->user_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a 
                                href="{{ route('mastercontrol.users.show', $user->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold text-xs hover:bg-brand-500 hover:text-white transition-all shadow-theme-xs"
                            >
                                Take Action
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No users found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
