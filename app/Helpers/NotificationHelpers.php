<?php
namespace App\Helpers;
use App\Models\HelperNotification;
use App\Models\User;



class NotificationHelpers
{


     public static function createNotification(array $data)
    {

        $type = $data['type'] ?? null;
        if (!$type) return null;

        $message = '';
        $recipients = [];

        switch ($type) {
            case 'birthday':
                $notification_type = $data['type'] ?? ' ';
                $recipients = $data['recipients'] ?? ' '; // All users
                $message = $data['message'] ?? ' ';
                break;

            case 'leave':
                $notification_type = $data['type'] ?? ' ';
                $recipients = $data['recipients'] ?? ' '; // All users
                $message = $data['message'] ?? ' ';
                break;

            case 'announcement':
                $title = $data['title'] ?? 'Important Announcement';
                $recipients = User::pluck('id')->toArray(); // All users
                $message = "📢 New Announcement: {$title}";
                break;

            case 'event':
                $eventName = $data['event_name'] ?? 'An upcoming event';
                $eventDate = $data['event_date'] ?? '';
                $recipients = $data['recipients'] ?? User::pluck('id')->toArray();
                $message = "📅 Don't miss: {$eventName} on {$eventDate}.";
                break;

            case 'policy':
                $eventName = $data['event_name'] ?? 'An upcoming event';
                $eventDate = $data['event_date'] ?? '';
                $recipients = $data['recipients'] ?? User::pluck('id')->toArray();
                $message = "📅 {$data['message']}.";
                break;

            default:
                // Unknown type
                return null;
        }

        return HelperNotification::create([
            'notification_type' => $type,
            'recipients_ids' => $recipients,
            'message' => $message,
            'readers_ids' => [],
        ]);
    }

}

