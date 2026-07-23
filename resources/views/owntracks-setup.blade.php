<x-layouts::app :title="__('OwnTracks Setup')">
    <div class="max-w-4xl mx-auto space-y-6 py-6">
        <!-- Header -->
        <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <flux:heading size="xl">📱 OwnTracks GPS Setup Guide</flux:heading>
            <flux:subheading class="text-base">
                Follow this quick guide to share your phone's live location with the team race map.
            </flux:subheading>
        </div>

        <!-- Step 1: Copy Endpoint Card -->
        <flux:card class="bg-zinc-900 border-indigo-500/30">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg" class="text-indigo-400">1. Copy Endpoint URL</flux:heading>
                    <flux:badge color="indigo" size="sm">Required for Step 3</flux:badge>
                </div>
                <p class="text-sm text-neutral-300">
                    Tap the button below to copy your unique location endpoint URL:
                </p>
                <div class="flex items-center gap-2 pt-1">
                    <flux:input readonly value="{{ request()->schemeAndHttpHost() }}/api/locations-webhook" id="endpoint-url" class="font-mono text-sm bg-zinc-950 text-emerald-400 font-bold" />
                    <flux:button icon="clipboard" variant="primary" onclick="navigator.clipboard.writeText(document.getElementById('endpoint-url').value); alert('URL copied to clipboard!');">
                        Copy URL
                    </flux:button>
                </div>
            </div>
        </flux:card>

        <!-- Step 2: Download & Initial Permissions -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">2. Install App & Allow ALL Permissions</flux:heading>
                <p class="text-sm text-neutral-300">Download the official free app on your phone:</p>
                <div class="flex flex-wrap gap-3 pt-1">
                    <flux:button href="https://apps.apple.com/us/app/owntracks/id989222396" target="_blank" variant="subtle" icon="arrow-top-right-on-square">
                        iPhone / iOS App Store
                    </flux:button>
                    <flux:button href="https://play.google.com/store/apps/details?id=org.owntracks.android" target="_blank" variant="subtle" icon="arrow-top-right-on-square">
                        Android / Google Play Store
                    </flux:button>
                </div>

                <div class="p-3.5 rounded-lg bg-amber-500/10 border border-amber-500/30 space-y-1.5 text-sm">
                    <div class="font-bold text-amber-400 flex items-center gap-2">
                        ⚠️ Important First-Launch Step:
                    </div>
                    <p class="text-neutral-300">
                        When you open OwnTracks for the first time, <strong>accept / allow all permissions it requests</strong> (Location, Motion & Fitness, Notifications, and Local Network).
                    </p>
                </div>
            </div>
        </flux:card>

        <!-- Step 3: Exact Screen Settings -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">3. Configure App Settings</flux:heading>
                <p class="text-sm text-neutral-300">
                    Open OwnTracks, tap <strong>(i) Status Info</strong> (top left), then tap <strong>Settings</strong> and match these exact values:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <!-- iOS Screen Fields -->
                    <div class="p-4 rounded-xl bg-zinc-800/60 border border-zinc-700/60 space-y-3 text-sm">
                        <div class="font-bold text-white flex items-center gap-2 border-b border-zinc-700 pb-2">
                            <span>🍎 iPhone / iOS Settings Screen</span>
                        </div>
                        <ul class="space-y-2 text-neutral-300 font-mono text-xs">
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">Mode:</span>
                                <span class="text-emerald-400 font-bold">HTTP</span>
                            </li>
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">UserID:</span>
                                <span class="text-indigo-300 font-bold">First Name Lowercase (e.g. chad)</span>
                            </li>
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">TrackerID:</span>
                                <span class="text-indigo-300 font-bold">Initials UPPERCASE (e.g. CD)</span>
                            </li>
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">TLS:</span>
                                <span class="text-emerald-400 font-bold">ON (Green Switch)</span>
                            </li>
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">Authentication:</span>
                                <span class="text-amber-400 font-bold">OFF</span>
                            </li>
                            <li class="bg-zinc-900/80 p-2 rounded space-y-1">
                                <span class="text-neutral-400 block">URL Field (at bottom):</span>
                                <span class="text-emerald-400 font-bold break-all block">{{ request()->schemeAndHttpHost() }}/api/locations-webhook</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Android Screen Fields -->
                    <div class="p-4 rounded-xl bg-zinc-800/60 border border-zinc-700/60 space-y-3 text-sm">
                        <div class="font-bold text-white flex items-center gap-2 border-b border-zinc-700 pb-2">
                            <span>🤖 Android Settings Screen</span>
                        </div>
                        <p class="text-xs text-neutral-400">Menu ➔ Preferences ➔ Connection:</p>
                        <ul class="space-y-2 text-neutral-300 font-mono text-xs">
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">Mode:</span>
                                <span class="text-emerald-400 font-bold">HTTP Private</span>
                            </li>
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">Username / UserID:</span>
                                <span class="text-indigo-300 font-bold">First Name Lowercase (e.g. kyle)</span>
                            </li>
                            <li class="flex justify-between items-center bg-zinc-900/80 p-2 rounded">
                                <span class="text-neutral-400">Tracker ID:</span>
                                <span class="text-indigo-300 font-bold">Initials UPPERCASE (e.g. KH)</span>
                            </li>
                            <li class="bg-zinc-900/80 p-2 rounded space-y-1">
                                <span class="text-neutral-400 block">Host / Endpoint URL:</span>
                                <span class="text-emerald-400 font-bold break-all block">{{ request()->schemeAndHttpHost() }}/api/locations-webhook</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Step 4: Background Permissions -->
        <flux:card class="border-amber-500/30">
            <div class="space-y-3">
                <flux:heading size="lg" class="text-amber-400">4. Set Location to "Always Allow"</flux:heading>
                <p class="text-sm text-neutral-300">
                    To keep tracking active while your phone is locked or in your pocket during the race:
                </p>
                <ul class="list-disc list-inside space-y-1.5 text-sm text-neutral-300">
                    <li>Go to your phone's main <strong>Settings ➔ OwnTracks ➔ Location</strong>.</li>
                    <li>Change location permission to <strong class="text-white">"Always Allow"</strong>.</li>
                    <li>Ensure <strong class="text-white">"Precise Location"</strong> is turned <strong>ON</strong>.</li>
                </ul>
            </div>
        </flux:card>

        <!-- Step 5: Test Ping -->
        <flux:card>
            <div class="space-y-3">
                <flux:heading size="lg">5. Test Connection</flux:heading>
                <ol class="list-decimal list-inside space-y-2 text-sm text-neutral-300">
                    <li>Go back to the main OwnTracks app screen.</li>
                    <li>Tap the <strong>Upload/Publish arrow</strong> icon in the top right corner.</li>
                    <li>Go to <strong>(i) Status Info</strong>. Verify <strong>HTTP Response</strong> displays <span class="text-emerald-400 font-bold">200 OK</span>.</li>
                </ol>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
