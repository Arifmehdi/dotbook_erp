<?php

use App\Http\Controllers\Report\StockAdjustmentReportController;
use App\Http\Controllers\StockAdjustmentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'stock/adjustments'], function () {

    Route::get('/', [StockAdjustmentController::class, 'index'])->name('stock.adjustments.index');
    Route::get('show/{adjustmentId}', [StockAdjustmentController::class, 'show'])->name('stock.adjustments.show');
    Route::get('create', [StockAdjustmentController::class, 'create'])->name('stock.adjustments.create');
    Route::get('create/from/warehouse', [StockAdjustmentController::class, 'createFromWarehouse'])->name('stock.adjustments.create.from.warehouse');
    Route::post('store', [StockAdjustmentController::class, 'store'])->name('stock.adjustments.store');
    Route::get('search/product/in/warehouse/{keyword}/{warehouse_id}', [StockAdjustmentController::class, 'searchProductInWarehouse']);
    Route::get('search/product/{keyword}', [StockAdjustmentController::class, 'searchProduct']);

    Route::get('check/single/product/stock/{product_id}', [StockAdjustmentController::class, 'checkSingleProductStock']);
    Route::get('check/single/product/stock/in/warehouse/{product_id}/{warehouse_id}', [StockAdjustmentController::class, 'checkSingleProductStockInWarehouse']);

    Route::get('check/variant/product/stock/{product_id}/{variant_id}', [StockAdjustmentController::class, 'checkVariantProductStock']);
    Route::get('check/variant/product/stock/in/warehouse/{product_id}/{variant_id}/{warehouse_id}', [StockAdjustmentController::class, 'checkVariantProductStockInWarehouse']);
    Route::delete('delete/{adjustmentId}', [StockAdjustmentController::class, 'delete'])->name('stock.adjustments.delete');

    Route::group(['prefix' => 'reports/stock/adjustments'], function () {

        Route::get('/', [StockAdjustmentReportController::class, 'index'])->name('reports.stock.adjustments.index');
        Route::get('all/adjustments', [StockAdjustmentReportController::class, 'allAdjustments'])->name('reports.stock.adjustments.all');
        Route::get('print', [StockAdjustmentReportController::class, 'print'])->name('reports.stock.adjustments.print');
    });
});
