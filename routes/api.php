<?php

use App\Http\Controllers\API\OneshipController;
use App\Http\Controllers\API\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('oneship', OneshipController::class);
Route::prefix('shipments')->group(function () {
    Route::get('/', [ShipmentController::class, 'index']);
    Route::get('/{id}', [ShipmentController::class, 'show']);
    Route::post('/', [ShipmentController::class, 'store']);
    Route::put('/{type}/{id}', [ShipmentController::class, 'update']);
});
// Route::middleware(['auth:sanctum'])->group(function () {
// });