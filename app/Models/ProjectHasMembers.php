<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectHasMembers extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id','project_id', 'user_id',
    ];
    /**
     * Get the user that owns the ProjectHasMembers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /**
     * user
     *
     * @return void
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id','name','avtar'])->with('roles:name');
    }
}
