<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // AFFICHER LE PANIER
    public function show()
    {
        $cart = Session::get('cart', []);

        // On récupère les stocks pour gérer la limite
        $ids = array_keys($cart);
        $stocks = Plat::whereIn('id', $ids)->pluck('stock', 'id');

        return view('panier', compact('cart', 'stocks'));
    }

    // AJOUTER UN PLAT
    public function add(Request $request)
    {
        $id = $request->id;
        $qty_demandee = (int) $request->input('quantity', 1);

        $plat = Plat::findOrFail($id);
        $cart = Session::get('cart', []);

        // Vérification du stock
        $qty_deja_prise = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        if (($qty_deja_prise + $qty_demandee) > $plat->stock) {
            return redirect()->back()->with('error', "Stock insuffisant !");
        }

        // Si le plat est déjà dans le panier, on augmente juste la quantité
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty_demandee;
        } else {
            // SINON, ON CRÉE LA LIGNE
            $cart[$id] = [
                //sécurité pour le nom
                "name" => $plat->nom ?? $plat->description,
                "quantity" => $qty_demandee,
                "price" => $plat->prix,

                "image" => $plat->image_url  // On utilise 'image_url'
            ];
        }

        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Ajouté au panier !');
    }

    // MISE À JOUR QUANTITÉ
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $plat = Plat::findOrFail($request->id);

            if ($request->quantity > $plat->stock) {
                session()->flash('error', "Stock insuffisant");
            } else {
                $cart = Session::get('cart');
                $cart[$request->id]["quantity"] = $request->quantity;
                Session::put('cart', $cart);
            }
        }
        return redirect()->route('cart.show');
    }

    // SUPPRIMER
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = Session::get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                Session::put('cart', $cart);
            }
        }
        return redirect()->route('cart.show');
    }
}
