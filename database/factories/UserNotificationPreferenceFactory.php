<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserNotificationPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserNotificationPreference>
 */
class UserNotificationPreferenceFactory extends Factory
{
    protected $model = UserNotificationPreference::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'channel' => $this->faker->randomElement(['email', 'database']),
            'enabled' => true,
        ];
    }

    /**
     * State: Email notification only
     */
    public function emailOnly(): static
    {
        return $this->state(fn () => [
            'channel' => 'email',
        ]);
    }

    /**
     * State: Database notification only
     */
    public function databaseOnly(): static
    {
        return $this->state(fn () => [
            'channel' => 'database',
        ]);
    }
}
