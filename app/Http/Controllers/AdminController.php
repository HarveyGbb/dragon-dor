<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Plat;

class AdminController extends Controller
{
    // 1. AFFICHER LE TABLEAU DE BORD (Commandes + Stocks)
    public function index()
    {
        // Récupère les commandes (les plus récentes en premier) avec les détails des plats
        $commandes = Commande::with('plats')->orderBy('created_at', 'desc')->get();

        // Récupère TOUS les plats pour l'onglet "Gestion des Stocks"

        $tousLesPlats = Plat::all();

        // On envoie les deux variables à la vue
        return view('admin.commandes.index', compact('commandes', 'tousLesPlats'));
    }

    // 2. METTRE À JOUR LE STATUT D'UNE COMMANDE (Cuisine)
    public function updateStatus(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        // Validation pour sécuriser les statuts autorisés
        $request->validate([
            'statut' => 'required|in:en_attente,en_preparation,prete,recuperee'
        ]);

        $commande->update([
            'statut' => $request->statut
        ]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour !');
    }

    // 3. GÉRER LES STOCKS (Boutons + et -)
    public function updateStock(Request $request, $id)
    {
        $plat = Plat::findOrFail($id);

        if ($request->action == 'augmenter') {
            $plat->increment('stock');
        }
        elseif ($request->action == 'diminuer') {
            if ($plat->stock > 0) {
                $plat->decrement('stock');
            } else {
                // On reste sur l'onglet stocks même en cas d'erreur
                return redirect()->back()->with('error', 'Stock déjà à 0')->with('tab', 'stocks');
            }
        }

        //  on ajoute ->with('tab', 'stocks')
        return redirect()->back()
            ->with('success', 'Stock mis à jour pour ' . $plat->nom)
            ->with('tab', 'stocks');
    }
}
