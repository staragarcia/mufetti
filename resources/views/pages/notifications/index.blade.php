@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6">
    <h1 class="text-xl font-bold mb-4">Notifications</h1>

    @forelse ($notifications as $notification)
        @php
            $actor = $notification->actorUser;
            $isCurrentlyUnread = in_array($notification->id, $unreadIds);
        @endphp

        <div class="flex items-start p-4 mb-2 border rounded transition-colors
            {{ $isCurrentlyUnread ? 'bg-blue-50 font-semibold' : 'bg-gray-100' }}">

            <!-- Foto do autor -->
            <img src="{{ $actor->avatar ?? 'https://via.placeholder.com/40' }}"
                 alt="{{ $actor->username }}"
                 class="h-10 w-10 rounded-full mr-4 object-cover">

            <div class="flex-1">
                <p class="text-sm">
                    @switch($notification->type)
                        @case('followRequest')
                            <a href="{{ route('followRequests.index') }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> sent you a follow request</a>
                            @break
                        @case('acceptedFollowRequest')
                            <a href="{{ route('followRequests.index') }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> accepted your follow request</a>
                            @break
                        @case('joinGroupRequest')
                            <a href="{{ route('groups.requests', $notification->groupJoinRequest->group->id) }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> requested to join your group</a>
                            @break
                        @case('acceptedJoinGroupRequest')
                            Your request to join the group was accepted
                            @break
                        @case('comment')
                            @if ($notification->postComment->parent->isPost())
                                <a href="{{ route('posts.show', $notification->postComment->parent->id) }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> commented on your post</a>
                            @else
                                <a href="{{ route('posts.show', $notification->postComment->parent->parent->id) }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> commented on your post</a>
                            @endif
                            @break
                        @case('reaction')
                            @if ($notification->reaction->content->isPost())
                                <a href="{{ route('posts.show', $notification->reaction->content->id) }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> liked your post</a>
                            @elseif ($notification->reaction->content->parent->isPost())
                                <a href="{{ route('posts.show', $notification->reaction->content->parent->id) }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> liked your comment</a>
                            @else
                                <a href="{{ route('posts.show', $notification->reaction->content->parent->parent->id) }}" class="underline hover:text-blue-600" ><strong>{{ $actor->username ?? 'Someone' }}</strong> liked your reply</a>
                            @endif
                            @break
                    @endswitch
                </p>

                <!-- Timestamp -->
                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </div>
    @empty
        <p>No notifications.</p>
    @endforelse
</div>
@endsection

