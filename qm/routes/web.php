<?php
use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Pharmacy;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/pharmacies', [HomeController::class, 'pharmacies'])->name('pharmacies.index');
Route::get('/pharmacies/{pharmacy}', [HomeController::class, 'pharmacy'])->name('pharmacies.show');

// Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer
Route::middleware(EnsureRole::class . ':customer')->group(function () {
    Route::get('/order/{listing}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/order/{listing}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Pharmacy owner
Route::prefix('pharmacy')->name('pharmacy.')->middleware(EnsureRole::class . ':pharmacy_owner')->group(function () {
    Route::get('/', [Pharmacy\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Pharmacy\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Pharmacy\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('inventory', Pharmacy\InventoryController::class)->parameters(['inventory' => 'listing'])->except('show');
    Route::get('/orders', [Pharmacy\OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}', [Pharmacy\OrderController::class, 'update'])->name('orders.update');
});

// Administrator
Route::prefix('admin')->name('admin.')->middleware(EnsureRole::class . ':admin')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/pharmacies', [Admin\PharmacyController::class, 'index'])->name('pharmacies.index');
    Route::patch('/pharmacies/{pharmacy}', [Admin\PharmacyController::class, 'update'])->name('pharmacies.update');
    Route::resource('drugs', Admin\DrugController::class)->except('show');
    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
});
