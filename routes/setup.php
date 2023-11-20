<?php

use App\Http\Controllers\BarcodeSettingController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentMethodSettingsController;
use App\Http\Controllers\ReleaseNoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'settings', 'namespace' => 'App\Http\Controllers'], function () {

    Route::group(['prefix' => 'core/settings'], function () {

        Route::group(['prefix' => 'general_settings'], function () {
            Route::get('/', [GeneralSettingController::class, 'index'])->name('settings.general.index');
            Route::post('business/settings', [GeneralSettingController::class, 'businessSettings'])->name('settings.business.settings');
            Route::post('tax/settings', [GeneralSettingController::class, 'taxSettings'])->name('settings.tax.settings');
            Route::post('product/settings', [GeneralSettingController::class, 'productSettings'])->name('settings.product.settings');
            Route::post('contact/settings', [GeneralSettingController::class, 'contactSettings'])->name('settings.contact.settings');
            Route::post('sale/settings', [GeneralSettingController::class, 'saleSettings'])->name('settings.sale.settings');
            Route::post('pos/settings', [GeneralSettingController::class, 'posSettings'])->name('settings.pos.settings');
            Route::post('purchase/settings', [GeneralSettingController::class, 'purchaseSettings'])->name('settings.purchase.settings');
            Route::post('dashboard/settings', [GeneralSettingController::class, 'dashboardSettings'])->name('settings.dashboard.settings');
            Route::post('prefix/settings', [GeneralSettingController::class, 'prefixSettings'])->name('settings.prefix.settings');
            Route::post('system/settings', [GeneralSettingController::class, 'systemSettings'])->name('settings.system.settings');
            Route::post('module/settings', [GeneralSettingController::class, 'moduleSettings'])->name('settings.module.settings');
            Route::post('send/email/sms/settings', [GeneralSettingController::class, 'SendEmailSmsSettings'])->name('settings.send.email.sms.settings');
            Route::post('sms/settings', [GeneralSettingController::class, 'smsSettings'])->name('settings.sms.settings');
            Route::post('rp/settings', [GeneralSettingController::class, 'rewardPoingSettings'])->name('settings.reward.point.settings');
        });

        Route::group(['prefix' => 'payment_method_settings'], function () {

            Route::get('/', [PaymentMethodSettingsController::class, 'index'])->name('settings.payment.method.settings.index');
            Route::post('update', [PaymentMethodSettingsController::class, 'update'])->name('settings.payment.method.settings.update');
        });

        Route::group(['prefix' => 'barcode_settings'], function () {

            Route::get('/', [BarcodeSettingController::class, 'index'])->name('settings.barcode.index');
            Route::get('create', [BarcodeSettingController::class, 'create'])->name('settings.barcode.create');
            Route::post('store', [BarcodeSettingController::class, 'store'])->name('settings.barcode.store');
            Route::get('edit/{id}', [BarcodeSettingController::class, 'edit'])->name('settings.barcode.edit');
            Route::post('update/{id}', [BarcodeSettingController::class, 'update'])->name('settings.barcode.update');
            Route::delete('delete/{id}', [BarcodeSettingController::class, 'delete'])->name('settings.barcode.delete');
            Route::get('set/default/{id}', [BarcodeSettingController::class, 'setDefault'])->name('settings.barcode.set.default');
        });

        Route::group(['prefix' => 'release/note'], function () {

            Route::get('/', [ReleaseNoteController::class, 'index'])->name('settings.release.note.index');
        });
    });
    Route::prefix('app-setup')->group(function () {
        Route::group(['prefix' => 'warehouses'], function () {
            Route::get('/', 'WarehouseController@index')->name('settings.warehouses.index');
            Route::post('store', 'WarehouseController@store')->name('settings.warehouses.store');
            Route::get('edit/{id}', 'WarehouseController@edit')->name('settings.warehouses.edit');
            Route::post('update/{id}', 'WarehouseController@update')->name('settings.warehouses.update');
            Route::delete('delete/{warehouseId}', 'WarehouseController@delete')->name('settings.warehouses.delete');
        });
        Route::group(['prefix' => 'payment_methods'], function () {

            Route::get('/', 'PaymentMethodController@index')->name('settings.payment.method.index');
            Route::post('store', 'PaymentMethodController@store')->name('settings.payment.method.store');
            Route::get('edit/{id}', 'PaymentMethodController@edit')->name('settings.payment.method.edit');
            Route::post('update/{id}', 'PaymentMethodController@update')->name('settings.payment.method.update');
            Route::delete('delete/{id}', 'PaymentMethodController@delete')->name('settings.payment.method.delete');
        });
        Route::group(['prefix' => 'invoices'], function () {
            Route::group(['prefix' => 'schemas'], function () {

                Route::get('/', 'InvoiceSchemaController@index')->name('invoices.schemas.index');
                Route::post('store', 'InvoiceSchemaController@store')->name('invoices.schemas.store');
                Route::get('edit/{schemaId}', 'InvoiceSchemaController@edit')->name('invoices.schemas.edit');
                Route::post('update/{schemaId}', 'InvoiceSchemaController@update')->name('invoices.schemas.update');
                Route::delete('delete/{schemaId}', 'InvoiceSchemaController@delete')->name('invoices.schemas.delete');
                Route::get('set/default/{schemaId}', 'InvoiceSchemaController@setDefault')->name('invoices.schemas.set.default');
            });

            Route::group(['prefix' => 'layouts'], function () {

                Route::get('/', 'InvoiceLayoutController@index')->name('invoices.layouts.index');
                Route::get('create', 'InvoiceLayoutController@create')->name('invoices.layouts.create');
                Route::post('/', 'InvoiceLayoutController@store')->name('invoices.layouts.store');
                Route::get('edit/{layoutId}', 'InvoiceLayoutController@edit')->name('invoices.layouts.edit');
                Route::post('update/{layoutId}', 'InvoiceLayoutController@update')->name('invoices.layouts.update');
                Route::delete('delete/{layoutId}', 'InvoiceLayoutController@delete')->name('invoices.layouts.delete');
                Route::get('set/default/{schemaId}', 'InvoiceLayoutController@setDefault')->name('invoices.layouts.set.default');
            });
        });
        Route::group(['prefix' => 'scash_counter'], function () {
            Route::get('/', 'CashCounterController@index')->name('settings.cash.counter.index');
            Route::post('store', 'CashCounterController@store')->name('settings.payment.cash.counter.store');
            Route::get('edit/{id}', 'CashCounterController@edit')->name('settings.cash.counter.edit');
            Route::post('update/{id}', 'CashCounterController@update')->name('settings.cash.counter.update');
            Route::delete('delete/{id}', 'CashCounterController@delete')->name('settings.cash.counter.delete');
        });
    });
});
Route::group(['prefix' => 'user-manage', 'namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('all/users', [UserController::class, 'allUsers'])->name('users.all.Users');
        Route::get('create', [UserController::class, 'create'])->name('users.create');
        Route::get('all/roles', [UserController::class, 'allRoles'])->name('users.all.roles');
        Route::post('store', [UserController::class, 'store'])->name('users.store');
        Route::get('edit/{userId}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('update/{userId}', [UserController::class, 'update'])->name('users.update');
        Route::delete('delete/{userId}', [UserController::class, 'delete'])->name('users.delete');
        Route::get('show/{userId}', [UserController::class, 'show'])->name('users.show');
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [UserProfileController::class, 'index'])->name('users.profile.index');
            Route::post('update', [UserProfileController::class, 'update'])->name('users.profile.update');
            Route::get('view/{id}', [UserProfileController::class, 'view'])->name('users.profile.view');
        });
    });
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('users.role.index');
        Route::get('all/roles', [RoleController::class, 'allRoles'])->name('users.role.all.roles');
        Route::get('create_v2', [RoleController::class, 'createV2'])->name('users.role.create.v2');
        Route::get('create', [RoleController::class, 'create'])->name('users.role.create');
        Route::post('store', [RoleController::class, 'store'])->name('users.role.store');
        Route::get('edit/{roleId}', [RoleController::class, 'edit'])->name('users.role.edit');
        Route::post('update/{roleId}', [RoleController::class, 'update'])->name('users.role.update');
        Route::delete('delete/{roleId}', [RoleController::class, 'delete'])->name('users.role.delete');
    });

});
Route::get('/notification', [NotificationController::class, 'index'])->name('notification.index');
