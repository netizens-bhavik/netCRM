<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = [
        'id',
        'manage_by',
        'client_id', 'name',
        'start_date',
        'manage_by',
        'deadline',
        'summary',
        'members',
        'currency',
    ];
    /**
     * Get all of the members for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected $casts = [
        'id' => 'string'
    ];
}
