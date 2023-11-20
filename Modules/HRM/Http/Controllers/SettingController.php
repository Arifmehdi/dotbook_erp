<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\FileUploaderServiceInterface;
use Illuminate\Contracts\Support\Renderable;
use Modules\HRM\Http\Requests\Settings\CardSettingsRequest;
use Modules\HRM\Http\Requests\Settings\ColorSettingsRequest;
use Modules\HRM\Http\Requests\Settings\GeneralSettingsRequest;
use Modules\HRM\Http\Requests\Settings\PayrollSettingsRequest;
use Modules\HRM\Interface\SettingServiceInterface;

class SettingController extends Controller
{
    private $settingsService;

    private $uploader;

    public function __construct(SettingServiceInterface $settingsService, FileUploaderServiceInterface $uploader)
    {
        $this->settingsService = $settingsService;
        $this->uploader = $uploader;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $settings = $this->settingsService->all();

        return view('hrm::settings.index', compact('settings'));
    }
    
    public function updateCardSetting(CardSettingsRequest $request)
    {
        $cardSettingAttribute = $request->validated();
        $this->settingsService->updateIdCardSetting($cardSettingAttribute);

        return response()->json('Card settings Successfully Updated!');
    }

    public function updatePayrollSetting(PayrollSettingsRequest $request)
    {
        $payrollSettingAttribute = $request->validated();
        $this->settingsService->updatePayrollSetting($payrollSettingAttribute);

        return response()->json('Payroll settings Successfully Updated!');
    }

    public function upgradePlan()
    {
        return view('hrm::upgrade_plan.index');
    }
}
