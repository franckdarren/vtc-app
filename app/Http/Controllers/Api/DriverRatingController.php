<?php

namespace App\Http\Controllers\Api;

use App\Models\DriverRating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DriverRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($driver_id)
    {
        // Afficher toutes les notations d'un chauffeur
        $ratings = DriverRating::where('driver_id', $driver_id)->get();

        return response()->json($ratings, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ajouter une notation
        $validated = $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'driver_id' => 'required|exists:drivers,id',
            'rider_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating = DriverRating::create($validated);

        return response()->json($rating, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
