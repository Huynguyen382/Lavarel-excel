<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OneshipController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware(['web'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::middleware(['auth'])->group(function () {
        Route::get('/oneship', [OneshipController::class, 'index'])->name('oneship.index');
        Route::get('/vnpost', [OneshipController::class, 'vnpost'])->name('vnpost.index');

        Route::post('/importExcel', [ImportController::class, 'importExcel'])->name('import.excel');
        Route::post('/export-excel', [ExportController::class, 'exportExcel'])->name('export.excel');
        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate(); 
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Bạn đã đăng xuất thành công!');
        })->name('logout');
    });
});

