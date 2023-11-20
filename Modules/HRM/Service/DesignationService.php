<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Designation;
use Modules\HRM\Interface\DesignationServiceInterface;

class DesignationService implements DesignationServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_designations_index'), 403, 'Access Forbidden');
        $item = Designation::with(['sections', 'parent_designation'])->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_designations_create'), 403, 'Access Forbidden');
        $item = Designation::create($attributes);

        return $item;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_designations_view'), 403, 'Access Forbidden');
        $item = Designation::find($id);

        return $item;
    }

    public function getDesignationBySection(int $id)
    {
        abort_if(! auth()->user()->can('hrm_designations_view'), 403, 'Access Forbidden');
        $item = Designation::where('section_id', $id)->get();

        return $item;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_designations_update'), 403, 'Access Forbidden');
        $item = Designation::find($id);
        $updatedDesignation = $item->update($attributes);

        return $updatedDesignation;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_designations_index'), 403, 'Access Forbidden');
        $item = Designation::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_designations_delete'), 403, 'Access Forbidden');
        $item = Designation::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_designations_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Designation::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_designations_delete'), 403, 'Access Forbidden');
        $item = Designation::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_designations_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Designation::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_designations_delete'), 403, 'Access Forbidden');
        $item = Designation::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_designations_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Designation::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_designations_index'), 403, 'Access Forbidden');
        $count = Designation::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_designations_index'), 403, 'Access Forbidden');
        $count = Designation::onlyTrashed()->count();

        return $count;
    }

    public function designationSelectedAndSortListWithId()
    {
        abort_if(! auth()->user()->can('hrm_designations_index'), 403, 'Access Forbidden');
        $department = Designation::orderBy('name', 'asc')->where('deleted_at', null)->get(['id', 'name', 'section_id', 'parent_designation_id']);

        return $department;
    }
}
