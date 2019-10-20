<?php

namespace App\Listeners;

use App\Events\ModelLiked;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewLikeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLikeNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModelLiked  $event
     * @return void
     */
    public function handle(ModelLiked $event)
    {
        // send notification
        $event->model->user->notify(new NewLikeNotification());
    }
}
