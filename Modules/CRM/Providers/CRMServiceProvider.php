<?php

namespace Modules\CRM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CRM\Interfaces\AppointmentServiceInterface;
use Modules\CRM\Interfaces\BusinessLeadServiceInterface;
use Modules\CRM\Interfaces\FileUploaderServiceInterface;
use Modules\CRM\Interfaces\FollowupServiceInterface;
use Modules\CRM\Interfaces\IndividualLeadServiceInterface;
use Modules\CRM\Interfaces\LeadServiceInterface;
use Modules\CRM\Interfaces\ProposalServiceInterface;
use Modules\CRM\Interfaces\SourceServiceInterface;
use Modules\CRM\Interfaces\SubscriptionServiceInterface;
use Modules\CRM\Interfaces\TaskServiceInterface;
use Modules\CRM\Services\AppointmentService;
use Modules\CRM\Services\BusinessLeadService;
use Modules\CRM\Services\FileUploaderService;
use Modules\CRM\Services\FollowupService;
use Modules\CRM\Services\IndividualLeadService;
use Modules\CRM\Services\LeadService;
use Modules\CRM\Services\ProposalService;
use Modules\CRM\Services\SourceService;
use Modules\CRM\Services\SubscriptionService;
use Modules\CRM\Services\TaskService;

class CRMServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'CRM';

    /**
     * @var string
     */
    protected $moduleNameLower = 'crm';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(AppointmentServiceInterface::class, AppointmentService::class);
        $this->app->bind(LeadServiceInterface::class, LeadService::class);
        $this->app->bind(SourceServiceInterface::class, SourceService::class);
        $this->app->bind(IndividualLeadServiceInterface::class, IndividualLeadService::class);
        $this->app->bind(BusinessLeadServiceInterface::class, BusinessLeadService::class);
        $this->app->bind(FileUploaderServiceInterface::class, FileUploaderService::class);
        $this->app->bind(ProposalServiceInterface::class, ProposalService::class);
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(FollowupServiceInterface::class, FollowupService::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower.'.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
