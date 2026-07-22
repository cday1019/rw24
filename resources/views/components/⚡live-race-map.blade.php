<?php
/** @var \App\Models\User $user */

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\TeamLocation;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public array $teammateLocations = [];
    public array $routePaths = [];
    public array $checkpoints = [];

    public function mount()
    {
        $this->updateLocations();
        $this->loadRouteData();
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

        // Extract Points for Checkpoints
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
            ->orderBy('pinged_at', 'desc') // Sort latest pings first
            ->get()
            ->unique('user_id')             // Keep only the most recent ping per user
            ->map(fn (TeamLocation $location) => [
                'lat'      => (float) $location->latitude,
                'lng'      => (float) $location->longitude,
                'name'     => $location->user->name,
                'initials' => $location->user->initials(),
                'status'   => $location->user->status,
            ])
            ->values()
            ->toArray();
    }
};
?>

<div class="relative h-full w-full min-h-[400px] rounded-xl overflow-hidden"
     wire:poll.10s="updateLocations"
     x-data="{
        locations: @entangle('teammateLocations'),
        routePaths: @js($routePaths),
        checkpoints: @js($checkpoints),
        map: null,
        init() {
            this.$watch('locations', () => this.updateMarkers());
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
                    strokeOpacity: 0.85,
                    strokeWeight: 5,
                    map: this.map
                });
                mapEl._polylines.push(polyline);
            });

            // Clear existing checkpoint markers
            mapEl._checkpoints.forEach(m => m.setMap(null));
            mapEl._checkpoints = [];

            // Render new checkpoint markers
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
                        scale: 6
                    },
                    label: {
                        text: cp.name,
                        color: 'white',
                        fontSize: '10px',
                        fontWeight: 'bold',
                        className: 'mt-8'
                    }
                });
                mapEl._checkpoints.push(marker);
            });

            this.fitMapToRoute();
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
                    title: loc.name + ' (' + loc.status + ')'
                });
                mapEl._riderMarkers.push(marker);
            });
        }
    }"
>
    <div id="map" class="h-full w-full" wire:ignore></div>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

    <script>
        function initMap() {
            const mapContainer = document.getElementById('map');
            const mapOptions = {
                zoom: 13,
                styles: [
                    { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                    { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                    { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                    {
                        featureType: "administrative.locality",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#d59563" }],
                    },
                    {
                        featureType: "poi",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#d59563" }],
                    },
                    {
                        featureType: "poi.park",
                        elementType: "geometry",
                        stylers: [{ color: "#263c3f" }],
                    },
                    {
                        featureType: "poi.park",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#6b9a76" }],
                    },
                    {
                        featureType: "road",
                        elementType: "geometry",
                        stylers: [{ color: "#38414e" }],
                    },
                    {
                        featureType: "road",
                        elementType: "geometry.stroke",
                        stylers: [{ color: "#212a37" }],
                    },
                    {
                        featureType: "road",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#9ca5b3" }],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "geometry",
                        stylers: [{ color: "#746855" }],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "geometry.stroke",
                        stylers: [{ color: "#1f2835" }],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#f3d19c" }],
                    },
                    {
                        featureType: "transit",
                        elementType: "geometry",
                        stylers: [{ color: "#2f3948" }],
                    },
                    {
                        featureType: "transit.station",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#d59563" }],
                    },
                    {
                        featureType: "water",
                        elementType: "geometry",
                        stylers: [{ color: "#17263c" }],
                    },
                    {
                        featureType: "water",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#515c6d" }],
                    },
                    {
                        featureType: "water",
                        elementType: "labels.text.stroke",
                        stylers: [{ color: "#17263c" }],
                    },
                ],
            };
            const mapElement = document.getElementById("map");
            const map = new google.maps.Map(mapElement, mapOptions);

            const data = Alpine.$data(mapElement.closest('[x-data]'));
            data.map = map;
            data.renderRoute();
            data.updateMarkers();
        }
    </script>
</div>
