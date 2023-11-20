<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Grade;
use Modules\HRM\Interface\GradeServiceInterface;

class GradeService implements GradeServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_grades_index'), 403, 'Access Forbidden');
        $grade = Grade::orderBy('id', 'desc')->get();

        return $grade;
    }

    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_grades_create'), 403, 'Access Forbidden');
        $grade = Grade::create($attributes);

        return $grade;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_grades_view'), 403, 'Access Forbidden');
        $grade = Grade::find($id);

        return $grade;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_grades_update'), 403, 'Access Forbidden');
        $grade = Grade::find($id);
        $updatedGrade = $grade->update($attributes);

        return $updatedGrade;
    }

    //Get Trashed Itemlist
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_grades_index'), 403, 'Access Forbidden');
        $item = Grade::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_grades_delete'), 403, 'Access Forbidden');
        $item = Grade::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_grades_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Grade::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_grades_delete'), 403, 'Access Forbidden');
        $item = Grade::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_grades_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Grade::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_grades_delete'), 403, 'Access Forbidden');
        $item = Grade::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_grades_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Grade::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_grades_index'), 403, 'Access Forbidden');
        $count = Grade::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_grades_index'), 403, 'Access Forbidden');
        $count = Grade::onlyTrashed()->count();

        return $count;
    }

    public function calculateGrossSalary(int $id)
    {
        abort_if(! auth()->user()->can('hrm_grades_index'), 403, 'Access Forbidden');
        $salary = Grade::where('id', '=', $id)->sum(\DB::raw('basic + house_rent + medical + food + transport + other'));

        return $salary;
    }

    public function gradeSelectedAndSortListWithId()
    {
        abort_if(! auth()->user()->can('hrm_grades_index'), 403, 'Access Forbidden');
        $department = Grade::orderBy('name', 'asc')->get(['id', 'name']);

        return $department;
    }
}
