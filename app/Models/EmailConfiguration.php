<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    use HasFactory;

    protected $table = 'email_configurations';

    protected $fillable = [
        'user_id',
        'mail_protocol',          // imap / pop3
        'incoming_host',
        'incoming_port',
        'incoming_encryption',    // ssl / tls / none
        'incoming_username',
        'incoming_password',
    ];

    protected $casts = [
        'incoming_port' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
