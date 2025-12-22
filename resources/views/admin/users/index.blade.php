@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <div class="w-[80%] mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Users</h1>
                <p class="text-slate-500 text-sm">Pesquise e gira todos os membros da plataforma.</p>
            </div>

            {{-- Pesquisa Simples e Eficaz --}}
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
        </div>

        <div class="border border-slate-100 rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse" id="usersTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">User Profile</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Role</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Visibility</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="usersTableBody">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/50 transition-colors user-row">
                            {{-- 1. Perfil --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $u->avatar }}" class="h-10 w-10 rounded-full object-cover bg-slate-100 border border-slate-50 shadow-sm">
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 leading-none">{{ $u->name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-1">{{ "@" . $u->username }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Contact --}}
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 truncate max-w-[180px]">{{ $u->email }}</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-tighter">Joined {{ $u->created_at?->format('M Y') }}</div>
                            </td>
                            
                            {{-- 3. Role (Texto Colorido Azul) --}}
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold {{ $u->is_admin ? 'text-[rgb(13,162,231)]' : 'text-slate-500' }}">
                                    {{ $u->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>

                            {{-- 4. Visibility --}}
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-medium text-slate-600">
                                    {{ $u->is_public ? 'Public' : 'Private' }}
                                </span>
                            </td>

                            {{-- 5. Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3 text-slate-400">
                                    <a href="{{ route('admin.users.show', $u) }}" class="hover:text-[rgb(13,162,231)] transition-colors" title="View Profile">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u) }}" class="hover:text-[rgb(13,162,231)] transition-colors" title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    
                                    @if(auth()->id() !== $u->id)
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove this user?')">
                                            @csrf @method('DELETE')
                                            <button class="hover:text-red-500 transition-colors" title="Delete">
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

        <div class="mt-8" id="paginationContainer">
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