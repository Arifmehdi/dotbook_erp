<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Event;
use Modules\HRM\Interface\HolidayEventServiceInterface;

class HolidayEventService implements HolidayEventServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_holidays_index'), 403, 'Access Forbidden');
        $items = Event::orderBy('id', 'desc')->get();

        return $items;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_holidays_index'), 403, 'Access Forbidden');
        $item = Event::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_holidays_create'), 403, 'Access Forbidden');
        $item = Event::create($attributes);

        return $item;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_view'), 403, 'Access Forbidden');
        $item = Event::find($id);

        return $item;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_update'), 403, 'Access Forbidden');
        $item = Event::find($id);
        $updatedItem = $item->update($attributes);

        return $updatedItem;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_delete'), 403, 'Access Forbidden');
        $item = Event::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_holidays_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Event::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_delete'), 403, 'Access Forbidden');
        $item = Event::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_holidays_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Event::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_holidays_delete'), 403, 'Access Forbidden');
        $item = Event::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_holidays_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Event::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_holidays_index'), 403, 'Access Forbidden');
        $count = Event::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_holidays_index'), 403, 'Access Forbidden');
        $count = Event::onlyTrashed()->count();

        return $count;
    }
}
