<?php

namespace App\Console\Commands;

use App\Jobs\UpComingTaskJob;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Notification;
use App\Models\TaskHasMembers;
use App\Models\User;
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

        $tomorrow = Carbon::tomorrow()->toDateString();
        $taskData = Task::where('start_date',$tomorrow)->whereNull('completed_date')->get();
        $taskData = $taskData ? $taskData->toArray() : [];
        if(!empty($taskData))
        {
            foreach ($taskData as $taskResponse) {
                $taskId = $taskResponse['id'];
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
                                    if(!empty($value['user_id']))
                                    {
                                        Notification::create([
                                            'title' => 'Upcoming Task',
                                            'description' => 'Stay tuned for the upcoming " '.$taskResponse['name'].' "',
                                            'user_id' => $value['user_id'],
                                            'refrence_id' => $taskId,
                                            'type' => 'task'
                                        ]);
                                    }
                                    // Log::info("Stay tuned for the upcoming {$taskResponse['name']}.");
                                    send_firebase_notification($value['device_token'],'Upcoming Task' ,'Stay tuned for the upcoming " '.$taskResponse['name'].' "');
                                }

                            }
                        }
                    }
                }
            }
        }
    }
}
