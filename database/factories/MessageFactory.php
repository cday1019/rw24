<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
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
            'channel_id' => fake()->numberBetween(1, 3),
            'body' => fake()->sentence(),
            'image_path' => null,
        ];
    }

    public function warRoom(): static
    {
        return $this->state(fn (array $attributes) => [
            'channel_id' => 1,
        ]);
    }

    public function sos(): static
    {
        return $this->state(fn (array $attributes) => [
            'channel_id' => 2,
        ]);
    }

    public function vibeWard(): static
    {
        return $this->state(fn (array $attributes) => [
            'channel_id' => 3,
        ]);
    }
}
