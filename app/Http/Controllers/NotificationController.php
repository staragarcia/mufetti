<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // US303 – Notification list
    public function index()
    {
        $notifications = Notification::where('receiver', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.notifications.index', compact('notifications'));
    }

    // US304 – Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('receiver', Auth::id())
            ->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        return redirect()->back();
    }
}
