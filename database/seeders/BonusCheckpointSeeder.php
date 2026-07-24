<?php

namespace Database\Seeders;

use App\Models\BonusCheckpoint;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BonusCheckpointSeeder extends Seeder
{
    public function run(): void
    {
        $teams = Team::all();

        if ($teams->isEmpty()) {
            $this->command->error('No teams found! Please create a team first before seeding checkpoints.');
            return;
        }

        // 2026 Race Weekend Dates: Friday July 24 & Saturday July 25
        $friday = '2026-07-24';
        $saturday = '2026-07-25';

        $checkpoints = [
            [
                'name' => 'Checkpoint #1: Shake Shake Shake Senora',
                'location' => 'Gathering Place',
                'opens_at' => "{$friday} 19:30:00",
                'closes_at' => "{$friday} 21:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #2: A World Without Borders',
                'location' => 'Reservoir Basketball Courts',
                'opens_at' => "{$friday} 20:00:00",
                'closes_at' => "{$friday} 22:00:00",
                'points' => 2,
                'lat' => 43.0565, 'lng' => -87.9025,
            ],
            [
                'name' => 'Checkpoint #3: MANIFEST! For Martha',
                'location' => 'For Martha',
                'opens_at' => "{$friday} 20:30:00",
                'closes_at' => "{$friday} 22:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #4: Strike While The Steel Is Hot',
                'location' => 'Riverwest Pizza',
                'opens_at' => "{$friday} 21:00:00",
                'closes_at' => "{$friday} 23:00:00",
                'points' => 2,
            ],
            [
                'name' => "Checkpoint #5: We've Got The Biggest DAM Balls!",
                'location' => '2217 N Booth St',
                'opens_at' => "{$friday} 21:30:00",
                'closes_at' => "{$friday} 23:30:00",
                'points' => 2,
            ],
            [
                'name' => "Checkpoint #6: Fred Astaire's Applied Motion Studies",
                'location' => 'Amorphic Brewing',
                'opens_at' => "{$friday} 22:00:00",
                'closes_at' => "{$saturday} 00:00:00",
                'points' => 2,
            ],
            [
                'name' => "Checkpoint #7: Center Street Steppin'",
                'location' => 'Diaspora',
                'opens_at' => "{$friday} 22:30:00",
                'closes_at' => "{$saturday} 00:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #8: The Floor Show',
                'location' => 'Timbuk2 Alley',
                'opens_at' => "{$friday} 23:00:00",
                'closes_at' => "{$saturday} 01:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #9: What A Horrible Night For A Curse',
                'location' => 'Veggas Pub',
                'opens_at' => "{$friday} 23:30:00",
                'closes_at' => "{$saturday} 01:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #10: Signed Sealed Delivered',
                'location' => 'Hedwigs',
                'opens_at' => "{$saturday} 00:00:00",
                'closes_at' => "{$saturday} 02:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #11: Bad Hair Day',
                'location' => 'Cutting Group',
                'opens_at' => "{$saturday} 00:30:00",
                'closes_at' => "{$saturday} 02:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #12: A Little More Than A Cameo',
                'location' => 'Gaenslen Playground',
                'opens_at' => "{$saturday} 01:00:00",
                'closes_at' => "{$saturday} 03:00:00",
                'points' => 2,
                'lat' => 43.0665, 'lng' => -87.9015,
            ],
            [
                'name' => 'Checkpoint #13: Beat The Rich',
                'location' => '2968 N Booth St (Alley)',
                'opens_at' => "{$saturday} 01:30:00",
                'closes_at' => "{$saturday} 03:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #14: Decisions Decisions',
                'location' => '2832 N Holton St',
                'opens_at' => "{$saturday} 02:00:00",
                'closes_at' => "{$saturday} 04:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #15: Too Many Variables',
                'location' => 'The Jerk Circle @ Gordon Park',
                'opens_at' => "{$saturday} 03:00:00",
                'closes_at' => "{$saturday} 05:00:00",
                'points' => 2,
                'lat' => 43.0682, 'lng' => -87.8965,
            ],
            [
                'name' => 'Checkpoint #16: Fuel For The Fire',
                'location' => '500 E Center St',
                'opens_at' => "{$saturday} 04:00:00",
                'closes_at' => "{$saturday} 06:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #17: Tri-Cycle-Asana',
                'location' => 'Riverwest Yogashala',
                'opens_at' => "{$saturday} 05:00:00",
                'closes_at' => "{$saturday} 07:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #18: From France, With Love',
                'location' => 'St. Casimir Church',
                'opens_at' => "{$saturday} 06:00:00",
                'closes_at' => "{$saturday} 08:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #19: Better Know Your Tool',
                'location' => 'Milwaukee Tool Library',
                'opens_at' => "{$saturday} 06:30:00",
                'closes_at' => "{$saturday} 08:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #20: COME AND GET IT!!',
                'location' => "All People's Church",
                'opens_at' => "{$saturday} 07:00:00",
                'closes_at' => "{$saturday} 09:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #21: The Early Morning Mineral Society',
                'location' => '2428 N Pierce St',
                'opens_at' => "{$saturday} 07:30:00",
                'closes_at' => "{$saturday} 09:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #22: Community Benchmarks',
                'location' => 'Wu-Tang Park',
                'opens_at' => "{$saturday} 08:00:00",
                'closes_at' => "{$saturday} 10:00:00",
                'points' => 2,
                'lat' => 43.0620, 'lng' => -87.8990,
            ],
            [
                'name' => "Checkpoint #23: You're Not Riding Alone",
                'location' => 'Gordon Circle',
                'opens_at' => "{$saturday} 08:30:00",
                'closes_at' => "{$saturday} 10:30:00",
                'points' => 2,
                'lat' => 43.0690, 'lng' => -87.8950,
            ],
            [
                'name' => 'Checkpoint #24: Be Wheelie Rind',
                'location' => '3049 N Pierce St',
                'opens_at' => "{$saturday} 09:00:00",
                'closes_at' => "{$saturday} 11:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #25: A Curious Pitstop',
                'location' => '3820 N Bremen St',
                'opens_at' => "{$saturday} 09:30:00",
                'closes_at' => "{$saturday} 11:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #26: FUCK YOUR PARKING!',
                'location' => 'Rat Statue by Holton Bridge',
                'opens_at' => "{$saturday} 10:00:00",
                'closes_at' => "{$saturday} 12:00:00",
                'points' => 2,
                'lat' => 43.0575, 'lng' => -87.9042,
            ],
            [
                'name' => 'Checkpoint #27a: Kids 24! (Teams 1-275)',
                'location' => 'La Escuela Fratney',
                'opens_at' => "{$saturday} 10:30:00",
                'closes_at' => "{$saturday} 10:45:00",
                'points' => 2,
                'notes' => 'Register at 10:30am, Rider Manifests due by 10:45am',
            ],
            [
                'name' => 'Checkpoint #27b: Kids 24! (Teams 276-554)',
                'location' => 'La Escuela Fratney',
                'opens_at' => "{$saturday} 11:30:00",
                'closes_at' => "{$saturday} 11:45:00",
                'points' => 2,
                'notes' => 'Register at 11:30am, Rider Manifests due by 11:45am',
            ],
            [
                'name' => 'Checkpoint #28: Get Rad With Brad!',
                'location' => 'Gordon Park',
                'opens_at' => "{$saturday} 11:30:00",
                'closes_at' => "{$saturday} 13:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #29: Ride Captain Ride',
                'location' => 'Reservoir Hill',
                'opens_at' => "{$saturday} 12:00:00",
                'closes_at' => "{$saturday} 14:00:00",
                'points' => 2,
                'lat' => 43.0560, 'lng' => -87.9020,
            ],
            [
                'name' => 'Checkpoint #30: Four One Forever',
                'location' => 'Black Husky',
                'opens_at' => "{$saturday} 12:00:00",
                'closes_at' => "{$saturday} 15:00:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #31: Beer On A String',
                'location' => 'Lakefront Brewery',
                'opens_at' => "{$saturday} 12:30:00",
                'closes_at' => "{$saturday} 14:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #32: Flutter & Buzz',
                'location' => "Snail's Crossing",
                'opens_at' => "{$saturday} 13:00:00",
                'closes_at' => "{$saturday} 15:00:00",
                'points' => 2,
                'lat' => 43.0640, 'lng' => -87.8970,
            ],
            [
                'name' => 'Checkpoint #33: Button Up, Buttercup',
                'location' => 'Uptowner',
                'opens_at' => "{$saturday} 13:30:00",
                'closes_at' => "{$saturday} 15:30:00",
                'points' => 2,
            ],
            [
                'name' => 'Checkpoint #34: Turning Up The Heat',
                'location' => 'Radish Park',
                'opens_at' => "{$saturday} 14:00:00",
                'closes_at' => "{$saturday} 16:00:00",
                'points' => 2,
                'lat' => 43.0655, 'lng' => -87.9030,
            ],
            [
                'name' => 'Checkpoint #35: Ring My Belt',
                'location' => 'Kern Park Pavilion',
                'opens_at' => "{$saturday} 14:30:00",
                'closes_at' => "{$saturday} 14:45:00",
                'points' => 2,
                'notes' => 'Rider Manifests due at 2:30pm!',
            ],
            [
                'name' => 'Checkpoint #36: Turbo Casino',
                'location' => '814 E Wright St (Alley)',
                'opens_at' => "{$saturday} 15:00:00",
                'closes_at' => "{$saturday} 17:00:00",
                'points' => 2,
            ],
        ];

        foreach ($teams as $team) {
            $this->command->info("Seeding bonus checkpoints for Team: {$team->name}");

            foreach ($checkpoints as $cpData) {
                $lat = $cpData['lat'] ?? null;
                $lng = $cpData['lng'] ?? null;

                // Auto-Geocode via Google Maps API if no hardcoded coordinates exist
                if (($lat === null || $lng === null) && ! empty($cpData['location'])) {
                    $search = trim($cpData['location']);
                    if (! str_contains(strtolower($search), 'milwaukee')) {
                        $search .= ', Milwaukee, WI';
                    }

                    try {
                        $res = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                            'address' => $search,
                            'key'     => config('services.google.maps_key'),
                        ]);

                        if ($res->successful() && isset($res['results'][0]['geometry']['location'])) {
                            $lat = $res['results'][0]['geometry']['location']['lat'];
                            $lng = $res['results'][0]['geometry']['location']['lng'];
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Could not geocode {$cpData['location']}: " . $e->getMessage());
                    }
                }

                BonusCheckpoint::updateOrCreate(
                    [
                        'team_id' => $team->id,
                        'name'    => $cpData['name'],
                    ],
                    [
                        'location'  => $cpData['location'],
                        'latitude'  => $lat,
                        'longitude' => $lng,
                        'opens_at'  => $cpData['opens_at'],
                        'closes_at' => $cpData['closes_at'],
                        'points'    => $cpData['points'],
                        'notes'     => $cpData['notes'] ?? null,
                        'status'    => 'pending',
                    ]
                );
            }
        }

        $this->command->info('All 37 RW24 2026 bonus checkpoints seeded successfully at 2 points each!');
    }
}
