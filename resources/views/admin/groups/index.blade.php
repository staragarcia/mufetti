@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <div class="w-[80%] mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Groups</h1>
                <p class="text-slate-500 text-sm">Gerencie os grupos da comunidade e pedidos de adesão.</p>
            </div>

            <div class="relative w-full md:w-80">
                <input 
                    type="text" 
                    id="groupSearch" 
                    placeholder="Search groups..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:border-[rgb(13,162,231)] transition-colors"
                >
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="border border-slate-200 rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse" id="groupsTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Group</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Privacy</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Pending</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="groupsTableBody">
                    @foreach($groups as $group)
                        <tr class="hover:bg-slate-50/50 transition-colors group-row">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-700">{{ $group->name }}</div>
                                <div class="text-xs text-slate-400">{{ $group->member_count }} members</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $group->ownerUser->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-medium {{ $group->is_public ? 'text-[rgb(13,162,231)]' : 'text-slate-400' }}">
                                    {{ $group->is_public ? 'Public' : 'Private' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!$group->is_public && $group->join_requests_count > 0)
                                    <a href="{{ route('admin.groups.requests', $group) }}" class="text-xs font-bold text-orange-500 hover:underline">
                                        {{ $group->join_requests_count }} requests
                                    </a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4 text-slate-400">
                                    {{-- Botões de texto/ícone simples --}}
                                    <a href="{{ route('admin.groups.edit', $group) }}" class="hover:text-[rgb(13,162,231)] transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.groups.members', $group) }}" class="hover:text-[rgb(13,162,231)] transition-colors" title="Members">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Delete group?')">
                                        @csrf @method('DELETE')
                                        <button class="hover:text-red-500 transition-colors">
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

        <div class="mt-6">
            {{ $groups->links() }}
        </div>
    </div>
</div>

<script>
    document.getElementById('groupSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.group-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>
@endsection