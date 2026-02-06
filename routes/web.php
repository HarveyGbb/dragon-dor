<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| 1. ZONE PUBLIQUE (Clients)
|--------------------------------------------------------------------------
*/

// ACCUEIL
Route::get('/', function () {
    return view('accueil');
})->name('accueil.index');

// MENU
Route::get('/menu', [PlatController::class, 'index'])->name('menu.index');

// CONTACT
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// PANIER
Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
Route::post('/panier/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/remove', [CartController::class, 'remove'])->name('cart.remove');

// COMMANDE
Route::get('/commande', [CommandeController::class, 'create'])->name('order.create');
Route::post('/commande', [CommandeController::class, 'store'])->name('order.store');
Route::get('/commande/confirmation/{id}', [CommandeController::class, 'confirmation'])->name('order.confirmation');


/*
|--------------------------------------------------------------------------
| 2. ZONE PRIVÉE (Admin / Cuisinier)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Cuisine
    Route::get('/admin/commandes', [AdminController::class, 'index'])->name('admin.index');

    // Actions Admin
    Route::post('/admin/commandes/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.commandes.update_status');
    Route::post('/admin/plats/{id}/stock', [AdminController::class, 'updateStock'])->name('admin.plats.update_stock');

    // Redirection Dashboard -> Cuisine
    Route::get('/dashboard', function () {
        return redirect()->route('admin.index');
    })->name('dashboard');

    // Profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
