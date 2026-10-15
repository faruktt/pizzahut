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
use App\Http\Controllers\Admin\AdminAddOnController;
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

// Custom CMS Pages
Route::get('/page/{slug}', [HomeController::class, 'showPage'])->name('page.show');

// New Combo Section (admin-managed combos)
Route::get('/combo/{id}', [ComboController::class, 'show'])->name('combo.show');
Route::post('/combo/{id}/add', [ComboController::class, 'addToCart'])->name('combo.add');

// Cart Routes (Matching Image 3)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
Route::post('/cart/apply-points', [CartController::class, 'applyPoints'])->name('cart.applyPoints');
Route::post('/cart/remove-points', [CartController::class, 'removePoints'])->name('cart.removePoints');

// Checkout & Order Tracking (Protected - Customer must be logged in to order)
Route::middleware('auth:web')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
Route::get('/order-success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Customer Authentication (Guard: web)
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// Customer Dashboard (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CustomerAuthController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/dashboard/orders', [CustomerAuthController::class, 'orders'])->name('customer.orders');
    Route::get('/dashboard/orders/{id}', [CustomerAuthController::class, 'orderDetail'])->name('customer.orders.show');
    Route::put('/dashboard/profile', [CustomerAuthController::class, 'updateProfile'])->name('customer.profile.update');
    Route::put('/dashboard/password', [CustomerAuthController::class, 'updatePassword'])->name('customer.password.update');
});


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

    // Add Ons CRUD (Extra items for products)
    Route::get('/addons', [AdminAddOnController::class, 'index'])->name('admin.addons.index');
    Route::get('/addons/create', [AdminAddOnController::class, 'create'])->name('admin.addons.create');
    Route::post('/addons', [AdminAddOnController::class, 'store'])->name('admin.addons.store');
    Route::get('/addons/{id}/edit', [AdminAddOnController::class, 'edit'])->name('admin.addons.edit');
    Route::put('/addons/{id}', [AdminAddOnController::class, 'update'])->name('admin.addons.update');
    Route::delete('/addons/{id}', [AdminAddOnController::class, 'destroy'])->name('admin.addons.destroy');
    Route::post('/addons/{id}/toggle', [AdminAddOnController::class, 'toggleStatus'])->name('admin.addons.toggle');

    // Combos CRUD (admin-managed combo meals)
    Route::get('/combos', [AdminComboController::class, 'index'])->name('admin.combos.index');
    Route::get('/combos/create', [AdminComboController::class, 'create'])->name('admin.combos.create');
    Route::post('/combos', [AdminComboController::class, 'store'])->name('admin.combos.store');
    Route::get('/combos/{id}/edit', [AdminComboController::class, 'edit'])->name('admin.combos.edit');
    Route::put('/combos/{id}', [AdminComboController::class, 'update'])->name('admin.combos.update');
    Route::delete('/combos/{id}', [AdminComboController::class, 'destroy'])->name('admin.combos.destroy');
    Route::post('/combos/{id}/toggle', [AdminComboController::class, 'toggleStatus'])->name('admin.combos.toggle');

    // Coupons CRUD
    Route::get('/coupons', [\App\Http\Controllers\Admin\AdminCouponController::class, 'index'])->name('admin.coupons.index');
    Route::get('/coupons/create', [\App\Http\Controllers\Admin\AdminCouponController::class, 'create'])->name('admin.coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\Admin\AdminCouponController::class, 'store'])->name('admin.coupons.store');
    Route::get('/coupons/{id}/edit', [\App\Http\Controllers\Admin\AdminCouponController::class, 'edit'])->name('admin.coupons.edit');
    Route::put('/coupons/{id}', [\App\Http\Controllers\Admin\AdminCouponController::class, 'update'])->name('admin.coupons.update');
    Route::delete('/coupons/{id}', [\App\Http\Controllers\Admin\AdminCouponController::class, 'destroy'])->name('admin.coupons.destroy');
    Route::post('/coupons/{id}/toggle', [\App\Http\Controllers\Admin\AdminCouponController::class, 'toggleStatus'])->name('admin.coupons.toggleStatus');

    // Website Banners CRUD (multiple banners shown on frontend)
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('admin.banners.index');
    Route::get('/banners/create', [AdminBannerController::class, 'create'])->name('admin.banners.create');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('admin.banners.store');
    Route::get('/banners/{id}/edit', [AdminBannerController::class, 'edit'])->name('admin.banners.edit');
    Route::put('/banners/{id}', [AdminBannerController::class, 'update'])->name('admin.banners.update');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('admin.banners.destroy');

    // Website Settings (Logo & Favicon management)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings/general', [AdminSettingController::class, 'updateGeneral'])->name('admin.settings.general');
    Route::post('/settings/logo', [AdminSettingController::class, 'updateLogo'])->name('admin.settings.logo');
    Route::post('/settings/favicon', [AdminSettingController::class, 'updateFavicon'])->name('admin.settings.favicon');
    Route::delete('/settings/logo', [AdminSettingController::class, 'removeLogo'])->name('admin.settings.logo.remove');
    Route::delete('/settings/favicon', [AdminSettingController::class, 'removeFavicon'])->name('admin.settings.favicon.remove');
    Route::get('/settings/points', [AdminSettingController::class, 'pointsIndex'])->name('admin.settings.points');
    Route::post('/settings/points', [AdminSettingController::class, 'updatePoints'])->name('admin.settings.points.update');

    // Website CMS Pages CRUD
    Route::get('/pages', [\App\Http\Controllers\Admin\AdminPageController::class, 'index'])->name('admin.pages.index');
    Route::get('/pages/create', [\App\Http\Controllers\Admin\AdminPageController::class, 'create'])->name('admin.pages.create');
    Route::post('/pages', [\App\Http\Controllers\Admin\AdminPageController::class, 'store'])->name('admin.pages.store');
    Route::get('/pages/{id}/edit', [\App\Http\Controllers\Admin\AdminPageController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{id}', [\App\Http\Controllers\Admin\AdminPageController::class, 'update'])->name('admin.pages.update');
    Route::delete('/pages/{id}', [\App\Http\Controllers\Admin\AdminPageController::class, 'destroy'])->name('admin.pages.destroy');
    Route::post('/pages/{id}/toggle', [\App\Http\Controllers\Admin\AdminPageController::class, 'toggleStatus'])->name('admin.pages.toggleStatus');
});

