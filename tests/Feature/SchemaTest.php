<?php

use App\Models\Checkpoint;
use App\Models\Lap;
use App\Models\Message;
use App\Models\RaceState;
use App\Models\TelemetryLog;
use App\Models\User;

test('users can have roles and status', function () {
    $user = User::factory()->create([
        'role' => 'scout',
        'status' => 'on_deck',
    ]);

    expect($user->role)->toBe('scout')
        ->and($user->status)->toBe('on_deck');
});

test('messages can be created for different channels', function () {
    $user = User::factory()->create();

    $warRoomMsg = Message::factory()->warRoom()->create(['user_id' => $user->id]);
    $vibeWardMsg = Message::factory()->vibeWard()->create(['user_id' => $user->id]);

    expect($warRoomMsg->channel)->toBe('war_room')
        ->and($vibeWardMsg->channel)->toBe('vibe_ward')
        ->and($user->messages)->toHaveCount(2);
});

test('telemetry logs can be recorded', function () {
    $user = User::factory()->create();

    $log = TelemetryLog::factory()->create(['user_id' => $user->id]);

    expect($log->user_id)->toBe($user->id)
        ->and($user->telemetryLogs)->toHaveCount(1);
});

test('global manifest holder can be set in race state', function () {
    $user = User::factory()->create();

    $raceState = RaceState::factory()->create(['manifest_holder_id' => $user->id]);

    expect($raceState->manifestHolder->id)->toBe($user->id);
});

test('checkpoints can be claimed by ghost waiters', function () {
    $scout = User::factory()->create(['role' => 'scout']);
    $checkpoint = Checkpoint::factory()->create(['ghost_waiter_id' => $scout->id]);

    expect($checkpoint->ghostWaiter->id)->toBe($scout->id);
});

test('laps can be recorded for users at checkpoints', function () {
    $rider = User::factory()->create(['role' => 'rider']);
    $checkpoint = Checkpoint::factory()->create();

    $lap = Lap::factory()->create([
        'user_id' => $rider->id,
        'checkpoint_id' => $checkpoint->id,
    ]);

    expect($lap->user->id)->toBe($rider->id)
        ->and($lap->checkpoint->id)->toBe($checkpoint->id)
        ->and($rider->laps)->toHaveCount(1);
});
