<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\Notification;
use App\Models\ProjectHasMembers;
use Illuminate\Support\Facades\Log;

class ProjectHasMembersObserver
{
    /**
     * Handle the ProjectHasMembers "created" event.
     */
    public function created(ProjectHasMembers $projectHasMembers): void
    {
        $notification = Notification::create([
            'title' => 'Project Created',
            'description' => Project::find($projectHasMembers->project_id)->name,
            'user_id' => $projectHasMembers->user_id,
            'refrence_id' => $projectHasMembers->project_id,
            'type' => 'project'
        ]);
        // Log::info('project notification Delete');

    }

    /**
     * Handle the ProjectHasMembers "updated" event.
     */
    public function updated(ProjectHasMembers $projectHasMembers): void
    {
        //
    }

    /**
     * Handle the ProjectHasMembers "deleted" event.
     */
    public function deleted(ProjectHasMembers $projectHasMembers): void
    {
        Notification::where('type','project')->where('refrence_id',$projectHasMembers->project_id)->delete();
        // Log::info('project notification Delete');

    }

    /**
     * Handle the ProjectHasMembers "restored" event.
     */
    public function restored(ProjectHasMembers $projectHasMembers): void
    {
        //
    }

    /**
     * Handle the ProjectHasMembers "force deleted" event.
     */
    public function forceDeleted(ProjectHasMembers $projectHasMembers): void
    {
        //
    }
}
