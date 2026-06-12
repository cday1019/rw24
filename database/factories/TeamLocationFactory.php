<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamLocation>
 */
class TeamLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'pinged_at' => now(),
        ];
    }
}
