<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeadLineTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($object)
    {
        $this->data = $object;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $taskResponse = $this->data;

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

                            Log::info("Reminder: Deadline for {$taskResponse['name']} is {$taskDate}. Please ensure completion by then.");
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
