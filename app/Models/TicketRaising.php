<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRaising extends Model
{
    use HasFactory;
    protected $fillable = ['user','ticket_department','ticket_title','ticket_description','issue_date_time','close_date_time','picture','status','comment'];

    public function ticket_raiser()
    {
        return $this->belongsTo(Employee::class,'user','user_id');
    }

    public function ticketDepartment()
    {
        return $this->belongsTo(Department::class, 'ticket_department', 'id');
    }

}
