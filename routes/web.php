<?php

use Illuminate\Support\Facades\Route;

// PUBLIC CONTROLLERS
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ProductsPageController;

// AUTH
use App\Http\Controllers\ProfileController;

// CUSTOMER
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\Customer\WalletController;

// SELLER
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\BalanceController;
use App\Http\Controllers\Seller\WithdrawalController;

// ADMIN
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\StoreVerificationController;
// member
use App\Http\Controllers\Customer\HomeController as MemberHomeController;
use App\Http\Controllers\Customer\ProductController as MemberProductController;
use App\Http\Controllers\Customer\CheckoutController as MemberCheckoutController;
use App\Http\Controllers\Customer\TransactionController as MemberTransactionController;
use App\Http\Controllers\Customer\WalletController as MemberWalletController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Guest & User)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomepageController::class, 'index'])->name('home');

Route::get('/product/{slug}', [ProductsPageController::class, 'show'])
    ->name('product.detail');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze Default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| CUSTOMER MEMBER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'member'])->group(function () {
    Route::get('/store/register', [StoreController::class, 'registerForm'])->name('store.register');
    Route::post('/store/register', [StoreController::class, 'store'])->name('store.register.save');
});

// Dashboard Seller (harus punya store & verified)
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {

    Route::get('/dashboard', [StoreController::class, 'dashboard'])->name('dashboard');

    // Manage toko
    Route::get('/profile', [StoreController::class, 'edit'])->name('profile');
    Route::put('/profile', [StoreController::class, 'update'])->name('profile.update');

    // Categories
    Route::resource('/categories', CategoryController::class);

    // Products + Images
    Route::resource('/products', SellerProductController::class);
    Route::post('/products/{id}/images', [SellerProductController::class, 'addImage'])->name('products.images.add');
    Route::delete('/products/images/{id}', [SellerProductController::class, 'deleteImage'])->name('products.images.delete');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/{id}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking');

    // Store Balance
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance');
    Route::get('/balance/history', [BalanceController::class, 'history'])->name('balance.history');

    // Withdrawal
    Route::get('/withdraw', [WithdrawalController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [WithdrawalController::class, 'requestWithdraw'])->name('withdraw.submit');
});
// admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Verifikasi Toko
    Route::get('/verification', [StoreVerificationController::class, 'index'])->name('verification');
    Route::post('/verification/{id}/approve', [StoreVerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verification/{id}/reject', [StoreVerificationController::class, 'reject'])->name('verification.reject');

    // Manajemen Users & Stores
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.delete');
});
//member 
Route::middleware(['auth', 'member'])->group(function () {

    // Homepage member
    Route::get('/home', [MemberHomeController::class, 'index'])->name('member.home');

    // Detail Produk
    Route::get('/product/{slug}', [MemberProductController::class, 'show'])->name('member.product.show');

    // Checkout
    Route::get('/checkout/{productId}', [MemberCheckoutController::class, 'index'])->name('member.checkout.index');
    Route::post('/checkout/process', [MemberCheckoutController::class, 'process'])->name('member.checkout.process');

    // History
    Route::get('/history', [TransactionController::class, 'index'])
        ->name('member.history');

    // Wallet / Topup
    Route::get('/wallet/topup', [WalletController::class, 'topupForm'])->name('member.wallet.topup');
    Route::post('/wallet/topup', [WalletController::class, 'makeTopup'])->name('member.wallet.topup.process');

    // Payment VA
    Route::get('/payment', [WalletController::class, 'paymentPage'])->name('member.payment.page');
    Route::post('/payment/confirm', [WalletController::class, 'confirmPayment'])->name('member.payment.confirm');
});

require __DIR__.'/auth.php';
