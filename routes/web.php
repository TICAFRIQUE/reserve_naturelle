<?php

use App\Http\Controllers\Admin\AchatController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategorieDepenseController;
use App\Http\Controllers\Admin\CompteExploitationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepenseController;
use App\Http\Controllers\Admin\DocumentationController;
use App\Http\Controllers\Admin\FournisseurController;
use App\Http\Controllers\Admin\InventaireController;
use App\Http\Controllers\Admin\LibelleDepenseController;
use App\Http\Controllers\Admin\LivreurController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SousCategoryController;
use App\Http\Controllers\Admin\StockAjustementController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMouvementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\SitemapController;
use App\Models\Order;
use App\Notifications\OrderValidatedNotification;
use Illuminate\Support\Facades\Route;

// ==========================================================================
// ROUTES PUBLIQUES
// ==========================================================================

// Accueil = catalogue produit (public)
Route::get('/', [ClientProductController::class, 'index'])->name('home');
Route::redirect('/connexion', '/login');
Route::redirect('/inscription', '/register');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \App\Http\Middleware\ShareCartPendingOrder::class,
        \App\Http\Middleware\ShareCartCount::class,
    ])->name('sitemap');

// Prévisualisation email (debug)
Route::get('/preview-mail/{order}', function (Order $order) {
    return (new OrderValidatedNotification($order))->toMail($order->user)->render();
})->middleware('auth');

// ==========================================================================
// ROUTES CLIENT (Public / Invité + Connecté)
// ==========================================================================

Route::prefix('client')->name('client.')->group(function () {
    Route::get('/produits', [ClientProductController::class, 'index'])->name('products.index');
    Route::get('/catalogue', [ClientProductController::class, 'catalogue'])->name('products.catalogue');
    
    // Page dédiée à une catégorie
    Route::get('/categorie/{category:slug}', [ClientProductController::class, 'category'])->name('products.category');

    Route::prefix('produits')->name('products.')->group(function () {
        Route::get('/{product:slug}', [ClientProductController::class, 'show'])->name('show');
    });

    Route::prefix('panier')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store')->middleware(['throttle:30,1']);
        Route::patch('/items/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/items/{item}', [CartController::class, 'destroy'])->name('destroy');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
    });
});

// ==========================================================================
// ROUTES CLIENT (Protégées : Auth requis)
// ==========================================================================

Route::prefix('client')->name('client.')->middleware(['auth', 'auth.session'])->group(function () {

    // Commandes
    Route::prefix('commandes')->name('orders.')->group(function () {
        Route::get('/', [ClientOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [ClientOrderController::class, 'show'])->name('show');
        Route::post('/', [ClientOrderController::class, 'store'])->name('store');
        
        // Paiement / Abandon
        Route::delete('/{order}/abandon', [ClientOrderController::class, 'abandon'])->name('abandon');
        
        // Téléchargement du reçu
        Route::get('/{order}/ticket', [ClientOrderController::class, 'downloadTicket'])->name('ticket');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/{order}', [CheckoutController::class, 'show'])->name('show');
        Route::post('/{order}', [CheckoutController::class, 'store'])->name('store');
    });

    // Réservations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ClientReservationController::class, 'index'])->name('index');
        Route::get('/{reservation}', [ClientReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/recu', [ClientReservationController::class, 'downloadReceipt'])->name('receipt');
        Route::post('/', [ClientReservationController::class, 'store'])->name('store')->middleware('throttle:10,1');
        Route::patch('/{reservation}/annuler', [ClientReservationController::class, 'cancel'])->name('cancel');
    });
});

// ==========================================================================
// PAGES STATIQUES
// ==========================================================================

Route::view('/contact', 'client.contact')->name('contact');

// ==========================================================================
// AUTHENTIFICATION
// ==========================================================================

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store')->middleware(['throttle:login', 'recaptcha']);
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware(['throttle:10,1', 'recaptcha']);
Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email')->middleware('throttle:10,1');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/mot-de-passe', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/mot-de-passe', [PasswordController::class, 'update'])->name('password.update')->middleware('throttle:6,1');
});

