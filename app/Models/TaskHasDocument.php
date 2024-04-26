<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskHasDocument extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = ['document','task_id','original_document_name'];
    protected $visible = ['id', 'document','task_id','original_document_name'];
    /**
     * Get the task that owns the TaskHasDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
