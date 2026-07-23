<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6 py-6">
        <div>
            <flux:heading size="xl">OwnTracks GPS Setup Guide</flux:heading>
            <flux:subheading>Follow these steps to connect your phone telemetry to the race map.</flux:subheading>
        </div>

        <!-- Step 1: Download App -->
        <flux:card>
            <div class="space-y-3">
                <flux:heading size="lg">1. Download OwnTracks</flux:heading>
                <p class="text-sm text-neutral-400">Install the official OwnTracks app on your mobile device:</p>
                <div class="flex gap-3 pt-2">
                    <flux:button href="https://apps.apple.com/us/app/owntracks/id989222396" target="_blank" variant="subtle" icon="arrow-top-right-on-square">iOS App Store</flux:button>
                    <flux:button href="https://play.google.com/store/apps/details?id=org.owntracks.android" target="_blank" variant="subtle" icon="arrow-top-right-on-square">Google Play Store</flux:button>
                </div>
            </div>
        </flux:card>

        <!-- Step 2: Configure Endpoint -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">2. Configure Connection Settings</flux:heading>

                <ol class="list-decimal list-inside space-y-2 text-sm text-neutral-300">
                    <li>Open OwnTracks -> <strong>Settings</strong> -> <strong>Connection</strong>.</li>
                    <li>Set <strong>Mode</strong> to: <strong class="text-white">HTTP</strong></li>
                    <li>Copy and paste this URL into the <strong>URL</strong> field:</li>
                </ol>

                <div class="flex items-center gap-2 pt-2">
                    <flux:input readonly value="{{ request()->schemeAndHttpHost() }}/api/location" id="endpoint-url" class="font-mono text-sm" />
                    <flux:button icon="clipboard" onclick="navigator.clipboard.writeText(document.getElementById('endpoint-url').value)">Copy</flux:button>
                </div>
            </div>
        </flux:card>

        <!-- Step 3: Test Ping -->
        <flux:card>
            <div class="space-y-3">
                <flux:heading size="lg">3. Send Test Location Ping</flux:heading>
                <ol class="list-decimal list-inside space-y-2 text-sm text-neutral-300">
                    <li>In OwnTracks, tap the <strong>Info / Status</strong> icon.</li>
                    <li>Verify <strong>HTTP Status</strong> displays: <span class="text-green-400 font-bold">200 OK</span></li>
                    <li>Tap the <strong>Publish / Upload arrow</strong> in the top right corner to send your ping.</li>
                </ol>
            </div>
        </flux:card>
    </div>
</x-app-layout>
