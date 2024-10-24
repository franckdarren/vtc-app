<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);   // Liste tous les utilisateurs
    Route::get('/users/{id}', [UserController::class, 'show']); // Afficher un utilisateur spécifique
    Route::post('/users', [UserController::class, 'store']);  // Créer un nouvel utilisateur
    Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer un utilisateur
});
