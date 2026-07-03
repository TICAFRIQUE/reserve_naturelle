<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FournisseurController;
use App\Http\Controllers\Admin\SousCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====== PAGE D'ACCUEIL ======
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ====== PAGES STATIQUES ======
Route::get('/a-propos', function () {
    return view('client');
})->name('about');

Route::get('/contact', function () {
    return view('client.contact');
})->name('contact');

// ====== AUTHENTIFICATION ======
Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');

// ====== ROUTES PROTÉGÉES (AUTH) ======
Route::middleware(['auth'])->group(function () {
    
    // Routes client (espace membre)
    Route::get('/client/index', function () {
        return view('client.index');
    })->name('client.index');

    // ====== ROUTES ADMIN ======
    Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
        
        // Tableau de bord admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Gestion des catégories (CRUD complet)
        Route::resource('categories', CategoryController::class);

         //Gestion des sous categories
        Route::resource('sous-categories', SousCategoryController::class);
        
        //Gestion des utilisateurs
        Route::resource('users', UserController::class);

        //Gestion des fournisseurs
        Route::resource('fournisseurs', FournisseurController::class);

       //Gestion des produits
        Route::resource('produits', ProductController::class);
        Route::post('/produits/{id}/stock', [ProductController::class, 'updateStock'])->name('produits.update-stock');
        Route::post('/produits/{id}/restore', [ProductController::class, 'restore'])->name('produits.restore');

        //Gestion des commandes
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'changeStatus'])->name('orders.status');
    });
});