<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Page (Root)
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');


// =========================
// Authentication Routes
// =========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// Protected Routes (Using simple session-based middleware)
// =========================
Route::group(['middleware' => 'check.session'], function () {

    //Home Routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/personal', [ProfileController::class, 'updatePersonal'])->name('profile.update.personal');
    Route::post('/profile/financial', [ProfileController::class, 'updateFinancial'])->name('profile.update.financial');
    Route::post('/profile/categories', [ProfileController::class, 'updateCategories'])->name('profile.update.categories');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

    // Expense Routes
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    
    // NEW: Receipt parsing endpoint
    Route::post('/expenses/parse-receipt', [ExpenseController::class, 'parseReceipt'])->name('expenses.parseReceipt');

    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::post('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');

    Route::post('/expenses/{expense}/delete', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});

// =========================
// Language Switcher
// =========================
Route::get('/locale/{lang}', function ($lang) {
    if (!in_array($lang, ['en', 'id'])) {
        $lang = 'en';
    }

    session(['locale' => $lang]);

    return redirect()->back();   // return to previous page
});