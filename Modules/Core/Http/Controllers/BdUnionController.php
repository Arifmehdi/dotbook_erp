<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\BdUnion\CreateBdUnionRequest;
use Modules\Core\Http\Requests\BdUnion\UpdateBdUnionRequest;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class BdUnionController extends Controller
{
    private $bdUnionService;

    private $bdUpazilaService;

    public function __construct(BdUnionServiceInterface $bdUnionService, BdUpazilaServiceInterface $bdUpazilaService)
    {
        $this->bdUnionService = $bdUnionService;
        $this->bdUpazilaService = $bdUpazilaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $param = [];
        if ($request->showTrashed == 'true') {
            $unions = $this->bdUnionService->getTrashedItem();
        } else {
            $unions = $this->bdUnionService->all($param);
        }

        $upazilas = $this->bdUpazilaService->all($param);

        $rowCount = $this->bdUnionService->getRowCount();
        $trashedCount = $this->bdUnionService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($unions)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="union_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    if (isset($row->deleted_at) && ! empty($row->deleted_at)) {
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

                    if (auth()->user()->can('hrm_union_update')) {
                        $html .= '<a href="'.route('core.bd-unions.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_union" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_union_delete')) {
                        $html .= '<a href="'.route('core.bd-unions.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_union" title="Delete">'.$icon2.'</a>';
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

        return view('core::bd_unions.index', compact('unions', 'upazilas'));
    }

    /**
     * get Union By Upazilla.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function getUnionByUpazila($id)
    {
        $items = $this->bdUnionService->getUnionByUpazila($id);

        return $items;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateBdUnionRequest $request)
    {
        $union = $this->bdUnionService->store($request->validated());

        return response()->json('Union created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $param = [];
        $bd_upazilas = $this->bdUpazilaService->all($param);
        $bd_union = $this->bdUnionService->find($id);

        return view('core::bd_unions.ajax_views.edit', compact('bd_union', 'bd_upazilas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateBdUnionRequest $request, $id)
    {
        $union = $this->bdUnionService->update($request->validated(), $id);

        return response()->json('Union updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $union = $this->bdUnionService->trash($id);

        return response()->json('Union deleted successfully');
    }

    /**
     * Permanent Delete the union Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $union = $this->bdUnionService->permanentDelete($id);

        return response()->json('Union is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $union = $this->bdUnionService->restore($id);

        return response()->json('Union restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->union_id)) {
            if ($request->action_type == 'move_to_trash') {
                $union = $this->bdUnionService->bulkTrash($request->union_id);

                return response()->json('Unions are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $union = $this->bdUnionService->bulkRestore($request->union_id);

                return response()->json('Unions are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $union = $this->bdUnionService->bulkPermanentDelete($request->union_id);

                return response()->json('Unions are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
