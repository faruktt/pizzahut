<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminPosController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminSizeController;
use App\Http\Controllers\Admin\AdminCrustController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminComboController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\AdminRedirectIfAuthenticated;

/*
|--------------------------------------------------------------------------
| Customer & Frontend Routes (Pizza Hut / Italian Pizza Experience)
|--------------------------------------------------------------------------
*/

// Homepage (Matching Image 1)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product Customization Modal Details (Matching Image 2)
Route::get('/product-details/{id}', [HomeController::class, 'getProductDetails'])->name('product.details');

// Deal Combo Details Page & Ordering
Route::get('/deal/{id}', [\App\Http\Controllers\DealController::class, 'show'])->name('deal.show');
Route::post('/deal/{id}/add', [\App\Http\Controllers\DealController::class, 'addComboToCart'])->name('deal.add');

// New Combo Section (admin-managed combos)
Route::get('/combo/{id}', [ComboController::class, 'show'])->name('combo.show');
Route::post('/combo/{id}/add', [ComboController::class, 'addToCart'])->name('combo.add');

// Cart Routes (Matching Image 3)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & Order Tracking
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order-success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Customer Authentication (Guard: web)
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Portal & POS System Routes (Guard: admin)
|--------------------------------------------------------------------------
*/

// Admin Guest Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->middleware(AdminRedirectIfAuthenticated::class)
        ->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware(AdminRedirectIfAuthenticated::class);
});

// Admin Protected Routes (Light Dashboard, POS, Product & Order Management)
Route::prefix('admin')->middleware(AdminAuthenticate::class)->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Dashboard (Premium Light Theme)
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Point of Sale (POS) - Table & Live Dine-in Ordering
    Route::get('/pos', [AdminPosController::class, 'index'])->name('admin.pos.index');
    Route::get('/pos/table/{tableId}', [AdminPosController::class, 'getTableOrder'])->name('admin.pos.tableOrder');
    Route::post('/pos/table/{tableId}/add', [AdminPosController::class, 'addItemToTable'])->name('admin.pos.addItem');
    Route::post('/pos/item/{itemId}/remove', [AdminPosController::class, 'removeItemFromTable'])->name('admin.pos.removeItem');
    Route::post('/pos/table/{tableId}/complete', [AdminPosController::class, 'completeTableOrder'])->name('admin.pos.completeOrder');

    // Orders Management (Supports orders with or without registered customers)
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/create', [AdminOrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/orders', [AdminOrderController::class, 'store'])->name('admin.orders.store');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Products Management (Sizes, Crusts, Prices)
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    // Restaurant Tables Management
    Route::get('/tables', [AdminTableController::class, 'index'])->name('admin.tables.index');
    Route::post('/tables', [AdminTableController::class, 'store'])->name('admin.tables.store');
    Route::post('/tables/{id}/status', [AdminTableController::class, 'updateStatus'])->name('admin.tables.updateStatus');
    Route::delete('/tables/{id}', [AdminTableController::class, 'destroy'])->name('admin.tables.destroy');

    // Categories CRUD
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Sizes CRUD
    Route::get('/sizes', [AdminSizeController::class, 'index'])->name('admin.sizes.index');
    Route::post('/sizes', [AdminSizeController::class, 'store'])->name('admin.sizes.store');
    Route::put('/sizes/{id}', [AdminSizeController::class, 'update'])->name('admin.sizes.update');
    Route::delete('/sizes/{id}', [AdminSizeController::class, 'destroy'])->name('admin.sizes.destroy');

    // Crusts CRUD
    Route::get('/crusts', [AdminCrustController::class, 'index'])->name('admin.crusts.index');
    Route::post('/crusts', [AdminCrustController::class, 'store'])->name('admin.crusts.store');
    Route::put('/crusts/{id}', [AdminCrustController::class, 'update'])->name('admin.crusts.update');
    Route::delete('/crusts/{id}', [AdminCrustController::class, 'destroy'])->name('admin.crusts.destroy');

    // Combos CRUD (admin-managed combo meals)
    Route::get('/combos', [AdminComboController::class, 'index'])->name('admin.combos.index');
    Route::get('/combos/create', [AdminComboController::class, 'create'])->name('admin.combos.create');
    Route::post('/combos', [AdminComboController::class, 'store'])->name('admin.combos.store');
    Route::get('/combos/{id}/edit', [AdminComboController::class, 'edit'])->name('admin.combos.edit');
    Route::put('/combos/{id}', [AdminComboController::class, 'update'])->name('admin.combos.update');
    Route::delete('/combos/{id}', [AdminComboController::class, 'destroy'])->name('admin.combos.destroy');
    Route::post('/combos/{id}/toggle', [AdminComboController::class, 'toggleStatus'])->name('admin.combos.toggle');

    // Website Banners CRUD (multiple banners shown on frontend)
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('admin.banners.index');
    Route::get('/banners/create', [AdminBannerController::class, 'create'])->name('admin.banners.create');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('admin.banners.store');
    Route::get('/banners/{id}/edit', [AdminBannerController::class, 'edit'])->name('admin.banners.edit');
    Route::put('/banners/{id}', [AdminBannerController::class, 'update'])->name('admin.banners.update');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('admin.banners.destroy');

    // Website Settings (Logo & Favicon management)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings/logo', [AdminSettingController::class, 'updateLogo'])->name('admin.settings.logo');
    Route::post('/settings/favicon', [AdminSettingController::class, 'updateFavicon'])->name('admin.settings.favicon');
    Route::delete('/settings/logo', [AdminSettingController::class, 'removeLogo'])->name('admin.settings.logo.remove');
    Route::delete('/settings/favicon', [AdminSettingController::class, 'removeFavicon'])->name('admin.settings.favicon.remove');
});
