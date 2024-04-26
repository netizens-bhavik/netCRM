<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHasDocument extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = ['document','task_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id','name','avtar'])->with('roles:name');
    }
}
