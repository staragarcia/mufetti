@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-12 gap-6">
        
        {{-- COLUNA ESQUERDA: Perfil --}}
        <div class="col-span-12 lg:col-span-3">
            @if($user)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm sticky top-10">
                    <div class="h-20 bg-gray-100"></div>
                    <div class="px-4 pb-4 -mt-12">
                        <a href="{{ route('pages.profile.show') }}" class="block">
                            <img src="{{ $user->avatar }}" class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg mb-3">
                            <h2 class="text-xl font-bold text-gray-900 hover:text-primary">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ "@" . $user->username }}</p>
                        </a>
                        <div class="mt-4 flex gap-4 text-sm border-t pt-4">
                            <a href="{{ route('followers.show', $user) }}" class="hover:underline">
                                <span class="font-bold text-gray-900">{{ $user->followers()->count() }}</span> Followers
                            </a>
                            <a href="{{ route('following.show', $user) }}" class="hover:underline">
                                <span class="font-bold text-gray-900">{{ $user->following()->count() }}</span> Following
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm text-center">
                    <h2 class="font-bold text-xl mb-2">Welcome to Mufetti</h2>
                    <p class="text-sm text-gray-500 mb-4">Join the community to share your music passion.</p>
                    <a href="{{ route('register') }}" class="block w-full py-2 bg-primary text-white rounded-lg font-medium text-sm">Sign Up</a>
                </div>
            @endif
        </div>

        {{-- COLUNA CENTRAL: Feed --}}
        <div class="col-span-12 lg:col-span-6 space-y-6">
            @if($user)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="flex">
                    <a href="{{ route('feed.show') }}" 
                       class="flex-1 px-4 py-3 text-center text-sm font-semibold border-b-2 {{ ($feedType ?? 'all') === 'all' ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-gray-500 hover:bg-gray-50' }}">
                        All Posts
                    </a>
                    <a href="{{ route('feed.following') }}" 
                       class="flex-1 px-4 py-3 text-center text-sm font-semibold border-b-2 {{ ($feedType ?? 'all') === 'following' ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-gray-500 hover:bg-gray-50' }}">
                        Following
                    </a>
                </div>
            </div>
            @endif

            {{-- CHAMA O PARTIAL COM A LÓGICA DOS POSTS E BLOQUEIO --}}
            @include('partials.showPosts', ['posts' => $posts])
        </div>

        {{-- COLUNA DIREITA: Sugestões --}}
        <div class="col-span-12 lg:col-span-3 hidden lg:block">
            <div class="sticky top-10 space-y-6">
                
                {{-- Sugestões de Álbuns --}}
                @if(isset($suggestedAlbums) && $suggestedAlbums->count() > 0)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b bg-gray-50 font-bold text-xs uppercase text-gray-500 tracking-wider">Hot Albums</div>
                    <div class="divide-y divide-gray-100">
                        @foreach($suggestedAlbums as $album)
                        <a href="{{ route('albums.show', $album) }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 group transition-colors">
                            <img src="{{ $album->cover_url }}" class="w-12 h-12 rounded shadow-sm object-cover">
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold truncate group-hover:text-primary">{{ $album->title }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $album->artists->pluck('name')->first() }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Sugestões de User --}}
                @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b bg-gray-50 font-bold text-xs uppercase text-gray-500 tracking-wider">Who to follow</div>
                    <div class="divide-y divide-gray-100">
                        @foreach($suggestedUsers as $sUser)
                        <div class="p-3 flex items-center justify-between gap-2">
                            <a href="{{ route('profile.showOther', $sUser->id) }}" class="flex items-center gap-2 min-w-0">
                                <img src="{{ $sUser->avatar }}" class="w-8 h-8 rounded-full object-cover">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold truncate">{{ $sUser->name }}</p>
                                </div>
                            </a>
                            <form action="{{ route('users.follow', $sUser) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-full font-bold hover:bg-blue-700">Follow</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection