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
            foreach ($taskData as $taskResponse) {
                dispatch(new DeadLineTaskJob($taskResponse))->onQueue('deadline');
            }
        }
    }
}
