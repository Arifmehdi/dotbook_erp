<?php

use App\Http\Controllers\BranchReceiveStockController;
use App\Http\Controllers\ReceiveTransferBranchToBranchController;
use App\Http\Controllers\TransferStockBranchToBranchController;
use App\Http\Controllers\TransferToBranchController;
use App\Http\Controllers\TransferToWarehouseController;
use App\Http\Controllers\WarehouseReceiveStockController;

Route::group(['prefix' => 'transfer/stocks', 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', [TransferToBranchController::class, 'index'])->name('transfer.stock.to.branch.index');
    Route::get('show/{transferId}', [TransferToBranchController::class, 'show'])->name('transfer.stock.to.branch.show');
    Route::get('transfer/products/{transferId}', [TransferToBranchController::class, 'transferProduct']);
    Route::get('all/transfer/', [TransferToBranchController::class, 'allTransfer'])->name('transfer.stock.to.branch.all.transfer');
    Route::get('create', [TransferToBranchController::class, 'create'])->name('transfer.stock.to.branch.create');
    Route::post('store', [TransferToBranchController::class, 'store'])->name('transfer.stock.to.branch.store');
    Route::get('get/all/warehouse', [TransferToBranchController::class, 'getAllWarehouse'])->name('transfer.stock.to.branch.all.warehouse');
    Route::get('edit/{transferId}', [TransferToBranchController::class, 'edit'])->name('transfer.stock.to.branch.edit');
    Route::get('get/editable/transfer/{transferId}', [TransferToBranchController::class, 'editableTransfer'])->name('transfer.stock.to.branch.editable.transfer');
    Route::post('update/{transferId}', [TransferToBranchController::class, 'update'])->name('transfer.stock.to.branch.update');
    Route::delete('delete/{transferId}', [TransferToBranchController::class, 'delete'])->name('transfer.stock.to.branch.delete');
    Route::get('search/product/{product_code}/{warehouse_id}', [TransferToBranchController::class, 'productSearch']);
    Route::get('check/warehouse/variant/qty/{product_id}/{variant_id}/{warehouse_id}', [TransferToBranchController::class, 'checkWarehouseProductVariant']);
    Route::get('check/warehouse/qty/{product_id}/{warehouse_id}', [TransferToBranchController::class, 'checkWarehouseSingleProduct']);

    // Receive stock from warehouse **route group**
    Route::group(['prefix' => 'receive'], function () {
        Route::get('/', [WarehouseReceiveStockController::class, 'index'])->name('transfer.stocks.to.branch.receive.stock.index');
        Route::get('show/{sendStockId}', [WarehouseReceiveStockController::class, 'show'])->name('transfer.stocks.to.branch.receive.stock.show');
        Route::get('process/{sendStockId}', [WarehouseReceiveStockController::class, 'receiveProcessView'])->name('transfer.stocks.to.branch.receive.stock.process.view');
        Route::get('receivable/stock/{sendStockId}', [WarehouseReceiveStockController::class, 'receivableStock'])->name('transfer.stocks.to.branch.receive.stock.get.receivable.stock');
        Route::post('process/save/{sendStockId}', [WarehouseReceiveStockController::class, 'receiveProcessSave'])->name('transfer.stocks.to.branch.receive.stock.process.save');
    });

    //Transfer Stock Branch To Branch
    Route::group(['prefix' => 'branch/to/branch'], function () {
        Route::get('transfer/list', [TransferStockBranchToBranchController::class, 'transferList'])->name('transfer.stock.branch.to.branch.transfer.list');

        Route::get('create', [TransferStockBranchToBranchController::class, 'create'])->name('transfer.stock.branch.to.branch.create');

        Route::get('show/{transferId}', [TransferStockBranchToBranchController::class, 'show'])->name('transfer.stock.branch.to.branch.show');

        Route::post('store', [TransferStockBranchToBranchController::class, 'store'])->name('transfer.stock.branch.to.branch.store');

        Route::get('edit/{transferId}', [TransferStockBranchToBranchController::class, 'edit'])->name('transfer.stock.branch.to.branch.edit');

        Route::post('update/{transferId}', [TransferStockBranchToBranchController::class, 'update'])->name('transfer.stock.branch.to.branch.update');

        Route::delete('delete/{transferId}', [TransferStockBranchToBranchController::class, 'delete'])->name('transfer.stock.branch.to.branch.delete');

        Route::get('search/product/{product_code}/{warehouse_id}', [TransferStockBranchToBranchController::class, 'searchProduct']);

        Route::get('check/single/product/stock/{product_id}/{warehouse_id}', [TransferStockBranchToBranchController::class, 'checkSingleProductStock']);

        Route::get('check/variant/product/stock/{product_id}/{variant_id}/{warehouse_id}', [TransferStockBranchToBranchController::class, 'checkVariantProductStock']);

        Route::group(['prefix' => 'receive'], function () {

            Route::get('receivable/list', [ReceiveTransferBranchToBranchController::class, 'receivableList'])->name('transfer.stock.branch.to.branch.receivable.list');
            Route::get('show/{transferId}', [ReceiveTransferBranchToBranchController::class, 'show'])->name('transfer.stock.branch.to.branch.receivable.show');
            Route::get('process/to/receive/{transferId}', [ReceiveTransferBranchToBranchController::class, 'processToReceive'])->name('transfer.stock.branch.to.branch.ProcessToReceive');
            Route::post('process/to/receive/save/{transferId}', [ReceiveTransferBranchToBranchController::class, 'processToReceiveSave'])->name('transfer.stock.branch.to.branch.ProcessToReceive.save');
        });
    });
});

