<?php

use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'settings/app-setup/modules', 'as' => 'modules.'], function () {
    Route::get('/control', [ModuleController::class, 'control'])->name('control');
});
Route::group(['prefix' => 'modules', 'as' => 'modules.'], function () {
    Route::get('/purchases', [ModuleController::class, 'purchases'])->name('purchases');
});
