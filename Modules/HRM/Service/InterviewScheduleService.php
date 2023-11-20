<?php

namespace Modules\HRM\Service;

use App\Interface\EmailServiceInterface;
use Modules\Communication\Entities\EmailTemplate;
use Modules\HRM\Emails\ScheduleMail;
use Modules\HRM\Entities\InterviewSchedule;
use Modules\HRM\Interface\InterviewScheduleServiceInterface;
use Modules\Website\Entities\JobApply;

class InterviewScheduleService implements InterviewScheduleServiceInterface
{
    public function __construct(
        private EmailServiceInterface $emailService,
    ) {
    }

    public function all()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $InterviewSchedule = InterviewSchedule::with(['applicant'])
            ->orderBy('id', 'desc');

        return $InterviewSchedule;
    }

    public function store($attributes)
    {
        // abort_if(!auth()->user()->can('hrm_interview_create'),  403, 'Access Forbidden');
        $InterviewSchedule = InterviewSchedule::create($attributes);
        $applicant = JobApply::where('id', $attributes['applicant_id'])->first();
        $email_template = EmailTemplate::where('id', $attributes['email_template_id'])->first();
        $this->emailService->send($applicant->email, new ScheduleMail(
            $applicant, $email_template, $InterviewSchedule
        ));

        return $InterviewSchedule;
    }

    public function find(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_view'),  403, 'Access Forbidden');
        $InterviewSchedule = InterviewSchedule::find($id);

        return $InterviewSchedule;
    }

    public function update(array $attributes, int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_update'),  403, 'Access Forbidden');
        $InterviewSchedule = InterviewSchedule::find($id);
        $updatedInterviewSchedule = $InterviewSchedule->update($attributes);

        return $updatedInterviewSchedule;
    }

    // Get Trashed Item list
    public function getTrashedItem()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $item = InterviewSchedule::onlyTrashed()->with(['applicant'])->orderBy('id', 'desc');

        return $item;
    }

    // Move To Trash
    public function trash(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $item = InterviewSchedule::find($id);
        $item->delete($item);

        return $item;
    }

    // Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = InterviewSchedule::find($id);
            $item->delete($item);
        }

        return $item;
    }

    // Permanent Delete
    public function permanentDelete(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $item = InterviewSchedule::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    // Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = InterviewSchedule::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    // Restore Trashed Item
    public function restore(int $id)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        $item = InterviewSchedule::withTrashed()->find($id)->restore();

        return $item;
    }

    // Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        // abort_if(!auth()->user()->can('hrm_interview_delete'),  403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = InterviewSchedule::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    // Get Row Count
    public function getRowCount()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $count = InterviewSchedule::all()->count();

        return $count;
    }

    // Get Trashed Item Count
    public function getTrashedCount()
    {
        // abort_if(!auth()->user()->can('hrm_interview_index'),  403, 'Access Forbidden');
        $count = InterviewSchedule::onlyTrashed()->count();

        return $count;
    }
}
