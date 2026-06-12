<?php

use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

test('map component is rendered on the dashboard', function () {
    $user = User::factory()->create();
    $team = Team::create(['name' => 'Test Team', 'category' => 'Pro', 'invite_code' => 'TEST123']);
    $user->update(['team_id' => $team->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertStatus(200)
        ->assertSeeLivewire('live-race-map');
});

test('map component loads google maps script', function () {
    Livewire::test('live-race-map')
        ->assertSee('https://maps.googleapis.com/maps/api/js?key='.config('services.google.maps_key'));
});
