<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // 🌟 AJOUTER CETTE LIGNE
use Illuminate\Support\Facades\Hash; // 🌟 AJOUTER CETTE LIGNE
use App\Models\User; // 🌟 AJOUTER CETTE LIGNE
use App\Http\Controllers\CommandeController;
use App\Models\Commande; // Pour que Laravel trouve ta table commandes
use Carbon\Carbon;       // Pour que Laravel comprenne la gestion des dates

// L'URL pour JavaFX
Route::get('/commandes/en-cours', [CommandeController::class, 'apiGetCommandesEnCours']);

// Route pour modifier le statut d'une commande depuis JavaFX
Route::put('/commandes/{id}/statut', [CommandeController::class, 'updateStatut']);

// Route de connexion mise à jour pour le nom "Cuisine"
Route::post('/login', function (Request $request) {
    // 🌟 ON CHERCHE PAR 'name' AU LIEU DE 'email'
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
