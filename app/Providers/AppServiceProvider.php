<?php

namespace App\Providers;

use App\Interface\CodeGenerationServiceInterface;
use App\Interface\EmailServiceInterface;
use App\Interface\FileUploaderServiceInterface;
use App\Interface\SmsServiceInterface;
use App\Service\CodeGenerationService;
use App\Service\EmailService;
use App\Service\FileUploaderService;
use App\Service\SmsService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(CodeGenerationServiceInterface::class, CodeGenerationService::class);
        app()->bind(FileUploaderServiceInterface::class, FileUploaderService::class);
        app()->bind(SmsServiceInterface::class, SmsService::class);
        app()->bind(EmailServiceInterface::class, EmailService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
            $platform->registerDoctrineTypeMapping('enum', 'string');
        }

        try {

            $generalSettings = DB::table('general_settings')->first();
            $addons = DB::table('addons')->first();
            if (isset($generalSettings) && isset($addons)) {
                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);
                view()->share('generalSettings', $generalSettings);
                view()->share('addons', $addons);
                view()->share('__date_format', $__date_format);
            }
        } catch (Exception $e) {
            // echo $e->getMessage() . PHP_EOL;
        }
    }
}
