<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\FileUploaderServiceInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CRM\Entities\FollowupCategory;
use Modules\CRM\Entities\Followups;
use Modules\CRM\Http\Requests\BusinessLeads\BusinessLeadStoreRequest;
use Modules\CRM\Http\Requests\BusinessLeads\BusinessLeadUpdateRequest;
use Modules\CRM\Imports\LeadsImport;
use Modules\CRM\Interfaces\BusinessLeadServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class BusinessLeadController extends Controller
{
    private $businessLeadService;

    public function __construct(BusinessLeadServiceInterface $businessLeadService)
    {
        $this->businessLeadService = $businessLeadService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $businessLeads = $this->businessLeadService->getTrashedItem();
        } else {
            $businessLeads = $this->businessLeadService->all();
        }

        $rowCount = $this->businessLeadService->getRowCount();
        $trashedCount = $this->businessLeadService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($businessLeads)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="business_leads_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })

                // ->addColumn('action', function ($row) {
                //     $html = '<div class="dropdown table-dropdown">';
                //     if ($row->trashed()) {
                //         if (auth()->user()->can('crm_business_leads_update')) {
                //             $html .= '<a href="' . route('crm.business-leads.restore', $row->id) . '" class="action-btn c-edit restore" id="restore_business_leads" title="restore"> <i class="fa-solid fa-recycle"></i> </a>';
                //         }
                //         if (auth()->user()->can('crm_business_leads_delete')) {
                //             $html .= '<a href="' . route('crm.business-leads.restore' , $row->id) . '" class="action-btn c-delete delete" id="delete_department" title="Delete"> <i class="fa-solid fa-trash-check"></i> </a>';
                //         }
                //     } else {

                //         if (auth()->user()->can('crm_business_leads_update')) {
                //             $html .= '<a href="' . route('crm.business-leads.edit', $row->id) . '" class="action-btn c-edit edit" id="edit_business_leads" title="Edit"><span class="fas fa-edit"></span></a></a>';
                //         }
                //         if (auth()->user()->can('crm_business_leads_delete')) {
                //             $html .= '<a href="' . route('crm.business-leads.destroy', $row->id) . '" class="action-btn c-delete delete" id="delete_department" title="Delete"><span class="fas fa-trash "></span></a>';
                //         }
                //     }
                //     $html .= '</div>';

                //     return $html;
                // })

                ->addColumn('action', function ($row) {
                    $icon3 = '<i class="fa-regular fa-headset"></i>';

                    if ($row->trashed()) {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('crm.business-leads.restore', $row->id).'" class="action-btn c-edit restore" id="restore_business_leads" title="restore"><i class="fa-solid fa-recycle"></i></a>';
                        $html .= '<a href="'.route('crm.business-leads.permanent-delete', $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete"><i class="fa-solid fa-trash-check"></i></a>';
                        $html .= '</div>';

                        return $html;
                    } else {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('crm.business-leads.edit', $row->id).'" class="action-btn c-edit edit" id="edit_business_leads" title="Edit"><span class="fas fa-edit"></span></a></a>';
                        $html .= '<a href="'.route('crm.business-leads.followup', $row->id).'" class="action-btn c-edit followup" id="add_followup_business_leads" title="Followup">'.$icon3.'</a>';
                        $html .= '<a href="'.route('crm.business-leads.destroy', $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete"><span class="fas fa-trash "></span></a>';
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

        return view('crm::leads.business_leads.index', compact('businessLeads'));
    }

    public function store(BusinessLeadStoreRequest $request, FileUploaderServiceInterface $fileUploaderService)
    {
        $validatedBusinessLeads = $request->validated();

        if ($request->hasFile('files')) {
            $validatedBusinessLeads['files'] = $fileUploaderService->uploadMultiple($request->file('files'), 'uploads/leads/business_leads');
        }

        $businessLeads = $this->businessLeadService->store($validatedBusinessLeads);

        return response()->json('Business Lead created successfully');
    }

    public function edit($id)
    {
        $businessLeads = $this->businessLeadService->find($id);
        $files_array = \null;
        if (isset($businessLeads->files)) {
            $files_array = \json_decode($businessLeads->files, true);

            foreach ($files_array as $key => $value) {
                if (! file_exists(\public_path('uploads/leads/business_leads/'.$value))) {
                    unset($files_array[$key]);
                }
            }
            $filesJson = \json_encode($files_array);
            $businessLeads->files = $filesJson;
            $businessLeads->save();
        }

        return view('crm::leads.business_leads.ajax_view.edit', compact('businessLeads', 'files_array'));
    }

    public function followup($id)
    {
        $businessLeads = $this->businessLeadService->find($id);
        $followup = Followups::where('business_id', $id)->get();
        $followup_category = FollowupCategory::orderBy('name', 'ASC')->get();

        return view('crm::leads.business_leads.ajax_view.followup', compact('businessLeads', 'followup_category', 'followup'));
    }

    public function update(BusinessLeadUpdateRequest $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        $validatedBusinessLeads = $request->validated();
        if ($request->hasFile('files')) {
            $validatedBusinessLeads['files'] = $fileUploaderService->uploadMultiple($request->file('files'), 'uploads/leads/business_leads');
        }
        $businessLeads = $this->businessLeadService->update($validatedBusinessLeads, $id);

        return response()->json('Business Lead updated successfully');
    }

    public function destroy($id)
    {
        $businessLeads = $this->businessLeadService->trash($id);

        return response()->json('Business Lead deleted successfully');
    }

    public function permanentDelete($id)
    {
        $businessLeads = $this->businessLeadService->permanentDelete($id);

        return response()->json('Business Lead permanently deleted successfully');
    }

    public function restore($id)
    {
        $businessLeads = $this->businessLeadService->restore($id);

        return response()->json('Business Lead restored successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->business_leads_id)) {
            if ($request->action_type == 'move_to_trash') {
                $businessleads = $this->businessLeadService->bulkTrash($request->business_leads_id);

                return response()->json('Business Leads are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $businessleads = $this->businessLeadService->bulkRestore($request->business_leads_id);

                return response()->json('Business Leads are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $businessleads = $this->businessLeadService->bulkPermanentDelete($request->business_leads_id);

                return response()->json('Business Leads are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function additional_file_delete($id, $get_file)
    {
        $businessLead = $this->businessLeadService->deleteAdditionalFile($id, $get_file);
        $businessLead->save();

        return ['message' => 'Additional file deleted!'];
    }

    public function show($id)
    {

    }

    public function leadsImport()
    {
        return view('crm::leads.business_leads.import_leads.create');
    }

    public function leadsImportStore(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);
        Excel::import(new LeadsImport, $request->import_file);

        return redirect()->back()->with('success', 'Successfully imported!');
    }
}
