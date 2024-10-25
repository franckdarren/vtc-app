<?php

namespace App\Http\Controllers\Api;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lister tous les conducteurs
        return response()->json(Driver::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Créer un nouveau chaufeur
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_number' => 'required|string|unique:drivers,license_number',
            'rating' => 'numeric|min:0|max:5',
            'availability_status' => 'boolean',
        ]);

        $driver = Driver::create($validated);

        return response()->json($driver, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // COnsulter un conducteur
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Chauffeur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($driver, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mettre à jour un chaufeur
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Chauffeur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'license_number' => 'string|unique:drivers,license_number,' . $id,
            'rating' => 'numeric|min:0|max:5',
            'availability_status' => 'boolean',
        ]);

        $driver->update($validated);

        return response()->json($driver, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer un conducteur
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Chauffeur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $driver->delete();

        return response()->json(['message' => 'Chauffeur supprimé'], Response::HTTP_OK);
    }
}
