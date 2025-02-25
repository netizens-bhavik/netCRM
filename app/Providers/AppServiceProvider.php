<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\TaskHasMembers;
use App\Observers\TaskHasMembersObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind('path.public', function() {
            return base_path('public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Task::observe(TaskObserver::class);
        // TaskHasMembers::observe(TaskHasMembersObserver::class);
    }
}
