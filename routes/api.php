<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\CommandeController;
use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <-- NOUVEL IMPORT OBLIGATOIRE POUR LES STATS

// L'URL pour JavaFX
Route::get('/commandes/en-cours', [CommandeController::class, 'apiGetCommandesEnCours']);

// Route pour modifier le statut d'une commande depuis JavaFX
Route::put('/commandes/{id}/statut', [CommandeController::class, 'updateStatut']);

// Route de connexion
Route::post('/login', function (Request $request) {
    // nom de l'username et mot de passe
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

// Route pour l'exportation CSV du jour
Route::get('/commandes/export-jour', function () {
    // Récupère les commandes du jour avec les relations 'plats'
    return Commande::whereDate('created_at', Carbon::today())
                    ->with('plats')
                    ->get();
});

// ROUTE : STATISTIQUES DES PLATS POUR LE BARCHART

Route::get('/commandes/stats-plats', function () {
    // Utilisation de la base de données (commande, plat, commande_plat)
    $stats = DB::table('commande_plat')
        ->join('plat', 'commande_plat.plat_id', '=', 'plat.id')
        ->join('commande', 'commande_plat.commande_id', '=', 'commande.id')
        ->whereDate('commande.created_at', \Carbon\Carbon::today())
        ->select('plat.nom', DB::raw('SUM(commande_plat.quantite) as total_vendu'))
        ->groupBy('plat.id', 'plat.nom')
        ->orderBy('total_vendu', 'desc')
        ->take(10)
        ->pluck('total_vendu', 'plat.nom');

    return response()->json($stats);
});

