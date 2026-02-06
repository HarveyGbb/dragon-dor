<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    // 1. Affiche la page avec le formulaire
    public function index()
    {
        return view('contact');
    }

    // 2. Traite le formulaire (Simulation)
    public function send(Request $request)
    {
        // Validation basique
        $request->validate([
            'email' => 'required|email',
            'message' => 'required'
        ]);

        // On renvoie l'utilisateur sur la même page avec un message de succès
        return redirect()->back()->with('success', 'Merci ! Votre message a bien été envoyé.');
    }
}
