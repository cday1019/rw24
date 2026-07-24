<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\BonusCheckpoint;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

new class extends Component
{
    public bool $showModal = false;
    public ?int $editingCheckpointId = null;

    // Form Fields
    public string $name = '';
    public string $location = '';
    public ?string $latitude = null;
    public ?string $longitude = null;
    public string $opens_at = '';
    public string $closes_at = '';
    public int $points = 1;
    public ?int $assigned_user_id = null;
    public string $notes = '';

    #[Computed]
    public function checkpoints()
    {
        $teamId = Auth::user()?->team_id;

        if (! $teamId) {
            return collect();
        }

        return BonusCheckpoint::where('team_id', $teamId)
            ->with('assignedUser')
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'completed' THEN 2 WHEN 'skipped' THEN 3 ELSE 4 END")
            ->orderBy('opens_at', 'asc')
            ->get();
    }

    #[Computed]
    public function teamMembers()
    {
        $teamId = Auth::user()?->team_id;

        if (! $teamId) {
            return collect();
        }

        return User::where('team_id', $teamId)->get();
    }

    #[Computed]
    public function totalPointsEarned(): int
    {
        return $this->checkpoints
            ->where('status', 'completed')
            ->sum('points');
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingCheckpointId', 'name', 'location', 'latitude', 'longitude', 'opens_at', 'closes_at', 'points', 'assigned_user_id', 'notes']);
        $this->points = 1;

        // Default opens_at to Friday night of race weekend if empty
        $this->opens_at = now()->format('Y-m-d\T19:00');
        $this->showModal = true;
    }

    public function openEditModal(int $checkpointId): void
    {
        $checkpoint = BonusCheckpoint::where('team_id', Auth::user()->team_id)->findOrFail($checkpointId);

        $this->editingCheckpointId = $checkpoint->id;
        $this->name = $checkpoint->name;
        $this->location = $checkpoint->location ?? '';
        $this->latitude = $checkpoint->latitude !== null ? (string) $checkpoint->latitude : '';
        $this->longitude = $checkpoint->longitude !== null ? (string) $checkpoint->longitude : '';

        // Format Carbon dates for datetime-local inputs
        $this->opens_at = $checkpoint->opens_at ? $checkpoint->opens_at->format('Y-m-d\TH:i') : '';
        $this->closes_at = $checkpoint->closes_at ? $checkpoint->closes_at->format('Y-m-d\TH:i') : '';

        $this->points = $checkpoint->points;
        $this->assigned_user_id = $checkpoint->assigned_user_id;
        $this->notes = $checkpoint->notes ?? '';

        $this->showModal = true;
    }

    public function saveCheckpoint(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'opens_at' => 'nullable|string',
            'closes_at' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $lat = is_numeric($this->latitude) ? (float) $this->latitude : null;
        $lng = is_numeric($this->longitude) ? (float) $this->longitude : null;

        // Auto-geocode if coordinates were left blank and location text exists
        if (($lat === null || $lng === null) && ! empty($this->location)) {
            $searchAddress = trim($this->location);

            if (! str_contains(strtolower($searchAddress), 'milwaukee')) {
                $searchAddress .= ', Milwaukee, WI';
            }

            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $searchAddress,
                    'key'     => config('services.google.maps_key'),
                ]);

                if ($response->successful() && isset($response['results'][0]['geometry']['location'])) {
                    $lat = $response['results'][0]['geometry']['location']['lat'];
                    $lng = $response['results'][0]['geometry']['location']['lng'];
                }
            } catch (\Throwable $e) {
                // Silently handle API issues so manual saves aren't blocked
            }
        }

        $data = [
            'name' => $this->name,
            'location' => $this->location,
            'latitude' => $lat,
            'longitude' => $lng,
            'opens_at' => $this->opens_at ?: null,
            'closes_at' => $this->closes_at ?: null,
            'points' => $this->points,
            'assigned_user_id' => $this->assigned_user_id ?: null,
            'notes' => $this->notes,
        ];

        if ($this->editingCheckpointId) {
            $checkpoint = BonusCheckpoint::where('team_id', Auth::user()->team_id)
                ->findOrFail($this->editingCheckpointId);

            $checkpoint->update($data);

            Flux::toast(text: __('Bonus Checkpoint updated!'), variant: 'success');
        } else {
            BonusCheckpoint::create(array_merge($data, [
                'team_id' => Auth::user()->team_id,
                'status' => 'pending',
            ]));

            Flux::toast(text: __('Bonus Checkpoint added!'), variant: 'success');
        }

        $this->showModal = false;
        unset($this->checkpoints);
    }

    public function updateStatus(int $checkpointId, string $status): void
    {
        if (! in_array($status, ['pending', 'completed', 'skipped'])) {
            return;
        }

        $checkpoint = BonusCheckpoint::where('team_id', Auth::user()->team_id)->findOrFail($checkpointId);
        $checkpoint->update(['status' => $status]);

        unset($this->checkpoints);

        Flux::toast(text: __("Checkpoint status updated to {$status}."), variant: 'success');
    }

    public function assignUser(int $checkpointId, ?int $userId): void
    {
        $checkpoint = BonusCheckpoint::where('team_id', Auth::user()->team_id)->findOrFail($checkpointId);
        $checkpoint->update(['assigned_user_id' => $userId]);

        unset($this->checkpoints);

        Flux::toast(text: __('Runner assignment updated.'), variant: 'success');
    }

    public function deleteCheckpoint(int $checkpointId): void
    {
        BonusCheckpoint::where('team_id', Auth::user()->team_id)->findOrFail($checkpointId)->delete();
        unset($this->checkpoints);

        Flux::toast(text: __('Checkpoint removed.'), variant: 'neutral');
    }
};
?>

