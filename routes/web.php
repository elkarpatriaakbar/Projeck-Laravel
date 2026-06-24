<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PolylinesController;

// ============================================================
// Public Routes — bisa diakses tanpa login
// ============================================================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/map', [PointsController::class, 'index'])->name('map');
Route::get('/table', [TableController::class, 'index'])->name('table');

// ============================================================
// Auth Routes — harus login
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Points, Polylines, Polygon
    Route::resources([
        'points'    => PointsController::class,
        'polylines' => PolylinesController::class,
        'polygon'   => PolygonController::class,
    ]);

});

// ============================================================
// Auth (login, register, dll)
// ============================================================
require __DIR__.'/auth.php';
