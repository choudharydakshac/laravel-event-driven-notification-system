<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\NotificationDispatcher;

class SendUserNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected NotificationDispatcher $dispatcher
    ) {}

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        if (!$event->user) {
            return;
        }

        $this->dispatcher->dispatch(
            $event->user,
            'UserRegistered'
        );
    }
}
