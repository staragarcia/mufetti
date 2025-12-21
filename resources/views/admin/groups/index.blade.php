@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Groups Management</h1>
                <p class="text-muted-foreground text-sm mt-1">Search and manage community groups and requests.</p>
            </div>

            {{-- Pesquisa Chique --}}
            <div class="relative w-full md:w-96">
                <input 
                    type="text" 
                    id="groupSearch" 
                    placeholder="Search group name, owner or privacy..." 
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
            <table class="w-full text-left border-collapse" id="groupsTable">
                <thead>
                    <tr class="bg-muted/30 border-b border-border">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider">Group</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider">Owner</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-center">Privacy</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-center">Requests</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm" id="groupsTableBody">
                    @foreach($groups as $group)
                        <tr class="hover:bg-muted/5 transition-colors group-row">
                            <td class="px-6 py-4">
                                <div class="font-bold text-foreground">{{ $group->name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $group->member_count }} members</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-foreground">{{ $group->ownerUser->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-foreground capitalize">
                                    {{ $group->is_public ? 'Public' : 'Private' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!$group->is_public && $group->join_requests_count > 0)
                                    <a href="{{ route('admin.groups.requests', $group) }}" class="inline-flex items-center gap-1 text-orange-600 font-bold hover:underline">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                        </span>
                                        {{ $group->join_requests_count }} Pending
                                    </a>
                                @else
                                    <span class="text-muted-foreground/50">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    {{-- Editar --}}
                                    <a href="{{ route('admin.groups.edit', $group) }}" class="p-2 hover:bg-muted rounded-lg text-muted-foreground hover:text-primary transition-all" title="Edit Group">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    {{-- Membros --}}
                                    <a href="{{ route('admin.groups.members', $group) }}" class="p-2 hover:bg-muted rounded-lg text-muted-foreground hover:text-blue-500 transition-all" title="Manage Members">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </a>
                                    {{-- Eliminar --}}
                                    <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this group forever?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 hover:bg-red-50 rounded-lg text-muted-foreground hover:text-red-600 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6" id="paginationContainer">
            {{ $groups->links() }}
        </div>
    </div>
</div>

<script>
    // Pesquisa Instantânea para Grupos
    document.getElementById('groupSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.group-row');
        const pagination = document.getElementById('paginationContainer');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });

        if(pagination) pagination.style.display = term.length > 0 ? 'none' : '';
    });
</script>
@endsection