@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <div class="w-[80%] mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Users</h1>
                <p class="text-slate-500 text-sm">Search and manage all platform members.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-3 items-center">
                <div class="relative w-full md:w-80">
                    <input 
                        type="text" 
                        id="userSearch" 
                        placeholder="Search name, email, role..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:border-[rgb(13,162,231)] transition-colors"
                    >
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[rgb(13,162,231)] text-white font-semibold rounded-lg shadow hover:bg-[rgb(10,140,200)] transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create User
                </a>
            </div>
        </div>

        <div class="border border-slate-100 rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse" id="usersTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">User Profile</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Role</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="usersTableBody">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/50 transition-colors user-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $u->avatar }}" class="h-10 w-10 rounded-full object-cover bg-slate-100 border border-slate-50 shadow-sm {{ $u->is_blocked ? 'grayscale' : '' }}">
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 leading-none">{{ $u->name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-1">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-[10px] font-bold uppercase px-2 py-1 rounded {{ $u->is_admin ? 'bg-blue-50 text-[rgb(13,162,231)]' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $u->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @if(auth()->id() !== $u->id)
                                        <form action="{{ route('admin.users.toggle-block', $u) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="group flex items-center gap-2 focus:outline-none">
                                                <div class="relative w-10 h-5 transition-colors duration-200 ease-linear rounded-full {{ !$u->is_blocked ? 'bg-green-500' : 'bg-red-400' }}">
                                                    <div class="absolute left-1 top-1 w-3 h-3 transition-transform duration-200 ease-linear transform bg-white rounded-full {{ !$u->is_blocked ? 'translate-x-5' : 'translate-x-0' }}"></div>
                                                </div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider {{ !$u->is_blocked ? 'text-green-600' : 'text-red-500' }}">
                                                    {{ !$u->is_blocked ? 'Active' : 'Blocked' }}
                                                </span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-300 uppercase italic">System</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3 text-slate-400">
                                    <a href="{{ route('admin.users.show', $u) }}" class="hover:text-[rgb(13,162,231)] transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u) }}" class="hover:text-[rgb(13,162,231)] transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    
                                    @if(auth()->id() !== $u->id)
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Remove user permanently?')">
                                            @csrf @method('DELETE')
                                            <button class="hover:text-red-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    document.getElementById('userSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>
@endsection