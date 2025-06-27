<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        // Find the notification by ID
        $notification = \App\Models\Notification::findOrFail($id);

        if ($notification->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Nu aveți permisiunea să marcați această notificare ca citită.');
        }

        // Mark the notification as read
        $notification->read_at = now();
        $notification->save();

        return redirect()->back()->with('success', 'Notificarea a fost marcată ca citită.');
    }
}
