<?php

namespace App\Http\Controllers\Api;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Lister tous les users
        return response()->json(User::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Créer un nouvel utilisateur
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Afficher un utilisateur spécifique
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mettre à jour un utilisateur existant
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'phone_number' => 'unique:users,phone_number,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->update($validatedData);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Supprimer un user
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    }

    // Récupère l'historique des courses d'un utilisateur client
    public function rideHistory()
    {
        // Obtenir l'utilisateur actuellement connecté
        $user = Auth::user();

        // Vérifier si l'utilisateur est un client (rôle client)
        if ($user->role !== 'client') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Récupérer les courses de l'utilisateur connecté
        $rides = Ride::where('rider_id', $user->id)
            ->with(['driver', 'vehicle']) // Charger les relations pour plus de détails
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($rides, 200);
    }
}
