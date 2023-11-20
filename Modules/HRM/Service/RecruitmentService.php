<?php

namespace Modules\HRM\Service;

use Illuminate\Support\Facades\DB;
use Modules\HRM\Enums\JobAppliedStatus;
use Modules\HRM\Interface\RecruitmentServiceInterface;

class RecruitmentService implements RecruitmentServiceInterface
{
    public function applicantFilter($request)
    {
        // abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function selectedForInterview($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::SelectedInterview->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function alreadyMailForInterview($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::SendMailForInterview->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function interviewParticipate($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::InterviewParticipant->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function applicantFinalSelected($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::FinalSelected->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function applicantOfferLetter($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::SendMailForOfferLetter->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function applicantHiredList($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::Hired->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function applicantRejectList($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::Rejected->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }

    public function convertEmployeeList($request)
    {
        $query = DB::connection('website')->table('job_applies')
            ->where('job_applies.deleted_at', null)
            ->leftJoin('jobs', 'job_applies.job_id', 'jobs.id')
            ->leftJoin('job_categories', 'jobs.job_category_id', 'job_categories.id')
            ->where('job_applies.status', JobAppliedStatus::ConvertToEmployee->value)
            ->orderBy('job_applies.id', 'desc');
        if ($request->job_category_id) {
            $query->where('job_applies.job_id', $request->job_category_id);
        }
        if ($request->job_title) {
            $query->where('jobs.job_title', $request->job_title);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('job_applies.created_at', '>=', $from_date);
            $query->where('job_applies.created_at', '<=', $to_date);
        }
        $jobApplicants = $query->select('job_applies.*', 'jobs.job_title', 'job_categories.name as jobCatName');

        return $jobApplicants;
    }
}
