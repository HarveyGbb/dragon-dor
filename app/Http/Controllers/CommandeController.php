<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Support\Facades\Session;

class CommandeController extends Controller
{
    // Affiche le formulaire de commande
    public function create()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Votre panier est vide.');
        }

        $total_general = 0;
        foreach($cart as $item) {
            $total_general += $item['price'] * $item['quantity'];
        }

        return view('commander', compact('total_general'));
    }

    // Enregistre la commande, VÉRIFIE et déduit les stocks
    public function store(Request $request)
    {
        // 1. Validation des champs du formulaire
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'heure_retrait' => 'required',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu.index');
        }

        // --- SÉCURITÉ STOCK  ---
        foreach ($cart as $id_plat => $details) {
            $plat = Plat::find($id_plat);

            // Si le plat n'existe plus OU si le client demande plus que le stock dispo
            if (!$plat || $plat->stock < $details['quantity']) {
                // On annule tout et on prévient le client
                return redirect()->back()->with('error', 'Désolé, stock insuffisant pour "' . $details['name'] . '". Quantité restante : ' . ($plat ? $plat->stock : 0));
            }
        }
        // -------------------------------------------------------

        $total_general = 0;
        foreach($cart as $item) {
            $total_general += $item['price'] * $item['quantity'];
        }

        // 2. Création de la commande (seulement si le stock est OK)
        $commande = Commande::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'heure_retrait' => $request->heure_retrait,
            'total' => $total_general,
            'statut' => 'en_attente'
        ]);

        // 3. Liaison des plats ET déduction des stocks
        foreach ($cart as $id_plat => $details) {
            // Liaison table pivot
            $commande->plats()->attach($id_plat, [
                'quantite' => $details['quantity'],
                'prix_unitaire' => $details['price']
            ]);

            // DÉDUCTION DU STOCK
            $plat = Plat::find($id_plat);
            if ($plat) {
                $plat->decrement('stock', $details['quantity']);
            }
        }

        // 4. Vide le panier
        Session::forget('cart');

        return redirect()->route('order.confirmation', ['id' => $commande->id]);
    }

    // Affiche la page de confirmation client
    public function confirmation($id)
    {
        $commande = Commande::findOrFail($id);
        return view('confirmation', compact('commande'));
    }

    // Modifier le statut depuis l'Admin (Interface Web)
    public function update_status(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        // On unifie les statuts avec ceux de JavaFX
        $request->validate([
            'statut' => 'required|in:en_attente,en_cuisine,prete,fini'
        ]);

        $commande->update([
            'statut' => $request->statut
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour !');
    }

   // =========================================================
    // API POUR L'APPLICATION DESKTOP (JAVAFX)
    // =========================================================

    // 1. Envoyer les commandes en attente ET en cuisine au format JSON
    public function apiGetCommandesEnCours()
    {
        //  utilise whereIn pour que la commande reste affichée en cuisine !
        $commandes = Commande::with('plats')
            ->whereIn('statut', ['en_attente', 'en_cuisine'])
            ->get();

        return response()->json($commandes, 200);
    }

    // 2. Modifier le statut depuis l'application JavaFX
    public function updateStatut(Request $request, $id)
    {
        //  cherche la commande dans la base de données
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json(['message' => 'Commande introuvable'], 404);
        }

        // On valide que le statut envoyé est correct
        $request->validate([
            'statut' => 'required|string|in:en_attente,en_cuisine,prete,fini'
        ]);

        // met à jour le statut
        $commande->statut = $request->statut;
        $commande->save();

        return response()->json([
            'message' => 'Statut mis à jour avec succès',
            'commande' => $commande
        ], 200);
    }
}
