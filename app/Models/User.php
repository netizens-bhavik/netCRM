<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens,HasRoles,HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';
    public $guard_name = 'sanctum';
    protected $fillable = [
        'id',
        'name',
        'avtar',
        'email',
        'password',
        'phone_no',
        'designation_id',
        'department_id',
        'date_of_birth',
        'gender',
        'date_of_join',
        'address',
        'about',
        'adhar_image'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, TaskHasMembers::class, 'user_id', 'id', 'id', 'task_id');
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_has_members', 'user_id', 'project_id');
    }
}
