<?php

use App\Models\Message;
use App\Models\User;
use Livewire\Livewire;

it('renders the team chat component', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-chat')
        ->assertSee('War Room')
        ->assertSee('SOS')
        ->assertSee('Vibe Ward');
});

it('can send a message to the war room', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-chat')
        ->set('body', 'Hello War Room!')
        ->call('sendMessage')
        ->assertSet('body', '');

    $this->assertDatabaseHas('messages', [
        'user_id' => $user->id,
        'channel_id' => 1,
        'body' => 'Hello War Room!',
    ]);
});

it('forces the active channel id when sending a message', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-chat')
        ->set('activeChannel', 2)
        ->set('body', 'Help needed!')
        ->call('sendMessage');

    $this->assertDatabaseHas('messages', [
        'channel_id' => 2,
        'body' => 'Help needed!',
    ]);
});

it('sanitizes text input', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-chat')
        ->set('body', '<script>alert("xss")</script>')
        ->call('sendMessage');

    $message = Message::first();
    expect($message->body)->not->toContain('<script>');
});

it('prevents image path processing for channel 1 and 2', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-chat')
        ->set('activeChannel', 1)
        ->set('body', 'Normal message')
        ->set('image_path', 'secret/path.jpg')
        ->call('sendMessage');

    $this->assertDatabaseHas('messages', [
        'channel_id' => 1,
        'image_path' => null,
    ]);
});

it('allows image path for channel 3', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('team-chat')
        ->set('activeChannel', 3)
        ->set('body', 'Look at this!')
        ->set('image_path', 'vibe/path.jpg')
        ->call('sendMessage');

    $this->assertDatabaseHas('messages', [
        'channel_id' => 3,
        'image_path' => 'vibe/path.jpg',
    ]);
});

it('detects SOS alert', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    // No SOS message yet
    Livewire::test('team-chat')
        ->assertSet('hasSosAlert', false);

    // Create SOS message from 10 minutes ago
    Message::factory()->create([
        'user_id' => $user->id,
        'channel_id' => 2,
        'body' => 'SOS!',
        'created_at' => now()->subMinutes(10),
    ]);

    Livewire::test('team-chat')
        ->assertSet('hasSosAlert', true);

    // Old SOS message (40 mins ago)
    Message::query()->delete();
    Message::factory()->create([
        'user_id' => $user->id,
        'channel_id' => 2,
        'body' => 'OLD SOS!',
        'created_at' => now()->subMinutes(40),
    ]);

    Livewire::test('team-chat')
        ->assertSet('hasSosAlert', false);
});
