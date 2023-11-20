<?php

use Illuminate\Support\Facades\Route;
use Modules\LCManagement\Http\Controllers\AdvisingBankController;
use Modules\LCManagement\Http\Controllers\CnfAgentController;
use Modules\LCManagement\Http\Controllers\ExporterController;
use Modules\LCManagement\Http\Controllers\ImportController;
use Modules\LCManagement\Http\Controllers\InsuranceCompanyController;
use Modules\LCManagement\Http\Controllers\LcController;

Route::group(['prefix' => 'lc'], function () {

    Route::group(['prefix' => 'opening'], function () {

        Route::get('/', [LcController::class, 'index'])->name('manage.lc.index');
        Route::get('show/{id}', [LcController::class, 'show'])->name('manage.lc.show');
        Route::post('store', [LcController::class, 'store'])->name('manage.lc.store');
        Route::get('edit/{id}', [LcController::class, 'edit'])->name('manage.lc.edit');
        Route::post('update/{id}', [LcController::class, 'update'])->name('manage.lc.update');
        Route::delete('delete/{id}', [LcController::class, 'delete'])->name('manage.lc.delete');
    });

    Route::group(['prefix' => 'imports'], function () {

        Route::get('/', [ImportController::class, 'index'])->name('lc.imports.index');
        Route::get('show/{id}', [ImportController::class, 'show'])->name('lc.imports.show');
        Route::get('create', [ImportController::class, 'create'])->name('lc.imports.create');
        Route::post('store', [ImportController::class, 'store'])->name('lc.imports.store');
        Route::get('edit/{id}', [ImportController::class, 'edit'])->name('lc.imports.edit');
        Route::post('update/{id}', [ImportController::class, 'update'])->name('lc.imports.update');
        Route::delete('delete/{id}', [ImportController::class, 'delete'])->name('lc.imports.delete');
    });

    Route::group(['prefix' => 'exporters'], function () {

        Route::post('store', [ExporterController::class, 'store'])->name('lc.exporters.store');
        Route::get('add/quick/exporter/modal', [ExporterController::class, 'addQuickExporterModal'])->name('lc.exporters.add.quick.exporter.modal');
    });

    Route::group(['prefix' => 'advising/bank'], function () {

        Route::get('add/quick/advising/bank/modal', [AdvisingBankController::class, 'addQuickAdvisingBankModal'])->name('lc.advising.bank.add.quick.modal');
        Route::post('store', [AdvisingBankController::class, 'store'])->name('lc.advising.bank.store');
    });

    Route::group(['prefix' => 'insurance/companies'], function () {

        Route::get('/', [InsuranceCompanyController::class, 'index'])->name('lc.insurance.companies.index');
        Route::post('store', [InsuranceCompanyController::class, 'store'])->name('lc.insurance.companies.store');
        Route::get('edit/{id}', [InsuranceCompanyController::class, 'edit'])->name('lc.insurance.companies.edit');
        Route::post('update/{id}', [InsuranceCompanyController::class, 'update'])->name('lc.insurance.companies.update');
        Route::delete('delete/{id}', [InsuranceCompanyController::class, 'delete'])->name('lc.insurance.companies.delete');
        Route::get('add/quick/insurance/company/modal', [InsuranceCompanyController::class, 'addQuickInsuranceCompanyModal'])->name('lc.insurance.companies.add.quick.modal');
        Route::get('manage/{id}', [InsuranceCompanyController::class, 'manage'])->name('lc.insurance.companies.manage');
    });

    Route::group(['prefix' => 'cnf/agents'], function () {

        Route::get('/', [CnfAgentController::class, 'index'])->name('lc.cnf.agents.index');
        Route::post('store', [CnfAgentController::class, 'store'])->name('lc.cnf.agents.store');
        Route::get('edit/{id}', [CnfAgentController::class, 'edit'])->name('lc.cnf.agents.edit');
        Route::post('update/{id}', [CnfAgentController::class, 'update'])->name('lc.cnf.agents.update');
        Route::delete('delete/{id}', [CnfAgentController::class, 'delete'])->name('lc.cnf.agents.delete');
        Route::get('add/quick/cnf/agent/modal', [CnfAgentController::class, 'addQuickInsuranceCompanyModal'])->name('lc.cnf.agents.add.quick.modal');
        Route::get('manage/{id}', [CnfAgentController::class, 'manage'])->name('lc.cnf.agents.manage');
    });

    Route::group(['prefix' => 'exporters'], function () {
        Route::get('/', [ExporterController::class, 'index'])->name('lc.exporters.index');
        Route::post('store', [ExporterController::class, 'store'])->name('lc.exporters.store');
        Route::get('edit/{id}', [ExporterController::class, 'edit'])->name('lc.exporters.edit');
        Route::post('update/{id}', [ExporterController::class, 'update'])->name('lc.exporters.update');
        Route::delete('destroy/{id}', [ExporterController::class, 'destroy'])->name('lc.exporters.destroy');
        Route::get('status/{status}/{id}', [ExporterController::class, 'status'])->name('lc.exporters.status');
        Route::get('/stas', [ExporterController::class, 'getStat'])->name('lc.exporters.stat');

    });

});
