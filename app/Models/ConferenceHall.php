<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceHall extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id', 
        'booked_by', 
        'booking_date', 
        'start_time', 
        'end_time',
        'participants',
        'purpose',
        'status'
    ];

    public function dept(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function bookedBy(){
        return $this->belongsTo(Employee::class, 'booked_by', 'user_id');
    }

    public function getParticipantsEmployeesAttribute(){
        if (!$this->participants) {
            return [];
        }
        $ids = explode(',', $this->participants);
        return Employee::whereIn('user_id', $ids)->pluck('full_name')->toArray();
    }
}
