<?php

namespace App\Http\Controllers\Api;

use App\Models\Ride;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Afficher toutes le courses
        return response()->json(Ride::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Créer une nouvelle course
        $validated = $request->validate([
            'rider_id' => 'required|exists:users,id',
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_location' => 'required|numeric',
            'end_location' => 'required|numeric',
            'status' => 'required|in:pending,accepted,completed,canceled',
            'distance' => 'required|numeric',
            'price' => 'required|numeric|between:0,9999.99',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);

        $ride = Ride::create($validated);

        return response()->json($ride, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Afficher une course spécifique
        $ride = Ride::find($id);

        if (!$ride) {
            return response()->json(['message' => 'Course non trouvée'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($ride, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Modifier une course
        $ride = Ride::find($id);

        if (!$ride) {
            return response()->json(['message' => 'Course non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'rider_id' => 'exists:users,id',
            'driver_id' => 'exists:drivers,id',
            'vehicle_id' => 'exists:vehicles,id',
            'start_location' => 'numeric',
            'end_location' => 'numeric',
            'status' => 'in:pending,accepted,completed,canceled',
            'distance' => 'numeric',
            'price' => 'numeric|between:0,9999.99',
            'start_time' => 'date',
            'end_time' => 'date',
        ]);

        $ride->update($validated);

        return response()->json($ride, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer une course
        $ride = Ride::find($id);

        if (!$ride) {
            return response()->json(['message' => 'Course non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $ride->delete();

        return response()->json(['message' => 'Course supprimée'], Response::HTTP_OK);
    }
}
