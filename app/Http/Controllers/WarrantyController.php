<?php

namespace App\Http\Controllers;

use App\Utils\UserActivityLogUtil;
use App\Utils\WarrantyUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarrantyController extends Controller
{
    protected $userActivityLogUtil;

    protected $warrantyUtil;

    public function __construct(UserActivityLogUtil $userActivityLogUtil, WarrantyUtil $warrantyUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->warrantyUtil = $warrantyUtil;
    }

    // Warranty main page/index page
    public function index(Request $request)
    {
        if (! auth()->user()->can('warranties')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->warrantyUtil->warrantiesTable();
        }

        return view('inventories.warranties.index');
    }

    public function create()
    {
        return view('inventories.warranties.ajax_view.create');
    }

    // Store warranty
    public function store(Request $request)
    {
        if (! auth()->user()->can('warranties')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        $addWarranty = $this->warrantyUtil->addWarranty($request);

        if ($addWarranty) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 25, data_obj: $addWarranty);
        }

        return $addWarranty;
    }

    public function edit($id)
    {
        $warranty = DB::table('warranties')->where('id', $id)->first();

        return view('inventories.warranties.ajax_view.edit', compact('warranty'));
    }

    // Update warranty
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('warranties')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        $updateWarranty = $this->warrantyUtil->updateWarranty($request, $id);

        if ($updateWarranty) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 25, data_obj: $updateWarranty);
        }

        return response()->json('Warranty updated successfully');
    }

    // Delete warranty
    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('warranties')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteWarranty = $this->warrantyUtil->deleteWarranty($id);

        if ($deleteWarranty['errorMsg'] == '') {

            if (! is_null($deleteWarranty)) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 25, data_obj: $deleteWarranty);
            }

            return response()->json('Warranty deleted successfully');
        } else {

            return response()->json(['errorMsg' => $deleteWarranty['errorMsg']]);
        }
    }
}
