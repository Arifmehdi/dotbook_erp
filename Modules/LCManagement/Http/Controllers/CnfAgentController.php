<?php

namespace Modules\LCManagement\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\LCManagement\Entities\CnfAgent;
use Yajra\DataTables\Facades\DataTables;

class CnfAgentController extends Controller
{
    public $converter;

    public function __construct(Converter $converter)
    {

        $this->converter = $converter;
    }

    public function index(Request $request)
    {

        if (! auth()->user()->can('cnf_agents_index')) {
            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            $cnfAgents = DB::table('cnf_agents')
                ->leftJoin('users as createdBy', 'cnf_agents.created_by_id', 'createdBy.id')
                ->leftJoin('users as updatedBy', 'cnf_agents.updated_by_id', 'updatedBy.id')
                ->select(
                    'cnf_agents.*',
                    'createdBy.prefix as c_prefix',
                    'createdBy.name as c_name',
                    'createdBy.last_name as c_last_name',
                    'updatedBy.prefix as u_prefix',
                    'updatedBy.name as u_name',
                    'updatedBy.last_name as u_last_name',
                )
                ->orderBy('id', 'desc');

            return DataTables::of($cnfAgents)
                ->addColumn('action', function ($row) {

                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="'.route('lc.cnf.agents.manage', [$row->id]).'"><i class="fas fa-tasks text-primary"></i> Manage</a>';

                    if (auth()->user()->can('cnf_agents_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('lc.cnf.agents.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('cnf_agents_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('lc.cnf.agents.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('createdBy', fn ($row) => $row->c_prefix.' '.$row->c_name.' '.$row->c_last_name)

                ->editColumn('updatedBy', fn ($row) => $row->u_prefix.' '.$row->u_name.' '.$row->u_last_name)

                ->editColumn('opening_balance', fn ($row) => '<span class="opening_balance" data-value="'.$row->opening_balance.'">'.$this->converter->format_in_bdt($row->opening_balance).'</span>')

                ->editColumn('total_service', fn ($row) => '<span class="total_service" data-value="'.$row->total_service.'">'.$this->converter->format_in_bdt($row->total_service).'</span>')

                ->editColumn('total_paid', fn ($row) => '<span class="total_paid text-success" data-value="'.$row->total_paid.'">'.$this->converter->format_in_bdt($row->total_paid).'</span>')

                ->editColumn('closing_balance', fn ($row) => '<span class="closing_balance text-danger" data-value="'.$row->closing_balance.'">'.$this->converter->format_in_bdt($row->closing_balance).'</span>')

                ->rawColumns(['action', 'createdBy', 'updatedBy', 'name', 'opening_balance', 'total_service', 'total_paid', 'closing_balance'])
                ->make(true);
        }

        return view('lcmanagement::cnf_agents.index');
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {

        if (! auth()->user()->can('cnf_agents_create')) {
            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $addInsuranceCompany = CnfAgent::create([
            'agent_id' => $request->company_id ? $request->company_id : $codeGenerationService->generate('cnf_agents', 'agent_id', 'CNFA'),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'tax_number' => $request->tax_number,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'state' => $request->state,
            'country' => $request->country,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'closing_balance' => $request->opening_balance ? $request->opening_balance : 0,
        ]);

        return response()->json($addInsuranceCompany, 200);
    }

    public function edit($id)
    {

        if (! auth()->user()->can('cnf_agents_update')) {
            abort(403, 'Access denied.');
        }

        $cnfAgent = DB::table('cnf_agents')->where('id', $id)->first();

        return view('lcmanagement::cnf_agents.ajax_view.edit', compact('cnfAgent'));
    }

    public function update(Request $request, $id)
    {

        if (! auth()->user()->can('cnf_agents_update')) {
            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $updateCnfAgent = CnfAgent::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'tax_number' => $request->tax_number,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'state' => $request->state,
            'country' => $request->country,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
        ]);

        return response()->json('Successfully CNF agent is edited.');
    }

    public function delete(Request $request, $id)
    {

        if (! auth()->user()->can('cnf_agents_delete')) {
            abort(403, 'Access denied.');
        }

        $deleteCnfAgent = CnfAgent::find($id);

        if (! is_null($deleteCnfAgent)) {

            $deleteCnfAgent->delete();
        }

        return response()->json('CNF agent is deleted successfully');
    }

    // lee_lc_4
    // which permission should i give here?
    public function addQuickInsuranceCompanyModal()
    {
        return view('lcmanagement::imports.ajax_view.add_quick_cnf_agent');
    }

    public function manage()
    {

    }
}
