<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\BdDistrict\CreateBdDistrictRequest;
use Modules\Core\Http\Requests\BdDistrict\UpdateBdDistrictRequest;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class BdDistrictController extends Controller
{
    private $bdDistrictService;

    private $bdDivisionService;

    public function __construct(BdDistrictServiceInterface $bdDistrictService, BdDivisionServiceInterface $bdDivisionService)
    {
        $this->bdDistrictService = $bdDistrictService;
        $this->bdDivisionService = $bdDivisionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $districts = $this->bdDistrictService->getTrashedItem();
        } else {
            // $districts = $this->bdDistrictService->all();
            $districts = $this->bdDistrictService->districtALL();
        }

        $bd_divisions = $this->bdDivisionService->all();
        $rowCount = $this->bdDistrictService->getRowCount();
        $trashedCount = $this->bdDistrictService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($districts)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="district_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash "></span>';
                    }

                    $html = '<div class="dropdown table-dropdown">';

                    if (auth()->user()->can('hrm_districts_update')) {
                        $html .= '<a href="'.route('core.bd-districts.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_district" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_districts_delete')) {
                        $html .= '<a href="'.route('core.bd-districts.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_district" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('core::bd-district.index', compact('districts', 'bd_divisions'));
        // return view('core::bd-district.ajax_views.edit', compact('bd_divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function getDistrictByDivision($id)
    {
        $district = $this->bdDistrictService->getDistrictByDivision($id);

        return $district;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateBdDistrictRequest $request)
    {
        $district = $this->bdDistrictService->store($request->validated());

        return response()->json('District created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $bd_divisions = $this->bdDivisionService->all();
        $bd_district = $this->bdDistrictService->find($id);

        return view('core::bd-district.ajax_views.edit', compact('bd_district', 'bd_divisions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateBdDistrictRequest $request, $id)
    {
        $district = $this->bdDistrictService->update($request->validated(), $id);

        return response()->json('District updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $district = $this->bdDistrictService->trash($id);

        return response()->json('District deleted successfully');
    }

    /**
     * Permanent Delete the district Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $district = $this->bdDistrictService->permanentDelete($id);

        return response()->json('District is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $district = $this->bdDistrictService->restore($id);

        return response()->json('District restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->district_id)) {
            if ($request->action_type == 'move_to_trash') {
                $district = $this->bdDistrictService->bulkTrash($request->district_id);

                return response()->json('Districts are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $district = $this->bdDistrictService->bulkRestore($request->district_id);

                return response()->json('Districts are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $district = $this->bdDistrictService->bulkPermanentDelete($request->district_id);

                return response()->json('Districts are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
