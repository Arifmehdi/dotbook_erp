<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Promotion;
use Modules\HRM\Entities\Section;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\PromotionServiceInterface;

class PromotionService implements PromotionServiceInterface
{
    public function all()
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $promotion = Promotion::all();

        return $promotion;
    }

    public function promoteEmployeeBuilder()
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $promotion = Promotion::orderBy('id', 'desc');

        return $promotion;
    }

    public function promoteEmployeeListAfterSort($request)
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $promotion = Promotion::query()
            ->leftJoin('employees', 'promotions.employee_id', 'employees.id')
            ->leftJoin('hrm_departments', 'promotions.new_hrm_department_id', 'hrm_departments.id')
            ->leftJoin('sections', 'promotions.new_section_id', 'sections.id')
            ->leftJoin('sub_sections', 'promotions.new_subsection_id', 'sub_sections.id')
            ->leftJoin('designations', 'promotions.new_designation_id', 'designations.id');
        // ->leftJoin(config('database.connections.mysql.database') .'.users', 'promotions.user_id', config('database.connections.mysql.database') . '.users.id');

        if ($request->employee_id) {
            $employee = $promotion->where('promotions.employee_id', $request->employee_id);
        }
        if ($request->hrm_department_id) {
            $employee = $promotion->where('promotions.new_hrm_department_id', $request->hrm_department_id)->get();
        }

        if ($request->designation_id) {
            $employee = $promotion->where('promotions.new_designation_id', $request->designation_id)->get();
        }

        if ($request->section_id) {
            $employee = $promotion->where('promotions.new_section_id', $request->section_id)->get();
        }

        if ($request->promoted_by) {
            $employee = $promotion->where('promotions.user_id', $request->promoted_by)->get();
        }

        $start_date = date('Y-m-d', strtotime('-1month'));
        $end_date = date('Y-m-d');

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $start_date = $form_date;
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $promotion->whereBetween('promotions.promoted_date', [$form_date, $to_date]);
            $end_date = $to_date;
        }
        $promotion->select(
            'promotions.id as promotion_id',
            'promotions.user_id',
            'employees.employee_id',
            'employees.name as employee_name',
            'hrm_departments.name as department_name',
            'sections.name as section_name',
            'sub_sections.name as subsection_name',
            'designations.name as designation_name',
            // config('database.connections.mysql.database') . '.users.name as promoted_by',
            'promotions.promoted_date as promoted_date',
        )->orderBy('promotions.promoted_date', 'desc');

        return $promotion;
    }

    public function getTrashedItem()
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $promotions = Promotion::query()
            ->whereNotNull('promotions.deleted_at')
            ->leftJoin('employees', 'promotions.employee_id', 'employees.id')
            ->leftJoin('hrm_departments', 'promotions.new_hrm_department_id', 'hrm_departments.id')
            ->leftJoin('sections', 'promotions.new_section_id', 'sections.id')
            ->leftJoin('sub_sections', 'promotions.new_subsection_id', 'sub_sections.id')
            ->leftJoin('designations', 'promotions.new_designation_id', 'designations.id')
            // ->leftJoin(config('database.connections.mysql.database') .'.users', 'promotions.user_id', config('database.connections.mysql.database') . '.users.id')
            ->select(
                'promotions.id as promotion_id',
                'promotions.user_id',
                'employees.employee_id',
                'employees.name as employee_name',
                'hrm_departments.name as department_name',
                'sections.name as section_name',
                'sub_sections.name as subsection_name',
                'designations.name as designation_name',
                // config('database.connections.mysql.database') . '.users.name as promoted_by',
                'promotions.promoted_date as promoted_date',
            )->orderBy('promotions.promoted_date', 'desc')
            ->get();

        return $promotions;
    }

    public function store(array $attributes)
    {
        abort_if(!auth()->user()->can('hrm_promotion_create'), 403, 'Access Forbidden');
        $section_id = $attributes['new_section_id'];
        $employee_id = $attributes['employee_id'];

        $section = Section::find($section_id);
        $employee = Employee::find($employee_id);
        // Previous employee state
        $previousState['previous_hrm_department_id'] = $employee->hrm_department_id;
        $previousState['previous_section_id'] = $employee->section_id;
        $previousState['previous_sub_section_id'] = $employee->sub_section_id;
        $previousState['previous_designation_id'] = $employee->designation_id;

        // New updated data after promotion
        $employee_data = [];
        $employee_data['hrm_department_id'] = $section->hrm_department_id;
        $employee_data['section_id'] = $section->id;
        $employee_data['sub_section_id'] = $attributes['new_subsection_id'];
        $employee_data['designation_id'] = $attributes['new_designation_id'];
        $employee->update($employee_data); // Update employee from new promotion data.

        $newData = [];
        $newData['employee_id'] = $employee_id;
        $newData['previous_hrm_department_id'] = $previousState['previous_hrm_department_id'];
        $newData['previous_section_id'] = $previousState['previous_section_id'];
        $newData['previous_subsection_id'] = $attributes['new_subsection_id'];
        $newData['previous_designation_id'] = $previousState['previous_designation_id'];
        $newData['new_hrm_department_id'] = $attributes['new_hrm_department_id'];
        $newData['new_section_id'] = $attributes['new_section_id'];
        $newData['new_subsection_id'] = $attributes['new_subsection_id'];
        $newData['new_designation_id'] = $attributes['new_designation_id'];
        $newData['user_id'] = $attributes['user_id'];
        $newData['promoted_date'] = $attributes['promoted_date'];

        $promotion = Promotion::create($newData);

        return $promotion;
    }

    public function find(int $id)
    {
        abort_if(!auth()->user()->can('hrm_promotion_view'), 403, 'Access Forbidden');
        $promotion = Promotion::findOrFail($id);

        return $promotion;
    }

    public function getSectionByHrmDepartment(array $id)
    {
        abort_if(!auth()->user()->can('hrm_promotion_view'), 403, 'Access Forbidden');
        $promotion = Promotion::where('hrm_department_id', $id)->get();

        return $promotion;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(!auth()->user()->can('hrm_promotion_update'), 403, 'Access Forbidden');
        $section = Promotion::find($id);
        $updatedSection = $section->update($attributes);

        return $updatedSection;
    }

    public function getActiveItem()
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $employees = Employee::where('employment_status', EmploymentStatus::Active)->get();

        return $employees;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(!auth()->user()->can('hrm_promotion_delete'), 403, 'Access Forbidden');
        $item = Promotion::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_promotion_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Promotion::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(!auth()->user()->can('hrm_promotion_delete'), 403, 'Access Forbidden');
        $item = Promotion::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_promotion_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Promotion::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(!auth()->user()->can('hrm_promotion_delete'), 403, 'Access Forbidden');
        $item = Promotion::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_promotion_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Promotion::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $count = Promotion::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(!auth()->user()->can('hrm_promotion_index'), 403, 'Access Forbidden');
        $count = Promotion::onlyTrashed()->count();

        return $count;
    }
}
