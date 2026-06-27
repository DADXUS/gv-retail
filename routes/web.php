<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

use App\Http\Controllers\CajeroController;
use App\Http\Controllers\AdminController;

Route::get('/cajero/dashboard', [CajeroController::class, 'index'])->name('cajero.dashboard');
Route::get('/cajero/api/search-product', [CajeroController::class, 'searchProduct']);
Route::get('/cajero/api/search-client', [CajeroController::class, 'searchClient']);
Route::post('/cajero/api/process-sale', [CajeroController::class, 'processSale']);
Route::get('/cajero/receipt/{id}', [CajeroController::class, 'downloadReceipt'])->name('cajero.receipt');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('productos', AdminController::class)->except(['show']);
    Route::get('/reportes/ventas', [AdminController::class, 'reportesVentas'])->name('reportes.ventas');
    Route::get('/reportes/clientes', [AdminController::class, 'reportesClientes'])->name('reportes.clientes');
});
