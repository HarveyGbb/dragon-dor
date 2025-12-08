<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat; // Utilise le Modèle Plat (TAP 1)
use Illuminate\Support\Facades\Session; // Utilise le mécanisme de session de Laravel

class CartController extends Controller
{
    // Fonction pour ajouter un plat au panier (répond à la route POST /cart/add)
    public function add(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'plat_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $platId = $request->plat_id;
        $quantity = $request->quantity;

        // 2. Récupérer les infos du plat depuis la base de données
        $plat = Plat::findOrFail($platId);

        // 3. Récupérer le panier actuel (crée un tableau vide s'il n'existe pas)
        $cart = Session::get('cart', []);

        // 4. Logique d'ajout / mise à jour de la quantité
        if (isset($cart[$platId])) {
            $cart[$platId]['quantity'] += $quantity;
        } else {
            // Ajouter le nouveau plat au panier
            $cart[$platId] = [
                "plat_id" => $platId,
                "name" => $plat->nom,
                "price" => $plat->prix,
                "quantity" => $quantity
            ];
        }

        // 5. Sauvegarder le panier mis à jour dans la session (Panier persistant)
        Session::put('cart', $cart);

        return redirect()->back()->with('success', $quantity . ' x ' . $plat->nom . ' ajouté au panier !');
    }

    // Fonction pour afficher la page du panier (répond à la route GET /panier)
    public function show()
    {
        $cart = Session::get('cart', []);

        // Renvoyer les données du panier à la vue 'panier.blade.php' (à créer)
        return view('panier', compact('cart'));
    }

    // Fonction pour mettre à jour la quantité d'un plat ou le supprimer
    public function update(Request $request)
    {
        $request->validate([
            'plat_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Session::get('cart', []);
        $platId = $request->plat_id;
        $quantity = $request->quantity;

        if (isset($cart[$platId])) {
            if ($quantity > 0) {
                $cart[$platId]['quantity'] = $quantity;
                Session::put('cart', $cart);
                $message = "Quantité mise à jour.";
            } else {
                // Si la quantité est 0, suppression de l'article
                unset($cart[$platId]);
                Session::put('cart', $cart);
                $message = "Plat retiré du panier.";
            }
        } else {
            $message = "Erreur: Plat introuvable.";
        }

        return redirect()->route('cart.show')->with('success', $message);
    }

    // Fonction pour supprimer un plat (via son ID)
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            $message = "Plat supprimé du panier.";
        } else {
            $message = "Erreur: Plat introuvable.";
        }

        return redirect()->route('cart.show')->with('success', $message);
    }
}


