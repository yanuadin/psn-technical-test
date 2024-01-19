<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;

Route::prefix('address')->name('address.')->group(function () {
    Route::get('', [AddressController::class, 'index'])->name('index');
    Route::get('/{address}', [AddressController::class, 'show'])->name('show');
    Route::post('', [AddressController::class, 'store'])->name('store');
    Route::put('/{address}', [AddressController::class, 'update'])->name('update');
    Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
});
