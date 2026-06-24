<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\GeojsonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/points', [ApiController::class, 'points']) ->name('api.points');
Route::get('/point/{id}', [ApiController::class, 'point']) ->name('api.point');
Route::get('/polylines', [ApiController::class, 'polylines']) ->name('api.polylines');
Route::get('/polyline/{id}', [ApiController::class, 'polyline']) ->name('api.polyline');
Route::get('/polygon', [ApiController::class, 'polygon']) ->name('api.polygon');
Route::get('/polygonn/{id}', [ApiController::class, 'polygonn']) ->name('api.polygonn');

// GeoJSON uploaded files (publik — dipakai peta)
Route::get('/geojson', [GeojsonController::class, 'index'])->name('api.geojson');
Route::get('/geojson/{geojsonFile}', [GeojsonController::class, 'serve'])->name('api.geojson.serve');
