<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OneshipController;

Route::get('/oneship', [OneshipController::class, 'index'])->name('oneship.index');
Route::post('oneship/import', [OneshipController::class, 'importExcel'])->name('import.excel');
Route::post('/export', [OneshipController::class, 'exportExcel'])->name('export.excel');

