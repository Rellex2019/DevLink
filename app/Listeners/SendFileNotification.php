<?php

namespace App\Listeners;

use App\Events\FileCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFileNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FileCreated $event)
    {
        $event->file->project->users->each->notify(
            // new FileCreatedNotification($event->file) наверное сделать уведомление о добавлении
        );
    }
}
