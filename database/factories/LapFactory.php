<?php

namespace Database\Factories;

use App\Models\Checkpoint;
use App\Models\Lap;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lap>
 */
class LapFactory extends Factory
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
            'checkpoint_id' => Checkpoint::factory(),
            'completed_at' => now(),
        ];
    }
}
