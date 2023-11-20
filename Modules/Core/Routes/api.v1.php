<?php

use Modules\Core\Http\Controllers\Api\V1\Auth\JwtAuthController;
use Modules\Core\Http\Controllers\Api\V1\BdDistrictController;
use Modules\Core\Http\Controllers\Api\V1\BdDivisionController;
use Modules\Core\Http\Controllers\Api\V1\BdUnionController;
use Modules\Core\Http\Controllers\Api\V1\BdUpazilaController;

Route::post('login', [JwtAuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [JwtAuthController::class, 'logout'])->name('logout');
    Route::apiResource('bd-divisions', BdDivisionController::class);
    Route::apiResource('bd-districts', BdDistrictController::class);
    Route::apiResource('bd-upazilas', BdUpazilaController::class);
    Route::apiResource('bd-unions', BdUnionController::class);

    Route::get('bd-unions/trashed/all', [BdUnionController::class, 'allTrash'])->name('bd-unions.trash');
    Route::get('bd-unions/restore/{id}', [BdUnionController::class, 'restore'])->name('bd-unions.restore');
    Route::delete('bd-unions/permanent-delete/{id}', [BdUnionController::class, 'permanentDelete'])->name('bd-unions.permanent-delete');
    Route::post('bd-unions/bulk-actions', [BdUnionController::class, 'bulkAction'])->name('bd-unions.bulk-action');
    Route::get('get-union-by-thana/{id}', [BdUnionController::class, 'getUnionByUpazila'])->name('get-union-by-thana');

    Route::get('bd-upazilas/trashed/all', [BdUpazilaController::class, 'allTrash'])->name('bd-upazilas.trash');
    Route::get('bd-upazilas/restore/{id}', [BdUpazilaController::class, 'restore'])->name('bd-upazilas.restore');
    Route::delete('bd-upazilas/permanent-delete/{id}', [BdUpazilaController::class, 'permanentDelete'])->name('bd-upazilas.permanent-delete');
    Route::post('bd-upazilas/bulk-actions', [BdUpazilaController::class, 'bulkAction'])->name('bd-upazilas.bulk-action');

    Route::get('bd-districts/trashed/all', [BdDistrictController::class, 'allTrash'])->name('bd-districts.trash');
    Route::get('bd-districts/restore/{id}', [BdDistrictController::class, 'restore'])->name('bd-districts.restore');
    Route::delete('bd-districts/permanent-delete/{id}', [BdDistrictController::class, 'permanentDelete'])->name('bd-districts.permanent-delete');
    Route::post('bd-districts/bulk-actions', [BdDistrictController::class, 'bulkAction'])->name('bd-districts.bulk-action');

    Route::get('bd-divisions/trashed/all', [BdDivisionController::class, 'allTrash'])->name('bd-divisions.trash');
    Route::get('bd-divisions/restore/{id}', [BdDivisionController::class, 'restore'])->name('bd-divisions.restore');
    Route::delete('bd-divisions/permanent-delete/{id}', [BdDivisionController::class, 'permanentDelete'])->name('bd-divisions.permanent-delete');
    Route::post('bd-divisions/bulk-actions', [BdDivisionController::class, 'bulkAction'])->name('bd-divisions.bulk-action');
});
