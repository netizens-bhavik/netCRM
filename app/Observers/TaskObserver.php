<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\TaskHasMembers;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $notification = Notification::create([
            'title' => 'Task Created',
            'description' => $task->name,
            'user_id' => $task->created_by,
            'refrence_id' => $task->id,
            'type' => 'task'
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        Notification::where('type','task')->where('refrence_id',$task->id)->delete();
        Log::info('task notification Delete');
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
