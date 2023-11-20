<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CommonAjaxCallController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralProductSearchController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\PosShortMenuController;
use App\Http\Controllers\ShortcutBookmarkController;
use App\Http\Controllers\ShortMenuController;
use Illuminate\Support\Facades\Route;

if (config('app.debug')) {
    include_once __DIR__.'/dev_routes.php';
}

Route::post('change-current-password', [ResetPasswordController::class, 'resetCurrentPassword'])->name('password.updateCurrent');
Route::get('change/lang/{lang}', [DashboardController::class, 'changeLang'])->name('change.lang');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.dashboard');
Route::get('dashboard/card/amount', [DashboardController::class, 'cardData'])->name('dashboard.card.data');
Route::get('dashboard/stock/alert', [DashboardController::class, 'stockAlert'])->name('dashboard.stock.alert');
Route::get('dashboard/sale/order', [DashboardController::class, 'saleOrder'])->name('dashboard.sale.order');
Route::get('dashboard/sale/due', [DashboardController::class, 'saleDue'])->name('dashboard.sale.due');
Route::get('dashboard/purchase/due', [DashboardController::class, 'purchaseDue'])->name('dashboard.purchase.due');
Route::get('dashboard/today/summery', [DashboardController::class, 'todaySummery'])->name('dashboard.today.summery');

