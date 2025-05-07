<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    use HasFactory;

    protected $fillable = ['appreciant','project','display_date','appreciation_details','picture'];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'appreciant', 'user_id');
    }



    public function project()
    {
        return $this->belongsTo(Project::class, 'project','id');
    }

    public function appreciantEmployees()
    {
        return $this->belongsToMany(Employee::class, 'employees', 'id', 'id')
            ->whereIn('id', explode(',', $this->appreciant ?? ''));
    }

    public function appreciantEmployeesView()
    {
        $ids = explode(',', $this->appreciant ?? '');
        return Employee::whereIn('user_id', $ids)->get();
    }



}
