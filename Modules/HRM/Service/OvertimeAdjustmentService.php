<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\OvertimeAdjustment;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\OvertimeAdjustmentServiceInterface;

class OvertimeAdjustmentService implements OvertimeAdjustmentServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_index'), 403, 'Access Forbidden');
        $items = OvertimeAdjustment::orderBy('id', 'desc')->get();

        return $items;
    }

    // public function store($request)
    public function store(array $attributes)
    {

        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_create'), 403, 'Access Forbidden');

        $minute_to_hour = $attributes['ot_minutes'];
        $only_hour = explode('.', $minute_to_hour);
        $array_count = count($only_hour);
        if ($array_count > 1) {
            $hour_convert = $only_hour[0] * 60;
            $total_time = $hour_convert + $only_hour[1];
        } else {
            // $minutes = $attributes['ot_minutes'];

            // $hours = floor($minutes / 60);
            // $minute = $minutes - ($hours * 60);
            // $total_time = $hours.".".$minute;
            $total_time = $attributes['ot_minutes'];

        }

        $leaveApplication = OvertimeAdjustment::create([
            'employee_id' => $attributes['employee_id'],
            'type' => $attributes['type'],
            'ot_minutes' => $total_time,
            'month' => $attributes['month'],
            'year' => $attributes['year'],
            'description' => $attributes['description'],
        ]);

        return $leaveApplication;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_update'), 403, 'Access Forbidden');

        $minute_to_hour = $attributes['ot_minutes'];
        $only_hour = explode('.', $minute_to_hour);
        $array_count = count($only_hour);
        if ($array_count > 1) {
            $hour_convert = $only_hour[0] * 60;
            $total_time = $hour_convert + $only_hour[1];
        } else {
            // $minutes = $attributes['ot_minutes'];
            // $hours = floor($minutes / 60);
            // $minute = $minutes - ($hours * 60);
            // $total_time = $hours.".".$minute;
            $total_time = $attributes['ot_minutes'];
        }

        $overtimeAdjustmentService = OvertimeAdjustment::find($id);
        $updatedOvertimeAdjustmentService = $overtimeAdjustmentService->update([
            'employee_id' => $attributes['employee_id'],
            'type' => $attributes['type'],
            'ot_minutes' => $total_time,
            'month' => $attributes['month'],
            'year' => $attributes['year'],
            'description' => $attributes['description'],
        ]);

        return $updatedOvertimeAdjustmentService;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_view'), 403, 'Access Forbidden');
        $leaveApplication = OvertimeAdjustment::find($id);

        return $leaveApplication;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_delete'), 403, 'Access Forbidden');
        $item = OvertimeAdjustment::find($id);
        $item->delete($item);

        return $item;
    }

    public function allActiveEmployee()
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_index'), 403, 'Access Forbidden');
        $query = Employee::where('employment_status', EmploymentStatus::Active);
        //filter add here
        // if ($request->hrm_department_id) {
        //     $query->where('hrm_department_id', $request->hrm_department_id);
        // }
        // if ($request->shift_id) {
        //     $query->where('shift_id', $request->shift_id);
        // }
        // if ($request->designation_id) {
        //     $query->where('designation_id', $request->designation_id);
        // }
        // if ($request->grade_id) {
        //     $query->where('grade_id', $request->grade_id);
        // }
        // if ($request->employment_status && null != $request->employment_status) {
        //     $query->where('employment_status', $request->employment_status);
        // }
        // if ($request->date_range) {
        //     $date_range = explode('-', $request->date_range);
        //     $form_date = date('Y-m-d', strtotime(trim($date_range[0])));
        //     $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
        //     $query->whereBetween('joining_date', [$form_date, $to_date]); // Final
        // }
        return $query->get();
    }

    //Get Trashed ItemList
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_index'), 403, 'Access Forbidden');
        $item = OvertimeAdjustment::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = OvertimeAdjustment::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_delete'), 403, 'Access Forbidden');
        $item = OvertimeAdjustment::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = OvertimeAdjustment::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_delete'), 403, 'Access Forbidden');
        $item = OvertimeAdjustment::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = OvertimeAdjustment::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_index'), 403, 'Access Forbidden');
        $count = OvertimeAdjustment::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_index'), 403, 'Access Forbidden');
        $count = OvertimeAdjustment::onlyTrashed()->count();

        return $count;
    }

    public function employeeFilter($request)
    {
        abort_if(! auth()->user()->can('hrm_overtimeAdjustments_index'), 403, 'Access Forbidden');
        $query = OvertimeAdjustment::orderBy('id', 'desc');
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->month) {
            $query->where('month', $request->month);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('created_at', [$form_date, $to_date]); // Final
        }

        return $query->get();
    }
}
