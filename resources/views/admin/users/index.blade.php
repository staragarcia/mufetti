@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Users</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">View Reports</a>
                    <p class="text-muted-foreground text-sm mt-1">Search and manage platform members.</p>
                </div>
    </div>

            {{-- Pesquisa Chique --}}
            <div class="relative w-full md:w-96">
                <input 
                    type="text" 
                    id="userSearch" 
                    placeholder="Search name, email, role..." 
                    class="w-full bg-card border border-border rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 transition-all shadow-sm"
                >
                <div class="absolute left-3 top-3 text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse" id="usersTable">
                <thead>
                    <tr class="bg-muted/30 border-b border-border">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider">User Profile</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-center">Role</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-center">Visibility</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm" id="usersTableBody">
                    @foreach($users as $u)
                        <tr class="hover:bg-muted/5 transition-colors user-row">
                            {{-- 1. Perfil --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $u->avatar }}" class="h-10 w-10 rounded-full border border-border object-cover bg-muted">
                                    <div>
                                        <div class="font-bold text-foreground leading-none">{{ $u->name }}</div>
                                        <div class="text-xs text-muted-foreground mt-1">{{ "@" . $u->username }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Contact (Movido para aqui) --}}
                            <td class="px-6 py-4 text-muted-foreground">
                                <div class="truncate max-w-[180px]">{{ $u->email }}</div>
                                <div class="text-[10px] uppercase">Joined {{ $u->created_at?->format('M Y') }}</div>
                            </td>
                            
                            {{-- 3. Role (Texto simples) --}}
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-foreground">
                                    {{ $u->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>

                            {{-- 4. Visibility (Texto simples) --}}
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-foreground">
                                    {{ $u->is_public ? 'Public' : 'Private' }}
                                </span>
                            </td>

                            {{-- 5. Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $u) }}" class="p-2 hover:bg-muted rounded-lg text-muted-foreground hover:text-primary transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u) }}" class="p-2 hover:bg-muted rounded-lg text-muted-foreground hover:text-blue-500 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    
                                    @if(auth()->id() !== $u->id)
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove this user?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 hover:bg-red-50 rounded-lg text-muted-foreground hover:text-red-600 transition-all">
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
        <div class="mt-6" id="paginationContainer">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    document.getElementById('userSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');
        const pagination = document.getElementById('paginationContainer');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });

        if(pagination) pagination.style.display = term.length > 0 ? 'none' : '';
    });
</script>
@endsection