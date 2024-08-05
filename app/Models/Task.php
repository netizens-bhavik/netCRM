<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\TaskObserver;

// #[ObservedBy([TaskObserver::class])]
class Task extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id', 'name', 'project_id', 'start_date', 'due_date','completed_date','description', 'priority', 'status', 'voice_memo','document', 'created_by','assigned_to'
    ];
    public const priority = [
        '1', '2', '3','4'
    ];
    public const status = [
        'Pending', 'Hold', 'In-progress', 'To-Do','Completed'
    ];
    /**
     * Get the project that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->select('id', 'name');
    }
    /**
     * Get all of the members for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members():HasMany
    {
        return $this->hasMany(TaskHasMembers::class);
    }
    public function observers():HasMany
    {
        return $this->hasMany(TaskHasObservers::class);
    }
    /**
     * Get the managedBy that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'name','avtar'])->with('roles:name,label');
    }
    public function users()
    {
        return $this->hasManyThrough(User::class, TaskHasMembers::class, 'task_id', 'id', 'id', 'user_id');
    }
    /**
     * Get all of the documents for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TaskHasDocument::class);
    }

    /**
     * Get the user that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class,'assigned_to')->select(['id', 'name','avtar']);
    }
    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeWherePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
