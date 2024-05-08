<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHasObservers extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'task_id','observer_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'observer_id', 'id')->select(['id','name','avtar']);
    }
}
