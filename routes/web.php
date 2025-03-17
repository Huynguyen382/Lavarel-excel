<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OneshipController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ExportController;

Route::get('/oneship', [OneshipController::class, 'index'])->name('oneship.index');
Route::get('/vnpost', [OneshipController::class, 'vnpost'])->name('vnpost.index');
Route::post ('/importExcel',[ImportController::class, 'importExcel'])->name('import.excel');
Route::post('/export-excel', [ExportController::class, 'exportExcel'])->name('export.excel');
