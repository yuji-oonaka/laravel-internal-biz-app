<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController; // 追加
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Breeze標準のダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // プロフィール管理
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 申請管理（ここを追加）
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/create', [RequestController::class, 'create'])->name('create');
        Route::post('/', [RequestController::class, 'store'])->name('store');
    });
});

require __DIR__ . '/auth.php';
