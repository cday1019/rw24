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
                        <span class="text-neutral-400">Authentication:</span>
                        <span class="text-amber-400 font-bold">OFF</span>
                    </li>
                    <li class="bg-zinc-900/80 p-2 rounded space-y-1">
                        <span class="text-neutral-400 block">URL Field (at bottom - MUST include https://):</span>
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
