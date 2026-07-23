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
        <flux:card class="border-emerald-500/30 bg-emerald-500/5">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <flux:badge color="emerald" size="sm">Rule #1</flux:badge>
                    <flux:heading size="lg" class="text-emerald-500 dark:text-emerald-400">📜 Always Track the Manifest!</flux:heading>
                </div>
                <flux:text class="text-sm leading-relaxed">
                    Without the physical manifest, laps and bonus checkpoints don't count! Our app keeps a live <strong class="text-emerald-600 dark:text-emerald-400">MANIFEST HOLDER</strong> badge attached to whoever currently has the card.
                </flux:text>
                <div class="p-3.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-xs">
                    <strong class="text-emerald-600 dark:text-emerald-400">Auto-Handshake Feature:</strong> When you set your status to <em>"Riding (Has Manifest)"</em> or <em>"Bonus Checkpoint (Has Manifest)"</em>, the app automatically removes the badge from the previous holder. No double-manifest confusion!
                </div>
            </div>
        </flux:card>

        <!-- Section 2: Roster Statuses Reference Table -->
        <flux:card>
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
                            <flux:table.cell class="text-emerald-500 font-bold">YES</flux:table.cell>
                            <flux:table.cell>Out on course riding main laps with the physical manifest card.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="emerald" size="sm">🎯 Bonus (Has Manifest)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-emerald-500 font-bold">YES</flux:table.cell>
                            <flux:table.cell>At a bonus checkpoint getting the manifest stamped for bonus points!</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="warning" size="sm">⏱️ On Deck (Gets Manifest Next)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-amber-500 font-semibold">NEXT</flux:table.cell>
                            <flux:table.cell>Staged in the pit area, geared up and ready for the upcoming handoff.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="purple" size="sm">⏳ Bonus (No Manifest)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell>Holding a spot in line or scouting a bonus checkpoint before the manifest arrives.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="indigo" size="sm">🚲 Riding (Support)</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell>Riding along for drafting, safety, or night pacing without the manifest.</flux:table.cell>
                        </flux:table.row>

                        <flux:table.row class="bg-sky-500/5">
                            <flux:table.cell>
                                <flux:badge variant="sky" size="sm">🙋 Available / Ready</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="font-medium text-sky-600 dark:text-sky-400">
                                Benched & ready to help! Down to do food runs, bonus stops, or emergency relief.
                            </flux:table.cell>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:table.cell>
                                <flux:badge variant="neutral" size="sm">⛺ Off Duty</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-400">NO</flux:table.cell>
                            <flux:table.cell class="text-zinc-500">Sleeping, eating, or off shift. Do not disturb unless SOS.</flux:table.cell>
                        </flux:table.row>
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>

        <!-- Section 3: Chat Channels -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">💬 Team Chat Channels</flux:heading>
                <flux:subheading>
                    The team chat is split into 3 dedicated channels to keep tactical comms clear:
                </flux:subheading>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <!-- Channel 1 -->
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700/60 space-y-2">
                        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
                            <span class="font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                                <span>⚔️</span> War Room
                            </span>
                            <flux:badge size="sm" variant="neutral">Channel 1</flux:badge>
                        </div>
                        <flux:text class="text-xs leading-relaxed">
                            <strong>Tactical & Race Comms.</strong> Use this for handoff callouts, lap times, rider rotations, and manifest location updates.
                        </flux:text>
                    </div>

                    <!-- Channel 2 -->
                    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 space-y-2">
                        <div class="flex items-center justify-between border-b border-red-500/20 pb-2">
                            <span class="font-bold text-red-500 dark:text-red-400 flex items-center gap-1.5">
                                <span>🚨</span> SOS Channel
                            </span>
                            <flux:badge size="sm" color="red">Channel 2</flux:badge>
                        </div>
                        <flux:text class="text-xs leading-relaxed">
                            <strong>Emergency & Mechanicals.</strong> Flatted? Mechanical? Need a tube or crash help? Post here. Unread messages trigger a glowing red visual alert for the team!
                        </flux:text>
                    </div>

                    <!-- Channel 3 -->
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700/60 space-y-2">
                        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
                            <span class="font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                                <span>🎉</span> Vibe Ward
                            </span>
                            <flux:badge size="sm" color="blue">Channel 3</flux:badge>
                        </div>
                        <flux:text class="text-xs leading-relaxed">
                            <strong>Photos & Morale.</strong> Upload photos from the course, share memes, hype up the team, and post race banter!
                        </flux:text>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Section 4: Race Night Quick Tips -->
        <flux:card class="border-amber-500/30 bg-amber-500/5">
            <div class="space-y-3">
                <flux:heading size="lg" class="text-amber-500 dark:text-amber-400">⚡ Race Night Survival Checklist</flux:heading>
                <ul class="list-disc list-inside space-y-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <li><strong class="text-zinc-900 dark:text-white">OwnTracks in "Move" Mode:</strong> When you jump on the bike for your shift, ensure OwnTracks is in <span class="text-emerald-600 dark:text-emerald-400 font-bold">Move</span> mode so your live speed and map position update every 10–30 seconds.</li>
                    <li><strong class="text-zinc-900 dark:text-white">Keep Location on "Always":</strong> Confirm iOS/Android permissions remain set to <em>"Always Allow"</em> with <em>"Precise Location"</em> enabled so pings send while your phone is locked.</li>
                    <li><strong class="text-zinc-900 dark:text-white">Battery Check:</strong> OwnTracks continuous GPS consumes battery quickly. Keep a power bank hooked up in your jersey pocket or pit tent!</li>
                </ul>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
