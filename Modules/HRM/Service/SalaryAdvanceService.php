<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Advance;
use Modules\HRM\Interface\SalaryAdvanceServiceInterface;

class SalaryAdvanceService implements SalaryAdvanceServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_index'), 403, 'Access Forbidden');
        $items = Advance::orderBy('id', 'desc')->get();

        return $items;
    }

    // public function store($request)
    public function store(array $attributes)
    {

        abort_if(! auth()->user()->can('hrm_salary_advances_create'), 403, 'Access Forbidden');
        $leaveApplication = Advance::create($attributes);

        return $leaveApplication;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_update'), 403, 'Access Forbidden');
        $leaveApplication = Advance::find($id);
        $updatedLeaveApplication = $leaveApplication->update($attributes);

        return $updatedLeaveApplication;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_view'), 403, 'Access Forbidden');
        $leaveApplication = Advance::find($id);

        return $leaveApplication;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_delete'), 403, 'Access Forbidden');
        $item = Advance::find($id);
        $item->delete($item);

        return $item;
    }

    //Get Trashed ItemList
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_index'), 403, 'Access Forbidden');
        $item = Advance::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Advance::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_delete'), 403, 'Access Forbidden');
        $item = Advance::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Advance::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_delete'), 403, 'Access Forbidden');
        $item = Advance::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Advance::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_index'), 403, 'Access Forbidden');
        $count = Advance::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_index'), 403, 'Access Forbidden');
        $count = Advance::onlyTrashed()->count();

        return $count;
    }

    public function employeeFilter($request)
    {
        abort_if(! auth()->user()->can('hrm_salary_advances_index'), 403, 'Access Forbidden');
        $query = Advance::orderBy('id', 'desc');
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
