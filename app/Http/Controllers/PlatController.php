<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;
use App\Models\Categorie;
use Illuminate\Support\Facades\Session;

class PlatController extends Controller
{
    // =========================================================
    // PARTIE PUBLIQUE (CLIENT)
    // =========================================================

    public function index()
    {
        // On ne charge que les plats disponibles
        $plats = Plat::with('laCategorie')->where('disponible', 1)->get();

        $platsParCategorie = $plats->groupBy(fn($item) => $item->laCategorie ? $item->laCategorie->nom : 'Autres');

        $cart = Session::get('cart', []);
        $itemCount = array_sum(array_column($cart, 'quantity'));

        return view('menu', compact('platsParCategorie', 'itemCount'));
    }

    // =========================================================
    // PARTIE ADMIN
    // =========================================================

    // FORMULAIRE D'AJOUT
    public function create()
    {
        $categories = Categorie::all();
        return view('admin.plats.create', compact('categories'));
    }

    // ENREGISTRER UN PLAT
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0'
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('plats', 'public') : null;

        Plat::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'categorie_id' => $request->categorie_id,
            'image_url' => $imagePath,
            'stock' => $request->stock,
            'disponible' => true
        ]);

        return redirect()->route('admin.index')->with('success', 'Nouveau plat ajouté au menu ! 🍜');
    }

    // =========================================================
    // NOUVELLE MÉTHODE : ACTIVER / DÉSACTIVER UN PLAT
    // =========================================================
    public function toggleVisibility(Plat $plat)
    {
        // On inverse la valeur actuelle (si true devient false, et inversement)
        $plat->disponible = !$plat->disponible;
        $plat->save();

        $message = $plat->disponible
            ? "Le plat '{$plat->nom}' est maintenant visible par les clients ! 🟢"
            : "Le plat '{$plat->nom}' a été masqué. 🔴";

        // On redirige sur la page précédente
        return redirect()->back()->with('success', $message);
    }
// =========================================================
    // MODIFIER UN PLAT
    // =========================================================

    // 1. Afficher le formulaire pré-rempli
    public function edit(Plat $plat)
    {
        $categories = Categorie::all();
        return view('admin.plats.edit', compact('plat', 'categories'));
    }

    // 2. Enregistrer les modifications en base de données
    public function update(Request $request, Plat $plat)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0'
        ]);

        //  récupère toutes les données sauf l'image
        $data = $request->except(['image']);

        // Si l'admin a mis une NOUVELLE image, on la sauvegarde
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('plats', 'public');
        }

        //  met à jour le plat
        $plat->update($data);

        return redirect()->route('admin.index')->with('success', 'Le plat a été modifié avec succès ! 🍲');
    }
}
