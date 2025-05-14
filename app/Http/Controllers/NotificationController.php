<?php

namespace App\Http\Controllers;

use App\Models\HelperNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead1(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }

    public function getNotifications()
    {
        if (auth()->check()) {

            $userId = auth()->id();
           $notifications = HelperNotification::whereJsonContains('recipients_ids', (string) $userId)
                            ->where(function ($query) use ($userId) {
                                $query->whereNull('readers_ids')
                                    ->orWhereJsonDoesntContain('readers_ids', (string) $userId);
                            })
                            ->get();

            return response()->json([
                'notifications' => $notifications,
                'count' => $notifications->count()
            ]);


        }
        return [];
    }

    public function markAsRead($id)
    {
        $notification = HelperNotification::findOrFail($id);
        $userId = (string) auth()->id();

        $readers = $notification->readers_ids ?? [];

        // Only add if not already marked
        if (!in_array($userId, $readers)) {
            $readers[] = $userId;
            $notification->readers_ids = $readers;
            $notification->save();
        }

        return response()->json(['status' => 'read']);
    }

}
