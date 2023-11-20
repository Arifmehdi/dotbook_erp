<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\FileUploaderServiceInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CRM\Entities\FollowupCategory;
use Modules\CRM\Entities\Followups;
use Modules\CRM\Http\Requests\IndividualLeads\IndividualLeadStoreRequest;
use Modules\CRM\Http\Requests\IndividualLeads\IndividualLeadUpdateRequest;
use Modules\CRM\Imports\IndividualLeadsImport;
use Modules\CRM\Interfaces\IndividualLeadServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class IndividualLeadController extends Controller
{
    private $IndividualLeadService;

    public function __construct(IndividualLeadServiceInterface $IndividualLeadService)
    {
        $this->IndividualLeadService = $IndividualLeadService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $individualLeads = $this->IndividualLeadService->getTrashedItem();
        } else {
            $individualLeads = $this->IndividualLeadService->all();
        }

        $rowCount = $this->IndividualLeadService->getRowCount();
        $trashedCount = $this->IndividualLeadService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($individualLeads)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="individual_leads_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })

                 // ->addColumn('action', function ($row) {
                //     if ($row->trashed()){
                //         $html = '';
                //         $html .= '<div class="btn-group" role="group">';
                //         $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                //         $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                //         $html .= '<a class="dropdown-item" href="'. route('crm.business-leads.edit', $row->id) .'" id="edit_business_leads" class="action-btn c-edit edit" title="Edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                //         $html .= '<a class="dropdown-item" id="delete_department" class="action-btn c-delete delete" href="'. route('crm.followup.category.delete', $row->id) .'" title="Delete"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                //         $html .= '</div>';
                //         $html .= '</div>';
                //         return $html;
                //     }else{
                //         $html = '';
                //         $html .= '<div class="btn-group" role="group">';
                //         $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                //         $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                //         $html .= '<a class="dropdown-item" href="'. route('crm.business-leads.edit', $row->id) .'" id="edit_business_leads" class="action-btn c-edit edit" title="Edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                //         $html .= '<a class="dropdown-item" id="delete_department" class="action-btn c-delete delete" href="'. route('crm.business-leads.destroy', $row->id) .'" title="Delete"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                //         $html .= '</div>';
                //         $html .= '</div>';
                //         return $html;
                //     }
                // })

                ->addColumn('action', function ($row) {
                    $icon3 = '<i class="fa-regular fa-headset"></i>';

                    if ($row->trashed()) {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('crm.individual-leads.restore', $row->id).'" class="action-btn c-edit restore" id="restore_business_leads" title="restore"><i class="fa-solid fa-recycle"></i></a>';
                        $html .= '<a href="'.route('crm.individual-leads.permanent-delete', $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete"><i class="fa-solid fa-trash-check"></i></a>';
                        $html .= '</div>';

                        return $html;
                    } else {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('crm.individual-leads.edit', $row->id).'" class="action-btn c-edit edit" id="edit_business_leads" title="Edit"><span class="fas fa-edit"></span></a></a>';
                        $html .= '<a href="'.route('crm.individual-leads.followup', $row->id).'" class="action-btn c-edit followup" id="add_followup_business_leads" title="Followup">'.$icon3.'</a>';
                        $html .= '<a href="'.route('crm.individual-leads.destroy', $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete"><span class="fas fa-trash "></span></a>';
                        $html .= '</div>';

                        return $html;
                    }
                })

                ->addColumn('status_color', function ($row) {

                    if (count($row->followup_status) > 0) {
                        $status = $row->followup_status[0]->status;
                        if ($row->followup_status[0]->status == 'Interested') {
                            $color = 'success';
                        } elseif ($row->followup_status[0]->status == 'Pending') {
                            $color = 'info';
                        } elseif ($row->followup_status[0]->status == 'Not Connect') {
                            $color = 'secondary';
                        } elseif ($row->followup_status[0]->status == 'Not Interested') {
                            $color = 'danger';
                        }
                    } else {
                        $status = 'record not found';
                        $color = 'dark';
                    }

                    $html = '';
                    $html .= '<div class="text-center">
                                     <span >'.$status.'</span>
                                    <span class="badge bg-'.$color.' d-inline-block p-15 rounded-circle"></span>
                                </div>';

                    return $html;
                })

                ->rawColumns(['action', 'check', 'status_color'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('crm::leads.individual_leads.index', compact('individualLeads'));
    }

    public function store(IndividualLeadStoreRequest $request, FileUploaderServiceInterface $fileUploaderService)
    {
        $validatedIndividualLeads = $request->validated();

        if ($request->hasFile('files')) {
            $validatedIndividualLeads['files'] = $fileUploaderService->uploadMultiple($request->file('files'), 'uploads/leads/individual_leads');
        }

        $individualLeads = $this->IndividualLeadService->store($validatedIndividualLeads);

        return response()->json('Individual Lead created successfully');
    }

    public function edit($id)
    {
        $individualLeads = $this->IndividualLeadService->find($id);
        $files_array = \null;
        if (isset($individualLeads->files)) {
            $files_array = \json_decode($individualLeads->files, true);

            foreach ($files_array as $key => $value) {
                if (! file_exists(\public_path('uploads/leads/individual_leads/'.$value))) {
                    unset($files_array[$key]);
                }
            }
            $filesJson = \json_encode($files_array);
            $individualLeads->files = $filesJson;
            $individualLeads->save();
        }

        return view('crm::leads.Individual_leads.ajax_view.edit', compact('individualLeads', 'files_array'));
    }

    public function followup($id)
    {
        $individualLeads = $this->IndividualLeadService->find($id);
        $followup = Followups::with('individual_lead')->where('individual_id', $id)->get();
        $followup_category = FollowupCategory::orderBy('name', 'ASC')->get();

        return view('crm::leads.Individual_leads.ajax_view.followup', compact('individualLeads', 'followup_category', 'followup'));
    }

    public function update(IndividualLeadUpdateRequest $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        $validatedIndividualLeads = $request->validated();
        if ($request->hasFile('files')) {
            $validatedIndividualLeads['files'] = $fileUploaderService->uploadMultiple($request->file('files'), 'uploads/leads/individual_leads');
        }
        $individualLeads = $this->IndividualLeadService->update($validatedIndividualLeads, $id);

        return response()->json('Individual Lead updated successfully');
    }

    public function destroy($id)
    {
        $individualLeads = $this->IndividualLeadService->trash($id);

        return response()->json('Individual Lead deleted successfully');
    }

    public function permanentDelete($id)
    {
        $individualLeads = $this->IndividualLeadService->permanentDelete($id);

        return response()->json('Individual Lead permanently deleted successfully');
    }

    public function restore($id)
    {
        $individualLeads = $this->IndividualLeadService->restore($id);

        return response()->json('Individual Lead restored successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->individual_leads_id)) {
            if ($request->action_type == 'move_to_trash') {
                $individualLeads = $this->IndividualLeadService->bulkTrash($request->individual_leads_id);

                return response()->json('Individual Leads are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $individualLeads = $this->IndividualLeadService->bulkRestore($request->individual_leads_id);

                return response()->json('Individual Leads are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $individualLeads = $this->IndividualLeadService->bulkPermanentDelete($request->individual_leads_id);

                return response()->json('Individual Leads are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function additional_file_delete($id, $get_file)
    {
        $individualLead = $this->IndividualLeadService->deleteAdditionalFile($id, $get_file);
        $individualLead->save();

        return ['message' => 'Additional file deleted!'];
    }

    public function show($id)
    {

    }

    public function leadsImport()
    {
        return view('crm::leads.Individual_leads.import_leads.create');
    }

    public function leadsImportStore(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);
        Excel::import(new IndividualLeadsImport, $request->import_file);

        return redirect()->back()->with('success', 'Successfully imported!');
    }
}
