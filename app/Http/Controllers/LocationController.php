<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Requests\OptimizedRouteRequest;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    // Store a new location
    public function store(StoreLocationRequest $request)
    {
        $data = $request->validated();

        $location = $this->locationService->createLocation($data);

        return response()->json([
            'success' => true,
            'message' => 'Location created successfully',
            'data' => $location,
        ], 201);
    }

    // List all locations
    public function index()
    {
        $locations = $this->locationService->getAllLocations();

        return response()->json([
            'success' => true,
            'message' => 'Locations retrieved successfully',
            'data' => $locations,
        ]);
    }

    // Get a specific location by ID
    public function show($id)
    {
        $location = $this->locationService->getLocationById($id);

        return response()->json([
            'success' => true,
            'message' => 'Location retrieved successfully',
            'data' => $location,
        ]);
    }

    // Update a specific location by ID
    public function update(UpdateLocationRequest $request, $id)
    {
        $data = $request->validated();
        $location = $this->locationService->updateLocation($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $location,
        ]);
    }

    // Delete a specific location by ID
    public function destroy($id)
    {
        $this->locationService->deleteLocation($id);

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully',
            'data' => null,
        ]);
    }

    // Get optimized route based on starting latitude and longitude
    public function optimizedRoute(OptimizedRouteRequest $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $optimizedRoute = $this->locationService->getOptimizedRoute($latitude, $longitude);

        if ($optimizedRoute->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No locations available',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Optimized route retrieved successfully',
            'data' => $optimizedRoute,
        ]);
    }
}
