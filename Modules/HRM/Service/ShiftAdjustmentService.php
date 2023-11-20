<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\ShiftAdjustment;
use Modules\HRM\Interface\ShiftAdjustmentServiceInterface;

class ShiftAdjustmentService implements ShiftAdjustmentServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_index'), 403, 'Access Forbidden');
        $shiftAdjustment = ShiftAdjustment::with('shift')->orderBy('id', 'desc')->get();
        // $shiftAdjustment = ShiftAdjustment::with('shift')->orderBy('id', 'desc');
        return $shiftAdjustment;
    }

    public function store($request)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_create'), 403, 'Access Forbidden');
        $shiftAdjustment = new ShiftAdjustment();
        $request = $request->validated();

        if (! isset($request['with_break'])) {
            $request['with_break'] = 0;
        }
        $shiftAdjustment->shift_id = $request['shift_id'];
        $shiftAdjustment->start_time = $request['start_time'];

        $shiftAdjustment->end_time = $request['end_time'];
        $shiftAdjustment->late_count = $request['late_count'];
        $shiftAdjustment->applied_date_from = $request['applied_date_from'];
        $shiftAdjustment->applied_date_to = $request['applied_date_to'];
        $shiftAdjustment->with_break = $request['with_break'];
        if ($request['with_break'] == 1) {
            $shiftAdjustment->break_start = $request['break_start'];
            $shiftAdjustment->break_end = $request['break_end'];
        }

        $shiftAdjustment->save();

        return $shiftAdjustment;
    }

    public function find($id)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_view'), 403, 'Access Forbidden');
        $shiftAdjustment = ShiftAdjustment::find($id);

        return $shiftAdjustment;
    }

    public function update($request, $id)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_update'), 403, 'Access Forbidden');
        $shiftAdjustment = ShiftAdjustment::find($id);
        $request = $request->validated();

        if (! isset($request['with_break'])) {
            $request['with_break'] = 0;
        }
        $shiftAdjustment->shift_id = $request['shift_id'];
        $shiftAdjustment->start_time = $request['start_time'];

        $shiftAdjustment->end_time = $request['end_time'];
        $shiftAdjustment->late_count = $request['late_count'];
        $shiftAdjustment->applied_date_from = $request['applied_date_from'];
        $shiftAdjustment->applied_date_to = $request['applied_date_to'];
        $shiftAdjustment->with_break = $request['with_break'];
        if ($request['with_break'] == 1) {
            $shiftAdjustment->break_start = $request['break_start'];
            $shiftAdjustment->break_end = $request['break_end'];
        } else {
            $shiftAdjustment->break_start = null;
            $shiftAdjustment->break_end = null;
        }
        $shiftAdjustment->save();

        return $shiftAdjustment;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_index'), 403, 'Access Forbidden');
        $item = ShiftAdjustment::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash($id)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_delete'), 403, 'Access Forbidden');
        $item = ShiftAdjustment::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = ShiftAdjustment::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete($id)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_delete'), 403, 'Access Forbidden');
        $item = ShiftAdjustment::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete($ids)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = ShiftAdjustment::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore($id)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_delete'), 403, 'Access Forbidden');
        $item = ShiftAdjustment::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = ShiftAdjustment::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_index'), 403, 'Access Forbidden');
        $count = ShiftAdjustment::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_shift_adjustments_index'), 403, 'Access Forbidden');
        $count = ShiftAdjustment::onlyTrashed()->count();

        return $count;
    }
}
