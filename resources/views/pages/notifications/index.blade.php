@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span class="text-base font-semibold text-slate-700">Notifications</span>
    </div>

    @forelse ($notifications as $notification)
        @php
            $actor = $notification->actorUser;
            $isCurrentlyUnread = in_array($notification->id, $unreadIds);
            $icon = match($notification->type) {
                'followRequest' => 'user-plus',
                'startFollowing' => 'user-plus',
                'acceptedFollowRequest' => 'user-check',
                'joinGroupRequest' => 'users',
                'acceptedJoinGroupRequest' => 'check-circle',
                'comment' => 'chat-alt-2',
                'reaction' => 'heart',
                default => 'bell',
            };
            $iconColor = match($notification->type) {
                'followRequest' => 'bg-blue-50 text-blue-400',
                'startFollowing' => 'bg-blue-50 text-blue-400',
                'acceptedFollowRequest' => 'bg-green-50 text-green-500',
                'joinGroupRequest' => 'bg-purple-50 text-purple-400',
                'acceptedJoinGroupRequest' => 'bg-green-50 text-green-500',
                'comment' => 'bg-amber-50 text-amber-400',
                'reaction' => 'bg-rose-50 text-rose-400',
                default => 'bg-slate-50 text-slate-400',
            };
        @endphp

        <div class="flex items-center gap-3 border rounded-md px-4 py-3 mb-2 shadow-sm hover:shadow transition-all
            {{ $isCurrentlyUnread
                ? 'bg-white border-blue-400 ring-1 ring-blue-100'
                : 'bg-slate-100 border-slate-300 text-slate-500' }}">
            <div class="flex-shrink-0 flex flex-col items-center">
                @switch($icon)
                    @case('user-plus')
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-400 bg-opacity-30 text-2xl font-medium leading-none">+</span>
                        @break
                    @case('user-check')
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-500 bg-opacity-30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                        @break
                    @case('users')
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-50 text-purple-400 bg-opacity-30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg></span>
                        @break
                    @case('check-circle')
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-500 bg-opacity-30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/></svg></span>
                        @break
                    @case('chat-alt-2')
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-amber-400 bg-opacity-30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V10a2 2 0 012-2h2m4-4h.01"/></svg></span>
                        @break
                    @case('heart')
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-50 text-rose-400 bg-opacity-30"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg></span>
                        @break
                    @default
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 text-slate-400 bg-opacity-30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></span>
                @endswitch
                <img src="{{ $actor->avatar ?? 'https://via.placeholder.com/40' }}" alt="{{ $actor->username }}" class="h-6 w-6 rounded-full mt-1 border border-slate-200 object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-700 leading-tight font-normal">
                        @switch($notification->type)
                            @case('startFollowing')
                                <a href="{{ route('profile.showOther', $actor->id ?? 1) }}" class="text-rose-400 hover:underline font-medium">{{ $actor->username ?? 'Someone' }}</a> <span class="text-slate-700 font-normal">started following you</span>
                                @break
                            @case('followRequest')
                                <a href="{{ route('followRequests.index') }}" class="text-blue-500 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> sent you a follow request</a>
                                @break
                            @case('acceptedFollowRequest')
                                <a href="{{ route('followRequests.index') }}" class="text-green-500 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> accepted your follow request</a>
                                @break
                            @case('joinGroupRequest')
                                <a href="{{ route('groups.requests', $notification->groupJoinRequest->group->id) }}" class="text-purple-500 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> requested to join your group</a>
                                @break
                            @case('acceptedJoinGroupRequest')
                                <span class="text-green-500">Your request to join the group was accepted</span>
                                @break
                            @case('comment')
                                    @php
                                        $parent = $notification->postComment->parent ?? null;
                                        $postId = null;
                                        if ($parent && method_exists($parent, 'isPost') && $parent->isPost()) {
                                            $postId = $parent->id;
                                        } else {
                                            $postId = $parent->parent->id;
                                        }
                                    @endphp
                                    @if ($notification->postComment->parent && $notification->postComment->parent->isPost())
                                        <a href="{{ route('profile.showOther', $actor->id ?? 1) }}" class="text-amber-500 hover:underline font-medium">{{ $actor->username ?? 'Someone' }}</a>
                                        commented on
                                        @if($postId)
                                            <a href="{{ route('posts.show', $postId) }}" class="text-amber-500 hover:underline font-medium">your post</a>
                                        @else
                                            <span class="text-red-500">a post (unavailable)</span>
                                        @endif
                                    @else
                                        <a href="{{ route('profile.showOther', $actor->id ?? 1) }}" class="text-amber-500 hover:underline font-medium">{{ $actor->username ?? 'Someone' }}</a>
                                        replied to your comment in
                                        @if($postId)
                                            <a href="{{ route('posts.show', $postId) }}" class="text-amber-500 hover:underline font-medium">this post</a>
                                        @else
                                            <span class="text-red-500">a post (unavailable)</span>
                                        @endif
                                    @endif
                                @break
                            @case('reaction')
                                @php
                                    if ($notification->reaction->content->isPost()) {
                                        $postId = $notification->reaction->content->id;
                                        $target = 'your post';
                                    } elseif ($notification->reaction->content->parent->isPost()) {
                                        $postId = $notification->reaction->content->parent->id;
                                        $target = 'your comment';
                                    } else {
                                        $postId = $notification->reaction->content->parent->parent->id;
                                        $target = 'your reply';
                                    }
                                @endphp
                                <a href="{{ route('profile.showOther', $actor->id ?? 1) }}" class="text-rose-400 hover:underline font-medium">{{ $actor->username ?? 'Someone' }}</a> liked <a href="{{ route('posts.show', $postId) }}" class="text-rose-400 hover:underline font-medium">{{ $target }}</a>
                                @break
                        @endswitch
                    </span>
                </div>
                <span class="text-xs text-slate-400 block mt-0.5">{{ $notification->time_ago }}</span>
            </div>
        </div>
    @empty
        <p class="text-center text-slate-400 py-16 text-base">No notifications.</p>
    @endforelse
</div>
@endsection

