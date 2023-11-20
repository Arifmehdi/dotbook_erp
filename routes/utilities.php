<?php

use App\Http\Controllers\CalenderController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Report\UserActivityLogReportController;
use App\Http\Controllers\Utilities\DownloadManagerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'utilities'], function () {
    Route::group(['prefix' => 'user/activities/log'], function () {
        Route::get('/', [UserActivityLogReportController::class, 'index'])->name('reports.user.activities.log.index');
    });
});

Route::group(['prefix' => 'utilities/feedback', 'as' => 'feedback.'], function () {
    Route::get('/index', [FeedbackController::class, 'index'])->name('index');
    Route::post('/store', [FeedbackController::class, 'store'])->name('store');
});

Route::group(['prefix' => 'utilities/download/center', 'as' => 'downloads.'], function () {
    Route::get('download', [DownloadManagerController::class, 'index'])->name('download.index');
    Route::post('download/store', [DownloadManagerController::class, 'store'])->name('download.store');
    Route::post('download/update/{id}', [DownloadManagerController::class, 'update'])->name('download.update');
    Route::get('download/edit/{id}', [DownloadManagerController::class, 'edit'])->name('download.edit');
    Route::delete('download/delete/{id}', [DownloadManagerController::class, 'destroy'])->name('download.destroy');
    Route::get('download/delete/{id}', [DownloadManagerController::class, 'view'])->name('download.view');
});

Route::group(['/prefix' => 'change_log', 'as' => 'change_log.'], function () {
    Route::prefix('utilities')->group(function () {
        Route::get('/index', [ChangelogController::class, 'index'])->name('index');
        Route::post('/store', [ChangelogController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ChangelogController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ChangelogController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ChangelogController::class, 'delete'])->name('delete');
        Route::get('/show/{id}', [ChangelogController::class, 'show'])->name('show');
    });
});

Route::group(['prefix' => 'utilities/database-backup', 'as' => 'database-backup.'], function () {
    Route::get('/index', [DatabaseBackupController::class, 'databaseBackup'])->name('index');
});

Route::group(['prefix' => 'calender', 'as' => 'calender.'], function () {
    Route::get('/index', [CalenderController::class, 'index'])->name('index');
});
