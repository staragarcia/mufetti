@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-5xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">My Groups</h1>
                <p class="text-muted-foreground mt-1">Groups you belong to.</p>
            </div>

            @if(!auth()->user()->is_blocked)
                <a href="{{ route('groups.create') }}"
                   class="bg-primary text-primary-foreground font-semibold py-2 px-4 rounded-md hover:bg-primary/90 transition">
                    Create Group
                </a>
            @else
                <span class="bg-red-300 text-primary-foreground font-semibold py-2 px-4 rounded-md cursor-not-allowed" title="Account Restricted">
                    Create Group (Blocked)
                </span>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="flex">
                <a href="{{ route('groups.showUserGroups', ['tab' => 'owned']) }}"
                   class="flex-1 px-6 py-3 text-center font-medium transition-all duration-200 border-b-2
                          {{ ($activeTab ?? 'owned') === 'owned'
                              ? 'border-blue-500 text-blue-600 bg-blue-50'
                              : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    My Groups
                </a>

                <a href="{{ route('groups.showUserGroups', ['tab' => 'member']) }}"
                   class="flex-1 px-6 py-3 text-center font-medium transition-all duration-200 border-b-2
                          {{ ($activeTab ?? 'owned') === 'member'
                              ? 'border-blue-500 text-blue-600 bg-blue-50'
                              : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    Groups I am member
                </a>
            </div>
        </div>

        @if(($activeTab ?? 'owned') === 'owned')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($groupsOwned as $group)
                    <a href="{{ route('groups.show', $group->id) }}"
                       class="bg-card border border-border rounded-lg p-6 shadow-sm space-y-3 block hover:bg-muted/40 transition">
                        <h2 class="text-xl font-semibold text-foreground">{{ $group->name }}</h2>
                        <p class="text-muted-foreground text-sm">{{ $group->description ?? 'No description available.' }}</p>
                        <div class="flex justify-between items-center text-sm text-muted-foreground pt-2">
                            <span>{{ $group->member_count }} members</span>
                            <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-muted-foreground">You do not own any groups yet.</p>
                @endforelse
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($groupsNotOwned as $group)
                    <a href="{{ route('groups.show', $group->id) }}"
                       class="bg-card border border-border rounded-lg p-6 shadow-sm space-y-3 block hover:bg-muted/40 transition">
                        <h2 class="text-xl font-semibold text-foreground">{{ $group->name }}</h2>
                        <p class="text-muted-foreground text-sm">{{ $group->description ?? 'No description available.' }}</p>
                        <div class="flex justify-between items-center text-sm text-muted-foreground pt-2">
                            <span>{{ $group->member_count }} members</span>
                            <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-muted-foreground">You are not a member of any group yet.</p>
                @endforelse
            </div>
        @endif

    </div>
</div>

{{-- Secção de Importação --}}
<div class="max-w-5xl mx-auto px-4 pb-10">
    @if(!auth()->user()->is_blocked)
        <form action="{{ route('albums.import') }}" method="POST">
            @csrf
            <input type="hidden" name="musicbrainz_id" value="6e335887-60ba-38f0-95af-fae7774336bf">
            <button type="submit" class="text-sm text-primary hover:underline">Import Album</button>
        </form>
    @else
        <p class="text-xs text-red-400 italic">Importing is disabled for restricted accounts.</p>
    @endif
</div>

@endsection