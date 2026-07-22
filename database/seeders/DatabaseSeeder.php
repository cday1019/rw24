<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $team = Team::create([
            'name' => "Manifest Destiny's Child",
            'category' => 'team',
            'invite_code' => 'MDC24',
        ]);

        $roster = [
            ['name' => 'Chad Day', 'email' => 'chaday@gmail.com'],
            ['name' => 'Adam Franz', 'email' => 'adam@example.com'],
            ['name' => 'Aaron Thrasher', 'email' => 'aaron@example.com'],
            ['name' => 'Troy Becker', 'email' => 'troy@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
            ['name' => 'Kyle', 'email' => 'kyle@example.com'],
            ['name' => 'Kris Kouba', 'email' => 'kris@example.com'],
        ];

        foreach ($roster as $member) {
            User::factory()->create([
                'name' => $member['name'],
                'email' => $member['email'],
                'team_id' => $team->id,
                'password' => Hash::make('password'),
                'role' => 'rider',
                'status' => 'off_duty',
            ]);
        }
    }
}
