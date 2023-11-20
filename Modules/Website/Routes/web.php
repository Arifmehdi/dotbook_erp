<?php

use Modules\Website\Http\Controllers\BlogCategoriesController;
use Modules\Website\Http\Controllers\BlogController;
use Modules\Website\Http\Controllers\CampaignController;
use Modules\Website\Http\Controllers\CategoryController;
use Modules\Website\Http\Controllers\ClientController;
use Modules\Website\Http\Controllers\CountryController;
use Modules\Website\Http\Controllers\DashboardController;
use Modules\Website\Http\Controllers\FaqController;
use Modules\Website\Http\Controllers\GalleryCategoriesController;
use Modules\Website\Http\Controllers\GalleryController;
use Modules\Website\Http\Controllers\JobCategoriesController;
use Modules\Website\Http\Controllers\JobController;
use Modules\Website\Http\Controllers\PageController;
use Modules\Website\Http\Controllers\PartnerController;
use Modules\Website\Http\Controllers\ProductController;
use Modules\Website\Http\Controllers\SettingController;
use Modules\Website\Http\Controllers\TestimonialController;
use Modules\Website\Http\Controllers\VideoController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::prefix('manage-blog')->group(function () {
    Route::resource('blog', BlogController::class);
    Route::resource('blog-categories', BlogCategoriesController::class);
    Route::get('/blogs/comments', [BlogController::class, 'blog_comments'])->name('comments');
    Route::get('/comments/status/{id}/{status}', [BlogController::class, 'blog_comments_status'])->name('comments.status');
    Route::post('/comments/delete/{id}', [BlogController::class, 'blog_comments_delete'])->name('comments.delete');
});
Route::prefix('contacts')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('teams', TeamsController::class);
});
Route::get('client/create/basic/modal', [ClientController::class, 'client_create_basic_modal'])->name('client.create.basic.modal');
Route::get('buyer-requisition', [ClientController::class, 'buyerRequisition'])->name('buyer-requisition.index');
Route::get('buyer-requisition/{id}', [ClientController::class, 'buyerRequisitionShow'])->name('buyer-requisition.show');
Route::get('buyer-requisition/destroy/{id}', [ClientController::class, 'buyerRequisitionDestroy'])->name('buyer-requisition.destroy');
Route::prefix('manage-products')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});
Route::prefix('manage-careers')->group(function () {
    Route::resource('job-categories', JobCategoriesController::class);
    Route::resource('jobs', JobController::class);
    Route::get('job-applied', [JobController::class, 'jobApplied'])->name('job-applied.index');
    Route::get('job-applied/download/{id}', [JobController::class, 'jobAppliedDownload'])->name('jobs-applied.download');
    Route::post('job-applied/delete/{id}', [JobController::class, 'jobAppliedDestroy'])->name('jobs-applied.delete');
});

Route::prefix('manage-gallery')->group(function () {
    Route::resource('gallery-categories', GalleryCategoriesController::class);
    Route::resource('gallery', GalleryController::class);
});
Route::prefix('manage-pages')->group(function () {
    Route::resource('pages', PageController::class);
    Route::get('about-us', [PageController::class, 'about_us'])->name('about.us');
    Route::post('about-us-post', [PageController::class, 'aboutusPost'])->name('about.us.post');
    Route::get('history', [PageController::class, 'history'])->name('history');
    Route::post('history/update', [PageController::class, 'historyUpdate'])->name('history.update');
    Route::get('message-of-director', [PageController::class, 'messageDirector'])->name('message.director');
    Route::post('director/update', [PageController::class, 'messageDirectorUpdate'])->name('director.update');
});

Route::prefix('manage-settings')->group(function () {
    Route::get('general-settings', [SettingController::class, 'generalSettings'])->name('general.settings');
    Route::post('general_settings/update', [SettingController::class, 'generalSettingsUpdate'])->name('general_settings.update');

    Route::get('seo-settings', [SettingController::class, 'seoSettings'])->name('seo.settings');
    Route::post('seo_settings/update', [SettingController::class, 'seoSettingsUpdate'])->name('seo_settings.update');

    Route::get('social-link', [SettingController::class, 'socialLink'])->name('social.link');
    Route::post('social/link/update', [SettingController::class, 'socialLinkUpdate'])->name('social.link.update');
    Route::get('contact', [SettingController::class, 'contact'])->name('contact');
    Route::get('contact/delete/{id}', [SettingController::class, 'contactDelete'])->name('contact.delete');
    Route::get('dealership-request', [SettingController::class, 'dealershipRequest'])->name('dealership.request');
    Route::get('dealer/request/delete/{id}', [SettingController::class, 'dealerrequestDelete'])->name('dealer.request.delete');

    Route::resource('testimonial', TestimonialController::class);
    Route::resource('campaign', CampaignController::class);
    Route::resource('award', AwardController::class);
    Route::resource('banner', BannerController::class);
    Route::resource('faq', FaqController::class);
    Route::resource('report', ReportController::class);
    Route::resource('slider', SliderController::class);
    Route::resource('video', VideoController::class);
    Route::resource('country', CountryController::class);
    Route::resource('city', CityController::class);
    Route::resource('faqs', FaqController::class);
});