<div wire:poll.10s class="max-w-6xl mx-auto space-y-6 py-6">
    <!-- Top Summary & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl bg-zinc-900 border border-zinc-800">
        <div class="flex items-center gap-4">
            <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                <flux:icon name="trophy" class="size-6" />
            </div>
            <div>
                <flux:heading size="lg" class="text-white">Bonus Checkpoints</flux:heading>
                <p class="text-xs text-zinc-400">
                    Total Bonus Points Earned: <strong class="text-emerald-400 font-mono text-sm">{{ $this->totalPointsEarned }} pts</strong>
                </p>
            </div>
        </div>

        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            Add Checkpoint
        </flux:button>
    </div>

    <!-- Checkpoint List Table -->
    <flux:card class="bg-zinc-900/90 border-zinc-800">
        <div class="space-y-4">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Checkpoint / Window') }}</flux:table.column>
                    <flux:table.column>{{ __('Location') }}</flux:table.column>
                    <flux:table.column>{{ __('Assigned Runner') }}</flux:table.column>
                    <flux:table.column>{{ __('Pts') }}</flux:table.column>
                    <flux:table.column>{{ __('Status / Action') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->checkpoints as $cp)
                        @php
                            $isOpen = $cp->isOpenNow();
                            $opensSoon = ! $isOpen && $cp->opens_at && $cp->opens_at->isFuture() && $cp->opens_at->lte(now()->addHour());
                            $isCompleted = $cp->status === 'completed';
                            $isSkipped = $cp->status === 'skipped';
                        @endphp
                        <flux:table.row wire:key="cp-row-{{ $cp->id }}" @class([
                            'opacity-60 bg-zinc-950/40' => $isSkipped,
                            'bg-emerald-950/20' => $isCompleted,
                        ])>
                            <!-- Checkpoint Name & Window -->
                            <flux:table.cell>
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white text-sm">{{ $cp->name }}</span>
                                        @if($isOpen && ! $isCompleted && ! $isSkipped)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 animate-pulse">
                                                <span class="size-1.5 rounded-full bg-emerald-400"></span> OPEN NOW
                                            </span>
                                        @elseif($opensSoon && ! $isCompleted && ! $isSkipped)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/20 text-sky-400 border border-sky-500/40">
                                                <span class="size-1.5 rounded-full bg-sky-400"></span> OPENS SOON
                                            </span>
                                        @endif
                                    </div>
                                    @if($cp->opens_at || $cp->closes_at)
                                        <span class="text-xs font-mono text-zinc-400">
                                            ⏰ {{ $cp->opens_at ? $cp->opens_at->format('D g:i A') : 'Anytime' }}
                                            - {{ $cp->closes_at ? $cp->closes_at->format('D g:i A') : 'End' }}
                                        </span>
                                    @endif
                                    @if($cp->notes)
                                        <p class="text-xs text-zinc-500 italic mt-0.5">{{ $cp->notes }}</p>
                                    @endif
                                </div>
                            </flux:table.cell>

                            <!-- Location & Navigation Link -->
                            <flux:table.cell>
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-zinc-300">{{ $cp->location ?: 'See Official Map' }}</span>
                                    @if($cp->latitude && $cp->longitude)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $cp->latitude }},{{ $cp->longitude }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-mono text-emerald-400 hover:underline">
                                            📍 Live Map Link ↗
                                        </a>
                                    @elseif($cp->location)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($cp->location . ', Milwaukee, WI') }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-mono text-sky-400 hover:underline">
                                            🗺️ Search Google Maps ↗
                                        </a>
                                    @else
                                        <span class="text-[10px] font-mono text-zinc-500">⚠️ No Location</span>
                                    @endif
                                </div>
                            </flux:table.cell>

                            <!-- Assigned Runner Dropdown -->
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button variant="subtle" size="sm" icon-trailing="chevron-down">
                                        @if($cp->assignedUser)
                                            <span class="text-xs text-indigo-300 font-semibold">🏃 {{ $cp->assignedUser->name }}</span>
                                        @else
                                            <span class="text-xs text-zinc-500">Unassigned</span>
                                        @endif
                                    </flux:button>
                                    <flux:menu>
                                        <flux:menu.item wire:click="assignUser({{ $cp->id }}, null)">
                                            Unassigned
                                        </flux:menu.item>
                                        @foreach($this->teamMembers as $member)
                                            <flux:menu.item wire:click="assignUser({{ $cp->id }}, {{ $member->id }})">
                                                🏃 {{ $member->name }}
                                            </flux:menu.item>
                                        @endforeach
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>

                            <!-- Points -->
                            <flux:table.cell>
                                <span class="font-mono text-xs font-bold text-amber-400">+{{ $cp->points }}</span>
                            </flux:table.cell>

                            <!-- Status Actions -->
                            <flux:table.cell>
                                <div class="flex items-center gap-1.5">
                                    @if($isCompleted)
                                        <flux:badge variant="emerald" size="sm">✅ Stamped (+{{ $cp->points }} pts)</flux:badge>
                                        <flux:button wire:click="updateStatus({{ $cp->id }}, 'pending')" variant="subtle" size="xs">
                                            Reset
                                        </flux:button>
                                    @elseif($isSkipped)
                                        <flux:badge variant="neutral" size="sm">Skipped</flux:badge>
                                        <flux:button wire:click="updateStatus({{ $cp->id }}, 'pending')" variant="subtle" size="xs">
                                            Reset
                                        </flux:button>
                                    @else
                                        <flux:button wire:click="updateStatus({{ $cp->id }}, 'completed')" variant="primary" size="xs" icon="check">
                                            Stamped
                                        </flux:button>
                                        <flux:button wire:click="updateStatus({{ $cp->id }}, 'skipped')" variant="danger" size="xs">
                                            Skip
                                        </flux:button>
                                    @endif

                                    <flux:button wire:click="openEditModal({{ $cp->id }})" variant="subtle" size="xs" icon="pencil" class="text-zinc-400 hover:text-indigo-400" />
                                    <flux:button wire:click="deleteCheckpoint({{ $cp->id }})" variant="subtle" size="xs" icon="trash" class="text-zinc-600 hover:text-red-400" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach

                    @if($this->checkpoints->isEmpty())
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-8 text-zinc-500 text-sm">
                                No bonus checkpoints added yet. Click "Add Checkpoint" above to populate the race list!
                            </flux:table.cell>
                        </flux:table.row>
                    @endif
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>

    <!-- Create / Edit Checkpoint Modal -->
    <flux:modal wire:model="showModal" class="md:w-[28rem] space-y-6">
        <div>
            <flux:heading size="lg">{{ $editingCheckpointId ? 'Edit Bonus Checkpoint' : 'Add Bonus Checkpoint' }}</flux:heading>
            <flux:subheading>Enter details from the official RW24 bonus schedule.</flux:subheading>
        </div>

        <form wire:submit="saveCheckpoint" class="space-y-4">
            <flux:input wire:model="name" label="Checkpoint Name" placeholder="e.g. Checkpoint #1: Pier Tattoo" required />
            <flux:input wire:model="location" label="Location / Address / Venue" placeholder="e.g. Booth and Garfield, or Black Husky" />

            <div class="space-y-1">
                <div class="grid grid-cols-2 gap-3">
                    <flux:input wire:model="latitude" label="Lat (Optional)" placeholder="e.g. 43.0683" />
                    <flux:input wire:model="longitude" label="Lng (Optional)" placeholder="e.g. -87.9048" />
                </div>
                <p class="text-[11px] text-zinc-500">Auto-geocoded via Google Maps if left blank.</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <flux:input wire:model="opens_at" type="datetime-local" label="Opens At" />
                <flux:input wire:model="closes_at" type="datetime-local" label="Closes At" />
            </div>

            <flux:input wire:model="points" type="number" min="1" label="Points Value" required />

            <flux:select wire:model="assigned_user_id" label="Assigned Runner / Scout" placeholder="Select team member...">
                <option value="">Unassigned</option>
                @foreach($this->teamMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model="notes" label="Notes / Requirements" placeholder="e.g. Bring $5 cash, must eat hot dog" />

            <div class="flex justify-end gap-2 pt-2">
                <flux:button wire:click="$set('showModal', false)" variant="subtle">Cancel</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingCheckpointId ? 'Update Checkpoint' : 'Add Checkpoint' }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
