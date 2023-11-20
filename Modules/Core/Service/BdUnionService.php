<?php

namespace Modules\Core\Service;

use Modules\Core\Entities\BdUnion;
use Modules\Core\Interface\BdUnionServiceInterface;

class BdUnionService implements BdUnionServiceInterface
{
    public function all(?array $param)
    {
        if (isset($param['upazila_id'])) {
            $upazila_id = $param['upazila_id'];
            $unions = BdUnion::where('upazilla_id', $upazila_id)->orderBy('id', 'desc');
        } else {
            $unions = BdUnion::orderBy('id', 'desc');
        }

        return $unions;
    }

    public function getTrashedItem()
    {
        $item = BdUnion::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function getUnionByUpazila($id)
    {
        $items = BdUnion::where('upazilla_id', $id)->get();

        return $items;
    }

    public function store($union)
    {
        $item = BdUnion::create($union);

        return $item;
    }

    public function find($id)
    {
        $item = BdUnion::find($id);

        return $item;
    }

    public function update($union, $id)
    {
        $item = BdUnion::find($id);
        $updatedItem = $item->update($union);

        return $updatedItem;
    }

    //Move To Trash
    public function trash($id)
    {
        $item = BdUnion::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('hrm_union_delete'), 403);
        foreach ($ids as $id) {
            $item = BdUnion::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete($id)
    {
        $item = BdUnion::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete($ids)
    {
        abort_if(! auth()->user()->can('hrm_union_delete'), 403);
        foreach ($ids as $id) {
            $item = BdUnion::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore($id)
    {
        $item = BdUnion::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('hrm_union_delete'), 403);
        foreach ($ids as $id) {
            $item = BdUnion::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        $count = BdUnion::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        $count = BdUnion::onlyTrashed()->count();

        return $count;
    }
}
