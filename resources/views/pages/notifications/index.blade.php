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
                'acceptedFollowRequest' => 'user-check',
                'joinGroupRequest' => 'users',
                'acceptedJoinGroupRequest' => 'check-circle',
                'comment' => 'chat-alt-2',
                'reaction' => 'heart',
                default => 'bell',
            };
            $iconColor = match($notification->type) {
                'followRequest' => 'bg-blue-50 text-blue-400',
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
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $iconColor }} bg-opacity-30">
                    @switch($icon)
                        @case('user-plus')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14v6M19 17h-6m6 0a6 6 0 10-12 0 6 6 0 0012 0z"/></svg>
                            @break
                        @case('user-check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @break
                        @case('users')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg>
                            @break
                        @case('check-circle')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/></svg>
                            @break
                        @case('chat-alt-2')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V10a2 2 0 012-2h2m4-4h.01"/></svg>
                            @break
                        @case('heart')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                            @break
                        @default
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endswitch
                </span>
                <img src="{{ $actor->avatar ?? 'https://via.placeholder.com/40' }}" alt="{{ $actor->username }}" class="h-6 w-6 rounded-full mt-1 border border-slate-200 object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-700 leading-tight font-normal">
                        @switch($notification->type)
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
                                @if ($notification->postComment->parent->isPost())
                                    <a href="{{ route('posts.show', $notification->postComment->parent->id) }}" class="text-amber-500 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> commented on your post</a>
                                @else
                                    <a href="{{ route('posts.show', $notification->postComment->parent->parent->id) }}" class="text-amber-500 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> commented on your post</a>
                                @endif
                                @break
                            @case('reaction')
                                @if ($notification->reaction->content->isPost())
                                    <a href="{{ route('posts.show', $notification->reaction->content->id) }}" class="text-rose-400 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> liked your post</a>
                                @elseif ($notification->reaction->content->parent->isPost())
                                    <a href="{{ route('posts.show', $notification->reaction->content->parent->id) }}" class="text-rose-400 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> liked your comment</a>
                                @else
                                    <a href="{{ route('posts.show', $notification->reaction->content->parent->parent->id) }}" class="text-rose-400 hover:underline"><span class="font-medium">{{ $actor->username ?? 'Someone' }}</span> liked your reply</a>
                                @endif
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

