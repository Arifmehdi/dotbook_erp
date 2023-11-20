<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\LeaveApplication;
use Modules\HRM\Interface\LeaveApplicationReportServiceInterface;

class LeaveApplicationReportService implements LeaveApplicationReportServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $items = LeaveApplication::orderBy('id', 'desc')->get();

        return $items;
    }

    public function store(array $attributes)
    {

        abort_if(! auth()->user()->can('hrm_leave_applications_create'), 403, 'Access Forbidden');
        $leaveApplication = LeaveApplication::create($attributes);

        return $leaveApplication;
    }

    public function update(array $attributes, int $id)
    {

        abort_if(! auth()->user()->can('hrm_leave_applications_update'), 403, 'Access Forbidden');
        $leaveApplication = LeaveApplication::find($id);
        $updatedLeaveApplication = $leaveApplication->update($attributes);

        return $updatedLeaveApplication;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_view'), 403, 'Access Forbidden');
        $leaveApplication = LeaveApplication::find($id);

        return $leaveApplication;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = LeaveApplication::find($id);
        $item->delete($item);

        return $item;
    }

    //Get Trashed Item List
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $item = LeaveApplication::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveApplication::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = LeaveApplication::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveApplication::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = LeaveApplication::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveApplication::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $count = LeaveApplication::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $count = LeaveApplication::onlyTrashed()->count();

        return $count;
    }

    public function leaveApplicationFilter($request)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $query = LeaveApplication::orderBy('id', 'desc');
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->type) {
            $query->where('status', $request->type);
        }
        if ($request->leave_type) {
            $query->where('leave_type_id', $request->leave_type);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('from_date', '>=', $from_date);
            $query->where('to_date', '<=', $to_date);
            // $query->whereBetween('created_at', [$form_date, $to_date]); // Final
        }

        return $query;
    }
}