Route::group(['prefix' => 'common/ajax/call'], function () {

    Route::get('category/subcategories/{categoryId}', [CommonAjaxCallController::class, 'categorySubcategories']);
    Route::get('only/search/product/for/reports/{product_name}', [CommonAjaxCallController::class, 'onlySearchProductForReports']);
    Route::get('recent/sales/{create_by}', [CommonAjaxCallController::class, 'recentSale']);
    Route::get('recent/quotations/{create_by}', [CommonAjaxCallController::class, 'recentQuotations']);
    Route::get('recent/drafts/{create_by}', [CommonAjaxCallController::class, 'recentDrafts']);
    Route::get('search/sales/order_ids/{key_word}', [CommonAjaxCallController::class, 'searchSalesOrderIds'])->name('common.ajax.call.search.sales.order.ids');
    Route::get('search/sales/do_ids/{key_word}', [CommonAjaxCallController::class, 'searchSaleDoIds'])->name('common.ajax.call.search.sales.do.ids');
    Route::get('get/ordered/products/{saleId}', [CommonAjaxCallController::class, 'getOrderedProducts'])->name('common.ajax.call.get.ordered.products');
    Route::get('get/do/products/{saleId}/{weightId?}', [CommonAjaxCallController::class, 'getDoProducts'])->name('common.ajax.call.get.do.products');
    Route::get('search/requisitions/{key_word}', [CommonAjaxCallController::class, 'searchRequisitions'])->name('common.ajax.call.search.requisitions');
    Route::get('get/requisition/products/for/purchase/{requisitionId}', [CommonAjaxCallController::class, 'getRequisitionProductsForPurchase'])->name('common.ajax.call.get.requisition.products.for.purchase');
    Route::get('get/requisition/products/for/receive/stock/{requisitionId}', [CommonAjaxCallController::class, 'getRequisitionProductsForReceiveStock'])->name('common.ajax.call.get.requisition.products.for.receive.stock');
    Route::get('count/sales/quotations/orders/do', [CommonAjaxCallController::class, 'countSalesQuotationsOrdersDo'])->name('common.ajax.call.count.sales.quotations.orders.do');
    Route::get('get/do/list', [CommonAjaxCallController::class, 'getDoList'])->name('common.ajax.call.do.list');
    Route::get('invoice/search/list/{invoiceId}', [CommonAjaxCallController::class, 'invoiceSearchList'])->name('common.ajax.call.invoice.search.list');
    Route::get('get/sale/products/{saleId}', [CommonAjaxCallController::class, 'getSaleProducts'])->name('common.ajax.call.get.sale.products');

    Route::get('search/receive/stocks/{rs_voucher_no}', [CommonAjaxCallController::class, 'searchReceiveStocks'])->name('common.ajax.call.search.receive.stocks');
    Route::get('get/receive/stock/products/{receiveStockId}', [CommonAjaxCallController::class, 'getReceiveStockProducts'])->name('common.ajax.call.get.receive.stock.products');

    Route::get('add/quick/product/modal', [CommonAjaxCallController::class, 'addQuickProductModal'])->name('common.ajax.call.add.quick.product.modal');
    Route::get('get/lc/{lc_id}', [CommonAjaxCallController::class, 'getLc'])->name('common.ajax.call.get.lc');
    Route::get('category/items/{categoryId}', [CommonAjaxCallController::class, 'categoryItems'])->name('common.ajax.call.category.items');
    Route::get('subcategory/items/{subcategoryId}', [CommonAjaxCallController::class, 'subcategoryItems'])->name('common.ajax.call.subcategory.items');
    Route::get('get/last/id/{table}/{placeholderLimit}', [CommonAjaxCallController::class, 'getLastId'])->name('common.ajax.call.get.last.id');
    Route::get('purchase/by/weight/challan/list', [CommonAjaxCallController::class, 'PurchaseByWeightChallanList'])->name('common.ajax.call.purchase.weight.challan.list');
    Route::get('search/purchase/by/weight/challan/list/{keyword}', [CommonAjaxCallController::class, 'searchPurchaseByWeightChallanList'])->name('common.ajax.call.purchase.weight.search.challan.list');

    Route::get('search/purchase/by/scale/product/list/for/purchase/{purchaseByScaleId}', [CommonAjaxCallController::class, 'purchaseByWeightProductsForPurchases'])->name('common.ajax.call.purchase.scale.product.list.for.purchase');

    Route::get('search/account/{keyword}/{onlyType}', [CommonAjaxCallController::class, 'searchAccount'])->name('common.ajax.call.search.account');
    Route::get('search/cost/centre/{keyword}/{onlyType}', [CommonAjaxCallController::class, 'searchCostCentre'])->name('common.ajax.call.search.cost.centre');

    Route::get('search/po/{key_word}', [CommonAjaxCallController::class, 'searchPo'])->name('common.ajax.call.search.po');
    Route::get('po/products/{order_id}', [CommonAjaxCallController::class, 'poProducts'])->name('common.ajax.call.po.products');

    Route::get('search/purchase/{key_word}', [CommonAjaxCallController::class, 'searchPurchase'])->name('common.ajax.call.search.purchase');
    Route::get('purchase/products/{purchaseId}', [CommonAjaxCallController::class, 'purchaseProducts'])->name('common.ajax.call.get.purchase.products');
    Route::get('accounts/by/group/id/{groupId}', [CommonAjaxCallController::class, 'accountsByGroupId'])->name('common.ajax.call.get.account.by.group.id');

    Route::group(['prefix' => 'short-menus'], function () {
        Route::get('modal/form', [ShortMenuController::class, 'showModalForm'])->name('short.menus.modal.form');
        Route::get('show', [ShortMenuController::class, 'show'])->name('short.menus.show');
        Route::post('store', [ShortMenuController::class, 'store'])->name('short.menus.store');
    });

    Route::group(['prefix' => 'pos-short-menus'], function () {
        Route::get('modal/form', [PosShortMenuController::class, 'showModalForm'])->name('pos.short.menus.modal.form');
        Route::get('show', [PosShortMenuController::class, 'show'])->name('pos.short.menus.show');
        Route::get('edit/page/show', [PosShortMenuController::class, 'editPageShow'])->name('pos.short.menus.edit.page.show');
        Route::post('store', [PosShortMenuController::class, 'store'])->name('pos.short.menus.store');
    });

    Route::group(['prefix' => 'communication/announcements', 'as' => 'announcements.'], function () {
        Route::get('/index', [AnnouncementController::class, 'announcement'])->name('index');
        Route::post('/store', [AnnouncementController::class, 'announcementStore'])->name('store');
        Route::delete('/delete/{id}', [AnnouncementController::class, 'announcementDelete'])->name('delete');
        Route::get('/edit/{id}', [AnnouncementController::class, 'announcementEdit'])->name('edit');
        Route::get('/show/{id}', [AnnouncementController::class, 'announcementShow'])->name('show');
        Route::post('/update/{id}', [AnnouncementController::class, 'announcementUpdate'])->name('update');
        Route::get('/print/{id}', [AnnouncementController::class, 'printAnnouncement'])->name('print');
    });

    Route::group(['prefix' => 'communication/notice-boards', 'as' => 'notice_boards.'], function () {
        Route::get('/', [NoticeBoardController::class, 'index'])->name('index');
        Route::post('/store', [NoticeBoardController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [NoticeBoardController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [NoticeBoardController::class, 'update'])->name('update');
        Route::get('/show/{id}', [NoticeBoardController::class, 'show'])->name('show');
        Route::delete('/delete/{id}', [NoticeBoardController::class, 'noticeDelete'])->name('delete');
        Route::get('/print/{id}', [NoticeBoardController::class, 'printNotice'])->name('print');
    });
    Route::group(['prefix' => 'communication/note', 'as' => 'note.'], function () {
        Route::get('/', [NoteController::class, 'index'])->name('index');
        Route::post('/store', [NoteController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [NoteController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [NoteController::class, 'update'])->name('update');
        Route::get('/show/{id}', [NoteController::class, 'show'])->name('show');
        Route::delete('/delete/{id}', [NoteController::class, 'noticeDelete'])->name('delete');
        Route::get('/print/{id}', [NoteController::class, 'printNotice'])->name('print');
    });
});

Route::controller(GeneralProductSearchController::class)->prefix('general/product/search')->group(function () {

    Route::get('common/{keyword}/{isShowNotForSaleItem}/{priceGroup?}/{type?}/{saleId?}', 'commonSearch')->name('general.product.search.common');
    Route::get('check/product/discount/{productId}/{priceGroupId}', 'checkProductDiscount')->name('general.product.search.check.product.discount');
    Route::get('check/product/discount/with/stock/{productId}/{variantId}/{priceGroupId}', 'checkProductDiscountWithStock')->name('general.product.search.check.product.discount.with.stock');
    Route::get('single/product/stock/{productId}/{warehouseId?}', 'singleProductStock')->name('general.product.search.single.product.stock');
    Route::get('variant/product/stock/{productId}/{variantId}/{warehouseId?}', 'variantProductStock')->name('general.product.search.variant.product.stock');
    Route::get('product/unit/and/multiplier/unit/{productId}', 'productUnitAndMultiplierUnit')->name('general.product.search.product.unit.and.multiplier.unit');
});

Route::resource('shortcut-bookmarks', ShortcutBookmarkController::class)->except(['index', 'create']);
