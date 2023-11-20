<?php

use Modules\Core\Http\Controllers\BdDistrictController;
use Modules\Core\Http\Controllers\BdDivisionController;
use Modules\Core\Http\Controllers\BdUnionController;
use Modules\Core\Http\Controllers\BdUpazilaController;

Route::prefix('locations')->group(function() {
    Route::resource('area', AreaController::class);
    Route::resource('bd-divisions', BdDivisionController::class);
    Route::resource('bd-districts', BdDistrictController::class);
    Route::resource('bd-upazila', BdUpazilaController::class);
    Route::resource('bd-unions', BdUnionController::class);

    Route::get('bd-district/restore/{id}', [BdDistrictController::class, 'restore'])->name('bd-districts.restore');
    Route::delete('bd-district/permanent-delete/{id}', [BdDistrictController::class, 'permanentDelete'])->name('bd-districts.permanent-delete');
    Route::post('bd-district/bulk-actions', [BdDistrictController::class, 'bulkAction'])->name('bd-districts.bulk-action');
    Route::get('get-district-by-division/{id}', [BdDistrictController::class, 'getDistrictByDivision'])->name('get-district-by-division');

    Route::get('division/restore/{id}', [BdDivisionController::class, 'restore'])->name('bd-divisions.restore');
    Route::delete('division/permanent-delete/{id}', [BdDivisionController::class, 'permanentDelete'])->name('bd-divisions.permanent-delete');
    Route::post('division/bulk-actions', [BdDivisionController::class, 'bulkAction'])->name('bd-divisions.bulk-action');

    Route::get('bd-upazila/restore/{id}', [BdUpazilaController::class, 'restore'])->name('bd-upazila.restore');
    Route::delete('bd-upazila/permanent-delete/{id}', [BdUpazilaController::class, 'permanentDelete'])->name('bd-upazila.permanent-delete');
    Route::post('bd-upazila/bulk-actions', [BdUpazilaController::class, 'bulkAction'])->name('bd-upazila.bulk-action');
    Route::get('get-thana-by-district/{id}', [BdUpazilaController::class, 'getUpazilaByDistrict'])->name('get-thana-by-district');

    Route::get('bd-unions/restore/{id}', [BdUnionController::class, 'restore'])->name('bd-unions.restore');
    Route::delete('bd-unions/permanent-delete/{id}', [BdUnionController::class, 'permanentDelete'])->name('bd-unions.permanent-delete');
    Route::post('bd-unions/bulk-actions', [BdUnionController::class, 'bulkAction'])->name('bd-unions.bulk-action');
    Route::get('get-union-by-thana/{id}', [BdUnionController::class, 'getUnionByUpazila'])->name('get-union-by-thana');
});