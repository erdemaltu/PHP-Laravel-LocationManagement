<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    // Store a new location
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'marker_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $location = $this->locationService->createLocation($request->all());

        return response()->json($location, 201);
    }

    // List all locations
    public function index()
    {
        $locations = $this->locationService->getAllLocations();
        return response()->json($locations);
    }

    // Get a specific location by ID
    public function show($id)
    {
        $location = $this->locationService->getLocationById($id);
        return response()->json($location);
    }

    // Update a specific location by ID
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'marker_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $location = $this->locationService->updateLocation($id, $request->all());

        return response()->json($location);
    }

    // Delete a specific location by ID
    public function destroy($id)
    {
        $this->locationService->deleteLocation($id);
        return response()->json(['message' => 'Location deleted successfully']);
    }

    // Get optimized route based on starting latitude and longitude
    public function optimizedRoute(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $optimizedRoute = $this->locationService->getOptimizedRoute($latitude, $longitude);

        if ($optimizedRoute->isEmpty()) {
            return response()->json(['message' => 'No locations available'], 404);
        }

        return response()->json($optimizedRoute);
    }
}
