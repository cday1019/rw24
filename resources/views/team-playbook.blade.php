<x-layouts::app :title="__('Team Playbook')">
    <div class="max-w-4xl mx-auto space-y-6 py-6 px-3 sm:px-0">
        <!-- Header -->
        <div class="border-b border-zinc-200 dark:border-zinc-800 pb-4">
            <flux:heading size="xl">📖 Team Race Playbook & Operational Guide</flux:heading>
            <flux:subheading class="text-base">
                Everything you need to know about team comms, manifest handoffs, live map navigation, and bonus checkpoint strategy.
            </flux:subheading>
        </div>

        <!-- Section 1: Emergency & Home Base Info -->
        <flux:card class="border-emerald-500/40 bg-zinc-900/90">
            <div class="space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <flux:badge color="emerald" size="sm">HQ & SOS</flux:badge>
                        <flux:heading size="lg" class="text-emerald-400">🏠 Home Base & Race Emergency</flux:heading>
                    </div>
                    <span class="text-xs font-mono text-zinc-400">RW24 2026 Edition</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 space-y-1">
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Team HQ / Base Spot</span>
                        <p class="text-sm font-semibold text-white">606 E Meinecke Ave</p>
                        <p class="text-xs text-zinc-400">Pinned in <strong class="text-emerald-400">Emerald Green</strong> on the live map with 1-tap directions.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 space-y-1">
                        <span class="text-xs font-bold text-red-400 uppercase tracking-wider">Emergency Contacts</span>
                        <p class="text-sm font-semibold text-white">Race Marshall: <span class="font-mono text-amber-400">414-316-0414</span></p>
                        <p class="text-xs text-zinc-400">Medical / Serious Injury: <strong class="text-red-400">Call 911 Immediately</strong></p>
                    </div>
                </div>

                <div class="p-3 rounded-lg bg-zinc-950/50 border border-zinc-800 text-xs text-zinc-300 flex items-center justify-between flex-wrap gap-2">
                    <span>🪖 <strong>Helmets required</strong> at all times while riding.</span>
                    <span>💡 <strong>Bike lights required</strong> from dusk 'til dawn.</span>
                    <span>🛑 Respect traffic laws—course is open!</span>
                </div>
            </div>
        </flux:card>

        <!-- Section 2: The Manifest Rule #1 & Telemetry -->
        <flux:card class="border-emerald-500/40 bg-zinc-900/90">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <flux:badge color="emerald" size="sm">Rule #1</flux:badge>
                    <flux:heading size="lg" class="text-emerald-400">📜 Always Track the Manifest!</flux:heading>
                </div>
                <p class="text-sm text-zinc-300 leading-relaxed">
                    Without the physical manifest, main laps and bonus checkpoints don't count! Our app keeps a live <strong class="text-emerald-400">MANIFEST HOLDER</strong> badge attached to whoever currently holds the physical card.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-zinc-300 pt-1">
                    <div class="p-3 rounded-lg bg-emerald-950/50 border border-emerald-500/30">
                        <strong class="text-emerald-400 block mb-1">Auto-Handshake Feature:</strong>
                        When you switch your status to <em>"Riding (Has Manifest)"</em> or <em>"Bonus Checkpoint (Has Manifest)"</em>, the app automatically strips the badge from the previous holder. No double-manifest confusion!
                    </div>
                    <div class="p-3 rounded-lg bg-zinc-950/60 border border-zinc-800">
                        <strong class="text-indigo-400 block mb-1">Live Telemetry & Recency:</strong>
                        Your roster displays live rider speed (mph), battery level (🔋), and a compact relative time tag (e.g., <code class="text-zinc-400">12s ago</code>, <code class="text-zinc-400">2m ago</code>) so the pit crew knows if data is fresh.
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Section 3: Roster Statuses Reference Table -->
        <flux:card class="bg-zinc-900/90 border-zinc-800">
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">👥 Team Roster Statuses</flux:heading>
                    <flux:subheading class="text-sm">
                        Keep your status updated on the <strong>Team Roster</strong> so the crew always knows who is on course, who gets the manifest next, and who is awake to help.
                    </flux:subheading>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Status Badge') }}</flux:table.column>
                        <flux:table.column>{{ __('Manifest?') }}</flux:table.column>
                        <flux:table.column>{{ __('What It Means') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="success" size="sm">📜 Riding (Has Manifest)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-emerald-400 font-bold">YES</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">Out on course riding main laps with the physical manifest card.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="emerald" size="sm">🎯 Bonus (Has Manifest)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-emerald-400 font-bold">YES</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">At a bonus checkpoint getting the physical manifest stamped for bonus points!</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="warning" size="sm">⏱️ On Deck (Gets Manifest Next)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-amber-400 font-semibold">NEXT</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">Staged in the pit area/HQ, geared up and ready for the upcoming handoff.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="purple" size="sm">⏳ Bonus (No Manifest)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">Holding a spot in line or scouting a bonus venue before the manifest arrives.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="indigo" size="sm">🚲 Riding (Support)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">Riding along for drafting, safety, or night pacing without the manifest.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row class="bg-sky-950/30">
                            <flux:table.cell>
                                <flux:badge variant="sky" size="sm">🙋 Available / Ready</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="font-medium text-sky-300">
                                Rested & ready to help! Down to do food runs, bonus scouts, or emergency relief.
                            </flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="neutral" size="sm">⛺ Off Duty</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="text-zinc-400">Sleeping, eating, or off shift. Do not disturb unless SOS.</flux:table.cell>
                        </flux:table.row>
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>

        <!-- Section 4: Live Map & Pin Legend -->
        <flux:card class="bg-zinc-900/90 border-zinc-800">
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">🗺️ Live Race Map & Pin Guide</flux:heading>
                    <flux:subheading class="text-sm">
                        The live map automatically zooms and centers on the official Riverwest loop and Home Base on load. Floating labels are stripped for a clean screen, but tapping any pin opens a popup with 1-tap navigation!
                    </flux:subheading>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-zinc-950/80 border border-emerald-500/30">
                        <div class="font-bold text-emerald-400 mb-1 flex items-center gap-1">
                            <span>🏠</span> Green Pin
                        </div>
                        <p class="text-zinc-300"><strong>Home Base:</strong> 606 E Meinecke Ave. Tap for instant Google Maps directions.</p>
                    </div>

                    <div class="p-3 rounded-xl bg-zinc-950/80 border border-pink-500/30">
                        <div class="font-bold text-pink-400 mb-1 flex items-center gap-1">
                            <span>🩷</span> Pink Circle
                        </div>
                        <p class="text-zinc-300"><strong>Standard Checkpoints:</strong> Official route checkpoints along the KML course loop.</p>
                    </div>

                    <div class="p-3 rounded-xl bg-zinc-950/80 border border-amber-500/30">
                        <div class="font-bold text-amber-400 mb-1 flex items-center gap-1">
                            <span>🟧</span> Amber Pin
                        </div>
                        <p class="text-zinc-300"><strong>Bonus Open Now:</strong> Bonus checkpoint currently open (+2 pts). Tap to view location & details.</p>
                    </div>

                    <div class="p-3 rounded-xl bg-zinc-950/80 border border-sky-500/30">
                        <div class="font-bold text-sky-400 mb-1 flex items-center gap-1">
                            <span>🩵</span> Cyan Pin
                        </div>
                        <p class="text-zinc-300"><strong>Opening Soon:</strong> Bonus checkpoint opening within the next hour so scouts can head over early.</p>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Section 5: Bonus Checkpoints Strategy Engine -->
        <flux:card class="border-amber-500/40 bg-zinc-900/90">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <flux:badge color="amber" size="sm">+2 PTS EACH</flux:badge>
                        <flux:heading size="lg" class="text-amber-400">🎯 Bonus Checkpoint Strategy</flux:heading>
                    </div>
                </div>

                <p class="text-sm text-zinc-300 leading-relaxed">
                    All 37 official 2026 bonus checkpoints are pre-loaded in our database and default to <strong class="text-amber-400 font-mono">2 points each</strong>.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 space-y-1">
                        <strong class="text-white block">1. Auto-Geocoding</strong>
                        <p class="text-zinc-400">Addresses auto-convert to GPS coordinates via Google Maps. Local landmarks (Wu-Tang Park, Rat Statue, Snail's Crossing) have custom hardcoded fallback coordinates.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 space-y-1">
                        <strong class="text-white block">2. Runner Assignments</strong>
                        <p class="text-zinc-400">Assign specific riders to specific bonus checkpoints from the table dropdown so nobody duplicates effort or misses a window.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-zinc-950/80 border border-zinc-800 space-y-1">
                        <strong class="text-white block">3. One-Tap Stamping</strong>
                        <p class="text-zinc-400">Once the physical manifest gets stamped at a bonus venue, tap <span class="text-emerald-400 font-bold">✓ Stamped</span> in the app to instantly credit +2 pts to the team score header!</p>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Section 6: Chat Channels -->
        <flux:card class="bg-zinc-900/90 border-zinc-800">
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">💬 Team Chat Channels</flux:heading>
                    <flux:subheading class="text-sm">
                        The team chat is split into 3 dedicated channels to keep tactical comms clear:
                    </flux:subheading>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <!-- Channel 1: War Room -->
                    <div class="p-4 rounded-xl bg-zinc-950/80 border border-zinc-700/60 space-y-2">
                        <div class="flex items-center justify-between border-b border-zinc-800 pb-2">
                            <span class="font-bold text-white flex items-center gap-1.5">
                                <span>⚔️</span> War Room
                            </span>
                            <flux:badge size="sm" variant="neutral">Channel 1</flux:badge>
                        </div>
                        <p class="text-xs text-zinc-300 leading-relaxed">
                            <strong class="text-white">Tactical & Race Comms.</strong> Use this for handoff callouts, lap times, rider rotations, and manifest location updates.
                        </p>
                    </div>

                    <!-- Channel 2: SOS -->
                    <div class="p-4 rounded-xl bg-red-950/30 border border-red-500/40 space-y-2">
                        <div class="flex items-center justify-between border-b border-red-500/20 pb-2">
                            <span class="font-bold text-red-400 flex items-center gap-1.5">
                                <span>🚨</span> SOS Channel
                            </span>
                            <flux:badge size="sm" color="red">Channel 2</flux:badge>
                        </div>
                        <p class="text-xs text-zinc-300 leading-relaxed">
                            <strong class="text-red-300">Emergency & Mechanicals.</strong> Flatted? Mechanical? Need a tube or crash help? Post here. Unread messages trigger a glowing red visual alert for the team!
                        </p>
                    </div>

                    <!-- Channel 3: Vibe Ward -->
                    <div class="p-4 rounded-xl bg-blue-950/30 border border-blue-500/30 space-y-2">
                        <div class="flex items-center justify-between border-b border-blue-500/20 pb-2">
                            <span class="font-bold text-blue-400 flex items-center gap-1.5">
                                <span>🎉</span> Vibe Ward
                            </span>
                            <flux:badge size="sm" color="blue">Channel 3</flux:badge>
                        </div>
                        <p class="text-xs text-zinc-300 leading-relaxed">
                            <strong class="text-blue-300">Photos & Morale.</strong> Upload photos from the course, share memes, hype up the team, and post race banter!
                        </p>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Section 7: Mobile Reliability & Race Night Tips -->
        <flux:card class="border-amber-500/40 bg-zinc-900/90">
            <div class="space-y-3">
                <flux:heading size="lg" class="text-amber-400">⚡ Mobile Performance & Race Night Checklist</flux:heading>
                <ul class="list-disc list-inside space-y-2 text-sm text-zinc-300">
                    <li><strong class="text-white">Mobile Keyboard Tip:</strong> When typing in chat, pressing <span class="text-emerald-400 font-bold">Go / Send</span> on your iPhone or Android virtual keyboard submits the message instantly without losing focus.</li>
                    <li><strong class="text-white">Smart Polling (`wire:poll.visible`):</strong> Background updates pause automatically when your phone is locked or the tab is off-screen, saving battery and keeping button taps responsive.</li>
                    <li><strong class="text-white">OwnTracks Location Tracking:</strong> Set tracking mode to <span class="text-emerald-400 font-bold">Move</span> while riding so your speed and location update every 10–30 seconds. Confirm location permission is set to <em>"Always Allow"</em> with <em>"Precise Location"</em> enabled.</li>
                    <li><strong class="text-white">Power Banks:</strong> Continuous GPS and map rendering consume battery quickly. Keep a power bank hooked up in your jersey pocket or at Home Base!</li>
                </ul>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
