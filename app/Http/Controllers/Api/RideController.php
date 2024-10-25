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
        // Valider les données du requête
        $validated = $request->validate([
            'rider_id' => 'required|exists:users,id',
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'latitude_start_location' => 'required|numeric',
            'longitude_start_location' => 'required|numeric',
            'latitude_end_location' => 'required|numeric',
            'longitude_end_location' => 'required|numeric',
            'status' => 'required|in:pending,accepted,completed,canceled',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);

        // Calculer la distance entre les points de départ et d'arrivée
        $validated['distance'] = $this->calculateDistance(
            $validated['latitude_start_location'],
            $validated['longitude_start_location'],
            $validated['latitude_end_location'],
            $validated['longitude_end_location']
        );

        // Calculer le prix comme distance * 100
        $validated['price'] = $validated['distance'] * 100;

        // Créer une nouvelle course avec les données validées
        $ride = Ride::create($validated);

        return response()->json($ride, Response::HTTP_CREATED);
    }


    /**
     * Calculate the distance between two geographical points.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // Rayon de la Terre en kilomètres

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Retourne la distance en kilomètres
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
            'latitude_start_location' => 'required|numeric',
            'longitude_start_location' => 'required|numeric',
            'latitude_end_location' => 'required|numeric',
            'longitude_end_location' => 'required|numeric',
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
