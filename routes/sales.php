<?php

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ManageSrController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RandomSaleReturnController;
use App\Http\Controllers\RecentPriceController;
use App\Http\Controllers\Report\CashRegisterReportController;
use App\Http\Controllers\Report\CustomerReportController;
use App\Http\Controllers\Report\DoReportController;
use App\Http\Controllers\Report\DoVsSalesReportController;
use App\Http\Controllers\Report\OrderedQuantityReportController;
use App\Http\Controllers\Report\SaleRepresentativeReportController;
use App\Http\Controllers\Report\SaleReturnReportController;
use App\Http\Controllers\Report\SalesOrderedItemReportController;
use App\Http\Controllers\Report\SalesOrderReportController;
use App\Http\Controllers\Report\SalesOrderReportUserWiseController;
use App\Http\Controllers\Report\SalesReportController;
use App\Http\Controllers\Report\SalesReturnItemReportController;
use App\Http\Controllers\Report\SoldItemReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\SaleReceiptController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SalesDashboardController;
use App\Http\Controllers\SaleSettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sales/app'], function () {

    Route::group(['prefix' => 'sales'], function () {

        Route::get('index/{customerAccountId?}/{userId?}', [SaleController::class, 'index'])->name('sales.index');
        Route::get('show/{saleId}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('print/{saleId}', [SaleController::class, 'print'])->name('sales.print');
        Route::get('create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('store', [SaleController::class, 'store'])->name('sales.store');
        Route::get('edit/{saleId}', [SaleController::class, 'edit'])->name('sales.edit');
        Route::post('update/{saleId}', [SaleController::class, 'update'])->name('sales.update');
        Route::delete('delete/{saleId}', [SaleController::class, 'delete'])->name('sales.delete');
        Route::post('change/status/{saleId}', [SaleController::class, 'changeStatus'])->name('sales.change.status');
        Route::get('get/recent/product/{product_id}', [SaleController::class, 'getRecentProduct']);
        Route::get('get/product/price/group', [SaleController::class, 'getProductPriceGroup'])->name('sales.product.price.groups');
        Route::get('notification/form/{saleId}', [SaleController::class, 'getNotificationForm'])->name('sales.notification.form');
        Route::get('print/sale/gate/pass/{saleId}', [SaleController::class, 'printSaleGatePass'])->name('sales.print.sales.gate.pass');
        Route::get('print/sale/weight/{saleId}', [SaleController::class, 'printSaleWeight'])->name('sales.print.sales.weight');

        //Sale discount routes
        Route::group(['prefix' => 'discounts'], function () {
            Route::get('/', [DiscountController::class, 'index'])->name('sales.discounts.index');
            Route::post('store', [DiscountController::class, 'store'])->name('sales.discounts.store');
            Route::get('edit/{discountId}', [DiscountController::class, 'edit'])->name('sales.discounts.edit');
            Route::post('update/{discountId}', [DiscountController::class, 'update'])->name('sales.discounts.update');
            Route::get('change/status/{discountId}', [DiscountController::class, 'changeStatus'])->name('sales.discounts.change.status');
            Route::delete('delete/{discountId}', [DiscountController::class, 'delete'])->name('sales.discounts.delete');
            Route::get('stat', [DiscountController::class, 'getStat'])->name('sales.discounts.stat');
        });
    });

    Route::group(['prefix' => 'quotations'], function () {
        Route::get('/', [QuotationController::class, 'index'])->name('sales.quotations');
        Route::get('show/{quotationId}', [QuotationController::class, 'show'])->name('sales.quotations.show');
        Route::get('create', [QuotationController::class, 'create'])->name('sales.quotations.create');
        Route::post('store', [QuotationController::class, 'store'])->name('sales.quotations.store');
        Route::get('edit/{quotationId}', [QuotationController::class, 'edit'])->name('sales.quotations.edit');
        Route::post('update/{quotationId}', [QuotationController::class, 'update'])->name('sales.quotations.update');
    });

    Route::controller(SaleOrderController::class)->prefix('orders')->group(function () {
        Route::get('index/{customerAccountId?}/{userId?}', 'index')->name('sales.order.index');
        Route::get('show/{orderId}', 'show')->name('sales.order.show');
        Route::get('to/sales', 'orderToSale')->name('sales.order.to.sale');
        Route::get('create', 'create')->name('sales.order.create');
        Route::post('store', 'store')->name('sales.order.store');
        Route::get('edit/{orderId}', 'edit')->name('sales.order.edit');
        Route::post('update/{orderId}', 'update')->name('sales.order.update');
        Route::get('do/approval/{saleId}', 'doApproval')->name('sales.order.do.approval');
        Route::post('do/approval/update/{saleId}', 'doApprovalUpdate')->name('sales.order.do.approval.update');
        Route::get('order/status/change/modal/{saleId}', 'orderStatusChangeModal')->name('sales.order.status.change.modal');
        Route::post('order/status/change/{saleId}', 'orderStatusChange')->name('sales.order.status.change');
    });

    Route::group(['prefix' => 'delivery/orders'], function () {
        Route::get('/', [DeliveryOrderController::class, 'index'])->name('sales.delivery.order.list');
        Route::get('show/{doId}', [DeliveryOrderController::class, 'show'])->name('sales.delivery.order.show');
        Route::get('to/final', [DeliveryOrderController::class, 'toFinal'])->name('sales.delivery.order.to.final');
        Route::get('create', [DeliveryOrderController::class, 'create'])->name('sales.delivery.order.create');
        Route::get('edit/{doId}', [DeliveryOrderController::class, 'edit'])->name('sales.delivery.order.edit');
        Route::post('update/{doId}', [DeliveryOrderController::class, 'update'])->name('sales.delivery.order.update');
        Route::post('to/final/confirm', [DeliveryOrderController::class, 'toFinalConfirm'])->name('sales.delivery.order.to.final.confirm');
        Route::get('print/invoice', [DeliveryOrderController::class, 'printInvoice'])->name('sales.delivery.print.invoice');
        Route::get('print/challan', [DeliveryOrderController::class, 'printChallan'])->name('sales.delivery.print.challan');
        Route::get('print/gate/pass', [DeliveryOrderController::class, 'printGatePass'])->name('sales.delivery.print.gate.pass');
        Route::post('save/car', [DeliveryOrderController::class, 'saveCar'])->name('sales.delivery.save.car');
        Route::get('get/weight/details', [DeliveryOrderController::class, 'getWeightDetails'])->name('sales.delivery.get.weight.details');
        Route::get('print/weight', [DeliveryOrderController::class, 'printWeight'])->name('sales.delivery.print.weight');
        Route::post('do/done/{weightId}', [DeliveryOrderController::class, 'doDone'])->name('sales.delivery.done');
        Route::get('edit/do/modal', [DeliveryOrderController::class, 'editDoModal'])->name('sales.edit.do.modal');
        Route::get('print/do', [DeliveryOrderController::class, 'printDo'])->name('sales.print.do');
        Route::get('print/bills/against/do', [DeliveryOrderController::class, 'printBillAgainstDo'])->name('sales.delivery.order.print.bills.against.do');
        Route::get('previous/invoice', [DeliveryOrderController::class, 'previousInvoice'])->name('sales.delivery.order.previous.invoice');
        Route::get('previous/weight', [DeliveryOrderController::class, 'previousWeight'])->name('sales.delivery.order.previous.weight');
    });

    Route::group(['prefix' => 'settings'], function () {

        Route::group(['prefix' => 'sale/settings'], function () {

            Route::get('index', [SaleSettingsController::class, 'index'])->name('sales.app.settings.sale.settings.index');
            Route::post('sale/settings/update', [SaleSettingsController::class, 'saleSettingsUpdate'])->name('sales.app.settings.sale.settings.update');
            Route::post('pos/settings/update', [SaleSettingsController::class, 'posSettingsUpdate'])->name('sales.app.settings.pos.settings.update');
        });
    });

    // Sale return route
    Route::group(['prefix' => 'returns'], function () {

        Route::get('/', [SaleReturnController::class, 'index'])->name('sales.returns.index');
        Route::get('show/{returnId}', [SaleReturnController::class, 'show'])->name('sales.returns.show');
        Route::delete('delete/{saleReturnId}', [SaleReturnController::class, 'delete'])->name('sales.returns.delete');

        Route::group(['prefix' => 'random'], function () {

            Route::get('create', [RandomSaleReturnController::class, 'create'])->name('sale.return.random.create');
            Route::post('store', [RandomSaleReturnController::class, 'store'])->name('sale.return.random.store');
            Route::get('edit/{returnId}', [RandomSaleReturnController::class, 'edit'])->name('sale.return.random.edit');
            Route::post('update/{returnId}', [RandomSaleReturnController::class, 'update'])->name('sale.return.random.update');
        });
    });

    // Purchase Payment
    Route::controller(SaleReceiptController::class)->prefix('sale-receipts')->group(function () {

        Route::get('create/{saleId}', 'create')->name('sales.receipts.create');
        Route::post('store/{saleId}', 'store')->name('sales.receipts.store');
    });

    //Pos cash register routes
    Route::group(['prefix' => 'pos/cash/register'], function () {
        Route::get('/', [CashRegisterController::class, 'create'])->name('sales.cash.register.create');
        Route::post('store', [CashRegisterController::class, 'store'])->name('sales.cash.register.store');
        Route::get('close/cash/register/modal/view', [CashRegisterController::class, 'closeCashRegisterModalView'])->name('sales.cash.register.close.modal.view');
        Route::get('cash/register/details', [CashRegisterController::class, 'cashRegisterDetails'])->name('sales.cash.register.details');
        Route::get('cash/register/details/for/report/{crId}', [CashRegisterController::class, 'cashRegisterDetailsForReport'])->name('sales.cash.register.details.for.report');
        Route::post('close', [CashRegisterController::class, 'close'])->name('sales.cash.register.close');
    });

    // Pos routes
    Route::group(['prefix' => 'pos'], function () {

        Route::get('/', [POSController::class, 'index'])->name('sales.pos.list');
        Route::get('create', [POSController::class, 'create'])->name('sales.pos.create');
        Route::get('show/{saleId}', [POSController::class, 'show'])->name('sales.pos.show');
        Route::get('product/list', [POSController::class, 'posProductList'])->name('sales.pos.product.list');
        Route::post('store', [POSController::class, 'store'])->name('sales.pos.store');
        Route::get('pick/hold/invoice', [POSController::class, 'pickHoldInvoice']);
        Route::get('edit/{saleId}', [POSController::class, 'edit'])->name('sales.pos.edit');
        Route::get('invoice/products/{saleId}', [POSController::class, 'invoiceProducts'])->name('sales.pos.invoice.products');
        Route::post('update', [POSController::class, 'update'])->name('sales.pos.update');
        Route::get('suspended/sale/list', [POSController::class, 'suspendedList'])->name('sales.pos.suspended.list');
        Route::get('branch/stock', [POSController::class, 'branchStock'])->name('sales.pos.branch.stock');
        Route::post('add/customer', [POSController::class, 'addCustomer'])->name('sales.pos.add.customer');
        Route::get('get/recent/product/{product_id}', [POSController::class, 'getRecentProduct']);
        Route::get('close/cash/registser/modal/view', [POSController::class, 'close']);
        Route::get('search/exchangeable/invoice', [POSController::class, 'searchExchangeableInv'])->name('sales.pos.serc.ex.inv');
        Route::post('prepare/exchange', [POSController::class, 'prepareExchange'])->name('sales.pos.prepare.exchange');
        Route::post('exchange/confirm', [POSController::class, 'exchangeConfirm'])->name('sales.pos.exchange.confirm');
    });

    //Sale discount routes
    Route::controller(ManageSrController::class)->prefix('sr')->group(function () {

        Route::get('/', 'index')->name('sales.sr.index');
        Route::get('create', 'create')->name('sales.sr.create');
        Route::get('manage/{id}', 'manage')->name('sales.sr.manage');
        Route::get('closing/balance/{id}', 'srClosingBalance')->name('sales.sr.closing.balance');
        Route::get('print/sr/orders/{id}', 'printSrOrders')->name('sales.sr.print.orders');
        Route::get('print/sr/sales/{id}', 'printSrSales')->name('sales.sr.print.sales');
    });

    //Sale discount routes
    Route::group(['prefix' => 'recent/prices'], function () {

        Route::get('index', [RecentPriceController::class, 'index'])->name('sales.recent.price.index');
        Route::get('for/create/page', [RecentPriceController::class, 'recentPriceForCreatePage'])->name('sales.recent.price.for.create.page');
        Route::get('create', [RecentPriceController::class, 'create'])->name('sales.recent.price.create');
        Route::post('store', [RecentPriceController::class, 'store'])->name('sales.recent.price.store');
        Route::delete('delete/{id}', [RecentPriceController::class, 'delete'])->name('sales.recent.price.delete');
        Route::get('today/prices', [RecentPriceController::class, 'todayPrices'])->name('sales.recent.price.today');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'sold/items'], function () {

            Route::get('/', [SoldItemReportController::class, 'index'])->name('reports.sold.items.report.index');
            Route::get('print', [SoldItemReportController::class, 'print'])->name('reports.sold.items.report.print');
        });

        Route::group(['prefix' => 'cash/registers'], function () {

            Route::get('/', [CashRegisterReportController::class, 'index'])->name('reports.cash.registers.index');
            Route::get('get', [CashRegisterReportController::class, 'getCashRegisterReport'])->name('reports.get.cash.registers');
            Route::get('details/{cashRegisterId}', [CashRegisterReportController::class, 'detailsCashRegister'])->name('reports.get.cash.register.details');
            Route::get('report/print', [CashRegisterReportController::class, 'reportPrint'])->name('reports.get.cash.register.report.print');
        });

        Route::group(['prefix' => 'sale/representative'], function () {

            Route::get('/', [SaleRepresentativeReportController::class, 'index'])->name('reports.sale.representive.index');
            Route::get('expenses', [SaleRepresentativeReportController::class, 'SaleRepresentiveExpenseReport'])->name('reports.sale.representive.expenses');
        });

        Route::group(['prefix' => 'sales/report'], function () {

            Route::get('/', [SalesReportController::class, 'index'])->name('reports.sales.report.index');
            Route::get('print', [SalesReportController::class, 'print'])->name('reports.sales.report.print');
            Route::get('print/summary', [SalesReportController::class, 'printSummary'])->name('reports.sales.report.print.summary');
        });

        Route::group(['prefix' => 'return/report'], function () {

            Route::get('/', [SaleReturnReportController::class, 'index'])->name('reports.sale.return.report.index');
            Route::get('print', [SaleReturnReportController::class, 'print'])->name('reports.sale.return.report.print');
        });

        Route::group(['prefix' => 'order/report'], function () {

            Route::get('/', [SalesOrderReportController::class, 'index'])->name('reports.sales.order.report.index');
            Route::get('print', [SalesOrderReportController::class, 'print'])->name('reports.sales.order.report.print');
            Route::get('print/item/wise', [SalesOrderReportController::class, 'printWithItem'])->name('reports.sales.order.report.print.with.item');
            Route::get('print/summary', [SalesOrderReportController::class, 'printSummary'])->name('reports.sales.order.report.print.summary');
        });

        Route::group(['prefix' => 'order/report/user/wise'], function () {

            Route::get('/', [SalesOrderReportUserWiseController::class, 'index'])->name('reports.sales.order.report.user.wise.index');
            Route::get('print', [SalesOrderReportUserWiseController::class, 'print'])->name('reports.sales.order.report.user.wise.print');
        });

        Route::group(['prefix' => 'do/report'], function () {

            Route::get('/', [DoReportController::class, 'index'])->name('reports.do.report.index');
            Route::get('print', [DoReportController::class, 'print'])->name('reports.reports.do.report.print');
        });

        Route::group(['prefix' => 'do/vs/sales/report'], function () {

            Route::get('/', [DoVsSalesReportController::class, 'index'])->name('reports.do.vs.sales.report.index');
            Route::get('print', [DoVsSalesReportController::class, 'print'])->name('reports.do.vs.sales.report.print');
        });

        Route::group(['prefix' => 'ordered/item/report'], function () {

            Route::get('/', [SalesOrderedItemReportController::class, 'index'])->name('reports.sales.ordered.items.report.index');
            Route::get('print', [SalesOrderedItemReportController::class, 'print'])->name('reports.sales.ordered.items.report.print');
        });

        Route::group(['prefix' => 'ordered/item/quantity/report'], function () {

            Route::get('/', [OrderedQuantityReportController::class, 'index'])->name('reports.sales.ordered.item.qty.report.index');
            Route::get('print', [OrderedQuantityReportController::class, 'print'])->name('reports.sales.ordered.item.qty.report.print');
        });

        Route::group(['prefix' => 'sales/return/item/report'], function () {

            Route::get('/', [SalesReturnItemReportController::class, 'index'])->name('reports.sales.returned.items.report.index');
            Route::get('print', [SalesReturnItemReportController::class, 'print'])->name('reports.sales.returned.items.report.print');
        });

        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', [CustomerReportController::class, 'index'])->name('reports.customer.index');
            Route::get('print', [CustomerReportController::class, 'print'])->name('reports.customer.print');
        });
    });

    //Sales dashboard
    Route::group(['prefix' => 'dashboard', 'as' => 'sales.'], function () {
        Route::get('/', [SalesDashboardController::class, 'index'])->name('dashboard.index');
    });
});
