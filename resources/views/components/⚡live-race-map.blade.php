<?php
/** @var \App\Models\User $user */

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\TeamLocation;
use App\Models\BonusCheckpoint;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public array $teammateLocations = [];
    public array $routePaths = [];
    public array $checkpoints = [];
    public array $openBonusCheckpoints = [];
    public ?int $teamId = null;

    public function handleLocationUpdated($event)
    {
        $updatedLoc = $event['locationData'];

        $locations = collect($this->teammateLocations)->keyBy('user_id');
        $locations->put($updatedLoc['user_id'], $updatedLoc);

        $this->teammateLocations = $locations->values()->toArray();
    }

    public function mount()
    {
        $this->teamId = Auth::user()?->team_id;
        $this->loadRouteData();
        $this->updateLocations();
        $this->updateBonusCheckpoints();
    }

    /**
     * Runs automatically before every render / wire:poll step
     */
    public function rendering()
    {
        $this->updateLocations();
        $this->updateBonusCheckpoints();
    }

    public function getListeners()
    {
        if (! $this->teamId) {
            return [];
        }

        return [
            "echo-private:team.{$this->teamId},LocationUpdated" => 'handleLocationUpdated',
        ];
    }

    public function loadRouteData(): void
    {
        $kmlPath = storage_path('app/rw24-route.kml');

        if (! file_exists($kmlPath)) {
            return;
        }

        $xml = simplexml_load_file($kmlPath);
        $xml->registerXPathNamespace('kml', 'http://www.opengis.net/kml/2.2');

        // Extract LineStrings for route paths
        $this->routePaths = collect($xml->xpath('//kml:Placemark[kml:LineString]'))
            ->map(function ($placemark) {
                $coordsText = (string) $placemark->LineString->coordinates;
                $coords = collect(explode("\n", trim($coordsText)))
                    ->map(fn ($line) => trim($line))
                    ->filter()
                    ->flatMap(fn ($line) => explode(' ', $line))
                    ->filter()
                    ->map(function ($triplet) {
                        $parts = explode(',', $triplet);
                        return [
                            'lat' => (float) $parts[1],
                            'lng' => (float) $parts[0],
                        ];
                    })
                    ->values()
                    ->toArray();

                return [
                    'name' => (string) $placemark->name,
                    'path' => $coords,
                ];
            })
            ->toArray();

        // Extract Points for Route Checkpoints
        $this->checkpoints = collect($xml->xpath('//kml:Folder[kml:name="Checkpoints"]/kml:Placemark[kml:Point]'))
            ->map(function ($placemark) {
                $coordsText = (string) $placemark->Point->coordinates;
                $parts = explode(',', trim($coordsText));

                return [
                    'name' => (string) $placemark->name,
                    'lat' => (float) $parts[1],
                    'lng' => (float) $parts[0],
                ];
            })
            ->toArray();
    }

    public function updateLocations(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->team_id) {
            $this->teammateLocations = [];
            return;
        }

        $this->teammateLocations = TeamLocation::query()
            ->with('user')
            ->where('team_id', $user->team_id)
            ->where('pinged_at', '>=', now()->subMinutes(30))
            ->orderBy('pinged_at', 'desc')
            ->get()
            ->unique('user_id')
            ->map(fn (TeamLocation $location) => [
                'user_id'  => $location->user_id,
                'lat'      => (float) $location->latitude,
                'lng'      => (float) $location->longitude,
                'name'     => $location->user->name,
                'initials' => $location->user->initials(),
                'status'   => $location->user->status,
                'speed'    => $location->speed ? round($location->speed) . ' mph' : '0 mph',
                'battery'  => $location->battery ? $location->battery . '%' : 'N/A',
            ])
            ->values()
            ->toArray();
    }

    public function updateBonusCheckpoints(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->team_id) {
            $this->openBonusCheckpoints = [];
            return;
        }

        $now = now();

        $this->openBonusCheckpoints = BonusCheckpoint::query()
            ->where('team_id', $user->team_id)
            ->where('status', 'pending')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function (BonusCheckpoint $cp) use ($now) {
                $hasOpened = ! $cp->opens_at || $cp->opens_at <= $now;
                $hasNotClosed = ! $cp->closes_at || $cp->closes_at >= $now;
                return $hasOpened && $hasNotClosed;
            })
            ->map(function (BonusCheckpoint $cp) {
                $windowText = '';
                if ($cp->opens_at || $cp->closes_at) {
                    $openStr = $cp->opens_at ? $cp->opens_at->format('g:i A') : 'Anytime';
                    $closeStr = $cp->closes_at ? $cp->closes_at->format('g:i A') : 'End';
                    $windowText = "⏰ {$openStr} - {$closeStr}";
                }

                return [
                    'id'       => $cp->id,
                    'name'     => $cp->name,
                    'location' => $cp->location,
                    'points'   => $cp->points,
                    'window'   => $windowText,
                    'lat'      => (float) $cp->latitude,
                    'lng'      => (float) $cp->longitude,
                ];
            })
            ->values()
            ->toArray();
    }
};
?>

