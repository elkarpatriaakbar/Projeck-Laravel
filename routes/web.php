<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PolylinesController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeojsonController;

// ============================================================
// Public Routes — bisa diakses tanpa login (Guest & Auth)
// ============================================================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/map', [PointsController::class, 'index'])->name('map');
Route::get('/table', [TableController::class, 'index'])->name('table');

// ============================================================
// Wisata — Route Public (Index & Show)
// PENTING: definisikan 'wisata/create' SEBELUM 'wisata/{wisata}'
// agar Laravel tidak menganggap "create" sebagai {wisata} ID.
// ============================================================
Route::get('/wisata/create', [WisataController::class, 'create'])
    ->middleware('auth')
    ->name('wisata.create');

Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');

// ============================================================
// Wisata — Route Auth (Create, Store, Edit, Update, Destroy)
// Semua route di sini WAJIB login terlebih dahulu.
// ============================================================
Route::middleware('auth')->group(function () {
    Route::post('/wisata',           [WisataController::class, 'store'])->name('wisata.store');
    Route::get('/wisata/{wisata}/edit', [WisataController::class, 'edit'])->name('wisata.edit');
    Route::patch('/wisata/{wisata}', [WisataController::class, 'update'])->name('wisata.update');
    Route::delete('/wisata/{wisata}', [WisataController::class, 'destroy'])->name('wisata.destroy');
});

// ============================================================
// Auth Routes — harus login
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Points, Polylines, Polygon (sistem lama)
    Route::resources([
        'points'    => PointsController::class,
        'polylines' => PolylinesController::class,
        'polygon'   => PolygonController::class,
    ]);

    // GeoJSON file management
    Route::post('/geojson', [GeojsonController::class, 'store'])->name('geojson.store');
    Route::delete('/geojson/{geojsonFile}', [GeojsonController::class, 'destroy'])->name('geojson.destroy');

});

// ============================================================
// Auth (login, register, dll)
// ============================================================
require __DIR__ . '/auth.php';
