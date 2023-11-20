<?php

use Illuminate\Support\Facades\Route;
use Modules\Documentation\Http\Controllers\DocumentationController;
use Modules\Documentation\Http\Controllers\ScaleDocumentationController;

Route::prefix('documentation')->as('documentation.')->group(function () {
    Route::controller(DocumentationController::class)->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::get('/developer-change-log', 'returnDeveloperChangeLog')->name('developer_change_log');
    });

    Route::controller(ScaleDocumentationController::class)->prefix('scale')->as('scale.')->group(function () {
        Route::get('/index', 'index')->name('index');
    });
});
