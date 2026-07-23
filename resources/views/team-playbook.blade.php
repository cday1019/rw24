<x-layouts::app :title="__('Team Playbook')">
    <div class="max-w-4xl mx-auto space-y-6 py-6">
        <!-- Header -->
        <div class="border-b border-neutral-200 dark:border-zinc-700 pb-4">
            <flux:heading size="xl">📖 Team Race Playbook & Guide</flux:heading>
            <flux:subheading class="text-base">
                Everything you need to know about team communication, manifest tracking, and roster statuses.
            </flux:subheading>
        </div>

        <!-- Section 1: The Manifest Rule #1 -->
        <flux:card class="bg-gradient-to-r from-emerald-950/40 via-zinc-900 to-zinc-900 border-emerald-500/40">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <flux:badge color="emerald" size="sm">Rule #1</flux:badge>
                    <flux:heading size="lg" class="text-emerald-400">📜 Always Track the Manifest!</flux:heading>
                </div>
                <p class="text-sm text-neutral-300 leading-relaxed">
                    Without the physical manifest, laps and bonus checkpoints don't count! Our app keeps a live <strong class="text-emerald-400">MANIFEST HOLDER</strong> badge attached to whoever currently has the card.
                </p>
                <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-xs text-neutral-300">
                    <strong class="text-emerald-400">Auto-Handshake Feature:</strong> When you set your status to <em>"Riding (Has Manifest)"</em> or <em>"Bonus Checkpoint (Has Manifest)"</em>, the app automatically removes the badge from the previous holder. No double-manifest confusion!
                </div>
            </div>
        </flux:card>

        <!-- Section 2: Roster Statuses Reference Table -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">👥 Team Roster Statuses</flux:heading>
                <p class="text-sm text-neutral-300">
                    Keep your status updated on the <strong>Team Roster</strong> so the crew always knows who is on course, who gets the manifest next, and who is awake to help:
                </p>

                <div class="overflow-x-auto rounded-lg border border-neutral-200 dark:border-zinc-800">
                    <table class="w-full text-left text-xs text-neutral-300 border-collapse">
                        <thead>
                        <tr class="border-b border-neutral-200 dark:border-zinc-800 bg-neutral-100 dark:bg-zinc-900 text-neutral-700 dark:text-zinc-300 font-bold">
                            <th class="p-3">Status Badge</th>
                            <th class="p-3">Manifest?</th>
                            <th class="p-3">What It Means</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-zinc-800 bg-white dark:bg-zinc-950/60">
                        <tr>
                            <td class="p-3">
                                <flux:badge variant="success" size="sm">📜 Riding (Has Manifest)</flux:badge>
                            </td>
                            <td class="p-3 text-emerald-400 font-bold">YES</td>
                            <td class="p-3 text-neutral-300">Out on course riding main laps with the physical manifest card.</td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <flux:badge variant="emerald" size="sm">🎯 Bonus (Has Manifest)</flux:badge>
                            </td>
                            <td class="p-3 text-emerald-400 font-bold">YES</td>
                            <td class="p-3 text-neutral-300">At a bonus checkpoint getting the manifest stamped for bonus points!</td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <flux:badge variant="warning" size="sm">⏱️ On Deck (Gets Manifest Next)</flux:badge>
                            </td>
                            <td class="p-3 text-amber-400 font-semibold">NEXT</td>
                            <td class="p-3 text-neutral-300">Staged in the pit area, geared up and ready for the upcoming handoff.</td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <flux:badge variant="purple" size="sm">⏳ Bonus (No Manifest)</flux:badge>
                            </td>
                            <td class="p-3 text-neutral-400">NO</td>
                            <td class="p-3 text-neutral-300">Holding a spot in line or scouting a bonus checkpoint before the manifest arrives.</td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <flux:badge variant="indigo" size="sm">🚲 Riding (Support)</flux:badge>
                            </td>
                            <td class="p-3 text-neutral-400">NO</td>
                            <td class="p-3 text-neutral-300">Riding along for drafting, safety, or night pacing without the manifest.</td>
                        </tr>
                        <tr class="bg-sky-950/20">
                            <td class="p-3">
                                <flux:badge variant="sky" size="sm">🙋 Available / Ready</flux:badge>
                            </td>
                            <td class="p-3 text-neutral-400">NO</td>
                            <td class="p-3 text-neutral-300"><strong class="text-sky-300">Benched & ready to help!</strong> Down to do food runs, bonus stops, or emergency relief.</td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <flux:badge variant="neutral" size="sm">⛺ Off Duty</flux:badge>
                            </td>
                            <td class="p-3 text-neutral-400">NO</td>
                            <td class="p-3 text-neutral-400">Sleeping, eating, or off shift. Do not disturb unless SOS.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </flux:card>

        <!-- Section 3: Chat Channels -->
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">💬 Team Chat Channels</flux:heading>
                <p class="text-sm text-neutral-300">
                    The team chat is split into 3 dedicated channels to keep tactical comms clear:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <!-- Channel 1 -->
                    <div class="p-4 rounded-xl bg-neutral-50 dark:bg-zinc-800/60 border border-neutral-200 dark:border-zinc-700/60 space-y-2">
                        <div class="flex items-center justify-between border-b border-neutral-200 dark:border-zinc-700 pb-2">
                            <span class="font-bold text-neutral-900 dark:text-white flex items-center gap-1.5">
                                <span>⚔️</span> War Room
                            </span>
                            <flux:badge size="sm" variant="neutral">Channel 1</flux:badge>
                        </div>
                        <p class="text-xs text-neutral-600 dark:text-neutral-300 leading-relaxed">
                            <strong>Tactical & Race Comms.</strong> Use this for handoff callouts, lap times, rider rotations, and manifest location updates.
                        </p>
                    </div>

                    <!-- Channel 2 -->
                    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 space-y-2">
                        <div class="flex items-center justify-between border-b border-red-500/20 pb-2">
                            <span class="font-bold text-red-400 flex items-center gap-1.5">
                                <span>🚨</span> SOS Channel
                            </span>
                            <flux:badge size="sm" color="red">Channel 2</flux:badge>
                        </div>
                        <p class="text-xs text-neutral-300 leading-relaxed">
                            <strong>Emergency & Mechanicals.</strong> Flatted? Mechanical? Need a tube or crash help? Post here. Unread messages trigger a glowing red visual alert for the team!
                        </p>
                    </div>

                    <!-- Channel 3 -->
                    <div class="p-4 rounded-xl bg-neutral-50 dark:bg-zinc-800/60 border border-neutral-200 dark:border-zinc-700/60 space-y-2">
                        <div class="flex items-center justify-between border-b border-neutral-200 dark:border-zinc-700 pb-2">
                            <span class="font-bold text-neutral-900 dark:text-white flex items-center gap-1.5">
                                <span>🎉</span> Vibe Ward
                            </span>
                            <flux:badge size="sm" color="blue">Channel 3</flux:badge>
                        </div>
                        <p class="text-xs text-neutral-600 dark:text-neutral-300 leading-relaxed">
                            <strong>Photos & Morale.</strong> Upload photos from the course, share memes, hype up the team, and post race banter!
                        </p>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Section 4: Race Night Quick Tips -->
        <flux:card class="border-amber-500/30">
            <div class="space-y-3">
                <flux:heading size="lg" class="text-amber-400">⚡ Race Night Survival Checklist</flux:heading>
                <ul class="list-disc list-inside space-y-2 text-sm text-neutral-300">
                    <li><strong class="text-white">OwnTracks in "Move" Mode:</strong> When you jump on the bike for your shift, ensure OwnTracks is in <span class="text-emerald-400 font-bold">Move</span> mode so your live speed and map position update every 10–30 seconds.</li>
                    <li><strong class="text-white">Keep Location on "Always":</strong> Confirm iOS/Android permissions remain set to <em>"Always Allow"</em> with <em>"Precise Location"</em> enabled so pings send while your phone is locked.</li>
                    <li><strong class="text-white">Battery Check:</strong> OwnTracks continuous GPS consumes battery quickly. Keep a power bank hooked up in your jersey pocket or pit tent!</li>
                </ul>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
