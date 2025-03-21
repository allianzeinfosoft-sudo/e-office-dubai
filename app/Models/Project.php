<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_name',
        'department_id',
        'project_add_person',
        'start_date',
        'end_date',
        'total_hours',
        'total_day',
        'date_of_add',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'project_add_person'); // Adjust if 'project_add_person' is the foreign key
    }
}
