<x-layouts::app :title="__('OwnTracks Setup')">
    <div class="max-w-4xl mx-auto space-y-6 py-6">
        <!-- Header -->
        <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <flux:heading size="xl">📱 OwnTracks GPS Setup Guide</flux:heading>
            <flux:subheading class="text-base">
                Follow this quick guide to share your phone's live location with the team race map.
            </flux:subheading>
        </div>

        <!-- Copyable Endpoint Card -->
        <flux:card class="bg-zinc-900 border-indigo-500/30">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg" class="text-indigo-400">Your Connection URL</flux:heading>
                    <flux:badge color="indigo" size="sm">Step 2 Requirement</flux:badge>
                </div>
                <p class="text-sm text-neutral-300">
                    You'll need to paste this URL into the OwnTracks app in Step 2:
                </p>
                <div class="flex items-center gap-2 pt-1">
                    <flux:input readonly value="{{ request()->schemeAndHttpHost() }}/api/location" id="endpoint-url" class="font-mono text-sm bg-zinc-950 text-emerald-400 font-bold" />
                    <flux:button icon="clipboard" variant="primary" onclick="navigator.clipboard.writeText(document.getElementById('endpoint-url').value); alert('URL copied to clipboard!');">
                        Copy URL
                    </flux:button>
                </div>
            </div>
        </flux:card>

        <!-- Step 1: Download -->
        <flux:card>
            <div class="space-y-3">
                <flux:heading size="lg">Step 1: Download the App</flux:heading>
                <p class="text-sm text-neutral-300">Download the free, open-source OwnTracks app for your phone:</p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <flux:button href="https://apps.apple.com/us/app/owntracks/id989222396" target="_blank" variant="subtle" icon="arrow-top-right-on-square">
                        iPhone / iOS App Store
                    </flux:button>
                    <flux:button href="https://play.google.com/store/apps/details?id=org.owntracks.android" target="_blank" variant="subtle" icon="arrow-top-right-on-square">
                        Android / Google Play Store
                    </flux:button>
                </div>
            </div>
        </flux:card>

        <!-- Step 2: Configure App -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">Step 2: Configure Settings (Critical)</flux:heading>

                <p class="text-sm text-neutral-300">
                    By default, OwnTracks looks for a private server. You must switch it to <strong>HTTP mode</strong>:
                </p>

                <!-- iOS vs Android Toggle / Tabs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <!-- iOS Instructions -->
                    <div class="p-4 rounded-xl bg-zinc-800/60 border border-zinc-700/60 space-y-2 text-sm">
                        <div class="font-bold text-white flex items-center gap-2">
                            <span>🍎 Apple / iPhone (iOS)</span>
                        </div>
                        <ol class="list-decimal list-inside space-y-1.5 text-neutral-300">
                            <li>Open OwnTracks & tap <strong>(i) Settings</strong> in the top left.</li>
                            <li>Tap <strong>Settings</strong> $\rightarrow$ <strong>Mode</strong>.</li>
                            <li>Select <strong>HTTP</strong> (not MQTT).</li>
                            <li>Go back to Settings & tap <strong>HTTP</strong>.</li>
                            <li>Paste the copied URL into the <strong>URL</strong> field.</li>
                            <li>Set <strong>Tracker ID</strong> to your 2-letter initials (e.g. <code class="text-indigo-300 font-mono">KH</code>).</li>
                        </ol>
                    </div>

                    <!-- Android Instructions -->
                    <div class="p-4 rounded-xl bg-zinc-800/60 border border-zinc-700/60 space-y-2 text-sm">
                        <div class="font-bold text-white flex items-center gap-2">
                            <span>🤖 Android</span>
                        </div>
                        <ol class="list-decimal list-inside space-y-1.5 text-neutral-300">
                            <li>Open OwnTracks & tap ☰ <strong>Menu</strong> $\rightarrow$ <strong>Preferences</strong>.</li>
                            <li>Tap <strong>Connection</strong> $\rightarrow$ <strong>Mode</strong>.</li>
                            <li>Select <strong>HTTP Private</strong>.</li>
                            <li>Tap <strong>Host</strong> / <strong>Endpoint URL</strong>.</li>
                            <li>Paste the copied URL into the field.</li>
                            <li>Set <strong>Tracker ID</strong> to your 2-letter initials (e.g. <code class="text-indigo-300 font-mono">KH</code>).</li>
                        </ol>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Step 3: Permissions -->
        <flux:card class="border-amber-500/30">
            <div class="space-y-3">
                <flux:heading size="lg" class="text-amber-400">Step 3: Enable Background Tracking</flux:heading>
                <p class="text-sm text-neutral-300">
                    If you don't grant background permissions, your phone will stop sending location updates the moment your screen turns off!
                </p>
                <ul class="list-disc list-inside space-y-2 text-sm text-neutral-300">
                    <li>
                        <strong>Location Access:</strong> Set to <span class="text-white font-bold">"Always Allow"</span> (not "While Using App").
                    </li>
                    <li>
                        <strong>Precise Location:</strong> Ensure <span class="text-white font-bold">"Precise Location"</span> or <span class="text-white font-bold">"High Accuracy"</span> is turned <strong>ON</strong>.
                    </li>
                    <li>
                        <strong>Battery Optimization (Android):</strong> Exclude OwnTracks from "Battery Saver" or "App Optimization" so Android doesn't kill it in the background.
                    </li>
                </ul>
            </div>
        </flux:card>

        <!-- Step 4: Verification -->
        <flux:card>
            <div class="space-y-3">
                <flux:heading size="lg">Step 4: Send Test Ping</flux:heading>
                <ol class="list-decimal list-inside space-y-2 text-sm text-neutral-300">
                    <li>In OwnTracks, return to the main map screen.</li>
                    <li>Tap the <strong>Upload/Publish arrow</strong> icon (top right corner) to send a location ping.</li>
                    <li>Open <strong>(i) Status / Info</strong> in the app. Look for <span class="text-emerald-400 font-bold">HTTP 200</span>.</li>
                    <li>If you see <strong>200 OK</strong>, you're officially tracking on the race map! 🎉</li>
                </ol>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
