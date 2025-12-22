@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background py-10">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-12 gap-6">
            
            {{-- COLUNA ESQUERDA: Perfil --}}
            <div class="col-span-12 lg:col-span-3">
                @if($user)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm sticky top-10">
                        <div class="h-16 bg-gray-100"></div>
                        <div class="px-4 pb-4 -mt-10">
                            <a href="{{ route('pages.profile.show') }}" class="block">
                                <img src="{{ $user->avatar }}" class="h-20 w-20 rounded-full border-4 border-white object-cover shadow-md mb-3">
                                <h2 class="text-lg font-bold text-gray-900 hover:text-primary">{{ $user->name }}</h2>
                                <p class="text-xs text-gray-500">{{ "@" . $user->username }}</p>
                            </a>
                            <div class="mt-4 flex gap-4 text-xs border-t pt-4">
                                <div><span class="font-bold">{{ $user->followers()->count() }}</span> Followers</div>
                                <div><span class="font-bold">{{ $user->following()->count() }}</span> Following</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- COLUNA CENTRAL: Feed --}}
            <div class="col-span-12 lg:col-span-6 space-y-6">
                @if($user)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="flex">
                        <a href="{{ route('feed.show') }}" class="flex-1 px-4 py-3 text-center text-sm font-medium border-b-2 {{ ($feedType ?? 'all') === 'all' ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-gray-500' }}">All Posts</a>
                        <a href="{{ route('feed.following') }}" class="flex-1 px-4 py-3 text-center text-sm font-medium border-b-2 {{ ($feedType ?? 'all') === 'following' ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-gray-500' }}">Following</a>
                    </div>
                </div>
                @endif

                {{-- Uso do Partial para consistência total --}}
                @include('partials.showPosts', ['posts' => $posts])
            </div>

            {{-- COLUNA DIREITA: Sugestões --}}
            <div class="hidden lg:block lg:col-span-3">
                <div class="sticky top-10 space-y-4">
                    @if(isset($suggestedAlbums) && $suggestedAlbums->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-4 py-3 border-b font-bold text-xs uppercase tracking-wider text-gray-500">Hot Albums</div>
                        <div class="divide-y">
                            @foreach($suggestedAlbums as $album)
                            <a href="{{ route('albums.show', $album) }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 group">
                                <img src="{{ $album->cover_url }}" class="w-10 h-10 rounded shadow-sm">
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold truncate group-hover:text-primary">{{ $album->title }}</h4>
                                    <p class="text-[10px] text-gray-400 truncate">{{ $album->artists->pluck('name')->first() }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection