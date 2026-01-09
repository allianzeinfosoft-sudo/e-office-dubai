<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailBox extends Model{
    use HasFactory;

    protected $fillable = [
        'to_user_ids',
        'cc_user_ids',
        'bcc_user_ids',
        'subject',
        'message',
        'attachments',
        'status',
        'folder',
        'is_starred',
        'from_user_id',
        'mark_as_read',
        'external_email_id',
        'external_from',
        'external_date',
        'raw_headers',
        'owner_id',
    ];

    protected $casts = [
        'to_user_ids' => 'array',
        'cc_user_ids' => 'array',
        'bcc_user_ids' => 'array',
        'attachments' => 'array',
        'status' => 'integer',
    ];

    const STATUS_INBOX = 0;
    const STATUS_DRAFT = 1;
    const STATUS_STARRED = 2;
    const STATUS_SENT = 3;
    const STATUS_SPAM = 4;
    const STATUS_TRASH = 5;
    const STATUS_PENDING = 6;
    const STATUS_FAILED = 7;

    public static function getStatusOptions(){
        return [
            self::STATUS_INBOX => 'Inbox',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_STARRED => 'Starred',
            self::STATUS_SENT => 'Sent Items',
            self::STATUS_SPAM => 'Spam',
            self::STATUS_TRASH => 'Trash',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_FAILED => 'Failed',
        ];
    }

    public static function folders(){
        return [ 'inbox', 'draft', 'starred', 'sent', 'spam', 'trash', 'pending', 'failed'];
    }

    public function fromUser(){
        return $this->belongsTo(Employee::class, 'from_user_id', 'user_id');
    }

    public function userData(){
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

}
