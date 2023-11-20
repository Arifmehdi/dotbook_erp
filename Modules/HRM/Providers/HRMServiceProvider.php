<?php

namespace Modules\HRM\Providers;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\HRM\Entities\Setting;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\AttendanceRapidUpdateServiceInterface;
use Modules\HRM\Interface\AttendanceServiceInterface;
use Modules\HRM\Interface\AwardServiceInterface;
use Modules\HRM\Interface\CalculationCheckerServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\ELCalculationServiceInterface;
use Modules\HRM\Interface\ELPaymentServiceInterface;
use Modules\HRM\Interface\ELServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\EmployeeTaxAdjustmentServiceInterface;
use Modules\HRM\Interface\FinalSettlementServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\HolidayEventServiceInterface;
use Modules\HRM\Interface\HolidayServiceInterface;
use Modules\HRM\Interface\InterviewQuestionServiceInterface;
use Modules\HRM\Interface\InterviewScheduleServiceInterface;
use Modules\HRM\Interface\InterviewServiceInterface;
use Modules\HRM\Interface\JobCardServiceInterface;
use Modules\HRM\Interface\JobCardSummaryServiceInterface;
use Modules\HRM\Interface\LeaveApplicationReportServiceInterface;
use Modules\HRM\Interface\LeaveApplicationRepositoryInterface;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Interface\LeaveRegisterServiceInterface;
use Modules\HRM\Interface\LeaveServiceInterface;
// repository
use Modules\HRM\Interface\LeaveTypeServiceInterface;
use Modules\HRM\Interface\NoticeServiceInterface;
use Modules\HRM\Interface\OffDaysRepositoryInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;
use Modules\HRM\Interface\OrganogramServiceInterface;
use Modules\HRM\Interface\OvertimeAdjustmentServiceInterface;
use Modules\HRM\Interface\PaymentTypesServiceInterface;
use Modules\HRM\Interface\PromotionServiceInterface;
use Modules\HRM\Interface\RecruitmentServiceInterface;
use Modules\HRM\Interface\SalaryAdjustmentServiceInterface;
use Modules\HRM\Interface\SalaryAdvanceServiceInterface;
use Modules\HRM\Interface\SalaryListServiceInterface;
use Modules\HRM\Interface\SalaryServiceInterface;
use Modules\HRM\Interface\SalarySettlementServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\SettingServiceInterface;
use Modules\HRM\Interface\ShiftAdjustmentServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Modules\HRM\Interface\VisitServiceInterface;
use Modules\HRM\Repositories\LeaveApplicationRepository;
use Modules\HRM\Repositories\OffDaysRepository;
use Modules\HRM\Service\ArrivalService;
use Modules\HRM\Service\AttendanceRapidUpdateService;
use Modules\HRM\Service\AttendanceService;
use Modules\HRM\Service\AwardService;
use Modules\HRM\Service\CalculationCheckerService;
use Modules\HRM\Service\CommonService;
use Modules\HRM\Service\HrmDepartmentService;
use Modules\HRM\Service\DesignationService;
use Modules\HRM\Service\ELCalculationService;
use Modules\HRM\Service\ELPaymentService;
use Modules\HRM\Service\ELService;
use Modules\HRM\Service\EmployeeService;
use Modules\HRM\Service\EmployeeTaxAdjustmentService;
use Modules\HRM\Service\FinalSettlementService;
use Modules\HRM\Service\GradeService;
use Modules\HRM\Service\HolidayEventService;
use Modules\HRM\Service\HolidayService;
use Modules\HRM\Service\InterviewQuestionService;
use Modules\HRM\Service\InterviewScheduleService;
use Modules\HRM\Service\InterviewService;
use Modules\HRM\Service\JobCardService;
use Modules\HRM\Service\JobCardSummaryService;
use Modules\HRM\Service\LeaveApplicationReportService;
use Modules\HRM\Service\LeaveApplicationService;
use Modules\HRM\Service\LeaveRegisterService;
use Modules\HRM\Service\LeaveService;
use Modules\HRM\Service\LeaveTypeService;
use Modules\HRM\Service\NoticeService;
use Modules\HRM\Service\OffDayService;
use Modules\HRM\Service\OrganogramService;
use Modules\HRM\Service\OvertimeAdjustmentService;
use Modules\HRM\Service\PaymentTypeService;
use Modules\HRM\Service\PromotionService;
use Modules\HRM\Service\RecruitmentService;
use Modules\HRM\Service\SalaryAdjustmentService;
use Modules\HRM\Service\SalaryAdvanceService;
use Modules\HRM\Service\SalaryListService;
use Modules\HRM\Service\SalaryService;
use Modules\HRM\Service\SalarySettlementService;
use Modules\HRM\Service\SectionService;
use Modules\HRM\Service\SettingService;
use Modules\HRM\Service\ShiftAdjustmentService;
use Modules\HRM\Service\ShiftService;
use Modules\HRM\Service\SubSectionService;
use Modules\HRM\Service\VisitService;

class HRMServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'HRM';

    /**
     * @var string
     */
    protected $moduleNameLower = 'hrm';

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

        if (Schema::connection('mysql')->hasTable('general_settings')) {
            $settings = GeneralSetting::first();
            view()->share('settings', $settings);
        }

        if (Schema::connection('hrm')->hasTable('settings')) {
            // $hrm_settings = Setting::select('id','key','value')->orderBy('id')->get()->toArray();
            $hrm_settings = Setting::select('id', 'key', 'value')->orderBy('id')->get()->toArray();
            view()->share('hrm_settings', $hrm_settings);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(CommonServiceInterface::class, CommonService::class);
        $this->app->bind(HrmDepartmentServiceInterface::class, HrmDepartmentService::class);
        $this->app->bind(GradeServiceInterface::class, GradeService::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(LeaveTypeServiceInterface::class, LeaveTypeService::class);
        $this->app->bind(SectionServiceInterface::class, SectionService::class);
        $this->app->bind(SubSectionServiceInterface::class, SubSectionService::class);
        $this->app->bind(DesignationServiceInterface::class, DesignationService::class);
        $this->app->bind(ShiftServiceInterface::class, ShiftService::class);
        $this->app->bind(ShiftAdjustmentServiceInterface::class, ShiftAdjustmentService::class);
        $this->app->bind(HolidayServiceInterface::class, HolidayService::class);
        $this->app->bind(HolidayEventServiceInterface::class, HolidayEventService::class);
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);
        $this->app->bind(OrganogramServiceInterface::class, OrganogramService::class);
        $this->app->bind(LeaveApplicationServiceInterface::class, LeaveApplicationService::class);
        $this->app->bind(LeaveApplicationServiceInterface::class, LeaveApplicationService::class);
        $this->app->bind(ELPaymentServiceInterface::class, ELPaymentService::class);
        $this->app->bind(PaymentTypesServiceInterface::class, PaymentTypeService::class);
        $this->app->bind(PromotionServiceInterface::class, PromotionService::class);
        $this->app->bind(ELCalculationServiceInterface::class, ELCalculationService::class);
        $this->app->bind(AwardServiceInterface::class, AwardService::class);
        $this->app->bind(SalaryAdjustmentServiceInterface::class, SalaryAdjustmentService::class);
        $this->app->bind(OvertimeAdjustmentServiceInterface::class, OvertimeAdjustmentService::class);
        $this->app->bind(EmployeeTaxAdjustmentServiceInterface::class, EmployeeTaxAdjustmentService::class);
        $this->app->bind(VisitServiceInterface::class, VisitService::class);
        $this->app->bind(ArrivalServiceInterface::class, ArrivalService::class);
        $this->app->bind(AttendanceServiceInterface::class, AttendanceService::class);
        $this->app->bind(SalarySettlementServiceInterface::class, SalarySettlementService::class);
        $this->app->bind(NoticeServiceInterface::class, NoticeService::class);
        $this->app->bind(LeaveApplicationRepositoryInterface::class, LeaveApplicationRepository::class);
        $this->app->bind(LeaveApplicationReportServiceInterface::class, LeaveApplicationReportService::class);
        $this->app->bind(OffDaysRepositoryInterface::class, OffDaysRepository::class);
        $this->app->bind(OffDaysServiceInterface::class, OffDayService::class);
        $this->app->bind(SalaryAdvanceServiceInterface::class, SalaryAdvanceService::class);
        $this->app->bind(JobCardServiceInterface::class, JobCardService::class);
        $this->app->bind(AttendanceRapidUpdateServiceInterface::class, AttendanceRapidUpdateService::class);
        $this->app->bind(LeaveServiceInterface::class, LeaveService::class);
        $this->app->bind(LeaveRegisterServiceInterface::class, LeaveRegisterService::class);
        $this->app->bind(JobCardSummaryServiceInterface::class, JobCardSummaryService::class);
        $this->app->bind(SalaryListServiceInterface::class, SalaryListService::class);
        $this->app->bind(SalaryServiceInterface::class, SalaryService::class);
        $this->app->bind(FinalSettlementServiceInterface::class, FinalSettlementService::class);
        $this->app->bind(ELServiceInterface::class, ELService::class);
        $this->app->bind(CalculationCheckerServiceInterface::class, CalculationCheckerService::class);
        $this->app->bind(RecruitmentServiceInterface::class, RecruitmentService::class);
        $this->app->bind(InterviewServiceInterface::class, InterviewService::class);
        $this->app->bind(InterviewScheduleServiceInterface::class, InterviewScheduleService::class);
        $this->app->bind(InterviewQuestionServiceInterface::class, InterviewQuestionService::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
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
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
