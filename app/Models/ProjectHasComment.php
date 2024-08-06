<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectHasComment extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'user_id',
        'project_id',
        'comment',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id','name','avtar'])->with('roles:name,label');
    }
    /**
     * Get the task that owns the TaskHasComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id','id');
    }
}
