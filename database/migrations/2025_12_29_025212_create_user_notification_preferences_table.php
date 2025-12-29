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
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')
                ->comment('Reference to users table');

            $table->string('channel', 50)
                ->comment('Notification channel: email, database, sms, slack');

            $table->boolean('enabled')
                ->default(true)
                ->comment('Whether this channel is enabled for the user');

            $table->timestamps();

            // Constraints
            $table->unique(['user_id', 'channel'], 'uniq_user_channel');

            // Indexes
            $table->index(['user_id', 'enabled'], 'idx_user_enabled');

            // Foreign Key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE user_notification_preferences COMMENT = 'Stores user-wise notification channel preferences'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
