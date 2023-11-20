<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\FileUploaderServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\CRM\Entities\LifeStage;
use Modules\CRM\Entities\Source;
use Modules\CRM\Http\Requests\Leads\LeadsStoreRequest;
use Modules\CRM\Http\Requests\Leads\LeadsUpdateRequest;
use Modules\CRM\Interfaces\LeadServiceInterface;

class LeadController extends Controller
{
    private $leadService;

    public function __construct(LeadServiceInterface $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = $this->leadService->all();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.leads.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.leads.destroy', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '<a class="dropdown-item" id="convert" href="#"><i class="fa fa-undo text-primary"></i> Convert To Customer</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('debit', function ($row) {
                    return 'incomplete';
                })
                ->editColumn('credit', function ($row) {
                    return 'incomplete';
                })
                ->editColumn('closing_balance', function ($row) {
                    return 'incomplete';
                })
                ->rawColumns(['action', 'debit', 'credit', 'closing_balance'])
                ->smart(true)
                ->make(true);
        }

        $sources = Source::all();
        $life_stages = LifeStage::all();
        $users = User::all();

        return view('crm::leads.index', compact('sources', 'life_stages', 'users'));
    }

    public function store(LeadsStoreRequest $request, FileUploaderServiceInterface $fileUploaderService)
    {
        $lead = $request->validated();
        if ($request->hasFile('photo')) {
            $lead['photo'] = $fileUploaderService->uploadThumbnail($request->file('photo'), 'uploads/customers/');
        }
        $updatedLead = $this->leadService->store($lead);

        return response()->json('Leads created successfully');
    }

    public function edit($id)
    {
        $leads = $this->leadService->find($id);
        // $assigned_to_ids = json_decode($leads['assigned_to_ids'], true);
        // dd($assigned_to_ids);
        $sources = Source::all();
        $life_stages = LifeStage::all();
        $users = User::all();

        return view('crm::leads.ajax_view.edit', compact('sources', 'life_stages', 'users', 'leads'));
    }

    public function update(LeadsUpdateRequest $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        $lead = $request->validated();
        // dd($lead['assigned_to_ids']);
        if ($request->hasFile('photo')) {
            $lead['photo'] = $fileUploaderService->uploadThumbnail($request->file('photo'), 'uploads/customers/');
        }
        $updatedLead = $this->leadService->update($lead, $id);

        return response()->json('Leads updated successfully');
    }

    public function destroy($id)
    {
        $this->leadService->destroy($id);

        return response()->json('Leads deleted successfully');
    }
}
