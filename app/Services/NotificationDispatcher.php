<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\User;
use App\Models\UserNotificationPreference;

class NotificationDispatcher
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Dispatch notifications for a given user and event.
     */
    public function dispatch(User $user, string $event): void
    {
        // Fetch enabled channels for user
        $channels = UserNotificationPreference::query()
            ->where('user_id', $user->id)
            ->where('enabled', true)
            ->pluck('channel');

        if ($channels->isEmpty()) {
            return;
        }

        foreach ($channels as $channel) {
            // Log as pending
            NotificationLog::create([
                'user_id' => $user->id,
                'event'   => $event,
                'channel' => $channel,
                'status'  => 'pending',
            ]);

        }
    }
}
