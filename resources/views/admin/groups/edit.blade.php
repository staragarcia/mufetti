@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <div class="w-[80%] mx-auto">
        
        <div class="max-w-xl mx-auto mb-8">
            <a href="{{ route('admin.groups.index') }}" class="text-[10px] font-black text-slate-400 hover:text-[rgb(13,162,231)] flex items-center gap-2 uppercase tracking-[0.15em] transition-colors mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Back to Groups
            </a>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Edit Group</h1>
            <p class="text-slate-400 text-sm mt-1">Atualiza as definições de privacidade e detalhes do grupo.</p>
        </div>

        <div class="max-w-xl mx-auto bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">
            <form action="{{ route('admin.groups.update', $group) }}" method="POST" class="space-y-6">
                @csrf 
                @method('PUT')

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Group Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ $group->name }}" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 outline-none focus:border-[rgb(13,162,231)] focus:bg-white transition-all font-medium"
                    >
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Description</label>
                    <textarea 
                        name="description" 
                        rows="4" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 outline-none focus:border-[rgb(13,162,231)] focus:bg-white transition-all font-medium resize-none"
                    >{{ $group->description }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Privacy Mode</label>
                    <div class="relative">
                        <select 
                            name="is_public" 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 appearance-none outline-none focus:border-[rgb(13,162,231)] focus:bg-white transition-all font-bold cursor-pointer"
                        >
                            <option value="1" {{ $group->is_public ? 'selected' : '' }}>Public (Anyone can join)</option>
                            <option value="0" {{ !$group->is_public ? 'selected' : '' }}>Private (Requires approval)</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" class="w-full bg-[rgb(13,162,231)] text-white py-3.5 rounded-xl text-sm font-bold hover:brightness-105 transition-all shadow-lg shadow-[rgb(13,162,231)]/10">
                        Update Settings
                    </button>
                    <a href="{{ route('admin.groups.index') }}" class="w-full text-center py-2 text-slate-400 hover:text-slate-600 text-xs font-bold transition-colors">
                        Cancel Changes
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection