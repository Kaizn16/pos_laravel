<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitOfMeasureController;
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
    Route::get('/', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/fetchProducts', [SalesController::class, 'show'])->name('sales.get');
    Route::get('/SelectedProduct/{product_id}/View', [SalesController::class, 'preview'])->name('sales.view');
    Route::get('/NewTransaction', [TransactionController::class, 'generateNewTransaction'])->name('sales.new_transaction');
    Route::get('/Transaction/{transaction_id}/Cart', [CartController::class, 'show'])->name('cart.get');
    Route::post('/Transaction/Cart/Product/Add', [CartController::class, 'store'])->name('cart.store');
    Route::put('/Transaction/Cart/Product/{product_id}/AdjustQuantity', [CartController::class, 'update'])->name('cart.update');
    Route::patch('/Transaction/Cart/Product/{product_id}/Remove', [CartController::class, 'delete'])->name('cart.delete');
    Route::patch('/Transaction/Cart/{transaction_id}/Clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::post('/Transaction/Cart/Payment', [TransactionController::class, 'store'])->name('transaction.pay');
});


// Inventory Routes
Route::prefix('Inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('Inventory.index');
    Route::resource('data', InventoryController::class);

    Route::get('Products', [ProductController::class, 'index'])->name('Inventory.products'); // Initialize
    Route::get('/Products/fetchProducts', [ProductController::class, 'getProducts'])->name('products.get');// FETCH PRODUCTS
    Route::get('/Products/Product-Form', [ProductController::class, 'create'])->name('products.create'); // CREATE FORM
    Route::post('/Products/Product-Form/Create', [ProductController::class, 'store'])->name('products.store'); // SUBMIT
    Route::get('/Products/Product-Form/{product_id}/Edit', [ProductController::class, 'edit'])->name('product.edit'); // FORM
    Route::put('/Products/Product-Form/{product_id}/Update', [ProductController::class, 'update'])->name('product.update'); // UPDATE PRODUCT
    Route::patch('/Products/Product-Form/{product_id}/Delete', [ProductController::class, 'delete'])->name('product.delete'); // DELETE

    Route::get('Products/Categories', [CategoryController::class, 'index'])->name('Inventory.categories'); // Initialize
    Route::get('/Products/Categories/FetchCategories', [CategoryController::class, 'getCategories'])->name('category.get');// FETCH CATEGORIES
    Route::get('/Products/Categories/Category-Form', [CategoryController::class, 'create'])->name('category.create'); // Create FORM
    Route::post('/Products/Categories/Category-Form/Create', [CategoryController::class, 'store'])->name('category.store'); // SUBMIT
    Route::get('/Products/Categories/Category-Form/{category_id}/Edit', [CategoryController::class, 'edit'])->name('category.edit'); // EDIT FORM
    Route::put('/Products/Categories/Category-Form/{category_id}/Update', [CategoryController::class, 'update'])->name('category.update'); // UPDATE CATEGORY
    Route::patch('/Products/Categories/Category-Form/{category_id}/Delete', [CategoryController::class, 'delete'])->name('category.delete'); // DELETE

    Route::get('/Products/UOM', [UnitOfMeasureController::class, 'index'])->name('Inventory.uom'); // Initialize
    Route::get('/Products/UOM/fetchUnitOfMeasure', [UnitOfMeasureController::class, 'getUnitOfMeasures'])->name('uom.get'); // FETCH UNIT OF MEASURES
    Route::get('/Products/UOM/Uom-Form', [UnitOfMeasureController::class, 'create'])->name('uom.create'); // CREATE FORM
    Route::post('/Products/UOM/Uom-Form/Create', [UnitOfMeasureController::class, 'store'])->name('uom.store'); // SUBMIT
    Route::get('/Products/UOM/Uom-Form/{uom_id}/Edit', [UnitOfMeasureController::class, 'edit'])->name('uom.edit'); // EDIT FORM
    Route::put('/Products/UOM/Uom-Form/{uom_id}/Update', [UnitOfMeasureController::class, 'update'])->name('uom.update'); // UPDATE UOM

    Route::get('/Products/Stocks', [StockController::class, 'index'])->name('Inventory.stocks');
    Route::get('/Products/Stocks/FetchStocks', [StockController::class, 'getStocks'])->name('stocks.get');
    Route::get('/Products/Stocks/Stock-Form', [StockController::class, 'create'])->name('stocks.create');
    Route::post('/Products/Stocks/Stock-Form/Create', [StockController::class, 'store'])->name('stocks.store');
    Route::get('/Products/Stocks/Stock-Form/{stock_id}/Edit', [StockController::class, 'edit'])->name('stocks.edit');
    Route::put('/Products/Stocks/Stock-Form/{stock_id}/Update', [StockController::class, 'update'])->name('stocks.update');
    Route::patch('/Products/Stocks/Stock-Form/{stock_id}/Delete', [StockController::class, 'delete'])->name('stocks.delete');

    Route::get('/Products/Suppliers', [SupplierController::class, 'index'])->name('Inventory.suppliers'); // INITIALIZE
    Route::get('/Products/Suppliers/FetchSuppliers', [SupplierController::class, 'getSuppliers'])->name('suppliers.get');
    Route::get('/Products/Supplier-Form', [SupplierController::class, 'create'])->name('suppliers.create'); // FORM
    Route::post('/Products/Suppliers', [SupplierController::class, 'store'])->name('suppliers.store'); // SUBMIT
    Route::get('/Products/Suppliers/Search', [SupplierController::class, 'search'])->name('suppliers.search'); // SEARCH
});

// Users Routes
Route::prefix('Users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('Users.index'); 
 });

// Settings Routes
Route::prefix('Settings')->group(function () {
   Route::get('/', [SettingController::class, 'index'])->name('Settings.index'); 
});

// Route::prefix('Settings')->middleware('auth')->group(function ()