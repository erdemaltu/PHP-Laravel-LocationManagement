<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository
{
    // Get all locations
    public function getAll()
    {
        return Location::all();
    }

    // Find a location by ID
    public function findById($id)
    {
        return Location::findOrFail($id);
    }

    // Create a new location
    public function create(array $data)
    {
        return Location::create($data);
    }

    // Update a location
    public function update($id, array $data)
    {
        $location = Location::findOrFail($id);
        $location->update($data);
        return $location;
    }

    // Delete a location
    public function delete($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
    }
}