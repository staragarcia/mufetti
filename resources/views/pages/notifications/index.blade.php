@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6">
    <h1 class="text-xl font-bold mb-4">Notifications</h1>

    @forelse ($notifications as $notification)
        <div class="p-4 mb-2 border rounded
            {{ $notification->is_read ? 'bg-gray-100' : 'bg-white font-semibold' }}">
            @php
            $actorName = $notification->actorUser->username ?? 'Someone';
            @endphp

            <p>
                @switch($notification->type)

                    @case('followRequest')
                        <strong>{{ $actorName }}</strong> sent you a follow request
                        @break

                    @case('acceptedFollowRequest')
                        <strong>{{ $actorName }}</strong> accepted your follow request
                        @break

                    @case('joinGroupRequest')
                        <strong>{{ $actorName }}</strong> requested to join your group
                        @break

                    @case('acceptedJoinGroupRequest')
                        Your request to join the group was accepted
                        @break

                    @case('comment')
                        <strong>{{ $actorName }}</strong> commented on your post
                        @break

                    @case('reaction')
                        <strong>{{ $actorName }}</strong> liked your post
                        @break

                @endswitch
            </p>

            @if(!$notification->is_read)
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                    @csrf
                    <button class="text-sm text-blue-600 mt-2">
                        Mark as read
                    </button>
                </form>
            @endif
        </div>
    @empty
        <p>No notifications.</p>
    @endforelse
</div>
@endsection
