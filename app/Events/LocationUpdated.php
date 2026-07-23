<?php

namespace App\Events;

use App\Models\TeamLocation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $locationData;
    public int $teamId;

    public function __construct(TeamLocation $location)
    {
        $this->teamId = $location->team_id;
        $this->locationData = [
            'user_id'  => $location->user_id,
            'lat'      => (float) $location->latitude,
            'lng'      => (float) $location->longitude,
            'name'     => $location->user->name,
            'initials' => $location->user->initials(),
            'status'   => $location->user->status,
            'speed'    => ! is_null($location->speed) ? round($location->speed) . ' mph' : '0 mph',
            'battery'  => ! is_null($location->battery) ? $location->battery . '%' : 'N/A',
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('team.' . $this->teamId),
        ];
    }
}
