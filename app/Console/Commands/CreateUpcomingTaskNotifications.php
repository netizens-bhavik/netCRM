<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Notification;
use App\Models\TaskHasMembers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateUpcomingTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create-upcoming-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create notifications for upcoming tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $tomorrow = Carbon::tomorrow()->toDateString();
            $upcomingTasks = Task::where('start_date', $tomorrow)->get();
            foreach ($upcomingTasks as $task) {
                Notification::create([
                    'title' => 'Upcoming Task',
                    'description' => $task->name,
                    'user_id' => $task->manage_by,
                    'refrence_id' => $task->id,
                    'type' => 'task'
                ]);
                $members = TaskHasMembers::where('task_id', $task->id)->pluck('user_id')->toArray();
                $notificationData = [
                    'title' => 'Upcoming Task',
                    'description' => $task->name,
                    'refrence_id' => $task->id,
                    'type' => 'task'
                ];

                foreach ($members as $memberId) {
                    $notificationData['user_id'] = $memberId;
                    Notification::create($notificationData);
                }
            }
            $this->info('Upcoming task notifications created successfully.');
            Log::info('Upcoming task notifications created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating upcoming task notifications: ' . $e->getMessage());
            $this->error('An error occurred while creating upcoming task notifications. Check the logs for more details.');
        }
    }
}
