<?php

namespace Modules\Core\Service;

use Modules\Core\Entities\BdDistrict;
use Modules\Core\Interface\BdDistrictServiceInterface;

class BdDistrictService implements BdDistrictServiceInterface
{
    public function all(?array $params)
    {
        if (isset($params['division_id'])) {
            $items = BdDistrict::where('division_id', $params['division_id'])->orderBy('id', 'desc')->get();
        } else {
            $items = BdDistrict::orderBy('id', 'desc')->get();
        }

        return $items;
    }

    public function districtALL()
    {
        $items = BdDistrict::orderBy('id', 'desc')->get();

        return $items;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        $item = BdDistrict::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store($district)
    {
        $item = BdDistrict::create($district);

        return $item;
    }

    public function getDistrictByDivision($id)
    {
        $items = BdDistrict::where('division_id', $id)->get();

        return $items;
    }

    public function find($id)
    {
        $item = BdDistrict::find($id);

        return $item;
    }

    public function update($district, $id)
    {
        $item = BdDistrict::find($id);
        $updatedItem = $item->update($district);

        return $updatedItem;
    }

    //Move To Trash
    public function trash($id)
    {
        $item = BdDistrict::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('hrm_districts_delete'), 403);
        foreach ($ids as $id) {
            $item = BdDistrict::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete($id)
    {
        $item = BdDistrict::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete($ids)
    {
        abort_if(! auth()->user()->can('hrm_districts_delete'), 403);
        foreach ($ids as $id) {
            $item = BdDistrict::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore($id)
    {
        $item = BdDistrict::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('hrm_districts_delete'), 403);
        foreach ($ids as $id) {
            $item = BdDistrict::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        $count = BdDistrict::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        $count = BdDistrict::onlyTrashed()->count();

        return $count;
    }
}
