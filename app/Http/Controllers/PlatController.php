<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;
use Illuminate\Support\Facades\Session;

class PlatController extends Controller
{
    public function index()
    {
        // 1. On charge les plats avec la relation renommée 'laCategorie'
        // On ajoute ->where('disponible', 1) pour cacher les produits non dispos
        $plats = Plat::with('laCategorie')->where('disponible', 1)->get();

        // 2. On groupe les plats par le NOM de la catégorie
        $platsParCategorie = $plats->groupBy(function($item) {
            // On vérifie si la relation existe, sinon on met 'Autres'
            return $item->laCategorie ? $item->laCategorie->nom : 'Autres';
        });

        // 3. Compteur panier pour le header
        $cart = Session::get('cart', []);
        $itemCount = array_sum(array_column($cart, 'quantity'));

        return view('menu', compact('platsParCategorie', 'itemCount'));
    }
}
