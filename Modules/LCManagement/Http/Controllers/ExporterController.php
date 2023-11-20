<?php

namespace Modules\LCManagement\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Http\Request;
use Modules\LCManagement\Entities\Exporter;
use Yajra\DataTables\Facades\DataTables;

class ExporterController extends Controller
{
    public $invoiceVoucherRefIdUtil;

    public function __construct(InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {

        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = Exporter::all();

            return DataTables::of($query)
                ->addColumn('action', function ($row) {

                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('lc.exporters.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('lc.exporters.destroy', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    $html .= '<div class="form-check form-switch">';
                    if ($row->status == 1) {
                        // code...
                        $html .= '<input class="form-check-input change_status" data-url="'.route('lc.exporters.status', [$row->status, $row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    } else {
                        // code...
                        $html .= '<input class="form-check-input change_status" data-url="'.route('lc.exporters.status', [$row->status, $row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    }
                    // $html .= "<input class='form-check-input change_status' data-url='' style='width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;' type='checkbox' {{ $row->status == 1 ? 'checked' : '' }}/>";
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $exporters = Exporter::all();
        $total_exporters = Exporter::count();
        $total_active = Exporter::where('status', '1')->count();

        return view('lcmanagement::exporters.index', [
            'exporters' => $exporters,
            'total_exporters' => $total_exporters,
            'total_active' => $total_active,
        ]);
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {

        if (! auth()->user()->can('exporters_create')) {
            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $exporters = new Exporter();
        $exporters->exporter_id = $request->asset_code ? $request->asset_code : $codeGenerationService->generate('exporters', 'exporter_id', 'EP');
        $exporters->name = $request->name;
        $exporters->phone = $request->phone;
        $exporters->business = $request->business;
        $exporters->alternative_number = $request->alternative_number;
        $exporters->land_line = $request->land_line;
        $exporters->email = $request->email;
        $exporters->date_of_birth = $request->date_of_birth;
        $exporters->id_proof_name = $request->id_proof_name;
        $exporters->id_proof_number = $request->id_proof_number;
        $exporters->tax_number = $request->tax_number;
        $exporters->address = $request->address;
        $exporters->city = $request->city;
        $exporters->state = $request->state;
        $exporters->zip_code = $request->zip_code;
        $exporters->country = $request->country;
        $exporters->opening_balance = $request->opening_balance;
        $exporters->save();

        return response()->json('Exporter created successfully');
    }

    public function edit($id)
    {
        $exporters = Exporter::find($id);

        return view('lcmanagement::exporters.ajax_view.edit', [
            'exporters' => $exporters,
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $exporters = Exporter::find($id);
        $exporters->name = $request->name;
        $exporters->phone = $request->phone;
        $exporters->business = $request->business;
        $exporters->alternative_number = $request->alternative_number;
        $exporters->land_line = $request->land_line;
        $exporters->email = $request->email;
        $exporters->date_of_birth = $request->date_of_birth;
        $exporters->id_proof_name = $request->id_proof_name;
        $exporters->id_proof_number = $request->id_proof_number;
        $exporters->tax_number = $request->tax_number;
        $exporters->address = $request->address;
        $exporters->city = $request->city;
        $exporters->state = $request->state;
        $exporters->zip_code = $request->zip_code;
        $exporters->country = $request->country;
        $exporters->save();

        return response()->json('Exporter updated successfully');

    }

    public function destroy($id)
    {
        $exporters = Exporter::find($id);
        $exporters->delete();

        return response()->json(['errorMsg' => 'Exporter deleted successfully']);
    }

    public function status($status, $id)
    {

        $exporters = Exporter::find($id);

        if ($exporters->status == 1) {
            $exporters->status = 0;
        } else {
            $exporters->status = 1;
        }
        $exporters->save();

        return response()->json('Exporter status change successfully');

    }
}
