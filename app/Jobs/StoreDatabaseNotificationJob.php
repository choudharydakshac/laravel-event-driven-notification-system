<?php

namespace App\Jobs;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Queue\Queueable;
use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class StoreDatabaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected NotificationLog $log
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulate DB notification
        // Could be stored in notifications table or custom table

        $this->log->update([
            'status' => 'sent',
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->log->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
