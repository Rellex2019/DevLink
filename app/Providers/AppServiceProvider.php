<?php

namespace App\Providers;

use App\Events\FileCreated;
use App\Http\Controllers\FileController;
use App\Listeners\SendFileNotification;
use App\Models\File;
use App\Policies\FilePolicy;
use App\Services\FileService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            FileCreated::class,
            [SendFileNotification::class]
        );
        // Gate::policy(FileService::class, FilePolicy::class);
    }
}
