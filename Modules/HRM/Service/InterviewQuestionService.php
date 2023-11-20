<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\InterviewQuestions;
use Modules\HRM\Interface\InterviewQuestionServiceInterface;

class InterviewQuestionService implements InterviewQuestionServiceInterface
{
    public function all()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::orderBy('id', 'desc')->get();

        return $interviewQuestion;
    }

    public function store($attributes)
    {
        // abort_if(!auth()->user()->can('hrm_interview_create'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::create($attributes);

        return $interviewQuestion;
    }

    public function find(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_view'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::find($id);

        return $interviewQuestion;
    }

    public function update(array $attributes, int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_update'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::find($id);
        $updatedInterview = $interviewQuestion->update($attributes);

        return $updatedInterview;
    }

    //Get Trashed Itemist
    public function getTrashedItem()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::onlyTrashed()->orderBy('id', 'desc')->get();

        return $interviewQuestion;
    }

    //Move To Trash
    public function trash(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::find($id);
        $interviewQuestion->delete($interviewQuestion);

        return $interviewQuestion;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $interviewQuestion = InterviewQuestions::find($id);
            $interviewQuestion->delete($interviewQuestion);
        }

        return $interviewQuestion;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::onlyTrashed()->find($id);
        $interviewQuestion->forceDelete();

        return $interviewQuestion;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $interviewQuestion = InterviewQuestions::onlyTrashed()->find($id);
            $interviewQuestion->forceDelete($interviewQuestion);
        }

        return $interviewQuestion;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {

        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $interviewQuestion = InterviewQuestions::withTrashed()->find($id)->restore();

        return $interviewQuestion;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $interviewQuestion = InterviewQuestions::withTrashed()->find($id);
            $interviewQuestion->restore($interviewQuestion);
        }

        return $interviewQuestion;
    }

    //Get Row Count
    public function getRowCount()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $count = InterviewQuestions::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $count = InterviewQuestions::onlyTrashed()->count();

        return $count;
    }
}
