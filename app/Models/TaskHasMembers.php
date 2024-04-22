<?php

namespace App\Models;

use App\Observers\TaskHasMembersObserver;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([TaskHasMembersObserver::class])]
class TaskHasMembers extends Model
{
    use HasFactory,SoftDeletes,HasUuids;
    protected $fillable = [
        'id','task_id','user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id','name','avtar'])->with('roles:name');
    }
}
