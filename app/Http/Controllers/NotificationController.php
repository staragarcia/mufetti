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
        $userId = Auth::id();
    

        $notifications = Notification::where('receiver', $userId)
            ->orderByDesc('created_at') 
            ->get();
    

        $unreadIds = $notifications->where('is_read', false)->pluck('id')->toArray();

    
        if (!empty($unreadIds)) {
            Notification::whereIn('id', $unreadIds)->update(['is_read' => true]);
        }
    
        return view('pages.notifications.index', [
            'notifications' => $notifications,
            'unreadIds' => $unreadIds,
        ]);
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
