<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelperNotification extends Model
{
    protected $fillable = [
        'notification_type',
        'recipients_ids',
        'message',
        'readers_ids',
    ];

    protected $casts = [
        'recipients_ids' => 'array',
        'readers_ids' => 'array',
    ];
}