<div wire:poll.10s class="relative h-full w-full min-h-[400px] rounded-xl overflow-hidden" x-data="raceMap()">
    <!-- Custom CSS to seamlessly style Google Maps InfoWindow popups -->
    <style>
        .gm-style-iw {
            background-color: #18181b !important;
            border: 1px solid #27272a !important;
            border-radius: 12px !important;
            padding: 0 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .gm-style-iw-d {
            overflow: hidden !important;
            padding: 0 !important;
        }
        .gm-style-iw-tc::after {
            background-color: #18181b !important;
        }
        .gm-ui-hover-effect {
            filter: invert(1) !important;
            top: 4px !important;
            right: 4px !important;
        }
    </style>

    <div id="map" class="h-full w-full" wire:ignore></div>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

    <script>
        function raceMap() {
            return {
                locations: @entangle('teammateLocations'),
                routePaths: @js($routePaths),
                checkpoints: @js($checkpoints),
                bonusCheckpoints: @entangle('openBonusCheckpoints'),
                map: null,

                init() {
                    this.$watch('locations', () => this.updateMarkers());
                    this.$watch('bonusCheckpoints', () => this.renderBonusCheckpoints());
                },

                renderRoute() {
                    if (!this.map) return;
                    const mapEl = document.getElementById('map');
                    if (!mapEl._polylines) mapEl._polylines = [];
                    if (!mapEl._checkpoints) mapEl._checkpoints = [];

                    // Clear existing route polylines
                    mapEl._polylines.forEach(p => p.setMap(null));
                    mapEl._polylines = [];

                    // Render new route polylines
                    this.routePaths.forEach(rp => {
                        const polyline = new google.maps.Polyline({
                            path: rp.path,
                            geodesic: true,
                            strokeColor: '#00FFCC',
                            strokeOpacity: 0.9,
                            strokeWeight: 4,
                            map: this.map
                        });
                        mapEl._polylines.push(polyline);
                    });

                    // Clear existing checkpoint markers
                    mapEl._checkpoints.forEach(m => m.setMap(null));
                    mapEl._checkpoints = [];

                    // Render new route checkpoint markers
                    this.checkpoints.forEach(cp => {
                        const marker = new google.maps.Marker({
                            position: { lat: cp.lat, lng: cp.lng },
                            map: this.map,
                            title: cp.name,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                fillColor: '#FF007F',
                                fillOpacity: 1,
                                strokeWeight: 2,
                                strokeColor: 'white',
                                scale: 5
                            },
                            label: {
                                text: cp.name,
                                color: '#f4f4f5',
                                fontSize: '10px',
                                fontWeight: 'bold',
                                className: 'mt-7'
                            }
                        });
                        mapEl._checkpoints.push(marker);
                    });

                    this.fitMapToRoute();
                },

                renderBonusCheckpoints() {
                    if (!this.map) return;
                    const mapEl = document.getElementById('map');
                    if (!mapEl._bonusMarkers) mapEl._bonusMarkers = [];

                    // Clear existing bonus markers
                    mapEl._bonusMarkers.forEach(m => m.setMap(null));
                    mapEl._bonusMarkers = [];

                    // Shared InfoWindow for tap popups
                    if (!mapEl._infoWindow) {
                        mapEl._infoWindow = new google.maps.InfoWindow();
                    }

                    // Render open bonus checkpoint markers
                    this.bonusCheckpoints.forEach(cp => {
                        const labelText = cp.window ? `${cp.name}\n${cp.window}` : cp.name;

                        const marker = new google.maps.Marker({
                            position: { lat: cp.lat, lng: cp.lng },
                            map: this.map,
                            title: cp.name,
                            icon: {
                                path: 'M12 0C5.37 0 0 5.37 0 12c0 9 12 20 12 20s12-11 12-20C24 5.37 18.63 0 12 0z',
                                fillColor: '#F59E0B',
                                fillOpacity: 1,
                                strokeColor: '#FFFFFF',
                                strokeWeight: 1.5,
                                scale: 0.85,
                                anchor: new google.maps.Point(12, 32),
                                labelOrigin: new google.maps.Point(12, -10)
                            },
                            label: {
                                text: labelText,
                                color: '#FBBF24',
                                fontSize: '11px',
                                fontWeight: 'bold',
                                className: 'bg-zinc-900/90 px-2 py-1 rounded border border-amber-500/50 shadow-md text-center whitespace-pre leading-tight'
                            }
                        });

                        // Tap popup card with details & direct navigation
                        marker.addListener('click', () => {
                            const popupContent = `
                                <div style="background-color: #18181b; color: #f4f4f5; padding: 14px; font-family: system-ui, -apple-system, sans-serif; min-width: 190px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                        <span style="background-color: #f59e0b; color: #000; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px;">+${cp.points} PTS</span>
                                        <span style="color: #10b981; font-size: 10px; font-weight: 700;">● OPEN NOW</span>
                                    </div>
                                    <div style="font-weight: 700; font-size: 14px; color: #ffffff; margin-bottom: 2px;">${cp.name}</div>
                                    ${cp.window ? `<div style="font-size: 11px; color: #f59e0b; font-weight: 600; margin-bottom: 6px;">${cp.window}</div>` : ''}
                                    <div style="font-size: 11px; color: #a1a1aa; margin-bottom: 12px;">📍 ${cp.location || 'Riverwest'}</div>
                                    <a href="https://www.google.com/maps/search/?api=1&query=${cp.lat},${cp.lng}" target="_blank" style="display: flex; align-items: center; justify-content: center; gap: 6px; background-color: #27272a; color: #38bdf8; text-decoration: none; font-size: 11px; font-weight: 600; padding: 8px; border-radius: 8px; border: 1px solid #3f3f46;">
                                        🗺️ Open in Google Maps
                                    </a>
                                </div>
                            `;

                            mapEl._infoWindow.setContent(popupContent);
                            mapEl._infoWindow.open(this.map, marker);
                        });

                        mapEl._bonusMarkers.push(marker);
                    });
                },

                fitMapToRoute() {
                    if (!this.map) return;
                    const bounds = new google.maps.LatLngBounds();
                    let hasPoints = false;

                    // Include KML Route
                    this.routePaths.forEach(rp => {
                        rp.path.forEach(pos => {
                            bounds.extend(pos);
                            hasPoints = true;
                        });
                    });

                    // Include Active Teammates
                    this.locations.forEach(loc => {
                        bounds.extend({ lat: loc.lat, lng: loc.lng });
                        hasPoints = true;
                    });

                    // Include Open Bonus Checkpoints
                    this.bonusCheckpoints.forEach(cp => {
                        bounds.extend({ lat: cp.lat, lng: cp.lng });
                        hasPoints = true;
                    });

                    if (hasPoints) {
                        this.map.fitBounds(bounds);
                    }
                },

                updateMarkers() {
                    if (!this.map) return;
                    const mapEl = document.getElementById('map');
                    if (!mapEl._riderMarkers) mapEl._riderMarkers = [];

                    // Clear existing rider markers cleanly
                    mapEl._riderMarkers.forEach(marker => marker.setMap(null));
                    mapEl._riderMarkers = [];

                    // Add new rider markers
                    this.locations.forEach(loc => {
                        const marker = new google.maps.Marker({
                            position: { lat: loc.lat, lng: loc.lng },
                            map: this.map,
                            label: {
                                text: loc.initials,
                                color: 'white',
                                fontWeight: 'bold'
                            },
                            title: `${loc.name} (${loc.speed} • 🔋 ${loc.battery})`
                        });
                        mapEl._riderMarkers.push(marker);
                    });
                }
            };
        }

        function initMap() {
            const mapElement = document.getElementById("map");
            if (!mapElement) return;

            // Sleek, modern Zinc & Charcoal map theme
            const mapOptions = {
                zoom: 13,
                disableDefaultUI: false,
                zoomControl: true,
                mapTypeControl: false,
                streetViewControl: false,
                styles: [
                    { elementType: "geometry", stylers: [{ color: "#18181b" }] },
                    { elementType: "labels.text.stroke", stylers: [{ color: "#18181b" }] },
                    { elementType: "labels.text.fill", stylers: [{ color: "#a1a1aa" }] },
                    { featureType: "administrative.locality", elementType: "labels.text.fill", stylers: [{ color: "#e4e4e7" }] },
                    { featureType: "poi", elementType: "labels.text.fill", stylers: [{ color: "#71717a" }] },
                    { featureType: "poi.park", elementType: "geometry", stylers: [{ color: "#14231e" }] },
                    { featureType: "poi.park", elementType: "labels.text.fill", stylers: [{ color: "#4ade80" }] },
                    { featureType: "road", elementType: "geometry", stylers: [{ color: "#27272a" }] },
                    { featureType: "road", elementType: "geometry.stroke", stylers: [{ color: "#18181b" }] },
                    { featureType: "road", elementType: "labels.text.fill", stylers: [{ color: "#71717a" }] },
                    { featureType: "road.highway", elementType: "geometry", stylers: [{ color: "#3f3f46" }] },
                    { featureType: "road.highway", elementType: "geometry.stroke", stylers: [{ color: "#18181b" }] },
                    { featureType: "road.highway", elementType: "labels.text.fill", stylers: [{ color: "#a1a1aa" }] },
                    { featureType: "transit", elementType: "geometry", stylers: [{ color: "#27272a" }] },
                    { featureType: "transit.station", elementType: "labels.text.fill", stylers: [{ color: "#a1a1aa" }] },
                    { featureType: "water", elementType: "geometry", stylers: [{ color: "#0f172a" }] },
                    { featureType: "water", elementType: "labels.text.fill", stylers: [{ color: "#38bdf8" }] },
                ],
            };

            const map = new google.maps.Map(mapElement, mapOptions);
            const data = Alpine.$data(mapElement.closest('[x-data]'));

            if (data) {
                data.map = map;
                data.renderRoute();
                data.updateMarkers();
                data.renderBonusCheckpoints();
            }
        }
    </script>
</div>
