<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\LeaveType;
use Modules\HRM\Interface\LeaveTypeServiceInterface;

class LeaveTypeService implements LeaveTypeServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_leave_types_index'), 403, 'Access Forbidden');
        $leaveType = LeaveType::orderBy('id', 'desc')->get();

        return $leaveType;
    }

    // public function store($request)
    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_create'), 403, 'Access Forbidden');
        $leaveType = LeaveType::create($attributes);

        return $leaveType;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_view'), 403, 'Access Forbidden');
        $leaveType = LeaveType::find($id);

        return $leaveType;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_update'), 403, 'Access Forbidden');
        $leaveType = LeaveType::find($id);
        $updatedLeaveType = $leaveType->update($attributes);

        return $updatedLeaveType;
    }

    // get allowed leaveType
    public function allowedLeaveType()
    {
        abort_if(! auth()->user()->can('hrm_leave_types_view'), 403, 'Access Forbidden');
        $leaveType = LeaveType::where('is_active', 1)->orderBy('id', 'desc')->get();

        return $leaveType;
    }

    //Get Trashed Itemist
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_leave_types_index'), 403, 'Access Forbidden');
        $item = LeaveType::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_delete'), 403, 'Access Forbidden');
        $item = LeaveType::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveType::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_delete'), 403, 'Access Forbidden');
        $item = LeaveType::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveType::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {

        abort_if(! auth()->user()->can('hrm_leave_types_delete'), 403, 'Access Forbidden');
        $item = LeaveType::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveType::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_types_index'), 403, 'Access Forbidden');
        $count = LeaveType::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_types_index'), 403, 'Access Forbidden');
        $count = LeaveType::onlyTrashed()->count();

        return $count;
    }
}
