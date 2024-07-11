<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;

// Home Route
Route::get('/', function () {
    return view('home');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authentication
Auth::routes();

// Dashboard Routes
Route::prefix('Dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('Dashboard.index');
    Route::resource('charts', DashboardController::class);
});

// Point Of Sale Controller
Route::prefix('Sales')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('Sales.index');
});


// Inventory Routes
Route::prefix('Inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('Inventory.index');
    Route::resource('data', InventoryController::class);

    Route::get('Products', [ProductController::class, 'index'])->name('Inventory.products'); // Initialize
    Route::get('/Products/Product-Form', [ProductController::class, 'create'])->name('products.create'); // FORM
    Route::post('/Products', [ProductController::class, 'store'])->name('products.store'); // SUBMIT

    Route::get('Products/Categories', [CategoryController::class, 'index'])->name('Inventory.categories'); // Initialize
    Route::get('/Products/Categories/Category-Form', [CategoryController::class, 'create'])->name('category.create'); // FORM
    Route::get('/Products/Categories/FetchCategories', [CategoryController::class, 'getCategories'])->name('category.get');// FETCH CATEGORIES
    Route::post('/Products/Categories/Category-Form', [CategoryController::class, 'store'])->name('category.store'); // SUBMIT

    Route::get('Stocks', [StockController::class, 'index'])->name('Inventory.stocks');

    Route::get('Suppliers', [SupplierController::class, 'index'])->name('Inventory.suppliers'); // INITIALIZE
    Route::get('/Products/Supplier-Form', [SupplierController::class, 'create'])->name('suppliers.create'); // FORM
    Route::post('/Suppliers', [SupplierController::class, 'store'])->name('suppliers.store'); // SUBMIT
    Route::get('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search'); // SEARCH
});

// Users Routes
Route::prefix('Users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('Users.index'); 
 });

// Settings Routes
Route::prefix('Settings')->group(function () {
   Route::get('/', [SettingController::class, 'index'])->name('Settings.index'); 
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
