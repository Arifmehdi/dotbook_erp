<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CRM\Entities\FollowupCategory;
use Modules\CRM\Entities\Followups;
use Modules\CRM\Http\Requests\Followups\FollowupStoreRequest;
use Modules\CRM\Http\Requests\Followups\FollowupUpdateRequest;
use Modules\CRM\Interfaces\BusinessLeadServiceInterface;
use Modules\CRM\Interfaces\FileUploaderServiceInterface;
use Modules\CRM\Interfaces\FollowupServiceInterface;
use Modules\CRM\Interfaces\IndividualLeadServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class FollowupController extends Controller
{
    public $followupService;

    public $IndividualLeadService;

    public $businessLeadService;

    public function __construct(FollowupServiceInterface $followupService, IndividualLeadServiceInterface $IndividualLeadService, BusinessLeadServiceInterface $businessLeadService)
    {
        $this->followupService = $followupService;
        $this->IndividualLeadService = $IndividualLeadService;
        $this->businessLeadService = $businessLeadService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $followupService = $this->followupService->getTrashedItem();
        } else {
            $followupService = $this->followupService->all();
        }

        $rowCount = $this->followupService->getRowCount();
        $groupByRow = $this->followupService->getGroupByRowCount();
        $trashedCount = $this->followupService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($followupService)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="followups_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })

                ->addColumn('action', function ($row) {
                    $icon3 = '<i class="fa-regular fa-headset"></i>';

                    if ($row->trashed()) {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('crm.followup.restore', $row->id).'" class="action-btn c-edit restore" id="restore_business_leads" title="restore"><i class="fa-solid fa-recycle"></i></a>';
                        $html .= '<a href="'.route('crm.followup.permanent-delete', $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete"><i class="fa-solid fa-trash-check"></i></a>';
                        $html .= '</div>';

                        return $html;
                    } else {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('crm.followup.edit', $row->id).'" class="action-btn c-edit edit" id="edit_business_leads" title="Edit"><span class="fas fa-edit"></span></a></a>';
                        $html .= '<a href="'.route('crm.followup.add', $row->id).'" class="action-btn c-edit followup" id="add_followup" title="Followup">'.$icon3.'</a>';
                        $html .= '<a href="'.route('crm.followup.destroy', $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete"><span class="fas fa-trash "></span></a>';
                        $html .= '</div>';

                        return $html;
                    }

                })

                ->addColumn('status_color', function ($row) {
                    $status = '';
                    $color = '';
                    $status = $row->status;
                    if ($row->status == 'Interested') {
                        $color = 'success';
                    } elseif ($row->status == 'Pending') {
                        $color = 'info';
                    } elseif ($row->status == 'Not Connect') {
                        $color = 'secondary';
                    } elseif ($row->status == 'Not Interested') {
                        $color = 'danger';
                    }
                    $html = '';
                    $html .= '<div class="text-center">
                                    <span class="badge bg-'.$color.'">'.$status.'</span>
                                </div>';

                    return $html;
                })

                ->addColumn('user_name', function ($row) {
                    if ($row->leads_individual_or_business == 'business') {
                        $html = '';
                        $html .= '<div class="text-center"> <span>'.$row->busilness_lead->name.'</span> </div>';

                        return $html;
                    } elseif ($row->leads_individual_or_business == 'individual') {
                        $html = '';
                        $html .= '<div class="text-center"> <span>'.$row->individual_lead->name.'</span> </div>';

                        return $html;
                    }
                })

                ->rawColumns(['action', 'check', 'status_color', 'user_name'])
                ->with([
                    'allRow' => $rowCount,
                    'groupByRow' => $groupByRow,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        $followup_category = FollowupCategory::orderBy('name', 'ASC')->get();

        return view('crm::followups.index', compact('followup_category'));
    }

    public function getLeads($id)
    {
        $leads = [];
        if ($id == 'individual') {
            $leads = $this->IndividualLeadService->all();
        } elseif ($id == 'business') {
            $leads = $this->businessLeadService->all();
        }

        return $leads;
    }

    public function getLeadsDetails($id, $type)
    {
        $leads = '';
        if ($type == 'individual') {
            $leads = $this->IndividualLeadService->find($id);
        } elseif ($type == 'business') {
            $leads = $this->businessLeadService->find($id);
        }

        return $leads;
    }

    public function store(FollowupStoreRequest $request, FileUploaderServiceInterface $fileUploaderService)
    {

        $validatedFollowup = $request->validated();
        if ($request->hasFile('file')) {
            $validatedFollowup['file'] = $fileUploaderService->upload($request->file('file'), 'uploads/followup');
        }

        $followup = $this->followupService->store($validatedFollowup);

        return response()->json('followup created successfully');
    }

    public function show($id)
    {
        return view('crm::followups.show');
    }

    public function edit($id)
    {
        $categores = FollowupCategory::orderBy('name', 'ASC')->get();
        $followups = $this->followupService->find($id);
        $individualLeads = $this->IndividualLeadService->allLeads();

        return view('crm::followups.ajax_view.edit', compact('categores', 'followups', 'individualLeads'));
    }

    public function add($id)
    {
        $followup_category = FollowupCategory::orderBy('name', 'ASC')->get();
        $followups = $this->followupService->find($id);

        // return gettype($followups);
        $followupRecord = [];
        $leads = '';
        if ($followups->leads_individual_or_business == 'individual') {
            $leads = $this->IndividualLeadService->find($id);
            $followupRecord = Followups::with('categories')->where('individual_id', $followups->individual_id)->get();
        } elseif ($followups->leads_individual_or_business == 'business') {
            $leads = $this->businessLeadService->find($id);
            $followupRecord = Followups::with('categories')->where('business_id', $followups->business_id)->get();
        }

        return view('crm::followups.ajax_view.add', compact('followup_category', 'followupRecord', 'leads', 'followups'));
    }

    public function create()
    {
        $followup_category = FollowupCategory::orderBy('name', 'ASC')->get();

        return view('crm::followups.ajax_view.create', compact('followup_category'));
    }

    public function update(FollowupUpdateRequest $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        $validatedFollowup = $request->validated();
        if ($request->hasFile('file')) {
            $validatedFollowup['file'] = $fileUploaderService->upload($request->file('file'), 'uploads/followup');
        }
        $followup = $this->followupService->update($validatedFollowup, $id);

        return response()->json('followup update successfully');
    }

    public function destroy($id)
    {
        $followupService = $this->followupService->trash($id);

        return response()->json('followup deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->followups_id)) {
            if ($request->action_type == 'move_to_trash') {
                $followupService = $this->followupService->bulkTrash($request->followups_id);

                return response()->json('followup deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $followupService = $this->followupService->bulkRestore($request->followups_id);

                return response()->json('followup restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $followupService = $this->followupService->bulkPermanentDelete($request->followups_id);

                return response()->json('followup permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function filterAction(Request $request, $type)
    {
        return $followup = Followups::with('categories')->where('status', $type)->get();

    }

    public function restore($id)
    {
        $followupService = $this->followupService->restore($id);

        return response()->json('followup restore successfully');
    }

    public function permanentDelete($id)
    {
        $followupService = $this->followupService->permanentDelete($id);

        return response()->json('followup permanently delete successfully');
    }
}
