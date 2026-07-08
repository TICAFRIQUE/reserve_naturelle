    <?php

    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Admin\CategoryController;
    use App\Http\Controllers\Admin\FournisseurController;
    use App\Http\Controllers\Admin\SousCategoryController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\OrderController as AdminOrderController;
    use App\Http\Controllers\Admin\ProductController as AdminProductController;
    use App\Http\Controllers\Client\ProductController as ClientProductController;
    use App\Http\Controllers\Client\OrderController as ClientOrderController;
    use App\Http\Controllers\Client\CartController;
    use Illuminate\Support\Facades\Route;

    //-------------------------------------------------
    // LES ROUTES ADMIN
    //-------------------------------------------------

    // Accueil = catalogue produit (public)
        Route::get('/', [ClientProductController::class, 'index'])->name('home');


    // Alias public du catalogue
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/produits', [ClientProductController::class, 'index'])->name('products.index');
        // Catalogue complet (tous les produits)
        Route::get('/catalogue', [ClientProductController::class, 'catalogue'])->name('products.catalogue');
    });

    // Fiche produit + recherche AJAX : réservées aux utilisateurs connectés
    Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {

        Route::prefix('produits')->name('products.')->group(function () {
        
            Route::get('/recherche', [ClientProductController::class, 'search'])->name('search');
            Route::get('/{product}', [ClientProductController::class, 'show'])->name('show');
        });
        
        //Les routes paniers et commandes : réservés aux utilisateurs connectés
        Route::prefix('panier')->name('cart.')->group(function(){
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/', [CartController::class, 'store'])->name('store');
            Route::patch('/items/{item}', [CartController::class, 'update'])->name('update');
            Route::post('/items/{item}', [CartController::class, 'destroy'])->name('destroy');
            Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        });
        Route::prefix('commandes')->name('orders.')->group(function(){
            Route::get('/', [ClientOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [ClientOrderController::class, 'show'])->name('show');
            Route::post('/',[ClientOrderController::class, 'store'])->name('store');
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

    //-------------------------------------------------
    // LES ROUTES ADMIN
    //-------------------------------------------------
    // Back-office : auth + middleware admin
    Route::middleware(['auth'])->group(function () {
        Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
            Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

            //Routes CRUD categories,sous-categories,users, produits et fournisseurs admin
            Route::resource('categories', CategoryController::class);
            Route::resource('sous-categories', SousCategoryController::class);
            Route::resource('users', UserController::class);
            Route::resource('fournisseurs', FournisseurController::class);
            Route::resource('produits', AdminProductController::class);

            // Actions custom hors CRUD standard du resource produits
            Route::post('/produits/{id}/stock', [AdminProductController::class, 'updateStock'])->name('produits.update-stock');
            Route::post('/produits/{id}/restore', [AdminProductController::class, 'restore'])->name('produits.restore');

            // Routes commandes
            Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::patch('/orders/{order}/status', [AdminOrderController::class, 'changeStatus'])->name('orders.status');
        });
    });