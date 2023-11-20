<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;
use Modules\Contacts\Http\Controllers\CustomerController;
use Modules\Contacts\Http\Controllers\CustomerGroupController;
use Modules\Contacts\Http\Controllers\CustomerImportController;
use Modules\Contacts\Http\Controllers\CustomerOpeningBalanceController;
use Modules\Contacts\Http\Controllers\MoneyReceiptController;
use Modules\Contacts\Http\Controllers\SupplierController;
use Modules\Contacts\Http\Controllers\SupplierImportController;

Route::group(['prefix' => 'contacts'], function () {
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('contacts.customers.index');
        Route::get('add-more-info', [CustomerController::class, 'createWithMoreData'])->name('contacts.customers.create.more.info');
        Route::get('create/basic/modal', [CustomerController::class, 'basicModal'])->name('contacts.customers.create.basic.modal');
        Route::get('create/detailed/modal', [CustomerController::class, 'detailedModal'])->name('contacts.customers.create.detailed.modal');
        Route::post('store/more-info', [CustomerController::class, 'storeWithMoreData'])->name('contacts.customers.more.info.store');
        Route::post('store', [CustomerController::class, 'store'])->name('contacts.customers.store');
        Route::get('edit/{customerId}', [CustomerController::class, 'edit'])->name('contacts.customers.edit');
        Route::get('view/customer/{customerId}', [CustomerController::class, 'viewCustomer'])->name('contacts.customers.view');
        Route::get('view/customer/pdf/{customerId}', [CustomerController::class, 'viewCustomerPdf'])->name('contacts.customers.pdf');
        Route::post('update/{id}', [CustomerController::class, 'update'])->name('contacts.customers.update');
        Route::delete('delete/{customerId}', [CustomerController::class, 'delete'])->name('contacts.customers.delete');
        Route::get('change/status/{customerId}', [CustomerController::class, 'changeStatus'])->name('contacts.customers.change.status');
        Route::get('manage/{customerId}', [CustomerController::class, 'manage'])->name('customers.manage');
        Route::get('customerstatuschange', [CustomerController::class, 'changeStat'])->name('customers.change.status');
        Route::get('saleAndOrdersUserWise/{customerId}', [CustomerController::class, 'customerSaleAndOrdersUserWise'])->name('contacts.customers.sales.and.order.user.wise');

        Route::group(['prefix' => 'money/receipt'], function () {
            Route::get('index/{customerId}', [MoneyReceiptController::class, 'index'])->name('money.receipt.voucher.index');
            Route::get('create/{customerId}', [MoneyReceiptController::class, 'create'])->name('money.receipt.voucher.create');
            Route::post('store/{customerId}', [MoneyReceiptController::class, 'store'])->name('money.receipt.voucher.store');
            Route::get('print/{receiptId}', [MoneyReceiptController::class, 'moneyReceiptPrint'])->name('money.receipt.voucher.print');
            Route::get('edit/{receiptId}', [MoneyReceiptController::class, 'edit'])->name('money.receipt.voucher.edit');
            Route::post('update/{receiptId}', [MoneyReceiptController::class, 'update'])->name('money.receipt.voucher.update');
            Route::get('status/change/modal/{receiptId}', [MoneyReceiptController::class, 'changeStatusModal'])->name('money.receipt.voucher.status.change.modal');
            Route::delete('delete/{receiptId}', [MoneyReceiptController::class, 'delete'])->name('money.receipt.voucher.delete');
            Route::post('status/change/{receiptId}', [MoneyReceiptController::class, 'changeStatus'])->name('money.receipt.voucher.status.change');
        });

        Route::controller(CustomerOpeningBalanceController::class)->prefix('opening-balance')->group(function () {
            Route::post('update', 'update')->name('contacts.customers.opening.balance.update');
        });
    });
    Route::group(['prefix' => 'customers/import'], function () {
        Route::get('/', [CustomerImportController::class, 'create'])->name('contacts.customers.import.create');
        Route::post('store', [CustomerImportController::class, 'store'])->name('contacts.customers.import.store');
    });
    Route::controller(CustomerGroupController::class)->prefix('customers/groups')->group(function () {
        Route::get('/', 'index')->name('customers.groups.index');
        Route::post('/store', 'store')->name('customers.groups.store');
        Route::post('/update/{id}', 'update')->name('customers.groups.update');
        Route::get('/edit/{id}', 'edit')->name('customers.groups.edit');
        Route::delete('/delete/{id}', 'destroy')->name('customers.groups.delete');
    });
});
Route::group(['prefix' => 'contacts/procurement'], function () {
    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('/', [SupplierController::class, 'index'])->name('contacts.supplier.index');
        Route::get('create/basic/modal', [SupplierController::class, 'basicModal'])->name('contacts.supplier.create.basic.modal');
        Route::post('contacts/supplier/store', [SupplierController::class, 'store'])->name('contacts.supplier.store');
        Route::get('statistics', [SupplierController::class, 'statistics'])->name('supplier.statistics');
        Route::get('create/detailed/modal', [SupplierController::class, 'detailedModal'])->name('contacts.supplier.create.detailed.modal');
        Route::get('manage/{supplierId}', [SupplierController::class, 'manage'])->name('contacts.supplier.manage');
        Route::get('view/supplier/{supplierId}', [SupplierController::class, 'viewSupplier'])->name('contacts.supplier.view.details');
        Route::get('edit/{supplierId}', [SupplierController::class, 'edit'])->name('contacts.supplier.edit');
        Route::post('update', [SupplierController::class, 'update'])->name('contacts.supplier.update');
        Route::delete('delete/{supplierId}', [SupplierController::class, 'delete'])->name('contacts.supplier.delete');
        Route::get('change/status/{supplierId}', [SupplierController::class, 'changeStatus'])->name('contacts.supplier.change.status');
        Route::get('view/supplier/pdf/{supplierId}', [SupplierController::class, 'viewSupplierPdf'])->name('contacts.supplier.view.pdf');
        Route::get('/dashboard', [SupplierController::class, 'dashboard'])->name('suppliers.dashboard');
        Route::group(['prefix' => 'import'], function () {
            Route::get('/', [SupplierImportController::class, 'create'])->name('contacts.suppliers.import.create');
            Route::post('store', [SupplierImportController::class, 'store'])->name('contacts.suppliers.import.store');
        });
    });
});

Route::controller(ContactsController::class)->as('contacts.')->prefix('contacts')->group(function () {
    Route::get('/index2', 'index2')->name('index2');
    Route::get('/index', 'index')->name('index');
    Route::get('/total-status', 'totalStatus')->name('total_status');
    Route::get('/basic/modal', 'basicModal')->name('create.basic.modal');
    Route::get('/details/modal', 'detailedModal')->name('create.details.modal');
    Route::get('/change/status/{id}', 'changeStatus')->name('change.status');
    Route::post('/store', 'store')->name('store');
    Route::post('/import/store', 'importStore')->name('import.store');
    Route::get('/import', 'import')->name('import');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::get('/view/{id}', 'view')->name('view');
    Route::post('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'delete')->name('delete');
    Route::get('/restore/{id}', 'restore')->name('restore');
    Route::delete('/permanent-delete/{id}', 'permanentDelete')->name('permanent-delete');
    Route::post('/bulk-actions', 'bulkAction')->name('bulk-action');
    Route::get('/filter-actions/{filter_type}', 'filterAction')->name('filter-action');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
});