//Transfer stock to warehouse all route
Route::group(['prefix' => 'transfer/stocks/to/warehouse'], function () {
    Route::get('/', [TransferToWarehouseController::class, 'index'])->name('transfer.stock.to.warehouse.index');
    Route::get('show/{id}', [TransferToWarehouseController::class, 'show'])->name('transfer.stock.to.warehouse.show');
    Route::get('create', [TransferToWarehouseController::class, 'create'])->name('transfer.stock.to.warehouse.create');
    Route::post('store', [TransferToWarehouseController::class, 'store'])->name('transfer.stock.to.warehouse.store');
    Route::get('get/all/warehouse', [TransferToWarehouseController::class, 'getAllWarehouse'])->name('transfer.stock.to.warehouse.all.warehouse');
    Route::get('edit/{transferId}', [TransferToWarehouseController::class, 'edit'])->name('transfer.stock.to.warehouse.edit');
    Route::get('get/editable/transfer/{transferId}', [TransferToWarehouseController::class, 'editableTransfer'])->name('transfer.stock.to.warehouse.editable.transfer');
    Route::post('update/{transferId}', [TransferToWarehouseController::class, 'update'])->name('transfer.stock.to.warehouse.update');
    Route::delete('delete/{transferId}', [TransferToWarehouseController::class, 'delete'])->name('transfer.stock.to.warehouse.delete');
    Route::get('search/product/{product_code}', [TransferToWarehouseController::class, 'productSearch']);
    Route::get('check/single/product/stock/{product_id}', [TransferToWarehouseController::class, 'checkBranchSingleProduct']);
    Route::get('check/branch/variant/qty/{product_id}/{variant_id}', [TransferToWarehouseController::class, 'checkBranchProductVariant']);

    // Receive stock from branch **route group**
    Route::group(['prefix' => 'receive'], function () {
        Route::get('/', [BranchReceiveStockController::class, 'index'])->name('transfer.stocks.to.warehouse.receive.stock.index');
        Route::get('show/{sendStockId}', [BranchReceiveStockController::class, 'show'])->name('transfer.stocks.to.warehouse.receive.stock.show');
        Route::get('all/send/stocks', [BranchReceiveStockController::class, 'allSendStock'])->name('transfer.stocks.to.warehouse.receive.stock.all.send.stocks');
        Route::get('process/{sendStockId}', [BranchReceiveStockController::class, 'receiveProcessView'])->name('transfer.stocks.to.warehouse.receive.stock.process.view');
        Route::get('receivable/stock/{sendStockId}', [BranchReceiveStockController::class, 'receivableStock'])->name('transfer.stocks.to.warehouse.receive.stock.get.receivable.stock');
        Route::post('process/save/{sendStockId}', [BranchReceiveStockController::class, 'receiveProcessSave'])->name('transfer.stocks.to.warehouse.receive.stock.process.save');
        Route::post('mail/{sendStockId}', [BranchReceiveStockController::class, 'receiveMail'])->name('transfer.stocks.to.warehouse.receive.stock.mail');
    });
});
