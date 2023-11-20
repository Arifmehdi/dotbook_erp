<?php

use Illuminate\Support\Facades\Route;
use Modules\Scale\Http\Controllers\WeightClientController;
use Modules\Scale\Http\Controllers\WeightController;

Route::group(['prefix' => 'weight/scales'], function () {
    Route::controller(WeightController::class)->prefix('/')->group(function () {

        Route::get('/', 'index')->name('scale.index');
        Route::get('create', 'create')->name('scale.create');
        Route::get('show/{weightScaleId}', 'show')->name('scale.show');
        Route::delete('delete/{ScaleId}', 'delete')->name('scale.delete');
        Route::post('save/weight', 'saveWeight')->name('scale.save-weight');
        Route::post('scale/weight/vehicle/done/{scaleId}', 'weightScaleVehicleDone')->name('scale.weight-vehicle-done');
        Route::get('print/random/weight/{purchaseByScaleId}', 'printWeight')->name('random.scale.print.weight');
        Route::get('random/weight/challan/list', 'RandomWeightChallanList')->name('common.ajax.call.random.weight.challan.list');
        Route::get('search/random/weight/challan/list/{keyword}', 'searchRandomWeightChallanList')->name('common.ajax.call.random.weight.search.challan.list');
    });

    Route::controller(WeightClientController::class)->prefix('clients')->group(function () {

        Route::get('/', 'index')->name('scale.client.index');
        Route::post('store', 'store')->name('scale.client.store');
        Route::get('edit/{id}', 'edit')->name('scale.client.edit');
        Route::post('update/{id}', 'update')->name('scale.client.update');
        Route::delete('delete/{id}', 'delete')->name('scale.client.delete');
        Route::get('add/weight/client/modal', 'addWeightClientModal')->name('scale.add.weight.client.modal');
    });
});
