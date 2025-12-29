<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')
                ->comment('User who receives the notification');

            $table->string('event', 100)
                ->comment('Event name that triggered notification');

            $table->string('channel', 50)
                ->comment('Notification channel: email, database');

            $table->string('status', 30)
                ->comment('pending, sent, failed, rate_limited');

            $table->text('error_message')
                ->nullable()
                ->comment('Error message if notification failed');

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'event'], 'idx_user_event');
            $table->index(['status'], 'idx_status');
            $table->index(['channel'], 'idx_channel');

            // Foreign Key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE notification_logs COMMENT = 'Tracks delivery status of all notifications'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
