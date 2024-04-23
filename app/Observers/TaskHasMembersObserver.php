<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskHasMembers;
use Illuminate\Support\Facades\Log;

class TaskHasMembersObserver
{
    /**
     * Handle the TaskHasMembers "created" event.
     */
    public function created(TaskHasMembers $taskHasMembers): void
    {
        $notification = Notification::create([
            'title' => 'Task Created',
            'description' => Task::find($taskHasMembers->task_id)->name,
            'user_id' => $taskHasMembers->user_id,
            'refrence_id' => $taskHasMembers->task_id,
            'type' => 'task'
        ]);
    }

    /**
     * Handle the TaskHasMembers "updated" event.
     */
    public function updated(TaskHasMembers $taskHasMembers): void
    {
        //
    }

    /**
     * Handle the TaskHasMembers "deleted" event.
     */
    public function deleted(TaskHasMembers $taskHasMembers): void
    {
        Notification::where('type','task')->where('refrence_id',$taskHasMembers->task_id)->delete();
    }

    /**
     * Handle the TaskHasMembers "restored" event.
     */
    public function restored(TaskHasMembers $taskHasMembers): void
    {
        //
    }

    /**
     * Handle the TaskHasMembers "force deleted" event.
     */
    public function forceDeleted(TaskHasMembers $taskHasMembers): void
    {
        //
    }
}
