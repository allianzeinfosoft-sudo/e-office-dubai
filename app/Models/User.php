<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee(){
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    public function reporting_to(){
        return $this->hasOne(Employee::class, 'reporting_to', 'id');
    }

    public function projects(){
        return $this->hasMany(Project::class, 'project_add_person'); 
    }

    public function leave_allocation()
    {
        return $this->belongsTo(LeaveAllocation::class, 'id','user_id');
    }

    public function user_leaves()
    {
        return $this->hasMany(Leave::class, 'user_id','id');
    }


}
