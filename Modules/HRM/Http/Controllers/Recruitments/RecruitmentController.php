<?php

namespace Modules\HRM\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use App\Interface\EmailServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Communication\Entities\EmailTemplate;
use Modules\HRM\Emails\InterviewBulkMail;
use Modules\HRM\Emails\InterviewSingleMail;
use Modules\HRM\Emails\OfferLetterBulkMail;
use Modules\HRM\Emails\OfferLetterSingleMail;
use Modules\HRM\Enums\JobAppliedStatus;
use Modules\HRM\Interface\RecruitmentServiceInterface;
use Modules\Website\Entities\Job;
use Modules\Website\Entities\JobApply;
use Modules\Website\Entities\JobCategories;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentController extends Controller
{
    public function __construct(
        private EmailServiceInterface $emailService,
        private RecruitmentServiceInterface $recruitmentService,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function jobApplicantList(Request $request)
    {
        $job_applies = $this->recruitmentService->applicantFilter($request);
        $rowCount = JobApply::all()->count();
        if ($request->ajax()) {
            return DataTables::of($job_applies)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '</span><span class="badge bg-warning"> Applied </span><span><img style="max-width: 15%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Hired->value) {
                        $html = '<span class="badge bg-primary me-2"> Confirm </span> <span class="badge bg-danger"><a href="'.route('hrm.applicant_convert', [$row->id]).'" data-bs-toggle="tooltip" data-bs-placement="top" title="Convert to Employee">
                        <i class="fa-regular fa-arrow-right-arrow-left"></i>
                    </a></span>';
                    } elseif ($row->status == JobAppliedStatus::ConvertToEmployee->value) {
                        $html = '<span class="badge bg-success"> Employee </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-warning"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();
        $jobAppliedCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::Default->value)->get()->count();
        $selectedForInterview = $this->recruitmentService->selectedForInterview($request);
        $selectedForInterviewCount = $selectedForInterview->count();
        $interviewParticipates = $this->recruitmentService->interviewParticipate($request);
        $interviewParticipatesCount = $interviewParticipates->count();
        $applicantsHiredList = $this->recruitmentService->applicantHiredList($request);
        $applicantsHiredCount = $applicantsHiredList->count();
        $applicantsRejectList = $this->recruitmentService->applicantRejectList($request);
        $applicantsRejectListCount = $applicantsRejectList->count();

        return view('hrm::recruitments.index', compact('jobCategories', 'jobTitles', 'jobAppliedCount', 'interviewParticipatesCount', 'applicantsHiredCount', 'applicantsRejectListCount'));
    }

    public function jobApplicantView($id)
    {
        // $job_applys =JobApply::with('job_applied')->where('status', JobAppliedStatus::Default->value)->find($id);
        $job_applys = JobApply::with('job_applied')->find($id);

        return view('hrm::recruitments.ajax_views.view', compact('job_applys'));
    }

    public function jobApplicantDownload($id)
    {
        if (! auth()->user()->can('web_job_applied_download')) {
            abort(403, 'Access Forbidden.');
        }
        $application = JobApply::where('id', $id)->first();
        $resume = public_path('website'.$application->resume);

        return response()->download($resume);

        return Response::download($file);

        $extensionArr = explode('.', $file);
        $extension = $extensionArr[count($extensionArr) - 1];

        // return response()->streamDownload(function () {
        //     echo file_get_contents($file);
        // }, 'file.' . trim($extension));

        $contents = file_get_contents($file);
        $tempName = 'file.'.$extension;

        Storage::disk('public')->put($tempName, $contents);

        $path = Storage::url($tempName);

        return response()->download($path);
    }

    public function jobApplicantDestroy($id)
    {
        $job_applied = JobApply::find($id);
        $job_applied->delete();

        return response()->json('Job applied has been delete successfully');
    }

    public function applicantSingle(Request $request, $id)
    {
        // $selectedApplicant =JobApply::with('job_applied')->where('status', JobAppliedStatus::Default->value)->find($id);
        $selectedApplicant = JobApply::with('job_applied')->find($id);
        $selectedApplicant->status = $request->input('status');
        $selectedApplicant->save();

        return redirect()->route('hrm.job_applicant_list')->with('success', 'Record updated successfully.');
    }

    public function applicantSelectBulkAction(Request $request)
    {
        // $participants =JobApply::with('job_applied')->where('status', JobAppliedStatus::Default->value)->whereIn('id', $request->applicant_id)->get();
        $participants = JobApply::with('job_applied')->whereIn('id', $request->applicant_id)->get();
        foreach ($participants as $participant) {
            $participant->status = JobAppliedStatus::SelectedInterview->value;
            $participant->save();
        }

        return response()->json('Record updated successfully');
        // return redirect()->route('hrm.job_applicant_list')->with('success', 'Record updated successfully.');
    }

    public function selectedForInterview(Request $request)
    {
        $selectedForInterview = $this->recruitmentService->selectedForInterview($request);
        $rowCount = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::SelectedInterview->value)
            ->count();

        if ($request->ajax()) {
            return DataTables::of($selectedForInterview)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.selected_for_interviewer_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();

        $jobTitles = Job::select('id', 'job_title')->get();
        $email_templates = EmailTemplate::pluck('format_name', 'id');

        return view('hrm::recruitments.selected-for-interview.index', compact('jobCategories', 'jobTitles', 'email_templates'));
    }

    public function ApplicantSelectedInterviewerView($id)
    {
        $selectedInterviewer = JobApply::with('job_applied')->where('status', JobAppliedStatus::SelectedInterview->value)->find($id);
        $email_templates = EmailTemplate::pluck('format_name', 'id');

        return view('hrm::recruitments.selected-for-interview.ajax_views.view', compact('selectedInterviewer', 'email_templates'));
    }

    // Send Mail For Interview Invitation Single Applicant
    public function ApplicantSendSingleMailForInterview(Request $request, $id)
    {
        $recipient = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::SelectedInterview->value)
            ->find($id);
        $email_template = EmailTemplate::where('id', $request['email_template_id'])->first();
        if ($recipient?->email) {
            $this->emailService->send($recipient->email, new InterviewSingleMail(
                $recipient,
                $email_template
            ));
        }
        $recipient->status = $request->input('status');
        $recipient->save();
        // return response()->json('Record updated successfully');
        return redirect()->back()->with('success', 'Record updated successfully.');
    }

    // Send Mail For Interview Invitation Bulk Applicant
    public function sendMailForInterviewBulkAction(Request $request)
    {
        $recipients = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::SelectedInterview->value)
            ->whereIn('id', $request->applicant_id)
            ->get();
        $recipientsArray = $recipients->toArray();
        $email_template = EmailTemplate::where('id', $request['email_template_id'])->first();
        $this->emailService->sendMultiple(array_column($recipientsArray, 'email'), new InterviewBulkMail(
            $recipients,
            $email_template
        ));

        foreach ($recipients as $recipient) {
            $recipient->status = JobAppliedStatus::SendMailForInterview->value;
            $recipient->save();
        }

        return response()->json('Record updated successfully', 200);
    }

    public function alreadyMailForInterview(Request $request)
    {
        $alreadyMailForInterview = $this->recruitmentService->alreadyMailForInterview($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::SendMailForInterview->value)->count();
        // dd($rowCount);

        if ($request->ajax()) {
            return DataTables::of($alreadyMailForInterview)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.already_mail_for_interview_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.already-mail-for-interview.index', compact('jobCategories', 'jobTitles'));
    }

    public function alreadyMailForInterviewerView($id)
    {
        $alreadyMailedInterviewer = JobApply::with('job_applied')->where('status', JobAppliedStatus::SendMailForInterview->value)->find($id);

        return view('hrm::recruitments.already-mail-for-interview.ajax_views.view', compact('alreadyMailedInterviewer'));
    }

    public function participateInterviewBulkAction(Request $request)
    {
        $participants = JobApply::with('job_applied')->where('status', JobAppliedStatus::SendMailForInterview->value)->whereIn('id', $request->applicant_id)->get();
        foreach ($participants as $participant) {

            $participant->status = JobAppliedStatus::InterviewParticipant->value;
            $participant->save();
        }

        return response()->json('Record updated successfully');

    }

    public function singleParticipateInterviewer(Request $request, $id)
    {
        $participant = JobApply::with('job_applied')->where('status', JobAppliedStatus::SendMailForInterview->value)->find($id);
        $participant->status = $request->input('status');
        $participant->save();

        return redirect()->back()->with('success', 'Record updated successfully.');
    }

    public function interviewParticipate(Request $request)
    {
        $interviewParticipates = $this->recruitmentService->interviewParticipate($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::InterviewParticipant->value)->count();
        if ($request->ajax()) {
            return DataTables::of($interviewParticipates)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.interview_participate_list_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.interview-participate.index', compact('jobCategories', 'jobTitles'));
    }

    public function interviewParticipateView($id)
    {
        $interviewParticipate = JobApply::with('job_applied')->where('status', JobAppliedStatus::InterviewParticipant->value)->find($id);

        return view('hrm::recruitments.interview-participate.ajax_views.view', compact('interviewParticipate'));
    }

    public function applicantFinalSingleSelected(Request $request, $id)
    {
        $applicantFinalSelected = JobApply::with('job_applied')->where('status', JobAppliedStatus::InterviewParticipant->value)->find($id);
        $applicantFinalSelected->status = $request->input('status');
        $applicantFinalSelected->save();

        return redirect()->back()->with('success', 'Record updated successfully.');
    }

    public function applicantFinalSelectBulkAction(Request $request)
    {
        $participants = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::InterviewParticipant->value)
            ->whereIn('id', $request->applicant_id)
            ->get();

        foreach ($participants as $participant) {
            $participant->status = JobAppliedStatus::FinalSelected->value;
            $participant->save();
        }

        return response()->json('Record updated successfully');
        // return redirect()->back()->with('success', 'Record updated successfully.');
    }

    public function applicantFinalSelected(Request $request)
    {
        $applicantsFinalSelected = $this->recruitmentService->applicantFinalSelected($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::FinalSelected->value)->count();
        if ($request->ajax()) {
            return DataTables::of($applicantsFinalSelected)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.applicant_final_selected_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.final-selected.index', compact('jobCategories', 'jobTitles'));
    }

    public function applicantFinalSelectedView($id)
    {
        $finalSelectedApplicant = JobApply::with('job_applied')->where('status', JobAppliedStatus::FinalSelected->value)->find($id);

        return view('hrm::recruitments.final-selected.ajax_views.view', compact('finalSelectedApplicant'));
    }

    public function applicantSingleOfferLetterSend(Request $request, $id)
    {
        $participant = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::FinalSelected->value)
            ->find($id);
        if ($participant?->email) {
            $this->emailService->send($participant->email, new OfferLetterSingleMail(
                $participant,
                'You are invited',
                'Hello fro SpeedDigit',
                'red'
            ));
        }
        $participant->status = $request->input('status');
        $participant->save();

        return redirect()->back()->with('success', 'Record updated successfully.');
    }

    public function applicantOfferLetterBulkAction(Request $request)
    {
        $participants = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::FinalSelected->value)
            ->whereIn('id', $request->applicant_id)
            ->get();
        $participantsArray = $participants->toArray();
        $this->emailService->sendMultiple(array_column($participantsArray, 'email'), new OfferLetterBulkMail(
            $participants,
            'You are invited',
            'Hello fro SpeedDigit',
            'red'
        ));
        foreach ($participants as $recipient) {
            $recipient->status = JobAppliedStatus::SendMailForOfferLetter->value;
            $recipient->save();
        }

        return response()->json('Record updated successfully');

    }

    public function applicantOfferLetter(Request $request)
    {
        $applicantsOfferLetter = $this->recruitmentService->applicantOfferLetter($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::SendMailForOfferLetter->value)->count();
        if ($request->ajax()) {
            return DataTables::of($applicantsOfferLetter)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.applicant_offer_letter_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.applicant-offer-letter.index', compact('jobCategories', 'jobTitles'));
    }

    public function applicantOfferLetterView($id)
    {
        $applicantsOfferLetter = JobApply::with('job_applied')->where('status', JobAppliedStatus::SendMailForOfferLetter->value)->find($id);

        return view('hrm::recruitments.applicant-offer-letter.ajax_views.view', compact('applicantsOfferLetter'));
    }

    public function applicantSingleHired(Request $request, $id)
    {
        $participant = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::SendMailForOfferLetter->value)
            ->find($id);
        $participant->status = $request->input('status');
        $participant->save();

        return redirect()->back()->with('success', 'Record updated successfully.');
    }

    public function applicantBulkHired(Request $request)
    {
        $participants = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::SendMailForOfferLetter->value)
            ->whereIn('id', $request->applicant_id)
            ->get();
        foreach ($participants as $recipient) {
            $recipient->status = JobAppliedStatus::Hired->value;
            $recipient->save();
        }

        return response()->json('Record updated successfully');
    }

    public function applicantHiredList(Request $request)
    {
        $applicantsHiredList = $this->recruitmentService->applicantHiredList($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::Hired->value)->count();
        if ($request->ajax()) {
            return DataTables::of($applicantsHiredList)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.applicant_hired_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Hired->value) {
                        $html = '<span class="badge bg-primary me-2"> Confirm </span> <span class="badge bg-danger"><a href="'.route('hrm.applicant_convert', [$row->id]).'" data-bs-toggle="tooltip" data-bs-placement="top" title="Convert to Employee">
                    <i class="fa-regular fa-arrow-right-arrow-left"></i>
                </a></span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-warning"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.applicant-hired.index', compact('jobCategories', 'jobTitles'));
    }

    public function applicantHiredView($id)
    {
        $applicantsHired = JobApply::with('job_applied')->where('status', JobAppliedStatus::Hired->value)->find($id);

        return view('hrm::recruitments.applicant-hired.ajax_views.view', compact('applicantsHired'));
    }

    public function applicantBulkReject(Request $request)
    {
        $participants = JobApply::with('job_applied')
            ->where('status', JobAppliedStatus::Hired->value)
            ->whereIn('id', $request->applicant_id)
            ->get();
        foreach ($participants as $recipient) {
            $recipient->status = JobAppliedStatus::Rejected->value;
            $recipient->save();
        }

        return response()->json('Record updated successfully');
    }

    public function convertEmployeeList(Request $request)
    {
        $convertEmployeeList = $this->recruitmentService->convertEmployeeList($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::ConvertToEmployee->value)->count();
        if ($request->ajax()) {
            return DataTables::of($convertEmployeeList)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.convert_employee_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        // $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span>';
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::ConvertToEmployee->value) {
                        $html = '<span class="badge bg-success"> Employee </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.convert-employee.index', compact('jobCategories', 'jobTitles'));
    }

    public function convertEmployeeView($id)
    {
        $convert_employee = JobApply::with('job_applied')->where('status', JobAppliedStatus::ConvertToEmployee->value)->find($id);

        return view('hrm::recruitments.convert-employee.ajax_views.view', compact('convert_employee'));
    }

    public function applicantRejectList(Request $request)
    {
        $applicantsRejectList = $this->recruitmentService->applicantRejectList($request);
        $rowCount = JobApply::with('job_applied')->where('status', JobAppliedStatus::Rejected->value)->count();
        if ($request->ajax()) {
            return DataTables::of($applicantsRejectList)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                <input type="checkbox" name="applicant_id[]" value="'.$row->id.'" class="mt-2 check1">
                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.applicant_reject_view', [$row->id]).'" id="view"><i class="fa-duotone fa-eye text-primary"></i> View</a>';
                        $html .= '<a class="dropdown-item" href="'.route('hrm.job_applicant_download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('hrm.job_applicant_delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('apply_date', function ($row) {
                    $apply_date = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $apply_date ?? '';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        // $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span>';
                        $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span><span class="badge bg-warning"> Applied </span>';
                    } elseif ($row->status == JobAppliedStatus::SelectedInterview->value) {
                        $html = '<span class="badge bg-success"> Selected For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForInterview->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Interview </span>';
                    } elseif ($row->status == JobAppliedStatus::InterviewParticipant->value) {
                        $html = '<span class="badge bg-success"> Interview Participated </span>';
                    } elseif ($row->status == JobAppliedStatus::FinalSelected->value) {
                        $html = '<span class="badge bg-success"> Final Selected </span>';
                    } elseif ($row->status == JobAppliedStatus::SendMailForOfferLetter->value) {
                        $html = '<span class="badge bg-success"> Mail Sent For Offer Letter </span>';
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'check', 'job_title', 'name', 'email', 'mobile', 'apply_date', 'status'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $jobCategories = JobCategories::all();
        $jobTitles = Job::select('id', 'job_title')->get();

        return view('hrm::recruitments.applicant-rejected.index', compact('jobCategories', 'jobTitles'));
    }

    public function applicantRejectView($id)
    {
        $applicantsReject = JobApply::with('job_applied')->where('status', JobAppliedStatus::Rejected->value)->find($id);

        return view('hrm::recruitments.applicant-rejected.ajax_views.view', compact('applicantsReject'));
    }
}
