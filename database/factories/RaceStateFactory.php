<?php

namespace Database\Factories;

use App\Models\RaceState;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RaceState>
 */
class RaceStateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'manifest_holder_id' => User::factory(),
        ];
    }
}
