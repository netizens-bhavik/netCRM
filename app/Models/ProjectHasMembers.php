<?php

namespace App\Models;

use App\Observers\ProjectHasMembersObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ProjectHasMembersObserver::class])]
class ProjectHasMembers extends Model
{
    use HasFactory;
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id','name','avtar'])->with('roles:name,label');
    }
}
