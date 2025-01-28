<?php

namespace App\Services;

use App\Repositories\LocationRepository;

class LocationService
{
    protected $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    // Get all locations
    public function getAllLocations()
    {
        return $this->locationRepository->getAll();
    }

    // Get a single location by ID
    public function getLocationById($id)
    {
        return $this->locationRepository->findById($id);
    }

    // Create a new location
    public function createLocation(array $data)
    {
        return $this->locationRepository->create($data);
    }

    // Update a location
    public function updateLocation($id, array $data)
    {
        return $this->locationRepository->update($id, $data);
    }

    // Delete a location
    public function deleteLocation($id)
    {
        $this->locationRepository->delete($id);
    }

    // Get optimized route based on starting point
    public function getOptimizedRoute($latitude, $longitude)
    {
        $locations = $this->locationRepository->getAll();

        // Add distances to locations
        $locationsWithDistance = $locations->map(function ($location) use ($latitude, $longitude) {
            $location->distance = $this->calculateHaversine($latitude, $longitude, $location->latitude, $location->longitude);
            return $location;
        });

        // Sort by distance
        return $locationsWithDistance->sortBy('distance')->values();
    }

    // Haversine formula for distance calculation
    private function calculateHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
