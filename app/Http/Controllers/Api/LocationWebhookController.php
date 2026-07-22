<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamLocation;
use App\Models\User;
use Illuminate\Http\Request;

class LocationWebhookController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('OwnTracks payload:', $request->all());
        // Ignore ping/status events from OwnTracks
        if ($request->input('_type') !== 'location') {
            return response()->json([]);
        }

        $latitude  = $request->input('lat');
        $longitude = $request->input('lon');
        $trackerId = $request->input('tid'); // e.g. Rider initials ("CD")

        // OwnTracks sends velocity in km/h -> Convert to mph
        $velKm    = $request->input('vel', 0);
        $speedMph = round($velKm * 0.621371, 1);

        // Battery percentage (0 - 100)
        $battery = $request->input('batt');

        // Extract username from topic (e.g. "owntracks/chad/iphone" -> "chad")
        $topicParts = explode('/', $request->input('topic', ''));
        $username   = $topicParts[1] ?? null;

        $user = User::where('name', 'like', "%{$username}%")->first();

        if ($user && $user->team_id) {
            TeamLocation::create([
                'user_id'   => $user->id,
                'team_id'   => $user->team_id,
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'speed'     => $speedMph,
                'battery'   => $battery,
                'pinged_at' => now(),
            ]);
        }

        // OwnTracks expects a 200 OK with an empty JSON object
        return response()->json([]);
    }
}
