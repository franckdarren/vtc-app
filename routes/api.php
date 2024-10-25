<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\DriverRatingController;

Route::post('/login', [AuthController::class, 'login']);

// Actions utilisateurs
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);   // Liste tous les utilisateurs
    Route::get('/users/{id}', [UserController::class, 'show']); // Afficher un utilisateur spécifique
    Route::post('/users', [UserController::class, 'store']);  // Créer un nouvel utilisateur
    Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer un utilisateur
});

// Recupérer toutes les courses d'un utilisateur
Route::middleware('auth:sanctum')->get('/user/rides/history', [UserController::class, 'rideHistory']);

// Gestion des conducteurs
Route::apiResource('drivers', DriverController::class);

// Gestion des véhycules
Route::apiResource('vehicles', VehicleController::class);

// Actions commandes d'une courses
Route::apiResource('rides', RideController::class);

// Récupérer toutes les notations d'un chauffeur
Route::get('/drivers/{driver}/ratings', [DriverRatingController::class, 'index']);

// Ajouter une notation pour un chauffeur
Route::post('/drivers/{driver}/ratings', [DriverRatingController::class, 'store']);
