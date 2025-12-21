<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Broadcast the latest notification created by the current user
     * excluding themselves as receiver.
     */
    public function broadcastLatestForUser(): void
    {
        $notification = Notification::latest()
            ->where('actor', Auth::id())
            ->where('receiver', '!=', Auth::id())
            ->first();

        if ($notification) {
            broadcast(new NotificationCreated($notification))->toOthers();
        }
    }

    /**
     * Optionally, broadcast a specific notification object.
     */
    public function broadcast(Notification $notification): void
    {
        broadcast(new NotificationCreated($notification))->toOthers();
    }
}

