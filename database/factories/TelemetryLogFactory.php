<?php

namespace Database\Factories;

use App\Models\TelemetryLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TelemetryLog>
 */
class TelemetryLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'speed' => fake()->randomFloat(2, 0, 45),
            'heading' => fake()->numberBetween(0, 359),
        ];
    }
}
