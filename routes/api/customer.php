<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('', [CustomerController::class, 'index'])->name('index');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
    Route::post('', [CustomerController::class, 'store'])->name('store');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
});
