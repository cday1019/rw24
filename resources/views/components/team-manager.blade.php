<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Name') }}</flux:table.column>
                        <flux:table.column>{{ __('Status') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($this->members as $member)
                            @php
                                $location = $member->latestLocation;
                                $hasManifest = in_array($member->status, ['riding_manifest', 'bonus_manifest']);
                            @endphp
                            <flux:table.row wire:key="roster-member-{{ $member->id }}">
                                <flux:table.cell>
                                    <div class="flex items-center gap-3">
                                        <flux:avatar :name="$member->name" size="xs" />
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-white">{{ $member->name }}</span>
                                                @if ($hasManifest)
                                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">MANIFEST HOLDER</span>
                                                @endif
                                            </div>
                                            @if ($location)
                                                <span class="text-xs text-neutral-400 flex items-center gap-2 mt-0.5">
                                                    <span>⚡ {{ round($location->speed ?? 0) }} mph</span>
                                                    @if (! is_null($location->battery))
                                                        <span>• 🔋 {{ $location->battery }}%</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-xs text-neutral-500 mt-0.5">No telemetry yet</span>
                                            @endif
                                        </div>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <!-- Mobile-Friendly Status Picker -->
                                    <flux:select
                                        wire:change="updateStatus({{ $member->id }}, $event.target.value)"
                                        size="sm"
                                        class="w-full min-w-[200px]"
                                    >
                                        <option value="riding_manifest" @selected($member->status === 'riding_manifest')>📜 Riding (Has Manifest)</option>
                                        <option value="bonus_manifest" @selected($member->status === 'bonus_manifest')>🎯 Bonus (Has Manifest)</option>
                                        <option value="on_deck_next" @selected($member->status === 'on_deck_next')>⏱️ On Deck (Gets Manifest Next)</option>
                                        <option value="bonus_waiting" @selected($member->status === 'bonus_waiting')>⏳ Bonus (No Manifest)</option>
                                        <option value="riding_support" @selected($member->status === 'riding_support')>🚲 Riding (Support)</option>
                                        <option value="available" @selected($member->status === 'available')>🙋 Available / Ready</option>
                                        <option value="off_duty" @selected($member->status === 'off_duty')>⛺ Off Duty</option>
                                    </flux:select>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>
    @endif
</section>
