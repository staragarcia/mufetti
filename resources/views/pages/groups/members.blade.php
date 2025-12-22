@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-foreground mb-2">Members</h1>
            <p class="text-muted-foreground">
                Users in "{{ $group->name }}" ({{ $members->total() }})
            </p>
        </div>

        @if($members->isEmpty())
            <div class="text-center py-12">
                <p class="text-muted-foreground text-lg">No members in this group yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($members as $member)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.showOther', $member) }}" class="shrink-0">
                                <img
                                    src="{{ $member->avatar }}"
                                    alt="{{ $member->name }}"
                                    class="h-12 w-12 rounded-full border-2 border-gray-200 object-cover hover:border-blue-600 transition-colors"
                                />
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.showOther', $member) }}" class="hover:text-blue-600 font-semibold text-foreground block truncate">
                                    {{ $member->name }}
                                </a>
                                <p class="text-sm text-muted-foreground truncate">{{ '@' . $member->username }}</p>
                                <div class="flex gap-4 mt-1 text-xs text-muted-foreground">
                                   {{-- <span><span class="font-semibold text-foreground">{{ $member->following()->count() }}</span> Following</span>
                                    <span><span class="font-semibold text-foreground">{{ $member->followers()->count() }}</span> Followers</span> --}}
                                </div>
                            </div>
                            @auth
                                @if(auth()->id() === $group->owner && $member->id !== $group->owner)
                                    <div class="flex gap-2">
                                        {{-- Tornar owner --}}
                                        <form
                                            action="{{ route('groups.transferOwner', [$group->id, $member->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to make this user the owner of the group?')">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 text-xs font-medium whitespace-nowrap"
                                            >
                                                Make Owner
                                            </button>
                                        </form>

                                        {{-- Remover membro --}}
                                        <form
                                            action="{{ route('groups.members.remove', [$group->id, $member->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Remove this member?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-xs font-medium whitespace-nowrap">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $members->links() }}
            </div>
        @endif

    </main>
</div>
@endsection

