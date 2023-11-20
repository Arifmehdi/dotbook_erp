<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;

class HrmDepartmentService implements HrmDepartmentServiceInterface
{
    public function all()
    {
        abort_if(!auth()->user()->can('hrm_departments_index'), 403, 'Access Forbidden');
        $items = HrmDepartment::orderBy('id', 'desc');
        return $items;
    }

    public function getTrashedItem()
    {
        abort_if(!auth()->user()->can('hrm_departments_index'), 403, 'Access Forbidden');
        $item = HrmDepartment::onlyTrashed()->orderBy('id', 'desc');
        return $item;
    }

    public function store(array $department)
    {
        abort_if(!auth()->user()->can('hrm_departments_create'), 403, 'Access Forbidden');
        $item = HrmDepartment::create($department);
        return $item;
    }

    public function find(int $id)
    {
        abort_if(!auth()->user()->can('hrm_departments_view'), 403, 'Access Forbidden');
        $item = HrmDepartment::findOrFail($id);

        return $item;
    }

    public function update(array $department, int $id)
    {
        abort_if(!auth()->user()->can('hrm_departments_update'), 403, 'Access Forbidden');
        $item = HrmDepartment::find($id);
        $updatedItem = $item->update($department);

        return $updatedItem;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(!auth()->user()->can('hrm_departments_delete'), 403, 'Access Forbidden');
        $item = HrmDepartment::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_departments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = HrmDepartment::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(!auth()->user()->can('hrm_departments_delete'), 403, 'Access Forbidden');
        $item = HrmDepartment::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_departments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = HrmDepartment::onlyTrashed()->findOrFail($id);
            $item->forceDelete($item);
        }
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(!auth()->user()->can('hrm_departments_delete'), 403, 'Access Forbidden');
        $item = HrmDepartment::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_departments_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = HrmDepartment::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(!auth()->user()->can('hrm_departments_index'), 403, 'Access Forbidden');
        $count = HrmDepartment::count();
        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(!auth()->user()->can('hrm_departments_index'), 403, 'Access Forbidden');
        $count = HrmDepartment::onlyTrashed()->count();
        return $count;
    }

    public function departmentSelectedAndSortListWithId()
    {
        abort_if(!auth()->user()->can('hrm_departments_index'), 403, 'Access Forbidden');
        $department = HrmDepartment::orderBy('name', 'asc')->where('deleted_at', null)->get(['id', 'name']);
        return $department;
    }
}
