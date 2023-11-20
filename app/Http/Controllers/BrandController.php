<?php

namespace App\Http\Controllers;

use App\Utils\BrandUtil;
use App\Utils\UserActivityLogUtil;
use DB;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(
        private UserActivityLogUtil $userActivityLogUtil,
        private BrandUtil $brandUtil
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('brand')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->brandUtil->brandsTable();
        }

        return view('inventories.brands.index');
    }

    public function create()
    {
        return view('inventories.brands.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:brands,name',
            'photo' => 'sometimes|image|max:2048',
        ]);

        $addBrand = $this->brandUtil->addBrand($request);

        if ($addBrand) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 22, data_obj: $addBrand);
        }

        return $addBrand;
    }

    public function edit($id)
    {
        $brand = DB::table('brands')->where('id', $id)->first();

        return view('inventories.brands.ajax_view.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:brands,name,' . $id,
            'photo' => 'sometimes|image|max:2048',
        ]);

        $updateBrand = $this->brandUtil->updateBrand($request, $id);

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 22, data_obj: $updateBrand);

        return response()->json(__('brand.update_success'));
    }

    public function delete(Request $request, $id)
    {
        $deleteBrand = $this->brandUtil->deleteBrand($id);

        if (!is_null($deleteBrand)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 22, data_obj: $deleteBrand);
        }

        return response()->json(__('brand.delete_success'));
    }
}
