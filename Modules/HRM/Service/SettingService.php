<?php

namespace Modules\HRM\Service;

use App\Interface\FileUploaderServiceInterface;
use Modules\HRM\Entities\Setting;
use Modules\HRM\Interface\SettingServiceInterface;

class SettingService implements SettingServiceInterface
{
    private $uploader;

    public function __construct(FileUploaderServiceInterface $uploader)
    {
        $this->uploader = $uploader;
    }
    public function updateIdCardSetting($cardSettingAttribute)
    {
        abort_if(! auth()->user()->can('hrm_settings_update'), 403, 'Access forbidden');
        foreach ($cardSettingAttribute as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (isset($setting)) {
                if ($key == 'id_card_settings__footer_right_signature_image') {
                    if (isset($key) && ! empty($key) && file_exists('uploads/hrm/settings/'.$cardSettingAttribute['old_signature'])) {
                        unlink(public_path('uploads/hrm/settings/'.$cardSettingAttribute['old_signature']));
                    }
                    $image = $this->uploader->upload($cardSettingAttribute['id_card_settings__footer_right_signature_image'], 'uploads/hrm/settings/');

                    $data['key'] = 'id_card_settings__footer_right_signature_image';
                    $data['value'] = $image;
                } else {
                    $data = [
                        'key' => $key,
                        'value' => $value,
                    ];
                }
                $setting->update($data);
            }
        }
    }

    public function updatePayrollSetting($payrollSettingAttribute)
    {
        abort_if(! auth()->user()->can('hrm_settings_update'), 403, 'Access forbidden');
        foreach ($payrollSettingAttribute as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            $data = [
                'key' => $key,
                'value' => $value,
            ];

            if (isset($setting)) {
                if ($key == 'payroll_settings__prepared_by_signature') {

                    if (isset($key) && ! empty($key) && file_exists('uploads/hrm/settings/'.$payrollSettingAttribute['old_prepared_by_signature'])) {

                        unlink(public_path('uploads/hrm/settings/'.$payrollSettingAttribute['old_prepared_by_signature']));
                    }

                    $image1 = $this->uploader->upload($payrollSettingAttribute['payroll_settings__prepared_by_signature'], 'uploads/hrm/settings/');

                    $data['key'] = 'payroll_settings__prepared_by_signature';
                    $data['value'] = $image1;
                }
                if ($key == 'payroll_settings__checked_by_signature') {

                    if (isset($key) && ! empty($key) && file_exists('uploads/hrm/settings/'.$payrollSettingAttribute['old_checked_by_signature'])) {

                        unlink(public_path('uploads/hrm/settings/'.$payrollSettingAttribute['old_checked_by_signature']));
                    }
                    $image2 = $this->uploader->upload($payrollSettingAttribute['payroll_settings__checked_by_signature'], 'uploads/hrm/settings/');

                    $data['key'] = 'payroll_settings__checked_by_signature';
                    $data['value'] = $image2;
                }

                if ($key == 'payroll_settings__approved_by_signature') {

                    if (isset($key) && ! empty($key) && file_exists('uploads/hrm/settings/'.$payrollSettingAttribute['old_approved_by_signature'])) {

                        unlink(public_path('uploads/hrm/settings/'.$payrollSettingAttribute['old_approved_by_signature']));
                    }
                    $image3 = $this->uploader->upload($payrollSettingAttribute['payroll_settings__approved_by_signature'], 'uploads/hrm/settings/');

                    $data['key'] = 'payroll_settings__approved_by_signature';
                    $data['value'] = $image3;
                }

                $setting->update($data);
            }
        }
    }

    public function all()
    {
        abort_if(! auth()->user()->can('hrm_settings_index'), 403, 'Access forbidden');
        $setting = Setting::pluck('value', 'key')->toArray();

        return $setting;
    }

    public function getSettingsType($type)
    {

        abort_if(! auth()->user()->can('hrm_settings_index'), 403, 'Access forbidden');
        $setting = Setting::select('value')->where('key', '=', $type)->first();

        return json_decode($setting->value);
    }

    public function update($request, $id)
    {
        abort_if(! auth()->user()->can('hrm_settings_update'), 403, 'Access forbidden');
        $setting = Setting::find($id);
        $updatedSetting = $setting->update($request->validated());

        return $updatedSetting;
    }
}
