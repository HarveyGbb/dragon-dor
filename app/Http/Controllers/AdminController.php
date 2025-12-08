<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;

class AdminController extends Controller
{
    // Affiche la liste
    public function index()
    {
        $commandes = Commande::orderBy('date_creation', 'desc')->get();
        return view('admin.commandes.index', compact('commandes'));
    }

    // Affiche le détail
    public function show(Commande $commande)
    {
        return view('admin.commandes.show', compact('commande'));
    }

    // Met à jour le statut
    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate(['statut' => 'required|string']);
        $commande->statut = $request->statut;
        $commande->save();

        return redirect()->route('admin.commandes.index')->with('success', 'Statut mis à jour !');
    }
}
