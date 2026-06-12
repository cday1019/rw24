<?php

use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

test('authenticated user can successfully create a team', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-manager')
        ->set('name', 'Alpha Team')
        ->call('createTeam')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('teams', [
        'name' => 'Alpha Team',
    ]);

    $this->assertEquals('Alpha Team', $user->fresh()->team->name);
});

test('authenticated user can update their own status', function () {
    $user = User::factory()->create([
        'status' => 'off_duty',
    ]);

    $this->actingAs($user);

    Livewire::test('team-manager')
        ->call('updateStatus', $user->id, 'riding')
        ->assertHasNoErrors();

    $this->assertEquals('riding', $user->fresh()->status);
});

test('authenticated user can update others status', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create(['status' => 'off_duty']);

    $this->actingAs($user);

    Livewire::test('team-manager')
        ->call('updateStatus', $otherUser->id, 'riding')
        ->assertHasNoErrors();

    $this->assertEquals('riding', $otherUser->fresh()->status);
});

test('admin can update others status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $otherUser = User::factory()->create(['status' => 'off_duty']);

    $this->actingAs($admin);

    Livewire::test('team-manager')
        ->call('updateStatus', $otherUser->id, 'riding')
        ->assertHasNoErrors();

    $this->assertEquals('riding', $otherUser->fresh()->status);
});

test('invalid status is rejected', function () {
    $user = User::factory()->create(['status' => 'off_duty']);

    $this->actingAs($user);

    Livewire::test('team-manager')
        ->call('updateStatus', $user->id, 'invalid_status');

    $this->assertEquals('off_duty', $user->fresh()->status);
});

test('a team name must be unique', function () {
    Team::create(['name' => 'Existing Team', 'category' => 'team']);
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-manager')
        ->set('name', 'Existing Team')
        ->call('createTeam')
        ->assertHasErrors(['name' => 'unique']);
});
