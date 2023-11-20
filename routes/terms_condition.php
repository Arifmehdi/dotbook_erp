<?php

use App\Http\Controllers\TermsCondition\CategoryController;
use App\Http\Controllers\TermsCondition\TermsConditionController;

Route::group(['prefix' => 'utilities/app/terms/condition', 'as' => 'terms.'], function () {
    Route::get('/', [TermsConditionController::class, 'index'])->name('index');
    Route::post('/store', [TermsConditionController::class, 'store'])->name('store');
    Route::delete('/destroy/{id}', [TermsConditionController::class, 'destroy'])->name('delete');
    Route::get('/edit/{id}', [TermsConditionController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [TermsConditionController::class, 'update'])->name('update');

    // category
    Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {

        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });
});
