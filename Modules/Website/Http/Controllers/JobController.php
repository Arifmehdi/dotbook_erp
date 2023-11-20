<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\HRM\Enums\JobAppliedStatus;
use Modules\Website\Entities\Job;
use Modules\Website\Entities\JobApply;
use Modules\Website\Entities\JobCategories;
use Response;
use Str;
use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jobs = Job::orderBy('id', 'DESC')->get();

            return DataTables::of($jobs)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_job')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.jobs.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_job')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.jobs.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_type', function ($row) {
                    return $row->job_type;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_title;
                })
                ->editColumn('category', function ($row) {
                    return $row->job_category->name;
                })
                ->editColumn('location', function ($row) {
                    return $row->location;
                })
                ->editColumn('deadline', function ($row) {
                    return $row->deadline;
                })
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->image).'">';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'job_type', 'job_title', 'category', 'location', 'deadline', 'image'])
                ->smart(true)
                ->make(true);
        }

        return view('website::jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $categories = JobCategories::where('status', 1)->get();

        return view('website::jobs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_job')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'job_title' => 'required|string',
            'email' => 'required',
            'location' => 'required',
            'deadline' => 'required',
            'job_type' => 'required',
            'vacancy' => 'numeric',
        ]);

        $job = new Job();
        if ($request->hasFile('image')) {
            $job->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/jobs');
        }

        $job->slug = Str::of($request->job_title)->slug('-');
        $job->job_title = $request->job_title;
        $job->job_type = $request->job_type;
        $job->job_category_id = $request->category;
        $job->vacancy = $request->vacancy;
        $job->education_req = $request->education;
        $job->skill = $request->skill;
        $job->experience = $request->experience;
        $job->location = $request->location;
        $job->email = $request->email;
        $job->city = $request->city;
        $job->website = $request->website;
        $job->salary_type = $request->salary_type;
        $job->salary = $request->salary;
        $job->description = $request->description;
        $job->responsibility = $request->responsibility;
        $job->facilities = $request->facilities;
        $job->deadline = $request->deadline;
        $job->save();

        return response()->json('Job has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::jobs.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_job')) {
            abort(403, 'Access Forbidden.');
        }

        $categories = JobCategories::where('status', 1)->get();
        $job = Job::find($id);

        return view('website::jobs.edit', compact('categories', 'job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id, FileUploadUtil $FileUploadUtil)
    {
        $request->validate([
            'job_title' => 'required|string',
            'email' => 'required',
            'location' => 'required',
            'deadline' => 'required',
            'job_type' => 'required',
            'vacancy' => 'numeric',
        ]);

        $job = Job::find($id);
        if ($request->hasFile('image')) {
            $job->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/jobs');
        }

        $job->slug = Str::of($request->job_title)->slug('-');
        $job->job_title = $request->job_title;
        $job->job_type = $request->job_type;
        $job->job_category_id = $request->category;
        $job->vacancy = $request->vacancy;
        $job->education_req = $request->education;
        $job->skill = $request->skill;
        $job->experience = $request->experience;
        $job->location = $request->location;
        $job->email = $request->email;
        $job->city = $request->city;
        $job->website = $request->website;
        $job->salary_type = $request->salary_type;
        $job->salary = $request->salary;
        $job->description = $request->description;
        $job->responsibility = $request->responsibility;
        $job->facilities = $request->facilities;
        $job->deadline = $request->deadline;
        $job->save();

        return response()->json('Job has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $job = Job::find($id);
        $job->delete();

        return response()->json('Job has been delete successfully');
    }

    public function jobApplied(Request $request)
    {
        if ($request->ajax()) {
            $job_applys = JobApply::with('job_applied')->orderBy('id', 'DESC')->get();

            return DataTables::of($job_applys)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_job_applied_download')) {
                        // $html .= '<a href="'. $row->resume .'" class="btn btn-sm btn-success" download>Download</a>';
                        $html .= '<a class="dropdown-item" href="'.route('website.jobs-applied.download', [$row->id]).'"><i class="far fa-file-alt text-primary"></i> Download</a>';
                    }
                    if (auth()->user()->can('web_job_applied_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.jobs-applied.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('job_title', function ($row) {
                    return $row->job_applied->job_title;
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
                ->editColumn('created_at', function ($row) {
                    $created_at = Carbon::parse($row->created_at)->format('Y-m-d');

                    return $created_at ?? '';
                })
                // ->editColumn('resume', function ($row) {
                //     if ($row->resume != null)
                //         $html = '<a target="_blank" href="'. asset($row->resume) .'"><span class="badge bg-success">View</span></a>';
                //     else{
                //         $html = '<span class="badge bg-danger">No File</span>';
                //     }
                //     return $html;
                // })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == JobAppliedStatus::Default->value) {
                        // $html = '<span><img style="max-width: 10%;" src="https://emc2.stpi.in//assets/admin_images/new-icon-gif-2.jpg"></span></span>';
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
                    } elseif ($row->status == JobAppliedStatus::Pending->value) {
                        $html = '<span class="badge bg-success"> Pending </span>';
                    } else {
                        $html = '<span class="badge bg-danger"> Rejected </span>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'job_title', 'name', 'email', 'mobile', 'created_at', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::jobs.job_applied');
    }

    public function jobAppliedDestroy($id)
    {
        $job_applied = JobApply::find($id);
        $job_applied->delete();

        return response()->json('Job applied has been delete successfully');
    }

    public function jobAppliedDownload($id)
    {
        if (! auth()->user()->can('web_job_applied_download')) {
            abort(403, 'Access Forbidden.');
        }
        $apply = JobApply::where('id', $id)->first();
        $path = env('WEB_URL').($apply->resume);
        $path = public_path('http://erp.test/uploads/website/job/1765132110547640.pdf');

        return response()->download($path);

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
}
