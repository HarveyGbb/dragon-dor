<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\CommandeController;
use App\Models\Commande;
use Carbon\Carbon;

// L'URL pour JavaFX
Route::get('/commandes/en-cours', [CommandeController::class, 'apiGetCommandesEnCours']);

// Route pour modifier le statut d'une commande depuis JavaFX
Route::put('/commandes/{id}/statut', [CommandeController::class, 'updateStatut']);

// Route de connexion
Route::post('/login', function (Request $request) {
//nom de l'username et mot de passe
    $user = User::where('name', $request->identifiant)->first();

    // Vérification du mot de passe
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }

    return response()->json([
        'message' => 'Connexion réussie',
        'user' => $user
    ], 200);
});

Route::get('/commandes/export-jour', function () {
    // Récupère les commandes du jour avec les relations 'plats'
    return Commande::whereDate('created_at', Carbon::today())
                    ->with('plats')
                    ->get();
});
