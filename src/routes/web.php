<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController; // ← これが絶対に必要です！
use App\Http\Controllers\NotificationController; // ついでに次で使うこれも追加
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
        Route::get('/{id}', [RequestController::class, 'show'])->name('show');

        Route::patch('/{id}/approve', [RequestController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [RequestController::class, 'reject'])->name('reject');
    });
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [App\Http\Controllers\NotificationController::class, 'read'])->name('read');
    });
});

require __DIR__ . '/auth.php';
