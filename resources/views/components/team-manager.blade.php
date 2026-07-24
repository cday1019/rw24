<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

new class extends Component
{
    public string $name = '';
    public ?int $teamId = null;

    public function mount(): void
    {
        $this->teamId = Auth::user()?->team_id;
    }

    public function getListeners(): array
    {
        if (! $this->teamId) {
            return [];
        }

        return [
            "echo-private:team.{$this->teamId},LocationUpdated" => 'handleLocationUpdated',
        ];
    }

    public function handleLocationUpdated(): void
    {
        unset($this->members);
    }

    #[Computed]
    public function members()
    {
        if (! $this->teamId) {
            return collect();
        }

        return Auth::user()->team->members()->with('latestLocation')->get();
    }

    public function createTeam(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:teams,name'],
        ]);

        $team = Team::create([
            'name' => $this->name,
            'category' => 'team',
        ]);

        Auth::user()->update([
            'team_id' => $team->id,
        ]);

        $this->teamId = $team->id;
        $this->name = '';

        Flux::toast(
            text: __('Team created successfully.'),
            variant: 'success',
        );
    }

    public function updateStatus(int $userId, string $status): void
    {
        $validStatuses = [
            'off_duty',
            'available',
            'on_deck_next',
            'riding_manifest',
            'bonus_manifest',
            'bonus_waiting',
            'riding_support',
        ];

        if (! in_array($status, $validStatuses)) {
            return;
        }

        // Manifest holder states that must be unique across the team
        $manifestStatuses = ['riding_manifest', 'bonus_manifest'];

        // If passing/moving the manifest, clear it from anyone else on the team
        if (in_array($status, $manifestStatuses)) {
            User::where('team_id', $this->teamId)
                ->whereIn('status', $manifestStatuses)
                ->where('id', '!=', $userId)
                ->update(['status' => 'off_duty']);
        }

        $user = User::where('team_id', $this->teamId)->findOrFail($userId);
        $user->update(['status' => $status]);

        unset($this->members);

        $labels = [
            'riding_manifest' => 'Riding (Has Manifest)',
            'bonus_manifest'  => 'Bonus Checkpoint (Has Manifest)',
            'on_deck_next'     => 'On Deck (Gets Manifest Next)',
            'bonus_waiting'   => 'Bonus Checkpoint (No Manifest)',
            'riding_support'   => 'Riding (Support)',
            'available'        => 'Available / Ready',
            'off_duty'        => 'Off Duty',
        ];

        Flux::toast(
            text: __("Status updated to :status for :name.", ['status' => $labels[$status] ?? $status, 'name' => $user->name]),
            variant: 'success',
        );
    }
};
?>

<section class="w-full">
    @if (! Auth::user()->team_id)
        <flux:card class="max-w-xl mx-auto">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Create a Team') }}</flux:heading>
                    <flux:subheading>{{ __('Start a new team to begin collaborating with others.') }}</flux:subheading>
                </div>

                <form wire:submit="createTeam" class="space-y-6">
                    <flux:input
                        wire:model="name"
                        :label="__('Team Name')"
                        type="text"
                        required
                        autofocus
                        placeholder="{{ __('Enter team name') }}"
                    />

                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">
                            {{ __('Create Team') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:card>
    @else
        <flux:card class="bg-zinc-900 border-zinc-800">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg" class="text-white">{{ __('Team Roster') }}</flux:heading>
                    <flux:subheading>{{ __('Track who has the manifest, who is on deck, and team activity.') }}</flux:subheading>
                </div>

                <!-- Responsive Mobile-First Member Cards -->
                <div class="space-y-3">
                    @foreach ($this->members as $member)
                        @php
                            $location = $member->latestLocation;
                            $hasManifest = in_array($member->status, ['riding_manifest', 'bonus_manifest']);
                        @endphp
                        <div wire:key="roster-member-{{ $member->id }}" class="p-3.5 rounded-xl bg-zinc-950/60 border border-zinc-800/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <!-- Member Info & Telemetry -->
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$member->name" size="sm" />
                                <div class="flex flex-col min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-white text-sm truncate">{{ $member->name }}</span>
                                        @if ($hasManifest)
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 whitespace-nowrap">MANIFEST HOLDER</span>
                                        @endif
                                    </div>
                                    @if ($location)
                                        <span class="text-xs text-neutral-400 flex items-center gap-1.5 flex-wrap mt-0.5">
                                            <span>⚡ {{ round($location->speed ?? 0) }} mph</span>
                                            @if (! is_null($location->battery))
                                                <span>• 🔋 {{ $location->battery }}%</span>
                                            @endif
                                            @if (! empty($location->pinged_at))
                                                <span class="text-neutral-500 text-[11px] font-mono">• {{ Carbon::parse($location->pinged_at)->diffForHumans() }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-xs text-neutral-500 mt-0.5">No telemetry yet</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Full-Width Dropdown on Mobile / Auto-Width on Desktop -->
                            <div class="w-full sm:w-auto sm:min-w-[220px]">
                                <flux:select
                                    wire:change="updateStatus({{ $member->id }}, $event.target.value)"
                                    size="sm"
                                    class="w-full"
                                >
                                    <option value="riding_manifest" @selected($member->status === 'riding_manifest')>📜 Riding (Has Manifest)</option>
                                    <option value="bonus_manifest" @selected($member->status === 'bonus_manifest')>🎯 Bonus (Has Manifest)</option>
                                    <option value="on_deck_next" @selected($member->status === 'on_deck_next')>⏱️ On Deck (Gets Manifest Next)</option>
                                    <option value="bonus_waiting" @selected($member->status === 'bonus_waiting')>⏳ Bonus (No Manifest)</option>
                                    <option value="riding_support" @selected($member->status === 'riding_support')>🚲 Riding (Support)</option>
                                    <option value="available" @selected($member->status === 'available')>🙋 Available / Ready</option>
                                    <option value="off_duty" @selected($member->status === 'off_duty')>⛺ Off Duty</option>
                                </flux:select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </flux:card>
    @endif
</section>
