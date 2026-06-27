<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\CajeroController;

Route::get('/cajero/dashboard', [CajeroController::class, 'index'])->name('cajero.dashboard');
Route::get('/cajero/api/search-product', [CajeroController::class, 'searchProduct']);
Route::post('/cajero/api/process-sale', [CajeroController::class, 'processSale']);
Route::get('/cajero/receipt/{id}', [CajeroController::class, 'downloadReceipt'])->name('cajero.receipt');
