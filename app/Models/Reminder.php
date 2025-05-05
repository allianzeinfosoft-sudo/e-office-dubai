<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;
    protected $fillable = ['user_id',
                            'event_name',
                            'display_time',
                            'event_descriptioin',
                            'repeat_status',
                            'start_date',
                            'end_date',
                            'repeat_mode',
                            'monthly_type',
                            'day',
                            'monthly_on_week_position',
                            'yearly_in_month',
                            'active_status',
                        ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id' );
    }
}
