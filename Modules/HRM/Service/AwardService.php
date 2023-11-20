<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Award;
use Modules\HRM\Interface\AwardServiceInterface;

class AwardService implements AwardServiceInterface
{
    public function all()
    {
        abort_if(!auth()->user()->can('hrm_awards_index'), 403, 'Access Forbidden');
        $items = Award::orderBy('id', 'desc')->get();

        return $items;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(!auth()->user()->can('hrm_awards_index'), 403, 'Access Forbidden');
        $item = Award::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    public function store(array $award)
    {

        abort_if(!auth()->user()->can('hrm_awards_create'), 403, 'Access Forbidden');
        // $item =  Award::create($award);
        $date = $award['date'];
        $year = date('Y', strtotime($date));
        $month = date('F', strtotime($date));

        $item = Award::create([
            'employee_id' => $award['employee_id'],
            'award_name' => $award['award_name'],
            'award_description' => $award['award_description'],
            'gift_item' => $award['gift_item'],
            'award_by' => $award['award_by'],
            'date' => $award['date'],
            'month' => $month,
            'year' => $year,

        ]);

        return $item;
    }

    public function find(int $id)
    {
        abort_if(!auth()->user()->can('hrm_awards_view'), 403, 'Access Forbidden');
        $item = Award::findOrFail($id);

        return $item;
    }

    public function update(array $award, int $id)
    {
        abort_if(!auth()->user()->can('hrm_awards_update'), 403, 'Access Forbidden');
        $item = Award::find($id);
        // $updatedItem = $item->update($award);
        $date = $award['date'];
        $year = date('Y', strtotime($date));
        $month = date('F', strtotime($date));

        $updatedItem = $item->update([
            'employee_id' => $award['employee_id'],
            'award_name' => $award['award_name'],
            'award_description' => $award['award_description'],
            'gift_item' => $award['gift_item'],
            'award_by' => $award['award_by'],
            'date' => $award['date'],
            'month' => $month,
            'year' => $year,

        ]);

        return $updatedItem;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(!auth()->user()->can('hrm_awards_delete'), 403, 'Access Forbidden');
        $item = Award::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_awards_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Award::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(!auth()->user()->can('hrm_awards_delete'), 403, 'Access Forbidden');
        $item = Award::onlyTrashed()->find($id);
        $isDeleted = $item->forceDelete();

        return $isDeleted;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_awards_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Award::onlyTrashed()->findOrFail($id);
            $item->forceDelete($item);
        }
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(!auth()->user()->can('hrm_awards_delete'), 403, 'Access Forbidden');
        $item = Award::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_awards_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Award::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(!auth()->user()->can('hrm_awards_index'), 403, 'Access Forbidden');
        $count = Award::count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(!auth()->user()->can('hrm_awards_index'), 403, 'Access Forbidden');
        $count = Award::onlyTrashed()->count();

        return $count;
    }

    public function awardEmployeeFilter($request)
    {
        abort_if(!auth()->user()->can('hrm_awards_index'), 403, 'Access Forbidden');

        $query = Award::query()
            ->leftJoin('employees', 'awards.employee_id', 'employees.id')
            ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
            ->orderBy('id', 'desc');

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->whereBetween('awards.date', [$form_date, $to_date]); // Final
        }

        if ($request->employee_id) {
            $query->where('awards.employee_id', $request->employee_id);
        }

        if ($request->hrm_department_id) {
            $query->where('employees.hrm_department_id', $request->hrm_department_id);
        }

        if ($request->shift_id) {
            $query->where('employees.shift_id', $request->shift_id);
        }

        if ($request->grade_id) {
            $query->where('employees.grade_id', $request->grade_id);
        }

        $awards = $query->select(
            'awards.*',
            'employees.name as employee_name',
            'employees.employee_id'
        )
            ->get();

        return $awards;
    }
}
