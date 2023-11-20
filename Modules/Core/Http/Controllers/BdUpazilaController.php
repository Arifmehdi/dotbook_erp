<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\BdUpazila\CreateBdUpazilaRequest;
use Modules\Core\Http\Requests\BdUpazila\UpdateBdUpazilaRequest;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class BdUpazilaController extends Controller
{
    private $bdUpazilaService;

    private $bdDistrictService;

    public function __construct(BdUpazilaServiceInterface $bdUpazilaService, BdDistrictServiceInterface $bdDistrictService)
    {
        $this->bdUpazilaService = $bdUpazilaService;
        $this->bdDistrictService = $bdDistrictService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $upazilas = $this->bdUpazilaService->getTrashedItem();
        } else {
            $param = [];
            $upazilas = $this->bdUpazilaService->all($param);
        }

        // $districts = $this->bdDistrictService->all();
        $districts = $this->bdDistrictService->districtALL();

        $rowCount = $this->bdUpazilaService->getRowCount();
        $trashedCount = $this->bdUpazilaService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($upazilas)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="thana_id[]" value="'.$row->id.'" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_thana_update')) {
                        $html .= '<a href="'.route('core.bd-upazila.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_thana" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_thana_delete')) {
                        $html .= '<a href="'.route('core.bd-upazila.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_thana" title="Delete">'.$icon2.'</a>';
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

        return view('core::bd_upazila.index', compact('upazilas', 'districts'));
    }

    /**
     * get Upazila By District.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function getUpazilaByDistrict($id)
    {
        $items = $this->bdUpazilaService->getUpazilaByDistrict($id);

        return $items;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateBdUpazilaRequest $request)
    {
        $thana = $this->bdUpazilaService->store($request->validated());

        return response()->json('Upazila created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        // $bd_districts = $this->bdDistrictService->all();
        $bd_districts = $this->bdDistrictService->districtALL();
        $bd_upazila = $this->bdUpazilaService->find($id);

        return view('core::bd_upazila.ajax_views.edit', compact('bd_upazila', 'bd_districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateBdUpazilaRequest $request, $id)
    {
        $thana = $this->bdUpazilaService->update($request->validated(), $id);

        return response()->json('Upazila updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $thana = $this->bdUpazilaService->trash($id);

        return response()->json('Upazila deleted successfully');
    }

    /**
     * Permanent Delete the thana Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $thana = $this->bdUpazilaService->permanentDelete($id);

        return response()->json('Upazila is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $thana = $this->bdUpazilaService->restore($id);

        return response()->json('Upazila restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->thana_id)) {
            if ($request->action_type == 'move_to_trash') {
                $thana = $this->bdUpazilaService->bulkTrash($request->thana_id);

                return response()->json('Upazila are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $thana = $this->bdUpazilaService->bulkRestore($request->thana_id);

                return response()->json('Upazila are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $thana = $this->bdUpazilaService->bulkPermanentDelete($request->thana_id);

                return response()->json('Upazila are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
