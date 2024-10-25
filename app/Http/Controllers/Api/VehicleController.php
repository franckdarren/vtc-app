<?php

namespace App\Http\Controllers\Api;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Lister les véhycules
        return response()->json(Vehicle::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Créer un nouveau véhycule
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'marque' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'color' => 'required|string',
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json($vehicle, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Afficher un véhycule spécifique
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($vehicle, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mettre à jour un véhycule existant
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'driver_id' => 'exists:drivers,id',
            'make' => 'string',
            'model' => 'string',
            'year' => 'integer',
            'license_plate' => 'string|unique:vehicles,license_plate,' . $id,
            'color' => 'string',
        ]);

        $vehicle->update($validated);

        return response()->json($vehicle, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer un véhycule
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $vehicle->delete();

        return response()->json(['message' => 'Véhicule supprimé'], Response::HTTP_OK);
    }
}
