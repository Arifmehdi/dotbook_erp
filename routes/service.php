<?php

use App\Http\Controllers\Service\ConsumedServiceController;

Route::prefix('consumed-services')->as('consumed_services')->group(function () {
    Route::get('/', [ConsumedServiceController::class, 'index'])->name('index');
});