// ==========================================================================
// ROUTES ADMIN (Back-office)
// ==========================================================================

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {

        /**
         * Helper pour générer un CRUD avec une permission par action :
         * "{module}.{creer|voir|modifier|supprimer}".
         */
        $crud = function (string $uri, string $controller, string $perm, array $except = [], array $parameters = []) {
            $actions = [
                'creer'     => ['create', 'store'],
                'voir'      => ['index', 'show'],
                'modifier'  => ['edit', 'update'],
                'supprimer' => ['destroy'],
            ];
            foreach ($actions as $action => $methods) {
                $only = array_values(array_diff($methods, $except));
                if ($only) {
                    Route::resource($uri, $controller)
                        ->only($only)
                        ->parameters($parameters)
                        ->middleware("permission:$perm.$action");
                }
            }
        };

        // --- Dashboard & Aide ---
        Route::get('/', AdminHomeController::class)->name('index');
        
        Route::get('/aide/guide', [DocumentationController::class, 'guide'])->name('guide');
        Route::get('/aide/documentation', [DocumentationController::class, 'technique'])->middleware('role:admin')->name('documentation');
            
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard.voir')->name('dashboard');

        // --- Catalogue & Référentiels ---
        Route::get('/categories/{categoryId}/sous-categories', [AdminProductController::class, 'getSousCategories'])
            ->middleware('permission:produits.voir')->name('sous-categories.get');

        $crud('categories',         CategoryController::class,        'categories');
        $crud('sous-categories',    SousCategoryController::class,     'sous-categories');
        $crud('zones',              ZoneController::class,             'zones', ['show']);
        
        // Rôles & permissions : réservé au rôle admin
        Route::resource('roles', RoleController::class)->except('show')->middleware('role:admin');

        $crud('users',              UserController::class,             'users');
        $crud('livreurs',           LivreurController::class,          'livreurs');
        $crud('fournisseurs',       FournisseurController::class,      'fournisseurs');
        $crud('produits',           AdminProductController::class,     'produits');

        // Variantes produits (shallow = URLs simplifiées pour edit/update/delete)
        Route::resource('produits.variants', ProductVariantController::class)
            ->shallow()
            ->except(['show'])
            ->parameters(['produits' => 'product'])
            ->middleware('permission:produits.modifier');

        // --- Commandes ---
        Route::middleware('permission:orders.voir')->group(function () {
            Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::get('/orders/{order}/ticket', [AdminOrderController::class, 'downloadTicket'])->name('orders.ticket');
        });
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'changeStatus'])->middleware('permission:orders.modifier')->name('orders.status');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->middleware('permission:orders.supprimer')->name('orders.destroy');

        // --- Réservations ---
        Route::middleware('permission:reservations.creer')->group(function () {
            Route::get('/reservations/create', [AdminReservationController::class, 'create'])->name('reservations.create');
            Route::post('/reservations', [AdminReservationController::class, 'store'])->name('reservations.store');
        });
        Route::middleware('permission:reservations.voir')->group(function () {
            Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
            Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
            Route::get('/reservations/{reservation}/recu', [AdminReservationController::class, 'downloadReceipt'])->name('reservations.receipt');
        });
        Route::patch('/reservations/{reservation}/status', [AdminReservationController::class, 'changeStatus'])->middleware('permission:reservations.modifier')->name('reservations.status');

        // --- Achats (gestion de stock) ---
        Route::prefix('achat')->name('achats.')->group(function () {
            Route::middleware('permission:achats.creer')->group(function () {
                Route::get('/create', [AchatController::class, 'create'])->name('create');
                Route::post('/', [AchatController::class, 'store'])->name('store');
            });
            Route::middleware('permission:achats.voir')->group(function () {
                Route::get('/', [AchatController::class, 'index'])->name('index');
                Route::get('/{achat}', [AchatController::class, 'show'])->name('show');
            });
            Route::middleware('permission:achats.receptionner')->group(function () {
                Route::get('/{achat}/reception', [AchatController::class, 'receptionForm'])->name('reception');
                Route::post('/{achat}/receptionner', [AchatController::class, 'receptionner'])->name('receptionner');
            });
            Route::post('/{achat}/annuler', [AchatController::class, 'annuler'])->middleware('permission:achats.annuler')->name('annuler');
        });

        // --- Inventaires ---
        Route::prefix('inventaires')->name('inventaires.')->group(function () {
            Route::middleware('permission:inventaires.creer')->group(function () {
                Route::get('/create', [InventaireController::class, 'create'])->name('create');
                Route::post('/', [InventaireController::class, 'store'])->name('store');
            });
            Route::middleware('permission:inventaires.voir')->group(function () {
                Route::get('/', [InventaireController::class, 'index'])->name('index');
                Route::get('/{inventaire}', [InventaireController::class, 'show'])->name('show');
            });
            Route::post('/{inventaire}/valider', [InventaireController::class, 'valider'])->middleware('permission:inventaires.valider')->name('valider');
            Route::post('/{inventaire}/annuler', [InventaireController::class, 'annuler'])->middleware('permission:inventaires.annuler')->name('annuler');
            Route::delete('/{inventaire}', [InventaireController::class, 'destroy'])->middleware('permission:inventaires.supprimer')->name('destroy');
        });

        // --- Stock ---
        Route::get('/stock', [StockController::class, 'index'])->middleware('permission:stock.voir')->name('stock.index');
        Route::get('/stock-mouvements', [StockMouvementController::class, 'index'])->middleware('permission:stock-mouvements.voir')->name('stock-mouvements.index');

        // Ajustements
        Route::middleware('permission:stock.ajuster')->group(function () {
            Route::get('/stock-ajustements/create', [StockAjustementController::class, 'create'])->name('stock-ajustements.create');
            Route::post('/stock-ajustements', [StockAjustementController::class, 'store'])->name('stock-ajustements.store');
        });

        // --- Rapports ---
        Route::middleware('permission:rapports.voir')->group(function () {
            Route::get('/rapports/stock/export', [RapportController::class, 'exportPdf'])->name('rapports.stock_pdf');
            Route::get('/rapports/stock/{onglet?}', [RapportController::class, 'index'])->whereIn('onglet', RapportController::ONGLETS)->name('rapports.stock');
            Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
            Route::get('rapports/compte-exploitation', [CompteExploitationController::class, 'index'])->name('rapports.compte_exploitation');
            Route::get('rapports/compte-exploitation/pdf', [CompteExploitationController::class, 'exportPdf'])->name('rapports.compte_exploitation_pdf');
        });

        // --- Dépenses ---
        $crud('depenses',           DepenseController::class,          'depenses', ['show']);
        $crud('categorie-depenses', CategorieDepenseController::class, 'categorie-depenses', ['show']);
        $crud('libelle-depenses',   LibelleDepenseController::class,   'depenses', ['show'], ['libelle-depenses' => 'libelleDepense']);

        // --- Bannière ---
        Route::get('/banner', [BannerController::class, 'edit'])->middleware('permission:banner.voir')->name('banner.edit');
        Route::middleware('permission:banner.modifier')->group(function () {
            Route::put('/banner', [BannerController::class, 'update'])->name('banner.update');
            Route::post('/banner/slides', [BannerController::class, 'storeSlides'])->name('banner.slides.store');
            Route::delete('/banner/slides/{slide}', [BannerController::class, 'destroySlide'])->name('banner.slides.destroy');
            Route::patch('/banner/slides/{slide}/toggle', [BannerController::class, 'toggleSlide'])->name('banner.slides.toggle');
        });
    });
});