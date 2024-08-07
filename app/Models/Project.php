<?php

namespace App\Models;

use App\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

class Project extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'id',
        'client_id',
        'manage_by',
        'created_by',
        'name',
        'start_date',
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
    /**
     * Get the client that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->select(['id','name']);
    }
    /**
     * Get the managedBy that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manageBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manage_by')->select(['id','name'])->with('roles:name,label');
    }

    /**
     * Get the user that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manage_by')->select(['id','name'])->with('roles:name,label');
    }
    /**
     * Get all of the members for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(ProjectHasMembers::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function delete()
    {
        // Delete associated task notifications
        $taskIds = $this->tasks()->pluck('id')->toArray();
        if ($taskIds) {
            Notification::whereIn('refrence_id', $taskIds)
                        ->where('type', 'task')
                        ->delete();
        }
        
        // Delete associated tasks
        $this->tasks()->delete();
        // Delete the project itself
        return parent::delete();
    }

}
