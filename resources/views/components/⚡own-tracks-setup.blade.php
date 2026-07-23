<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public bool $hasPinged = false;

    public function mount()
    {
        $this->checkConnection();
    }

    public function getListeners()
    {
        $teamId = Auth::user()?->team_id;

        return $teamId ? [
            "echo-private:team.{$teamId},LocationUpdated" => 'checkConnection',
        ] : [];
    }

    public function checkConnection()
    {
        $latestLocation = Auth::user()?->latestLocation;

        if ($latestLocation && $latestLocation->pinged_at && $latestLocation->pinged_at->gt(now()->subHours(24))) {
            $this->hasPinged = true;
        }
    }
};
?>

<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <flux:heading size="xl">{{ __('OwnTracks GPS Setup Guide') }}</flux:heading>
        <flux:subheading>{{ __('Follow these steps to connect your phone telemetry to the race map.') }}</flux:subheading>
    </div>

    <!-- Real-time Status Banner -->
    @if ($hasPinged)
        <flux:card class="border-green-500/30 bg-green-500/10 text-green-400">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <span class="font-bold block">{{ __('Phone Connected Successfully!') }}</span>
                    <span class="text-xs text-neutral-300">{{ __('Your server is actively receiving location telemetry from your device.') }}</span>
                </div>
            </div>
        </flux:card>
    @else
        <flux:card class="border-amber-500/30 bg-amber-500/10 text-amber-400">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📡</span>
                <div>
                    <span class="font-bold block">{{ __('Waiting for First Location Ping...') }}</span>
                    <span class="text-xs text-neutral-300">{{ __('Complete the setup steps below and tap the Send button in OwnTracks.') }}</span>
                </div>
            </div>
        </flux:card>
    @endif

    <!-- Step 1: Download App -->
    <flux:card>
        <div class="space-y-3">
            <flux:heading size="lg">1. {{ __('Download OwnTracks') }}</flux:heading>
            <p class="text-sm text-neutral-400">{{ __('Download the official OwnTracks app on your phone:') }}</p>
            <div class="flex gap-3 pt-2">
                <flux:button href="https://apps.apple.com/us/app/owntracks/id989222396" target="_blank" variant="subtle" icon="arrow-top-right-on-square">iOS App Store</flux:button>
                <flux:button href="https://play.google.com/store/apps/details?id=org.owntracks.android" target="_blank" variant="subtle" icon="arrow-top-right-on-square">Google Play Store</flux:button>
            </div>
        </div>
    </flux:card>

    <!-- Step 2: Configure Endpoint -->
    <flux:card>
        <div class="space-y-4">
            <flux:heading size="lg">2. {{ __('Configure Connection Settings') }}</flux:heading>

            <ol class="list-decimal list-inside space-y-2 text-sm text-neutral-300">
                <li>{{ __('Open OwnTracks -> Settings -> Connection.') }}</li>
                <li>{{ __('Set Mode to:') }} <strong class="text-white">HTTP</strong></li>
                <li>{{ __('Copy and paste this URL into the URL field:') }}</li>
            </ol>

            <div class="flex items-center gap-2 pt-2">
                <flux:input readonly value="{{ request()->schemeAndHttpHost() }}/api/location" class="font-mono text-sm" />
                <flux:button icon="clipboard" @click="navigator.clipboard.writeText('{{ request()->schemeAndHttpHost() }}/api/location')">Copy</flux:button>
            </div>
        </div>
    </flux:card>

    <!-- Step 3: Test Ping -->
    <flux:card>
        <div class="space-y-3">
            <flux:heading size="lg">3. {{ __('Send Test Location Ping') }}</flux:heading>
            <ol class="list-decimal list-inside space-y-2 text-sm text-neutral-300">
                <li>{{ __('In OwnTracks, tap the Status/Info icon.') }}</li>
                <li>{{ __('Verify HTTP Status displays:') }} <span class="text-green-400 font-bold">200 OK</span></li>
                <li>{{ __('Tap the Upload/Publish arrow in the top right to send your ping.') }}</li>
            </ol>
        </div>
    </flux:card>
</div>
