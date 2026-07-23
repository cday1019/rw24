<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationUpdated; // <-- Add this import
use App\Http\Controllers\Controller;
use App\Models\TeamLocation;
use App\Models\User;
use Illuminate\Http\Request;

class LocationWebhookController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('OwnTracks payload:', $request->all());

        if ($request->input('_type') !== 'location') {
            return response()->json([]);
        }

        $latitude  = $request->input('lat');
        $longitude = $request->input('lon');

        $velKm    = $request->input('vel', 0);
        $speedMph = round($velKm * 0.621371, 1);
        $battery  = $request->input('batt');

        $topicParts = explode('/', $request->input('topic', ''));
        $username   = $topicParts[1] ?? null;

        $user = User::where('name', 'like', "%{$username}%")->first();

        if ($user && $user->team_id) {
            $location = TeamLocation::create([
                'user_id'   => $user->id,
                'team_id'   => $user->team_id,
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'speed'     => $speedMph,
                'battery'   => $battery,
                'pinged_at' => now(),
            ]);

            // Broadcast to WebSockets instantly!
            LocationUpdated::dispatch($location);
        }

        return response()->json([]);
    }
}
