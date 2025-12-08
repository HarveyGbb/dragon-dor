<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat; // 1. On appelle le Modèle qu'on vient de créer

class PlatController extends Controller
{
    // Cette fonction va gérer la page d'accueil
    public function index()
    {
        // 2. On demande au Modèle de récupérer TOUS les plats
        $plats = Plat::all();

        // 3. On envoie ces plats vers une vue (page web) nommée 'home'
        return view('home', compact('plats'));
    }
}
