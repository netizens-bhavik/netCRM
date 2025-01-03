<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $notification = Notification::create([
            'title' => 'Project Created',
            'description' => $project->name,
            'user_id' => $project->manage_by,
            'refrence_id' => $project->id,
            'type' => 'project'
        ]);
        // Log::info('Project Created'.$project);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        Notification::where('type','project')->where('refrence_id',$project->id)->delete();
        // Log::info('project notification Delete');
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
