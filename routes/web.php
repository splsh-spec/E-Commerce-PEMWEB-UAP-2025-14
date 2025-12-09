<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// PUBLIC
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ProductsPageController;
use App\Http\Controllers\ProductDetailPageController;

// AUTH
use App\Http\Controllers\ProfileController;

// CUSTOMER MEMBER
use App\Http\Controllers\Customer\HomeController as MemberHomeController;
use App\Http\Controllers\Customer\ProductController as MemberProductController;
use App\Http\Controllers\Customer\CheckoutController as MemberCheckoutController;
use App\Http\Controllers\Customer\TransactionController as MemberTransactionController;
use App\Http\Controllers\Customer\WalletController as MemberWalletController;

// SELLER
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\BalanceController;
use App\Http\Controllers\Seller\WithdrawalController;
use App\Http\Controllers\SellerDashboardController;

// ADMIN
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\StoreVerificationController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\SellerController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ProductDetailPageController::class, 'show'])->name('product.detail');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Profile)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| MEMBER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'member'])->group(function () {

    Route::get('/home', [MemberHomeController::class, 'index'])->name('member.home');

    // Store Register
    Route::get('/store/register', [StoreController::class, 'registerForm'])->name('store.register');
    Route::post('/store/register', [StoreController::class, 'store'])->name('store.register.save');

    // Checkout
    Route::get('/checkout/{productId}', [MemberCheckoutController::class, 'index'])->name('member.checkout.index');
    Route::post('/checkout/process', [MemberCheckoutController::class, 'process'])->name('member.checkout.process');

    // History
    Route::get('/history', [MemberTransactionController::class, 'index'])->name('member.history');

    // Wallet
    Route::get('/wallet/topup', [MemberWalletController::class, 'topupForm'])->name('member.wallet.topup');
    Route::post('/wallet/topup', [MemberWalletController::class, 'makeTopup'])->name('member.wallet.topup.process');

    // Payment
    Route::get('/payment', [MemberWalletController::class, 'paymentPage'])->name('member.payment.page');
    Route::post('/payment/confirm', [MemberWalletController::class, 'confirmPayment'])->name('member.payment.confirm');
});

/*
|--------------------------------------------------------------------------
| SELLER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {

    Route::get('/dashboard', [StoreController::class, 'dashboard'])->name('dashboard');

    // Store Profile
    Route::get('/profile', [StoreController::class, 'edit'])->name('profile');
    Route::put('/profile', [StoreController::class, 'update'])->name('profile.update');

    // Categories
    Route::resource('/categories', CategoryController::class);

    // Products
    Route::resource('/products', SellerProductController::class);
    Route::post('/products/{id}/images', [SellerProductController::class, 'addImage'])->name('products.images.add');
    Route::delete('/products/images/{id}', [SellerProductController::class, 'deleteImage'])->name('products.images.delete');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/{id}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking');

    // Balance
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance');
    Route::get('/balance/history', [BalanceController::class, 'history'])->name('balance.history');

    // Withdrawal
    Route::get('/withdraw', [WithdrawalController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [WithdrawalController::class, 'requestWithdraw'])->name('withdraw.submit');
});
Route::middleware(['auth', 'seller'])
    ->get('/seller/dashboard', [SellerDashboardController::class, 'index'])
    ->name('seller.dashboard');
    Route::post('/admin/create-seller', [SellerController::class, 'createSeller']);


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.delete');

    // Store Management
    Route::get('/stores', [StoreManagementController::class, 'index'])->name('stores');
    Route::get('/stores/{id}/edit', [StoreManagementController::class, 'edit'])->name('stores.edit');
    Route::put('/stores/{id}', [StoreManagementController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{id}', [StoreManagementController::class, 'destroy'])->name('stores.delete');

    // Store Verification
    Route::get('/verification', [StoreVerificationController::class, 'index'])->name('verification');
    Route::post('/verification/{id}/approve', [StoreVerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verification/{id}/reject', [StoreVerificationController::class, 'reject'])->name('verification.reject');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/admin/create-seller', [SellerController::class, 'createSeller'])
        ->name('admin.create.seller');
});
Route::get('/admin/create-seller-form', function () {
    return view('admin.create-seller');
})->middleware(['auth','admin']);


/*
|--------------------------------------------------------------------------
| LOGIN REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/login-redirect', function () {
    if (!Auth::check()) return redirect('/login');

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'seller' => redirect()->route('seller.dashboard'),
        'member' => redirect()->route('home'),
        default => redirect('/'),
    };
})->name('login.redirect');

require __DIR__.'/auth.php';