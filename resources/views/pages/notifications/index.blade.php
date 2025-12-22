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
            <img src="{{ $actor->profile_photo_url ?? 'https://via.placeholder.com/40' }}" 
                 alt="{{ $actor->username }}" 
                 class="h-10 w-10 rounded-full mr-4 object-cover">

            <div class="flex-1">
                <p class="text-sm">
                    @switch($notification->type)
                        @case('followRequest')
                            <strong>{{ $actor->username ?? 'Someone' }}</strong> sent you a follow request
                            @break
                        @case('acceptedFollowRequest')
                            <strong>{{ $actor->username ?? 'Someone' }}</strong> accepted your follow request
                            @break
                        @case('joinGroupRequest')
                            <strong>{{ $actor->username ?? 'Someone' }}</strong> requested to join your group
                            @break
                        @case('acceptedJoinGroupRequest')
                            Your request to join the group was accepted
                            @break
                        @case('comment')
                            <strong>{{ $actor->username ?? 'Someone' }}</strong> commented on your post
                            @break
                        @case('reaction')
                            <strong>{{ $actor->username ?? 'Someone' }}</strong> liked your post
                            @break
                    @endswitch
                </p>

                <!-- Timestamp -->
                <span class="text-xs text-gray-500">{{ $notification->time_ago }}</span>
            </div>
        </div>
    @empty
        <p>No notifications.</p>
    @endforelse
</div>
@endsection

