<?php

namespace Modules\Core\Service;

use Modules\Core\Entities\BdDivision;
use Modules\Core\Interface\BdDivisionServiceInterface;

class BdDivisionService implements BdDivisionServiceInterface
{
    public function all()
    {
        $items = BdDivision::orderBy('id', 'desc')->get();

        return $items;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        $item = BdDivision::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store($request)
    {
        $division = BdDivision::create($request);

        return $division;
    }

    public function find($id)
    {
        $item = BdDivision::find($id);

        return $item;
    }

    public function update($division, $id)
    {
        $item = BdDivision::find($id);
        $updatedItem = $item->update($division);

        return $updatedItem;
    }

    //Move To Trash
    public function trash($id)
    {
        $item = BdDivision::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('hrm_divisions_delete'), 403);
        foreach ($ids as $id) {
            $item = BdDivision::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete($id)
    {
        $item = BdDivision::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete($ids)
    {
        abort_if(! auth()->user()->can('hrm_divisions_delete'), 403);
        foreach ($ids as $id) {
            $item = BdDivision::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore($id)
    {
        $item = BdDivision::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('hrm_divisions_delete'), 403);
        foreach ($ids as $id) {
            $item = BdDivision::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        $count = BdDivision::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        $count = BdDivision::onlyTrashed()->count();

        return $count;
    }
}
