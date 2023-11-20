<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupController extends Controller
{
    // Customer main page/index page
    public function index(Request $request)
    {
        $customer_group = CustomerGroup::all();

        if ($request->ajax()) {

            return DataTables::of($customer_group)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';

                    $html .= '<a href="'.route('customers.groups.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';

                    $html .= '<a href="'.route('customers.groups.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('contacts::customer_group.index');
    }

    // Store customer group
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $addCustomer = CustomerGroup::create([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);

        return $addCustomer;
    }

    public function edit($id)
    {
        $customer_group = CustomerGroup::find($id);

        return view('contacts::customer_group.edit', compact('customer_group'));
    }

    // Update customer group
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $updateBank = CustomerGroup::find($id);
        $updateBank->update([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);

        return response()->json('Customer group updated successfully');
    }

    // delete customer group
    public function destroy($id)
    {
        $deleteCustomerGroup = CustomerGroup::find($id);
        if (! is_null($deleteCustomerGroup)) {
            $deleteCustomerGroup->delete();
        }

        return response()->json('Customer group deleted successfully');
    }
}
