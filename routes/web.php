<?php

    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Auth\PasswordController;
    use App\Http\Controllers\Admin\CategoryController;
    use App\Http\Controllers\Admin\FournisseurController;
    use App\Http\Controllers\Admin\SousCategoryController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\InventaireController;
    use App\Http\Controllers\Admin\LivreurController;
    use App\Http\Controllers\Admin\TourneeController;
    use App\Http\Controllers\Admin\ZoneController;
    use App\Http\Controllers\Admin\AchatController;
    use App\Http\Controllers\Admin\StockController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\StockMouvementController;
    use \App\Http\Controllers\Admin\StockAjustementController;
    use App\Http\Controllers\Admin\OrderController as AdminOrderController;
    use App\Http\Controllers\Admin\ProductController as AdminProductController;
    use App\Http\Controllers\Client\ProductController as ClientProductController;
    use App\Http\Controllers\Client\OrderController as ClientOrderController;
    use App\Http\Controllers\Client\CartController;
    use App\Http\Controllers\Client\CheckoutController;
    use Illuminate\Support\Facades\Route;

    // Accueil = catalogue produit (public)
    Route::get('/', [ClientProductController::class, 'index'])->name('home');

    // Public (invité + connecté)
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/produits', [ClientProductController::class, 'index'])->name('products.index');
        Route::get('/catalogue', [ClientProductController::class, 'catalogue'])->name('products.catalogue');

        Route::prefix('produits')->name('products.')->group(function () {
            // Route::get('/recherche', [ClientProductController::class, 'search'])->name('search');
            Route::get('/{product}', [ClientProductController::class, 'show'])->name('show');
        });

        Route::prefix('panier')->name('cart.')->group(function(){
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/', [CartController::class, 'store'])->name('store');
            Route::patch('/items/{item}', [CartController::class, 'update'])->name('update');
            Route::post('/items/{item}', [CartController::class, 'destroy'])->name('destroy');
            Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        });
    });

    // Protégé : commandes + checkout (réservés aux utilisateurs connectés)
    Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {

        // Les routes commandes
        Route::prefix('commandes')->name('orders.')->group(function(){
            Route::get('/', [ClientOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [ClientOrderController::class, 'show'])->name('show');
            Route::post('/', [ClientOrderController::class, 'store'])->name('store');

            // Routes paiement (déplacées ici pour cohérence)
            Route::get('/{order}/pay', [ClientOrderController::class, 'pay'])->name('pay');
            Route::post('/{order}/confirm', [ClientOrderController::class, 'confirm'])->name('confirm');
        });

        // Routes checkout (avec l'ID de la commande en paramètre)
        Route::prefix('checkout')->name('checkout.')->group(function(){
            // Affiche le formulaire de validation pour une commande spécifique
            Route::get('/{order}', [CheckoutController::class, 'show'])->name('show');
            // Met à jour la commande avec les infos de livraison
            Route::post('/{order}', [CheckoutController::class, 'store'])->name('store');
        });
    });

    // Pages statiques
    Route::get('/a-propos', fn () => view('client.about'))->name('client.about');
    Route::get('/contact', fn () => view('client.contact'))->name('contact');

    // Authentification
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');

    Route::middleware(['auth'])->group(function () {
        //Changer de password
        Route::get('/mot-de-passe', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('/mot-de-passe', [PasswordController::class, 'update'])->name('password.update');
    });
    //-------------------------------------------------
    // LES ROUTES ADMIN
    //-------------------------------------------------
    // Back-office : auth + middleware admin
    Route::middleware(['auth'])->group(function () {
        Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            //Routes CRUD categories,sous-categories,users,zones, produits,livreur et fournisseurs admin
             Route::get('/categories/{categoryId}/sous-categories', [AdminProductController::class, 'getSousCategories'])->name('sous-categories.get');
            Route::resource('categories', CategoryController::class);
            Route::resource('sous-categories', SousCategoryController::class);
            Route::resource('zones', ZoneController::class)->except(['show']);
            Route::resource('users', UserController::class);
            Route::resource('livreurs', LivreurController::class);
            Route::resource('fournisseurs', FournisseurController::class);
            Route::resource('produits', AdminProductController::class);

            // Routes commandes
            Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::patch('/orders/{order}/status', [AdminOrderController::class, 'changeStatus'])->name('orders.status');
            Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

            //Routes concernant les tournées(Livraison et expedition)
            Route::prefix('tournees')->name('tournees.')->group(function(){
                Route::get('/', [TourneeController::class, 'index'])->name('index');
                Route::get('/create', [TourneeController::class, 'create'])->name('create');
                Route::get('/zones/{zone}/orders', [TourneeController::class, 'ordersByZone'])->name('orders-by-zone');
                Route::post('/', [TourneeController::class, 'store'])->name('store');
                Route::get('/{tournee}', [TourneeController::class, 'show'])->name('show');
                Route::post('/{tournee}/orders/{order}/deliver', [TourneeController::class, 'markOrderDelivered'])->name('deliver-order');
                Route::post('/{tournee}/close', [TourneeController::class, 'close'])->name('close');
            });
            //Routes pour les achats PARTIE GESTION DE STOCK
            Route::prefix('achat')->name('achats.')->group(function (){
                Route::get('/', [AchatController::class, 'index'])->name('index');
                Route::get('/create', [AchatController::class,'create'])->name('create');
                Route::post('/', [AchatController::class, 'store'])->name('store');
                Route::get('/{achat}', [AchatController::class, 'show'])->name('show');
                Route::post('/{achat}/confirmer',    [AchatController::class, 'confirmer'])->name('confirmer');
                Route::get('/{achat}/reception',     [AchatController::class, 'receptionForm'])->name('reception');
                Route::post('/{achat}/receptionner', [AchatController::class, 'receptionner'])->name('receptionner');
                Route::post('/{achat}/annuler',      [AchatController::class, 'annuler'])->name('annuler');
            });
            //ROUTES INVENTAIRES PARTIE GESTION DE STOCK
        Route::prefix('inventaires')->name('inventaires.')->group(function () {
            Route::get('/', [InventaireController::class, 'index'])->name('index');
                Route::get('/create', [InventaireController::class, 'create'])->name('create');
                Route::post('/', [InventaireController::class, 'store'])->name('store');
                Route::get('/{inventaire}', [InventaireController::class, 'show'])->name('show');
                Route::patch('/{inventaire}', [InventaireController::class, 'update'])->name('update');
                Route::post('/{inventaire}/valider', [InventaireController::class, 'valider'])->name('valider');
                Route::post('/{inventaire}/annuler', [InventaireController::class, 'annuler'])->name('annuler');
                Route::delete('/{inventaire}', [InventaireController::class, 'destroy'])->name('destroy');
            });

            //Route Stock mouvement pour garder l'historique des mouvements
            Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
            Route::get('/stock-mouvements', [StockMouvementController::class, 'index'])->name('stock-mouvements.index');
            
            //ROUTES AJUSTEMENTS POUR LES CORRECTIONS 
            Route::get('/stock-ajustements/create', [StockAjustementController::class, 'create'])->name('stock-ajustements.create');
            Route::post('/stock-ajustements', [StockAjustementController::class, 'store'])->name('stock-ajustements.store');
        });
    });