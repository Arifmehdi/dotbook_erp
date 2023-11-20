<?php

use App\Http\Controllers\ProcurementDashboardController;
use App\Http\Controllers\PurchaseByScaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderReceiveController;
use App\Http\Controllers\PurchasePaymentController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReceiveStockController;
use App\Http\Controllers\ReceiveStockReportController;
use App\Http\Controllers\Report\ProductPurchaseReportController;
use App\Http\Controllers\Report\PurchaseReportController;
use App\Http\Controllers\Report\PurchaseReturnItemReportController;
use App\Http\Controllers\Report\PurchaseReturnReportController;
use App\Http\Controllers\Report\RequestedProductReportController;
use App\Http\Controllers\Report\SalePurchaseReportController;
use App\Http\Controllers\Report\StockIssueItemReportController;
use App\Http\Controllers\Report\StockIssueReportController;
use App\Http\Controllers\Report\SupplierReportController;
use App\Http\Controllers\Report\WeightedProductReportController;
use App\Http\Controllers\RequisitionDepartmentController;
use App\Http\Controllers\Requisitions\RequestersController;
use App\Http\Controllers\StockIssueController;
use App\Http\Controllers\StockIssueEventsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'procurement'], function () {

    Route::controller(PurchaseController::class)->prefix('purchases')->group(function () {

        Route::get('index/{supplierAccountId?}', 'index')->name('purchases.index');
        Route::get('product/list', 'purchaseProductList')->name('purchases.product.list');
        Route::get('show/{purchaseId}', 'show')->name('purchases.show');
        Route::get('create', 'create')->name('purchases.create');
        Route::get('create/order', 'createOrder')->name('purchases.create.order');
        Route::post('store', 'store')->name('purchases.store');
        Route::get('edit/{purchaseId}', 'edit')->name('purchases.edit');
        Route::post('update/{purchaseId}', 'update')->name('purchases.update');
        Route::get('get/all/unit', 'getAllUnit')->name('purchases.get.all.unites');
        Route::delete('delete/{purchaseId}', 'delete')->name('purchase.delete');
        Route::get('settings', 'settings')->name('purchase.settings');
        Route::post('settings/store', 'settingsStore')->name('purchase.settings.store');
    });

    Route::controller(ReceiveStockController::class)->prefix('receive/stocks')->group(function () {

        Route::get('/', 'index')->name('purchases.receive.stocks.index');
        Route::get('show/{id}', 'show')->name('purchases.receive.stocks.show');
        Route::get('create', 'create')->name('purchases.receive.stocks.create');
        Route::post('store', 'store')->name('purchases.receive.stocks.store');
        Route::get('edit/{id}', 'edit')->name('purchases.receive.stocks.edit');
        Route::post('update/{id}', 'update')->name('purchases.receive.stocks.update');
        Route::delete('delete/{id}', 'delete')->name('purchases.receive.stocks.delete');
    });

    Route::controller(PurchaseRequisitionController::class)->prefix('requisitions')->group(function () {

        Route::controller(RequisitionDepartmentController::class)->prefix('departments')->group(function () {

            Route::get('/', 'index')->name('requisitions.departments.index');
            Route::get('create', 'create')->name('requisitions.departments.create');
            Route::post('store', 'store')->name('requisitions.departments.store');
            Route::get('edit/{id}', 'edit')->name('requisitions.departments.edit');
            Route::post('update/{id}', 'update')->name('requisitions.departments.update');
            Route::delete('delete/{id}', 'delete')->name('requisitions.departments.delete');
            Route::get('print', 'print')->name('requisitions.departments.print');
        });

        Route::controller(RequestersController::class)->prefix('requesters')->group(function () {

            Route::get('/', 'index')->name('requesters.index');
            Route::get('create', 'create')->name('requesters.create');
            Route::post('/store', 'store')->name('requesters.store');
            Route::get('/edit/{id}', 'edit')->name('requesters.edit');
            Route::post('/update/{id}', 'update')->name('requesters.update');
            Route::delete('/delete/{id}', 'destroy')->name('requesters.destroy');
        });

        Route::get('/', 'index')->name('purchases.requisition.index');
        Route::get('show/{requisitionId}', 'show')->name('purchases.requisition.show');
        Route::get('create', 'create')->name('purchases.requisition.create');
        Route::post('store', 'store')->name('purchases.requisition.store');
        Route::get('edit/{requisitionId}', 'edit')->name('purchases.requisition.edit');
        Route::post('update/{requisitionId}', 'update')->name('purchases.requisition.update');
        Route::delete('delete/{requisitionId}', 'delete')->name('purchases.requisition.delete');
        Route::get('requisition/approval/{requisitionId}', 'requisitionApproval')->name('purchases.requisition.approval');
        Route::post('requisition/approval/update/{requisitionId}', 'requisitionApprovalUpdate')->name('purchases.requisition.approval.update');
    });

    Route::controller(PurchaseByScaleController::class)->prefix('purchase-by-scale')->group(function () {

        Route::get('/', 'index')->name('purchases.by.scale.index');
        Route::get('show/{purchaseByScaleId}', 'show')->name('purchases.by.scale.show');
        Route::get('create', 'create')->name('purchases.by.scale.create');
        Route::post('save/weight', 'saveWeight')->name('purchases.by.scale.save.weight');
        Route::post('completed', 'completed')->name('purchases.by.scale.completed');
        Route::delete('delete/{purchaseByScaleId}', 'delete')->name('purchases.by.scale.delete');
        Route::get('get/weight/details', 'getWeightDetails')->name('purchases.by.scale.get.weight.details');
        Route::post('save/weight/details/{purchaseByScaleId}', 'saveWeightDetails')->name('purchases.by.scale.save.weight.details');
        Route::get('purchase/by/scale/weights/by/items/{purchaseScaleId}', 'PurchaseByScaleWeightsByItems')->name('purchase.by.scale.weights.by.items');
        Route::post('purchase/by/scale/vehicle/done/{purchaseByScaleId}', 'purchaseByScaleVehicleDone')->name('purchase.by.scale.vehicle.done');
        Route::get('print/weight/challan/{purchaseByScaleId}', 'printWeightChallan')->name('purchase.by.scale.print.weight.challan');
        Route::get('print/weight/{printType?}/{purchaseByScaleId}', 'printWeight')->name('purchase.by.scale.print.weight');
    });

    Route::controller(PurchaseOrderController::class)->prefix('purchase-orders')->group(function () {

        Route::get('index/{supplierAccountId?}', 'index')->name('purchases.order.index');
        Route::get('show/{orderId}', 'show')->name('purchases.show.order');
        Route::get('create', 'create')->name('purchases.order.create');
        Route::post('store', 'store')->name('purchase.order.store');
        Route::get('edit/{orderId}', 'edit')->name('purchases.order.edit');
        Route::post('update/{orderId}', 'update')->name('purchases.order.update');
        Route::delete('delete/{orderId}', 'delete')->name('purchases.order.delete');
        Route::get('order/print/supplier/copy/{orderId}', 'printSupplierCopy')->name('purchases.order.supplier.copy.print');

        Route::controller(PurchaseOrderReceiveController::class)->prefix('/')->group(function () {

            Route::get('receive/{purchaseId}', 'processReceive')->name('purchases.po.receive.process');
            Route::post('receive/store/{purchaseId}', 'processReceiveStore')->name('purchases.po.receive.process.store');
        });
    });

    Route::controller(StockIssueController::class)->prefix('stock/issues')->group(function () {

        Route::get('/', 'index')->name('stock.issue.index');
        Route::get('create', 'create')->name('stock.issue.create');
        Route::post('store', 'store')->name('stock.issue.store');
        Route::get('show/{stockIssueId}', 'show')->name('stock.issue.show');
        Route::delete('delete/{stockIssueId}', 'delete')->name('stock.issue.delete');
        Route::get('edit/{stockIssueId}', 'edit')->name('stock.issue.edit');
        Route::post('update/{stockIssueId}', 'update')->name('stock.issue.update');

        Route::controller(StockIssueEventsController::class)->prefix('events')->group(function () {

            Route::get('/', 'index')->name('stock.issues.events.index');
            Route::get('create', 'create')->name('stock.issues.events.create');
            Route::post('store', 'store')->name('stock.issues.events.store');
            Route::get('edit/{id}', 'edit')->name('stock.issue.events.edit');
            Route::delete('delete/{id}', 'delete')->name('stock.issue.events.delete');
            Route::post('update/{id}', 'update')->name('stock.issue.events.update');
        });
    });

    // Purchase Return route
    Route::controller(PurchaseReturnController::class)->prefix('purchase-returns')->group(function () {

        Route::get('/', 'index')->name('purchases.returns.index');
        Route::get('create', 'create')->name('purchases.returns.create');
        Route::post('store', 'store')->name('purchases.returns.store');
        Route::get('show/{id}', 'show')->name('purchases.returns.show');
        Route::get('edit/{id}', 'edit')->name('purchases.returns.edit');
        Route::post('update/{id}', 'update')->name('purchases.returns.update');
        Route::delete('delete/{id}', 'delete')->name('purchases.returns.delete');
    });

    // Purchase Payment
    Route::controller(PurchasePaymentController::class)->prefix('purchase-payments')->group(function () {

        Route::get('create/{purchaseId}', 'create')->name('purchases.payments.create');
        Route::post('store/{purchaseId}', 'store')->name('purchases.payments.store');
    });

    Route::controller(ProcurementDashboardController::class)->prefix('dashboard')->group(function () {

        Route::get('/', 'index')->name('purchases.dashboard.index');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(SupplierReportController::class)->prefix('suppliers')->group(function () {

            Route::get('/', 'index')->name('reports.supplier.index');
            Route::get('print', 'print')->name('reports.supplier.print');
        });

        Route::controller(ReceiveStockReportController::class)->prefix('receive/stocks')->group(function () {

            Route::get('/', 'index')->name('reports.receive.stocks.index');
            Route::get('print', 'print')->name('reports.receive.stocks.print');
        });

        Route::controller(PurchaseReportController::class)->prefix('purchase/report')->group(function () {

            Route::get('/', 'index')->name('reports.purchases.report.index');
            Route::get('print', 'print')->name('reports.purchases.report.print');
            Route::get('print/summary', 'printSummary')->name('reports.purchases.report.print.summary');
        });

        Route::controller(RequestedProductReportController::class)->prefix('requested/products')->group(function () {

            Route::get('/', 'index')->name('reports.requested.products.index');
            Route::get('print', 'print')->name('reports.requested.products.print');
        });

        Route::controller(WeightedProductReportController::class)->prefix('weighted/products')->group(function () {

            Route::get('/', 'index')->name('reports.weighted.products.index');
            Route::get('print', 'print')->name('reports.weighted.products.print');
        });

        Route::controller(ProductPurchaseReportController::class)->prefix('product/purchases')->group(function () {

            Route::get('/', 'index')->name('reports.product.purchases.index');
            Route::get('print', 'print')->name('reports.product.purchases.print');
        });

        Route::controller(SalePurchaseReportController::class)->prefix('sales/purchase')->group(function () {

            Route::get('/', 'index')->name('reports.sales.purchases.index');
            Route::get('sale/purchase/amounts', 'salePurchaseAmounts')->name('reports.profit.sales.purchases.amounts');
            Route::get('filter/sale/purchase/amounts', 'filterSalePurchaseAmounts')->name('reports.profit.sales.filter.purchases.amounts');
            Route::get('print', 'printSalePurchase')->name('reports.sales.purchases.print');
        });

        Route::controller(StockIssueReportController::class)->prefix('stock/issue/report')->group(function () {

            Route::get('/', 'index')->name('reports.stock.issue.report.index');
            Route::get('print', 'print')->name('reports.stock.issue.report.print');
        });

        Route::controller(StockIssueItemReportController::class)->prefix('stock/issued/items')->group(function () {

            Route::get('/', 'index')->name('reports.stock.issued.items.report.index');
            Route::get('print', 'print')->name('reports.stock.issued.items.report.print');
        });

        Route::controller(PurchaseReturnReportController::class)->prefix('purchase/return/report')->group(function () {

            Route::get('/', 'index')->name('reports.purchase.return.report.index');
            Route::get('print', 'print')->name('reports.purchase.return.report.print');
        });

        Route::controller(PurchaseReturnItemReportController::class)->prefix('purchase/returned/items/report')->group(function () {

            Route::get('/', 'index')->name('reports.purchase.returned.items.report.index');
            Route::get('print', 'print')->name('reports.purchase.returned.items.report.print');
        });
    });
});
