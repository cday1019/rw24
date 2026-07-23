<x-layouts::app :title="__('Team Playbook')">
    <div class="max-w-4xl mx-auto space-y-6 py-6">
        <!-- Header -->
        <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <flux:heading size="xl">📖 Team Race Playbook & Guide</flux:heading>
            <flux:subheading class="text-base">
                Everything you need to know about team communication, manifest tracking, and roster statuses.
            </flux:subheading>
        </div>

        <!-- Section 1: The Manifest Rule #1 -->
        <flux:card class="border-emerald-500/40 bg-zinc-900/90">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <flux:badge color="emerald" size="sm">Rule #1</flux:badge>
                    <flux:heading size="lg" class="text-emerald-400">📜 Always Track the Manifest!</flux:heading>
                </div>
                <p class="text-sm text-zinc-300 leading-relaxed">
                    Without the physical manifest, laps and bonus checkpoints don't count! Our app keeps a live <strong class="text-emerald-400">MANIFEST HOLDER</strong> badge attached to whoever currently has the card.
                </p>
                <div class="p-3 rounded-lg bg-emerald-950/50 border border-emerald-500/30 text-xs text-zinc-300">
                    <strong class="text-emerald-400">Auto-Handshake Feature:</strong> When you set your status to <em>"Riding (Has Manifest)"</em> or <em>"Bonus Checkpoint (Has Manifest)"</em>, the app automatically removes the badge from the previous holder. No double-manifest confusion!
                </div>
            </div>
        </flux:card>

        <!-- Section 2: Roster Statuses Reference Table -->
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
                            <flux:table.cell class="text-zinc-300">At a bonus checkpoint getting the manifest stamped for bonus points!</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="warning" size="sm">⏱️ On Deck (Gets Manifest Next)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-amber-400 font-semibold">NEXT</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">Staged in the pit area, geared up and ready for the upcoming handoff.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="purple" size="sm">⏳ Bonus (No Manifest)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="text-zinc-300">Holding a spot in line or scouting a bonus checkpoint before the manifest arrives.</flux:table.cell>
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
                                Benched & ready to help! Down to do food runs, bonus stops, or emergency relief.
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

        <!-- Section 3: Chat Channels -->
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

        <!-- Section 4: Race Night Quick Tips -->
        <flux:card class="border-amber-500/40 bg-zinc-900/90">
            <div class="space-y-3">
                <flux:heading size="lg" class="text-amber-400">⚡ Race Night Survival Checklist</flux:heading>
                <ul class="list-disc list-inside space-y-2 text-sm text-zinc-300">
                    <li><strong class="text-white">OwnTracks in "Move" Mode:</strong> When you jump on the bike for your shift, ensure OwnTracks is in <span class="text-emerald-400 font-bold">Move</span> mode so your live speed and map position update every 10–30 seconds.</li>
                    <li><strong class="text-white">Keep Location on "Always":</strong> Confirm iOS/Android permissions remain set to <em>"Always Allow"</em> with <em>"Precise Location"</em> enabled so pings send while your phone is locked.</li>
                    <li><strong class="text-white">Battery Check:</strong> OwnTracks continuous GPS consumes battery quickly. Keep a power bank hooked up in your jersey pocket or pit tent!</li>
                </ul>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
