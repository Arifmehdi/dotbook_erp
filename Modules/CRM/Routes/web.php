<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\AppointmentController;
use Modules\CRM\Http\Controllers\BusinessLeadController;
use Modules\CRM\Http\Controllers\CrmDashboardController;
use Modules\CRM\Http\Controllers\CustomerController;
use Modules\CRM\Http\Controllers\EstimateController;
use Modules\CRM\Http\Controllers\FollowupCategoryController;
use Modules\CRM\Http\Controllers\FollowupController;
use Modules\CRM\Http\Controllers\IndividualLeadController;
use Modules\CRM\Http\Controllers\LifeStageController;
use Modules\CRM\Http\Controllers\ProposalController;
use Modules\CRM\Http\Controllers\ProposalTemplateController;
use Modules\CRM\Http\Controllers\SettingsController;
use Modules\CRM\Http\Controllers\SourceController;
use Modules\CRM\Http\Controllers\SubscriptionController;
use Modules\CRM\Http\Controllers\TaskController;

Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {
    // Route::controller(InactiveCustomerController::class)->prefix('inactive_customers')->group(function () {
    //     Route::get('/', 'index')->name('inactive_customers.index');
    // });

    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('/', 'index')->name('customers.index');
        Route::post('/store', 'store')->name('customers.store');
        Route::post('/update', 'update')->name('customers.update');
        Route::get('/edit/{id}', 'edit')->name('customers.edit');
        Route::delete('/delete/{id}', 'delete')->name('customers.delete');
        Route::post('update/{id}', 'update')->name('customer.update');
        Route::get('/status/{id}', 'status')->name('customers.status');
        Route::get('create/basic/modal', 'basicModal')->name('customer.create.basic.modal');
        Route::get('create/detailed/modal', 'detailedModal')->name('customer.create.detailed.modal');
    });

    // Individual Lead  Route
    Route::resource('individual-leads', IndividualLeadController::class);

    Route::controller(IndividualLeadController::class)->prefix('individual')->group(function () {
        Route::get('individual-leads/restore/{id}', 'restore')->name('individual-leads.restore');
        Route::get('individual-leads/followup/{id}', 'followup')->name('individual-leads.followup');
        Route::get('leads/import', 'leadsImport')->name('individual-leads.import');
        Route::post('leads/import/store', 'leadsImportStore')->name('individual-leads.import.store');
        Route::delete('individual-leads/permanent-delete/{id}', 'permanentDelete')->name('individual-leads.permanent-delete');
        Route::post('individual-leads/bulk-actions', 'bulkAction')->name('individual-leads.bulk-action');
        Route::get('individual-leads/additional-file-delete/{id}/{fileName}', 'additional_file_delete')->name('individual-leads.additional-file.delete');
    });

    // Business Route
    Route::resource('business-leads', BusinessLeadController::class);
    Route::controller(BusinessLeadController::class)->prefix('business')->group(function () {
        Route::get('leads/restore/{id}', 'restore')->name('business-leads.restore');
        Route::get('leads/followup/{id}', 'followup')->name('business-leads.followup');
        Route::get('leads/import', 'leadsImport')->name('business-leads.import');
        Route::post('leads/import/store', 'leadsImportStore')->name('business-leads.import.store');
        Route::delete('leads/permanent-delete/{id}', 'permanentDelete')->name('business-leads.permanent-delete');
        Route::post('leads/bulk-actions', 'bulkAction')->name('business-leads.bulk-action');
        Route::get('leads/additional-file-delete/{id}/{fileName}', 'additional_file_delete')->name('business-leads.additional-file.delete');
    });

    Route::controller(SourceController::class)->prefix('source')->group(function () {
        Route::get('/', 'index')->name('source.index');
        Route::post('/store', 'store')->name('source.store');
        Route::get('/edit/{id}', 'edit')->name('source.edit');
        Route::post('/update/{id}', 'update')->name('source.update');
        Route::delete('/delete/{id}', 'delete')->name('source.delete');
    });

    Route::controller(LifeStageController::class)->prefix('life/stage')->group(function () {
        Route::get('/', 'index')->name('life.stage.index');
        Route::post('/store', 'store')->name('life.stage.store');
        Route::get('/edit/{id}', 'edit')->name('life.stage.edit');
        Route::post('/update/{id}', 'update')->name('life.stage.update');
        Route::delete('/delete/{id}', 'delete')->name('life.stage.delete');
    });

    Route::controller(FollowupCategoryController::class)->prefix('followup/category')->group(function () {
        Route::get('/', 'index')->name('followup.category.index');
        Route::post('/store', 'store')->name('followup.category.store');
        Route::get('/edit/{id}', 'edit')->name('followup.category.edit');
        Route::post('/update/{id}', 'update')->name('followup.category.update');
        Route::delete('/delete/{id}', 'delete')->name('followup.category.delete');
    });

    Route::controller(FollowupController::class)->prefix('followups')->group(function () {
        Route::get('/', 'index')->name('followup.index');
        Route::get('/get/leads/{type}', 'getLeads')->name('followup.leads');
        Route::get('/get/leads/details/{id}/{type}', 'getLeadsDetails')->name('followup.leads.details');
        Route::post('/store', 'store')->name('followup.store');
        Route::get('/edit/{id}', 'edit')->name('followup.edit');
        Route::get('/add/{id}', 'add')->name('followup.add');
        Route::get('/create', 'create')->name('followup.create');
        Route::post('/update/{id}', 'update')->name('followup.update');
        Route::delete('/delete/{id}', 'delete')->name('followup.delete');
        Route::get('/restore/{id}', 'restore')->name('followup.restore');
        Route::delete('/permanent-delete/{id}', 'permanentDelete')->name('followup.permanent-delete');
        Route::post('/bulk-actions', 'bulkAction')->name('followup.bulk-action');
        Route::get('/filter-actions/{filter_type}', 'filterAction')->name('followup.filter-action');
        Route::delete('/destroy/{id}', 'destroy')->name('followup.destroy');
    });

    Route::controller(ProposalTemplateController::class)->prefix('proposal/template')->group(function () {
        Route::get('/', 'index')->name('proposal_template.index');
        Route::get('/leads/customers/list/{rel_type}', 'getLeadsCustomers')->name('proposal_template.leads-customers');
        Route::get('/leads/address/info/{customer_leads_val}/{rel_type}', 'findLeadsAddress')->name('proposal_template.leads-address');
        Route::post('/store', 'store')->name('proposal_template.store');
        Route::post('/total', 'total')->name('proposal_template.total');
        Route::get('/edit/{id}', 'edit')->name('proposal_template.edit');
        Route::get('/send/{id}', 'send')->name('proposal_template.send');
        Route::get('/view/{id}', 'view')->name('proposal_template.view');
        Route::get('/product/info/{item_id}', 'productDetails')->name('proposal_template.product-info');
        Route::get('/add/product/modal/view', 'addProductModalView')->name('proposal_template.add.product.modal.view');
        Route::post('/update/{id}', 'update')->name('proposal_template.update');
        Route::delete('/delete/{id}', 'delete')->name('proposal_template.delete');
        Route::get('/attachment/{id}/{file_name}', 'delete_attachment')->name('proposal_template.attachments.delete');
        Route::get('/decline/{propoId}', 'decline')->name('proposal_template.decline');
        Route::post('/accept/{id}', 'accept')->name('proposal_template.accept');
        Route::post('/comment/{propoId}', 'comment')->name('proposal_template.comment');
        Route::get('/get/comment/{propoId}', 'getComment')->name('proposal_template.comment.get');
    });

    Route::controller(SubscriptionController::class)->prefix('subscriptions')->group(function () {
        Route::get('/', 'index')->name('subscription.index');
        Route::get('/edit/{id}', 'edit')->name('subscription.edit');
        Route::delete('/destroy/{id}', 'destroy')->name('subscription.destroy');
        Route::post('/store', 'store')->name('subscription.store');
        Route::post('/update/{id}', 'update')->name('subscription.update');
        Route::get('/restore/{id}', 'restore')->name('subscription.restore');
        Route::delete('/permanent-delete/{id}', 'permanentDelete')->name('subscription.permanent-delete');
        Route::post('/bulk-actions', 'bulkAction')->name('subscription.bulk-action');
    });

    Route::controller(TaskController::class)->prefix('tasks')->group(function () {
        Route::get('/', 'index')->name('task.index');
        Route::get('/edit/{id}', 'edit')->name('task.edit');
        Route::delete('/destroy/{id}', 'destroy')->name('task.destroy');
        Route::post('/store', 'store')->name('task.store');
        Route::post('/update/{id}', 'update')->name('task.update');
        Route::get('/restore/{id}', 'restore')->name('task.restore');
        Route::delete('/permanent-delete/{id}', 'permanentDelete')->name('task.permanent-delete');
        Route::post('/bulk-actions', 'bulkAction')->name('task.bulk-action');
    });

    Route::controller(ProposalController::class)->prefix('proposals')->group(function () {
        Route::get('/', 'index')->name('proposal.index');
        Route::post('/store', 'store')->name('proposal.store');
        Route::get('/edit/{id}', 'edit')->name('proposal.edit');
        Route::post('/update/{id}', 'update')->name('proposal.update');
        Route::delete('/delete/{id}', 'delete')->name('proposal.delete');
    });

    Route::controller(SettingsController::class)->prefix('settings')->group(function () {
        Route::get('/', 'index')->name('settings.index');
        Route::post('/change', 'change')->name('settings.change');
    });

    Route::controller(EstimateController::class)->prefix('estimates')->group(function () {
        Route::get('/', 'index')->name('estimates.index');
        Route::get('/templates', 'add_content')->name('estimates.add_content');
    });

    Route::controller(AppointmentController::class)->prefix('appointments')->group(function () {
        Route::get('/', 'index')->name('appointment.index');
        Route::post('/store', 'store')->name('appointment.store');
        Route::get('/edit/{id}', 'edit')->name('appointment.edit');
        Route::post('/update/{id}', 'update')->name('appointment.update');
        Route::delete('/delete/{id}', 'destroy')->name('appointment.destroy');
    });

    Route::controller(CrmDashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('/', 'index')->name('dashboard.index');
    });
});
