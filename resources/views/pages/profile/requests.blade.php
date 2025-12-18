@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        
        <div class="mb-6">
            <a href="{{ route('pages.profile.show') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Profile
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Follow Requests</h1>
                <p class="text-gray-600 mt-1">Manage who can follow you</p>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="divide-y divide-gray-200">
                @forelse($requests as $request)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('profile.showOther', $request->follower) }}">
                                    <img 
                                        src="{{ $request->follower->avatar }}" 
                                        alt="{{ $request->follower->name }}"
                                        class="h-12 w-12 rounded-full object-cover border-2 border-gray-200"
                                    />
                                </a>
                                
                                <div>
                                    <a href="{{ route('profile.showOther', $request->follower) }}" 
                                       class="font-semibold text-gray-900 hover:text-blue-600">
                                        {{ $request->follower->name }}
                                    </a>
                                    <p class="text-sm text-gray-600">{{ '@' . $request->follower->username }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Requested {{ $request->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <form action="{{ route('followRequests.accept', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition">
                                        Accept
                                    </button>
                                </form>
                                
                                <form action="{{ route('followRequests.decline', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm font-medium hover:bg-gray-300 transition">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No pending requests</h3>
                        <p class="text-gray-600">You don't have any follow requests at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
@endsection
