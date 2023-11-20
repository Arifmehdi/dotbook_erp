<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\BdDivision\CreateBdDivisionRequest;
use Modules\Core\Http\Requests\BdDivision\UpdateBdDivisionRequest;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class BdDivisionController extends Controller
{
    private $bdDivisionService;

    public function __construct(BdDivisionServiceInterface $bdDivisionService)
    {
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
            $bd_divisions = $this->bdDivisionService->getTrashedItem();
        } else {
            $bd_divisions = $this->bdDivisionService->all();
        }

        $rowCount = $this->bdDivisionService->getRowCount();
        $trashedCount = $this->bdDivisionService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($bd_divisions)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="division_id[]" value="'.$row->id.'" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_divisions_update')) {
                        $html .= '<a href="'.route('core.bd-divisions.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_division" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_divisions_delete')) {
                        $html .= '<a href="'.route('core.bd-divisions.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_division" title="Delete">'.$icon2.'</a>';
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

        return view('core::bd_divisions.index', compact('bd_divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateBdDivisionRequest $request)
    {
        $division = $this->bdDivisionService->store($request->validated());

        return response()->json('Division created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $division = $this->bdDivisionService->find($id);

        return view('core::bd_divisions.ajax_views.edit', compact('division'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateBdDivisionRequest $request, $id)
    {
        $division = $this->bdDivisionService->update($request->validated(), $id);

        return response()->json('Division updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $division = $this->bdDivisionService->trash($id);

        return response()->json('Division deleted successfully');
    }

    /**
     * Permanent Delete the division Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $division = $this->bdDivisionService->permanentDelete($id);

        return response()->json('Division is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $division = $this->bdDivisionService->restore($id);

        return response()->json('Division restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->division_id)) {
            if ($request->action_type == 'move_to_trash') {
                $division = $this->bdDivisionService->bulkTrash($request->division_id);

                return response()->json('Divisions are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $division = $this->bdDivisionService->bulkRestore($request->division_id);

                return response()->json('Divisions are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $division = $this->bdDivisionService->bulkPermanentDelete($request->division_id);

                return response()->json('Divisions are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
