<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InactiveCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $customers = Customer::where('is_lead', 1)->where('status', '0')->get();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.customers.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="#"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == 0) {
                        $html .= '<div class="form-check form-switch"><input class="form-check-input change_status" data-url="'.route('crm.customers.status', $row->id).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox"></div>';
                    } else {
                        $html .= '<div class="form-check form-switch"><input class="form-check-input change_status" data-url="'.route('crm.customers.status', $row->id).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked=""></div>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'debit', 'credit', 'closing_balance', 'status'])
                ->smart(true)
                ->make(true);
        }

        $customer_group = CustomerGroup::all();

        return view('crm::customers.inactive_customers', compact('customer_group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('crm::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('crm::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('crm::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
