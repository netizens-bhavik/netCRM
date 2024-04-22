<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

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
            'user_id' => $task->manage_by
        ]);
        Log::info($task);
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
        //
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
