<?php

use Illuminate\Support\Facades\Route;
use Modules\Asset\Http\Controllers\AllocationController;
use Modules\Asset\Http\Controllers\AssetCategoryController;
use Modules\Asset\Http\Controllers\AssetController;
use Modules\Asset\Http\Controllers\AssetDepreciationController;
use Modules\Asset\Http\Controllers\AssetLocationController;
use Modules\Asset\Http\Controllers\AssetServiceConsumeController;
use Modules\Asset\Http\Controllers\AssetSettingController;
use Modules\Asset\Http\Controllers\AssetUnitController;
use Modules\Asset\Http\Controllers\AuditController;
use Modules\Asset\Http\Controllers\ComponentsController;
use Modules\Asset\Http\Controllers\LicensesCategoryController;
use Modules\Asset\Http\Controllers\LicensesController;
use Modules\Asset\Http\Controllers\ManufacturerController;
use Modules\Asset\Http\Controllers\RequestController;
use Modules\Asset\Http\Controllers\RevokeController;
use Modules\Asset\Http\Controllers\SupplierController;

Route::group(['prefix' => 'app/asset', 'as' => 'assets.'], function () {

    // Setting (Holds categories, locations and units in a tab format)
    Route::controller(AssetSettingController::class)->prefix('settings')->group(function () {
        Route::get('/', 'index')->name('settings.index');
    });

    // Categories
    Route::controller(AssetCategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index')->name('categories.index');
        Route::post('/store', 'store')->name('categories.store');
        Route::post('/update', 'update')->name('categories.update');
        Route::get('/edit/{id}', 'edit')->name('categories.edit');
        Route::delete('/delete/{id}', 'destroy')->name('categories.destroy');
    });

    // Locations
    Route::controller(AssetLocationController::class)->prefix('locations')->group(function () {
        Route::get('/', 'index')->name('locations.index');
        Route::post('/store', 'store')->name('locations.store');
        Route::post('/update', 'update')->name('locations.update');
        Route::get('/edit/{id}', 'edit')->name('locations.edit');
        Route::delete('/delete/{id}', 'destroy')->name('locations.destroy');
    });

    // Units
    Route::controller(AssetUnitController::class)->prefix('units')->group(function () {
        Route::get('/', 'index')->name('units.index');
        Route::post('/store', 'store')->name('units.store');
        Route::post('/update', 'update')->name('units.update');
        Route::get('/edit/{id}', 'edit')->name('units.edit');
        Route::delete('/delete/{id}', 'destroy')->name('units.destroy');
    });

    // Asset
    Route::controller(AssetController::class)->prefix('assets')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/destroy/{id}', 'destroy')->name('assets.destroy');
        Route::get('/additional-files/delete-entry/{id}/{fileName}', 'deleteFileFromAdditionalFilesJson')->name('additional.files.delete');
        Route::get('/image-files/delete-entry/{id}', 'AssetImageDelete')->name('image.file.delete');
    });

    // Allocation
    Route::controller(AllocationController::class)->prefix('allocations')->group(function () {
        Route::get('/', 'index')->name('allocation.index');
        Route::post('/store', 'store')->name('allocation.store');
        Route::get('/edit/{id}', 'edit')->name('allocation.edit');
        Route::post('/update/{id}', 'update')->name('allocation.update');
        Route::delete('/destroy/{id}', 'destroy')->name('allocation.destroy');
        Route::get('/{id}', 'revoke_index')->name('allocation.revoke');
        Route::post('/submit', 'revoke_insert')->name('revoke.insert');
    });

    // Revoke
    Route::controller(RevokeController::class)->prefix('revoke')->group(function () {
        Route::get('/', 'index')->name('revoke.index');
        Route::delete('/delete/{id}', 'destroy')->name('revoke.destroy');
        Route::get('/edit/{id}', 'edit')->name('revoke.edit');
        Route::post('/update/{id}', 'update')->name('revoke.update');
    });

    // Licenses
    Route::controller(LicensesController::class)->prefix('licenses')->group(function () {
        Route::get('/', 'index')->name('licenses.index');
        Route::post('/store', 'store')->name('licenses.submit');
        Route::delete('/delete/{id}', 'destroy')->name('licenses.destroy');
        Route::get('/edit/{id}', 'edit')->name('licenses.edit');
        Route::post('/update/{id}', 'update')->name('licenses.update');
    });

    // Licenses Category
    Route::controller(LicensesCategoryController::class)->prefix('licenses/category')->group(function () {
        Route::get('/', 'index')->name('licenses.category.index');
        Route::post('/store', 'store')->name('licenses.category.submit');
        Route::delete('/delete/{id}', 'destroy')->name('licenses.category.destroy');
        Route::get('/edit/{id}', 'edit')->name('licenses.category.edit');
        Route::post('/update/{id}', 'update')->name('licenses.category.update');

    });

    // Manufacturers
    Route::controller(ManufacturerController::class)->prefix('manufacturers')->group(function () {
        Route::get('/', 'index')->name('manufacturers.index');
        Route::post('/store', 'store')->name('manufacturers.submit');
        Route::delete('/delete/{id}', 'destroy')->name('manufacturers.destroy');
        Route::get('/edit/{id}', 'edit')->name('manufacturers.edit');
        Route::post('/update/{id}', 'update')->name('manufacturers.update');
    });

    // Request
    Route::controller(RequestController::class)->prefix('request')->group(function () {
        Route::get('/', 'index')->name('request.index');
        Route::post('/store', 'store')->name('request.submit');
        Route::delete('/delete/{id}', 'delete')->name('request.delete');
        Route::get('/edit/{id}', 'edit')->name('request.edit');
        Route::post('/update/{id}', 'update')->name('request.update');

    });

    // Audit
    Route::controller(AuditController::class)->prefix('audit')->group(function () {
        Route::get('/', 'index')->name('audit.index');
        Route::post('/store', 'store')->name('audit.submit');
        Route::get('/edit/{id}', 'edit')->name('audit.edit');
        Route::post('/update/{id}', 'update')->name('audit.update');
        Route::delete('/delete/{id}', 'delete')->name('audit.delete');
    });

    // Depreciation
    Route::controller(AssetDepreciationController::class)->prefix('depreciation')->group(function () {
        Route::get('/', 'index')->name('depreciation.index');
    });

    // Components
    Route::controller(ComponentsController::class)->prefix('components')->group(function () {
        Route::get('/', 'index')->name('components.index');
        Route::post('/store', 'store')->name('components.store');
        Route::post('/update/{id}', 'update')->name('components.update');
        Route::get('/edit/{id}', 'edit')->name('components.edit');
        Route::delete('/delete/{id}', 'destroy')->name('components.destroy');
    });

    // Suppliers
    Route::controller(SupplierController::class)->prefix('supplier')->group(function () {
        Route::get('/', 'index')->name('supplier.index');
        Route::post('/store', 'store')->name('supplier.submit');
        Route::delete('/delete/{id}', 'destroy')->name('supplier.destroy');
        Route::get('/edit/{id}', 'edit')->name('supplier.edit');
        Route::post('/update/{id}', 'update')->name('supplier.update');
    });

    // Consume Services
    Route::controller(AssetServiceConsumeController::class)->prefix('consume/services')->group(function () {
        Route::get('/', 'index')->name('consume.services.index');
        Route::post('/store', 'store')->name('consume.services.submit');
        Route::get('/edit/{id}', 'edit')->name('consume.services.edit');
        Route::post('/update/{id}', 'update')->name('consume.services.update');
        Route::delete('/delete/{id}', 'destroy')->name('consume.services.destroy');
    });

    // Dashboard
    Route::get('/dashboard', [AssetController::class, 'dashboard'])->name('dashboard');

});
