<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Models\Plat;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. ROUTES PUBLIQUES (CLIENT) ---

// Route 1. ACCUEIL (Page d'introduction)
Route::get('/', function () {
    return view('accueil');
})->name('accueil.index');


// Route 2. MENU (Page principale des plats)
Route::get('/menu', function () {
    // Compteur panier
    $cart = Session::get('cart', []);
    $itemCount = array_sum(array_column($cart, 'quantity'));

    // Récupération et tri des plats
    $plats = Plat::orderBy('categorie')->orderBy('nom')->get();

    // Groupement par catégorie (Entrées, Plats, etc.)
    $platsParCategorie = $plats->groupBy('categorie');

    // On envoie les variables à la vue 'welcome'
    return view('welcome', compact('itemCount', 'platsParCategorie'));
})->name('menu.index');


// --- ROUTES DU PANIER ---

// Ajouter
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
// Voir
Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
// Mettre à jour quantité
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
// Supprimer un article
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


// --- ROUTES DE COMMANDE ---

// Formulaire de validation
Route::get('/commander', [CommandeController::class, 'create'])->name('order.create');
// Enregistrement final
Route::post('/commander', [CommandeController::class, 'store'])->name('order.store');


// --- 2. ROUTES D'ADMINISTRATION (BACK-OFFICE) ---

// Groupe préfixé par '/admin'
Route::prefix('admin')->group(function () {

    // 1. Tableau de bord (Liste des commandes)
    Route::get('commandes', [AdminController::class, 'index'])
         ->name('admin.commandes.index');

    // 2. Détail d'une commande (Optionnel mais recommandé)
    Route::get('commandes/{commande}', [AdminController::class, 'show'])
         ->name('admin.commandes.show');

    // 3. Action : Mise à jour du statut (En cuisine -> Prêt)
    Route::post('commandes/{commande}/update-status', [AdminController::class, 'updateStatus'])
         ->name('admin.commandes.update_status');
});
