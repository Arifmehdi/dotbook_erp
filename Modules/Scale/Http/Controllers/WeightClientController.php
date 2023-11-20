<?php

namespace Modules\Scale\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Scale\Entities\WeightClient;
use Yajra\DataTables\Facades\DataTables;

class WeightClientController extends Controller
{
    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
    ) {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('index_weight_scale_client')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $clients = '';

            $query = DB::table('weight_clients');

            $clients = $query->select('weight_clients.*')->orderBy('weight_clients.name', 'asc');

            return DataTables::of($clients)
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';

                    if (auth()->user()->can('edit_weight_scale_client')) {

                        $html .= '<a href="'.route('scale.client.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    }

                    if (auth()->user()->can('delete_weight_scale_client')) {

                        $html .= '<a href="'.route('scale.client.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    }

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('scale::client.index');
    }

    // Get quick supplier modal
    public function addWeightClientModal()
    {
        if (! auth()->user()->can('add_weight_scale_client')) {

            abort(403, 'Access Forbidden.');
        }

        return view('scale::client.ajax_view.add_client_modal');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('add_weight_scale_client')) {

            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|unique:weight_clients,phone',
        ]);

        $addWeightClint = WeightClient::create([
            'client_id' => 'WC-'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('weight_clients'), 4, '0', STR_PAD_LEFT),
            'name' => $request->name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tax_no' => $request->tax_no,
        ]);

        return $addWeightClint;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('edit_weight_scale_client')) {

            abort(403, 'Access Forbidden.');
        }

        $client = WeightClient::where('id', $id)->first();

        return view('scale::client.ajax_view.edit_client_modal', compact('client'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('edit_weight_scale_client')) {

            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|unique:weight_clients,phone,'.$id,
        ]);

        $addWeightClint = WeightClient::where('id', $id)->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tax_no' => $request->tax_no,
        ]);

        return response()->json('Weight scale client is updated successfully.');
    }

    public function delete($id)
    {
        if (! auth()->user()->can('delete_weight_scale_client')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteWeightScaleClient = WeightClient::with('weightScales')->where('id', $id)->first();

        if (count($deleteWeightScaleClient->weightScales) > 0) {

            return response()->json(['errorMsg' => 'Weight Client can\'t be deleted. This client associated with weight scale']);
        }

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly

            if (! is_null($deleteWeightScaleClient)) {

                $deleteWeightScaleClient->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Weight scale client is deleted successfully.');
    }
}
