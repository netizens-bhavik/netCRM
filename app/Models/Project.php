<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'client_id', 'name',
        'start_date',
        'manage_by',
        'deadline',
        'summary',
        'members',
        'currency',
    ];
}
