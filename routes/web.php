<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsPageController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
// seller
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\Seller\CategoryController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\BalanceController;
use App\Http\Controllers\Seller\WithdrawalController;
// admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\StoreVerificationController;
// member

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('products', ProductController::class);

Route::get('/', [HomepageController::class, 'index']);

Route::get('/product/{slug}', [ProductsPageController::class, 'show'])
     ->name('product.detail');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
// seller routes

// Halaman pendaftaran toko (khusus member yang belum punya store)
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
// member routes
Route::middleware(['auth', 'member'])->group(function () {

    // Homepage
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Detail Produk
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

    // Checkout
    Route::get('/checkout/{productId}', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Riwayat Transaksi
    Route::get('/history', [TransactionController::class, 'index'])->name('member.history');

    // Wallet / Topup
    Route::get('/wallet/topup', [WalletController::class, 'topupForm'])->name('wallet.topup');
    Route::post('/wallet/topup', [WalletController::class, 'makeTopup'])->name('wallet.topup.process');

    // Halaman Payment (VA)
    Route::get('/payment', [WalletController::class, 'paymentPage'])->name('payment.page');
    Route::post('/payment/confirm', [WalletController::class, 'confirmPayment'])->name('payment.confirm');
});
require __DIR__.'/auth.php';
