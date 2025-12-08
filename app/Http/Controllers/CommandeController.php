<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\Session;

class CommandeController extends Controller
{
    // Affiche le formulaire de validation de la commande (GET /commander)
    public function create()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            // Utiliser le nom de route correct
            return redirect()->route('menu.index')->with('error', 'Votre panier est vide.');
        }

        // Calculer le total général pour l'affichage (important pour le client)
        $total_general = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('commander', compact('total_general'));
    }

    // Enregistre la commande finale en base de données (POST /commander)
    public function store(Request $request)
    {
        // 1. Validation des champs (Noms corrigés pour correspondre au formulaire)
        $request->validate([
            'nom_client' => 'required|string|max:255', // FIX: Utiliser nom_client
            'telephone' => 'required|string|max:20',
            'heure_retrait' => 'required|date_format:H:i',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Le panier est vide, impossible de finaliser la commande.');
        }

        // Calcul du total final
        $total_general = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // 2. Enregistrement de la commande en base de données
        Commande::create([
            // Enregistrement des données du client
            'nom_client' => $request->nom_client, // FIX: Utiliser nom_client
            'telephone' => $request->telephone,
            'heure_retrait' => $request->heure_retrait,

            // Données de la commande
            'total' => $total_general,
            'statut' => 'en attente', // Statut initial pour l'Admin
            'date_creation' => now(),
        ]);

        // 3. Vider le panier après la commande (action finale)
        Session::forget('cart');

        // 4. Redirection vers la page d'accueil avec un message de succès
        return redirect()->route('accueil.index')->with('success',
            'Commande enregistrée ! Le Manager/Cuisinier a été notifié. Votre total est de ' . number_format($total_general, 2) . ' €.'
        );
    }
}
