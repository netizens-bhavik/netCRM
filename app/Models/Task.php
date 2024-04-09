<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = [
        'id',
        'name',
        'project_id',
        'start_date',
        'due_date',
        'description',
        'priority',
        'status',
        'voice_memo','manage_by',
    ];
    public const priority = [
        'High', 'Low' , 'Medium' , 'Hold'
    ];
    public const status = [
        'Active','Inactive','Hold','Inprocess','Cancle'
    ];
}
