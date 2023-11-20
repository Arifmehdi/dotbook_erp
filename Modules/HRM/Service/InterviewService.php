<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\Interview;
use Modules\HRM\Interface\InterviewServiceInterface;

class InterviewService implements InterviewServiceInterface
{
    public function all()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $interview = Interview::orderBy('id', 'desc')->get();

        return $interview;
    }

    public function store($attributes)
    {
        // abort_if(!auth()->user()->can('hrm_interview_create'),  403, 'Access Forbidden');
        $interview = Interview::create($attributes);

        return $interview;
    }

    public function find(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_view'),  403, 'Access Forbidden');
        $interview = Interview::find($id);

        return $interview;
    }

    public function update(array $attributes, int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_update'),  403, 'Access Forbidden');
        $interview = Interview::find($id);
        $updatedInterview = $interview->update($attributes);

        return $updatedInterview;
    }

    //Get Trashed Itemist
    public function getTrashedItem()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $item = Interview::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $item = Interview::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Interview::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $item = Interview::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Interview::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {

        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $item = Interview::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Interview::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $count = Interview::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $count = Interview::onlyTrashed()->count();

        return $count;
    }
}
