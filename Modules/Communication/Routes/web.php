<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\ContactController;
use Modules\Communication\Http\Controllers\ContactGroupController;
use Modules\Communication\Http\Controllers\CustomEmailController;
use Modules\Communication\Http\Controllers\EmailServerController;
use Modules\Communication\Http\Controllers\EmailSettingController;
use Modules\Communication\Http\Controllers\EmailTemplateController;
use Modules\Communication\Http\Controllers\ManualMailController;
use Modules\Communication\Http\Controllers\ManualSmsController;
use Modules\Communication\Http\Controllers\NumberController;
use Modules\Communication\Http\Controllers\SmsController;
use Modules\Communication\Http\Controllers\SmsServerController;
use Modules\Communication\Http\Controllers\SmsSettingController;
use Modules\Communication\Http\Controllers\SmsTemplateController;
use Modules\Communication\Http\Controllers\WhatsappMessageController;
use Modules\Communication\Http\Controllers\WhatsappTemplateController;

Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
    Route::controller(SmsSettingController::class)->group(function () {
        Route::group(['prefix' => 'sms'], function () {
            Route::get('settings', 'smsSettingsUI')->name('sms.settings');
            Route::get('permission/on/module', 'smsPermission')->name('sms.permission');
            Route::get('setting', 'smsSettings')->name('sms.setting');
            Route::post('settings/store', 'smsSettingsStore')->name('sms.settings.store');
        });
    });

    Route::controller(SmsController::class)->prefix('sms')->group(function () {
        Route::get('/index', 'index')->name('sms.index');
        Route::post('/send', 'send')->name('sms.send');
        Route::get('/important/{id}/{flag}', 'important')->name('sms.important');
        Route::post('/delete/all', 'delete_all')->name('sms.delete_all');
        Route::delete('/delete/{id}', 'delete')->name('sms.delete');
    });

    Route::controller(ManualSmsController::class)->group(function () {
        Route::group(['prefix' => 'sms/manual/'], function () {
            Route::get('service', 'smsManual')->name('sms.manual-service');
            Route::get('service/sms/active/{sms}/{id}', 'manualPhoneStatus')->name('sms.manual-service-sms-active');
            Route::get('service/sms/list/{filterType}/{filterKey}', 'smsManualSmsList')->name('sms.manual-service-sms-list');
            Route::get('service/status/wise/sms/list/{statusType}', 'smsManualStatusWisePhoneNumberList')->name('sms.manual-service-sms-status-wise-list');
            Route::get('service/sms/import/modal', 'importPhoneModal')->name('sms.manual-service.sms-import-modal');
            Route::post('service/sms/import/store', 'importPhoneStore')->name('sms.manual-service.phone-number-import-store');
            Route::post('service/sms/send', 'manualSmsSend')->name('sms.manual-service.sms.send');
        });
    });

    Route::controller(SmsServerController::class)->prefix('sms')->group(function () {
        Route::get('server/setup', 'smsServerSetup')->name('sms.server-setup');
        Route::get('/server/edit/{id}', 'editServer')->name('sms.serve.edit');
        Route::post('server/setup', 'smsServerStore')->name('sms.server.store');
        Route::get('/serve/active/{id}/{flag}', 'activeServer')->name('sms.server.active');
        Route::delete('/delete/serve/{id}', 'deleteServe')->name('sms.serve.delete');
        Route::post('/delete/all/server', 'deleteAllServer')->name('sms.server.delete_all');
    });

    Route::controller(SmsTemplateController::class)->prefix('sms')->group(function () {
        Route::get('body', 'smsBody')->name('sms.body');
        Route::get('/view/{id}', 'view')->name('sms.body.view');
        Route::post('body', 'smsBodyStore')->name('sms.body-format.store');
        Route::get('/important/body/{id}/{flag}', 'importantBody')->name('sms.body.important');
        Route::post('/delete/all/body', 'deleteAllBody')->name('sms.body.delete_all');
        Route::delete('/delete/body/{id}', 'deleteBody')->name('sms.body.delete');
    });

    Route::controller(WhatsappMessageController::class)->prefix('whatsapp')->group(function () {
        Route::get('/index', 'index')->name('whatsapp.index');
        Route::get('/whatsapp', 'whatsapp')->name('whatsapp.whatsapp');
        Route::post('/whatsapp', 'whatsapp')->name('whatsapp.whatsapp.store');
        Route::post('/send', 'send')->name('whatsapp.send');
        Route::get('/important/{id}/{flag}', 'important')->name('whatsapp.important');
        Route::post('/delete/all', 'delete_all')->name('whatsapp.delete_all');
        Route::delete('/delete/{id}', 'delete')->name('whatsapp.delete');
    });

    Route::controller(ManualWhatsappController::class)->group(function () {
        Route::group(['prefix' => 'whatsapp/manual/'], function () {
            Route::get('service', 'whatsappManual')->name('whatsapp.manual-service');
            Route::get('service/whatsapp/active/{whatsapp}/{id}', 'manualPhoneStatus')->name('whatsapp.manual-service-whatsapp-active');
            Route::get('service/whatsapp/list/{filterType}/{filterKey}', 'whatsappManualWhatsappList')->name('whatsapp.manual-service-whatsapp-list');
            Route::get('service/status/wise/whatsapp/list/{statusType}', 'whatsappManualStatusWisePhoneNumberList')->name('whatsapp.manual-service-whatsapp-status-wise-list');
            Route::get('service/whatsapp/import/modal', 'importPhoneModal')->name('whatsapp.manual-service.whatsapp-import-modal');
            Route::post('service/whatsapp/import/store', 'importPhoneStore')->name('whatsapp.manual-service.phone-number-import-store');
            Route::post('service/whatsapp/send', 'manualWhatsappSend')->name('whatsapp.manual-service.whatsapp.send');
        });
    });

    Route::controller(WhatsappTemplateController::class)->prefix('whatsapp')->group(function () {
        Route::get('body', 'whatsappBody')->name('whatsapp.body');
        Route::get('/view/{id}', 'view')->name('whatsapp.body.view');
        Route::post('body', 'whatsappBodyStore')->name('whatsapp.body-format.store');
        Route::get('/important/body/{id}/{flag}', 'importantBody')->name('whatsapp.body.important');
        Route::post('/delete/all/body', 'deleteAllBody')->name('whatsapp.body.delete_all');
        Route::delete('/delete/body/{id}', 'deleteBody')->name('whatsapp.body.delete');
    });

    Route::controller(EmailSettingController::class)->prefix('email')->group(function () {
        Route::get('settings', 'emailSettingsUI')->name('email.settings');
        Route::get('permission/on/module', 'emailPermission')->name('email.permission');
        Route::get('setting', 'emailSettings')->name('email.setting');
        Route::post('settings/store', 'emailSettingsStore')->name('email.settings.store');
    });

    Route::controller(ManualMailController::class)->prefix('email/manual/')->group(function () {
        Route::get('service', 'emailManual')->name('email.manual-service');
        Route::get('service/mail/active/{email}/{id}', 'manualMailStatus')->name('email.manual-service-mail-active');
        Route::get('service/mail/list/{filterType}/{filterKey}', 'emailManualMailList')->name('email.manual-service-mail-list');
        Route::get('service/status/wise/mail/list/{statusType}', 'emailManualStatusWiseMailList')->name('email.manual-service-mail-status-wise-list');
        Route::get('service/mail/import/modal', 'importMailModal')->name('email.manual-service.mail-import-modal');
        Route::post('service/mail/import/store', 'importMailStore')->name('email.manual-service.mail-import-store');
        Route::post('service/mail/send', 'manualMailSend')->name('email.manual-service.mail.send');
    });

    Route::controller(EmailServerController::class)->prefix('email')->group(function () {
        Route::get('server/setup', 'emailServerSetup')->name('email.server-setup');
        Route::get('/server/edit/{id}', 'editServer')->name('email.serve.edit');
        Route::post('server/setup', 'emailServerStore')->name('email.server.store');
        Route::get('/serve/active/{id}/{flag}', 'activeServer')->name('email.server.active');
        Route::delete('/delete/serve/{id}', 'deleteServe')->name('email.serve.delete');
        Route::post('/delete/all/server', 'deleteAllServer')->name('email.server.delete_all');
    });

    Route::controller(EmailTemplateController::class)->prefix('email')->group(function () {
        Route::get('body', 'emailBody')->name('email.body');
        Route::get('/view/{id}', 'view')->name('email.body.view');
        Route::post('body', 'emailBodyStore')->name('email.body-format.store');
        Route::get('/important/body/{id}/{flag}', 'importantBody')->name('email.body.important');
        Route::post('/delete/all/body', 'deleteAllBody')->name('email.body.delete_all');
        Route::delete('/delete/body/{id}', 'deleteBody')->name('email.body.delete');
    });

    Route::controller(CustomEmailController::class)->prefix('email')->group(function () {
        Route::get('/index', 'index')->name('email.index');
        Route::post('/send', 'send')->name('email.send');
        Route::get('/important/{id}/{flag}', 'important')->name('email.important');
        Route::post('/delete/all', 'delete_all')->name('email.delete_all');
        Route::delete('/delete/{id}', 'delete')->name('email.delete');
    });

    Route::controller(ContactController::class)->prefix('contacts/settings/')->group(function () {
        Route::get('/index', 'index')->name('contacts.index');
    });

    Route::controller(ContactGroupController::class)->prefix('contacts/group/')->group(function () {
        Route::get('/index', 'index')->name('contacts.group.index');
        Route::post('/store', 'store')->name('contacts.group.store');
        Route::post('/update', 'update')->name('contacts.group.update');
        Route::get('/edit/{id}', 'edit')->name('contacts.group.edit');
        Route::delete('/destroy/{id}', 'destroy')->name('contacts.group.destroy');
    });

    Route::controller(NumberController::class)->prefix('contacts/number/')->group(function () {
        Route::get('/index', 'index')->name('contacts.number.index');
        Route::post('/store', 'store')->name('contacts.number.store');
        Route::post('/update', 'update')->name('contacts.number.update');
        Route::get('/edit/{id}', 'edit')->name('contacts.number.edit');
        Route::delete('/destroy/{id}', 'destroy')->name('contacts.number.destroy');
    });
});
