<?php

use Livewire\Component;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

new class extends Component
{
    public string $name = '';

    // Listens to your team's private WebSocket channel
    #[On('echo-private:team.{teamId},LocationUpdated')]
    public function handleLocationUpdated()
    {
        // Re-renders component when a location event arrives
    }

    public function createTeam()
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

        $this->name = '';

        Flux::toast(
            text: __('Team created successfully.'),
            variant: 'success',
        );
    }

    public function updateStatus(\App\Models\User $user, string $status)
    {
        if (! in_array($status, ['off_duty', 'on_deck', 'riding'])) {
            return;
        }

        $user->update(['status' => $status]);

        Flux::toast(
            text: __('Status updated to :status for :name.', ['status' => $status, 'name' => $user->name]),
            variant: 'success',
        );
    }
};
?>

    <!-- Added wire:poll.10s here to refresh the roster every 10 seconds -->
<section class="w-full" wire:poll.10s>
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
        <flux:card>
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Team Roster') }}</flux:heading>
                    <flux:subheading>{{ __('Members of your team and their current status.') }}</flux:subheading>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Name') }}</flux:table.column>
                        <flux:table.column>{{ __('Status') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        {{-- Eager-load latestLocation on every poll --}}
                        @foreach (Auth::user()->team->members()->with('latestLocation')->get() as $member)
                            @php
                                $location = $member->latestLocation;
                            @endphp
                            <flux:table.row>
                                <flux:table.cell>
                                    <div class="flex items-center gap-3">
                                        <flux:avatar :name="$member->name" size="xs" />
                                        <div class="flex flex-col">
                                            <span class="font-medium text-white">{{ $member->name }}</span>
                                            @if ($location)
                                                <span class="text-xs text-neutral-400 flex items-center gap-2">
                                                    <span>⚡ {{ round($location->speed ?? 0) }} mph</span>
                                                    @if (! is_null($location->battery))
                                                        <span>• 🔋 {{ $location->battery }}%</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-xs text-neutral-500">No telemetry yet</span>
                                            @endif
                                        </div>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    @php
                                        $variant = match($member->status) {
                                            'riding' => 'success',
                                            'on_deck' => 'warning',
                                            'off_duty' => 'neutral',
                                            default => 'neutral',
                                        };
                                        $label = str($member->status)->replace('_', ' ')->title();
                                    @endphp

                                    <flux:dropdown>
                                        <flux:button variant="subtle" size="sm" :icon-trailing="'chevron-down'">
                                            <flux:badge :variant="$variant" size="sm" class="cursor-pointer">{{ $label }}</flux:badge>
                                        </flux:button>

                                        <flux:menu>
                                            <flux:menu.item wire:click="updateStatus({{ $member->id }}, 'off_duty')">{{ __('Off Duty') }}</flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $member->id }}, 'on_deck')">{{ __('On Deck') }}</flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $member->id }}, 'riding')">{{ __('Riding') }}</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>
    @endif
</section>
