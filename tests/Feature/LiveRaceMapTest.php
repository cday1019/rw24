<?php

use App\Models\Team;
use App\Models\TeamLocation;
use App\Models\User;
use Livewire\Livewire;

it('retrieves all teammate locations for the authenticated user', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create(['team_id' => $team->id]);
    $teammate = User::factory()->create(['team_id' => $team->id]);
    $otherTeam = Team::factory()->create();
    $otherUser = User::factory()->create(['team_id' => $otherTeam->id]);

    TeamLocation::factory()->create([
        'team_id' => $team->id,
        'user_id' => $user->id,
        'latitude' => 44.0886,
        'longitude' => -87.6575,
        'pinged_at' => now(),
    ]);

    TeamLocation::factory()->create([
        'team_id' => $team->id,
        'user_id' => $teammate->id,
        'latitude' => 44.1000,
        'longitude' => -87.6600,
        'pinged_at' => now(),
    ]);

    TeamLocation::factory()->create([
        'team_id' => $otherTeam->id,
        'user_id' => $otherUser->id,
        'latitude' => 45.0000,
        'longitude' => -88.0000,
        'pinged_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('live-race-map')
        ->assertSet('teammateLocations', function ($locations) use ($user, $teammate) {
            $names = collect($locations)->pluck('name');

            return count($locations) === 2 &&
                   $names->contains($user->name) &&
                   $names->contains($teammate->name);
        });
});

it('filters out stale locations', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create(['team_id' => $team->id]);

    TeamLocation::factory()->create([
        'team_id' => $team->id,
        'user_id' => $user->id,
        'pinged_at' => now()->subMinutes(31),
    ]);

    $this->actingAs($user);

    Livewire::test('live-race-map')
        ->assertSet('teammateLocations', []);
});

it('updates the rider location', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create(['team_id' => $team->id]);

    $this->actingAs($user);

    Livewire::test('live-race-map')
        ->call('updateRiderLocation', 44.1234, -87.5432);

    $this->assertDatabaseHas('team_locations', [
        'user_id' => $user->id,
        'team_id' => $team->id,
        'latitude' => 44.1234,
        'longitude' => -87.5432,
    ]);

    $location = TeamLocation::where('user_id', $user->id)->first();
    expect($location->pinged_at)->toBeGreaterThan(now()->subSecond());
});
