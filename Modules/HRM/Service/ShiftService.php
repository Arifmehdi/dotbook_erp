<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Shift;
use Modules\HRM\Interface\ShiftServiceInterface;

class ShiftService implements ShiftServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_shifts_index'), 403, 'Access Forbidden');
        $items = Shift::orderBy('id', 'desc')->get();

        return $items;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_shifts_index'), 403, 'Access Forbidden');
        $item = Shift::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_shifts_create'), 403, 'Access Forbidden');
        $item = Shift::create($attributes);

        return $item;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_shifts_view'), 403, 'Access Forbidden');
        $item = Shift::find($id);

        return $item;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_shifts_update'), 403, 'Access Forbidden');
        $shift = Shift::find($id);
        $shift->update($attributes);

        return $shift;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_shifts_delete'), 403, 'Access Forbidden');
        $item = Shift::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_shifts_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Shift::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_shifts_delete'), 403, 'Access Forbidden');
        $item = Shift::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_shifts_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Shift::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {

        abort_if(! auth()->user()->can('hrm_shifts_delete'), 403, 'Access Forbidden');
        $item = Shift::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_shifts_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Shift::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_shifts_index'), 403, 'Access Forbidden');
        $count = Shift::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_shifts_index'), 403, 'Access Forbidden');
        $count = Shift::onlyTrashed()->count();

        return $count;
    }

    public function shiftOptimized()
    {
        abort_if(! auth()->user()->can('hrm_shifts_index'), 403, 'Access Forbidden');
        $items = Shift::orderBy('id', 'desc')->select('id', 'name')->get();

        return $items;
    }
}
