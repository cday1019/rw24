<?php

namespace Database\Factories;

use App\Models\Checkpoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checkpoint>
 */
class CheckpointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word().' Checkpoint',
            'ghost_waiter_id' => null,
        ];
    }
}
