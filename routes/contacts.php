<?php

use App\Http\Controllers\Report\CustomerReportController;
use App\Http\Controllers\Report\SupplierReportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/'], function () {
    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'suppliers'], function () {
            Route::get('/', [SupplierReportController::class, 'index'])->name('reports.supplier.index');
            Route::get('print', [SupplierReportController::class, 'print'])->name('reports.supplier.print');
        });

        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', [CustomerReportController::class, 'index'])->name('reports.customer.index');
            Route::get('print', [CustomerReportController::class, 'print'])->name('reports.customer.print');
        });
    });
});
