<?php

namespace Modules\Core\Service;

use Modules\Core\Entities\BdUpazila;
use Modules\Core\Interface\BdUpazilaServiceInterface;

class BdUpazilaService implements BdUpazilaServiceInterface
{
    public function all(?array $param)
    {
        if (isset($param['district_id'])) {
            $district_id = $param['district_id'];
            $upazilas = BdUpazila::orderBy('id', 'desc')->where('district_id', $district_id)->get();
        } else {
            $upazilas = BdUpazila::orderBy('id', 'desc')->get();
        }

        return $upazilas;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        $item = BdUpazila::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function getUpazilaByDistrict($id)
    {
        $items = BdUpazila::where('district_id', $id)->get();

        return $items;
    }

    public function store($thana)
    {
        $item = BdUpazila::create($thana);

        return $item;
    }

    public function find($id)
    {
        $item = BdUpazila::find($id);

        return $item;
    }

    public function update($thana, $id)
    {
        $item = BdUpazila::find($id);
        $updatedItem = $item->update($thana);

        return $updatedItem;
    }

    //Move To Trash
    public function trash($id)
    {
        $item = BdUpazila::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('hrm_thana_delete'), 403);
        foreach ($ids as $id) {
            $item = BdUpazila::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete($id)
    {
        $item = BdUpazila::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete($ids)
    {
        abort_if(! auth()->user()->can('hrm_thana_delete'), 403);
        foreach ($ids as $id) {
            $item = BdUpazila::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore($id)
    {
        $item = BdUpazila::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('hrm_thana_delete'), 403);
        foreach ($ids as $id) {
            $item = BdUpazila::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        $count = BdUpazila::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        $count = BdUpazila::onlyTrashed()->count();

        return $count;
    }
}
