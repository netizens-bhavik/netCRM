<?php

namespace App\Console\Commands;

use App\Jobs\DeadLineTaskJob;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeadLineTaskScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dead-line-task-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $taskData = Task::where('due_date',$tomorrow)->whereNotNull('due_date')->whereNull('completed_date')->get();
        $taskData = $taskData ? $taskData->toArray() : [];

        if(!empty($taskData))
        {
            foreach ($taskData as $taskResponse)
            {
                $taskId = $taskResponse['id'];
                $taskDate = Carbon::createFromFormat('Y-m-d', $taskResponse['due_date'])->format('F d, Y');
                $userIds = Task::with(['members.user:id', 'observers.user:id'])
                ->where('id', $taskId)
                ->get()
                ->flatMap(function ($task) {
                    return array_merge(
                        $task->members->pluck('user_id')->toArray(),
                        $task->observers->pluck('observer_id')->toArray(),
                        [$task->assigned_to, $task->created_by]
                    );
                })
                ->unique()
                ->values()
                ->toArray();
                if(!empty($userIds))
                {
                    $userData = User::with('token')->has('token')->whereIn('id',$userIds)->get()->toArray();
                    if(!empty($userData))
                    {
                        foreach ($userData as $userDataResponse) {
                            $device_token = $userDataResponse['token'];
                            foreach ($device_token as $value) {
                                if(!empty($value['device_token']))
                                {

                                    // Log::info("Reminder: Deadline for {$taskResponse['name']} is {$taskDate}. Please ensure completion by then.");
                                    send_firebase_notification($value['device_token'],'Task Deadline' ,'Reminder: Deadline for " ' .$taskResponse['name'].' " is ' .$taskDate.'. Please ensure completion by then.');
                                }

                            }
                            Notification::create([
                                'title' => 'Task Deadline',
                                'description' => 'Reminder: Deadline for ' .$taskResponse['name'].' is ' .$taskDate.'. Please ensure completion by then.',
                                'user_id' => $userDataResponse['id'],
                                'refrence_id' => $taskId,
                                'type' => 'task'
                            ]);
                        }
                    }
                }
            }
        }
    }
}
