<?php

namespace Modules\LCManagement\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\LCManagement\Entities\InsuranceCompany;
use Yajra\DataTables\Facades\DataTables;

class InsuranceCompanyController extends Controller
{
    public $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {

        if (! auth()->user()->can('insurance_companies_index')) {
            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            $insuranceCompanies = DB::table('insurance_companies')->orderBy('id', 'desc');

            return DataTables::of($insuranceCompanies)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="'.route('lc.insurance.companies.manage', [$row->id]).'"><i class="fas fa-tasks text-primary"></i> Manage</a>';

                    if (auth()->user()->can('insurance_companies_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('lc.insurance.companies.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('insurance_companies_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('lc.insurance.companies.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('opening_balance', fn ($row) => '<span class="opening_balance" data-value="'.$row->opening_balance.'">'.$this->converter->format_in_bdt($row->opening_balance).'</span>')

                ->editColumn('total_policy', fn ($row) => '<span class="total_policy" data-value="'.$row->total_policy.'">'.$this->converter->format_in_bdt($row->total_policy).'</span>')

                ->editColumn('total_premium_paid', fn ($row) => '<span class="total_premium_paid text-success" data-value="'.$row->total_premium_paid.'">'.$this->converter->format_in_bdt($row->total_premium_paid).'</span>')

                ->editColumn('closing_balance', fn ($row) => '<span class="closing_balance text-danger" data-value="'.$row->closing_balance.'">'.$this->converter->format_in_bdt($row->closing_balance).'</span>')

                ->rawColumns(['action', 'name', 'opening_balance', 'total_policy', 'total_premium_paid', 'closing_balance'])
                ->make(true);
        }

        return view('lcmanagement::insurance_companies.index');
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {

        if (! auth()->user()->can('insurance_companies_create')) {
            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $addInsuranceCompany = InsuranceCompany::create([
            'company_id' => $request->company_id ? $request->company_id : $codeGenerationService->generate('insurance_companies', 'company_id', 'INC'),
            'name' => $request->name,
            'branch' => $request->branch,
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
        if (! auth()->user()->can('insurance_companies_update')) {
            abort(403, 'Access denied.');
        }

        $insuranceCompany = DB::table('insurance_companies')->where('id', $id)->first();

        return view('lcmanagement::insurance_companies.ajax_view.edit', compact('insuranceCompany'));
    }

    public function update(Request $request, $id)
    {

        if (! auth()->user()->can('insurance_companies_update')) {
            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $addInsuranceCompany = InsuranceCompany::where('id', $id)->update([
            'name' => $request->name,
            'branch' => $request->branch,
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

        return response()->json('Successfully insurance company is edited.');
    }

    public function delete(Request $request, $id)
    {

        if (! auth()->user()->can('insurance_companies_delete')) {
            abort(403, 'Access denied.');
        }

        $deleteInsuranceCompany = InsuranceCompany::find($id);

        if (! is_null($deleteInsuranceCompany)) {

            $deleteInsuranceCompany->delete();
        }

        return response()->json('Insurance company is deleted successfully');
    }

    // lee_lc_1
    // which permission should i give here?
    public function addQuickInsuranceCompanyModal()
    {
        return view('lcmanagement::imports.ajax_view.add_quick_insurance_company');
    }

    public function manage()
    {

        return 'Manage page';
    }
}
