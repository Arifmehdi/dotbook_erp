<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\ShiftAdjustment;
use Modules\HRM\Http\Requests\ShiftAdjustment\CreateShiftAdjustmentRequest;
use Modules\HRM\Http\Requests\ShiftAdjustment\UpdateShiftAdjustmentRequest;
use Modules\HRM\Interface\ShiftAdjustmentServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ShiftAdjustmentController extends Controller
{
    public function __construct(private ShiftAdjustmentServiceInterface $shiftAdjustmentService, private ShiftServiceInterface $shiftService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $shifts = $this->shiftService->all();

        if ($request->showTrashed == 'true') {
            $shiftAdjustments = $this->shiftAdjustmentService->getTrashedItem();
        } else {
            $shiftAdjustments = $this->shiftAdjustmentService->all();
        }

        $rowCount = $this->shiftAdjustmentService->getRowCount();
        $trashedCount = $this->shiftAdjustmentService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($shiftAdjustments)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="shiftAdjustment_id[]" value="'.$row->id.'" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_shift_adjustments_update')) {
                        $html .= '<a href="'.route('hrm.shift-adjustments.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_shift_adjustment" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_shift_adjustments_delete')) {
                        $html .= '<a href="'.route('hrm.shift-adjustments.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_shift_adjustment" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('applied_date_from', function ($row) {
                    if (isset($row->applied_date_from) &&
                        strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) &&
                        strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)
                    ) {
                        // return '<span style="color: red;font-weight: bolder;">' . date('d F, Y', strtotime($row->applied_date_from)) . '</span>';
                        return '<span style="color: red;font-weight: bolder;">'.date(config('hrm.date_format'), strtotime($row->applied_date_from)).'</span>';
                    }

                    return date(config('hrm.date_format'), strtotime($row->applied_date_from));
                })
                ->editColumn('applied_date_to', function ($row) {
                    if (isset($row->applied_date_to) &&
                        strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) &&
                        strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)
                    ) {
                        return '<span style="color: red;font-weight: bolder;">'.date(config('hrm.date_format'), strtotime($row->applied_date_to)).'</span>';
                    }

                    return date(config('hrm.date_format'), strtotime($row->applied_date_to));
                })
                ->editColumn('start_time', function ($row) {
                    if (
                        isset($row->start_time) &&
                        strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) &&
                        strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)
                    ) {
                        $row->start_time = Carbon::parse($row->start_time)->format(config('hrm.time_format'));

                        return '<span style="color: red;font-weight: bolder;">'.Carbon::parse($row->start_time)->format(\config('hrm.time_format')).'</span>';
                    }

                    return $row->start_time;
                })
                ->editColumn('end_time', function ($row) {
                    if (
                        isset($row->end_time) &&
                        strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) &&
                        strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)
                    ) {
                        $row->end_time = Carbon::parse($row->end_time)->format(config('hrm.time_format'));

                        return '<span style="color: red;font-weight: bolder;">'.Carbon::parse($row->end_time)->format(config('hrm.time_format')).'</span>';
                    }

                    return $row->end_time;
                })
                ->editColumn('shift_name', function ($row) {
                    if (isset(($row->applied_date_from)) && strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) && strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)) {
                        return '<span style="color: red;font-weight: bolder;">'.$row?->shift?->name.'</span>';
                    }

                    return $row?->shift?->name;
                })
                ->editColumn('late_count', function ($row) {
                    if (isset($row->late_count) && strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) && strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)) {
                        return '<span style="color: red;font-weight: bolder;">'.Carbon::parse($row->late_count)->format(config('hrm.time_format')).'</span>';
                    }

                    return $row->late_count;
                })
                ->editColumn('break_start', function ($row) {
                    if (isset($row->break_start) && strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) && strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)) {
                        return '<span style="color: red;font-weight: bolder;">'.Carbon::parse($row->break_start)->format(\config('hrm.time_format')).'</span>';
                    }

                    return $row->break_start;
                })
                ->editColumn('break_end', function ($row) {
                    if (isset($row->break_end) && strtotime(date('Y-m-d')) >= strtotime($row->applied_date_from) && strtotime(date('Y-m-d')) <= strtotime($row->applied_date_to)) {
                        return '<span style="color: red;font-weight: bolder;">'.Carbon::parse($row->break_end)->format(config('hrm.time_format')).'</span>';
                    }

                    return $row->break_end;
                })
                ->editColumn('with_break', function ($row) {
                    return $row->with_break == 1 ? '<span class="badge bg-primary text-white">Allowed</span>' : '<span class="badge bg-info text-white">Not-Allowed</span>';
                })

                ->rawColumns(['applied_date_from', 'applied_date_to', 'action', 'check', 'with_break', 'start_time', 'end_time', 'shift_name', 'late_count', 'break_start', 'break_end'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::shift_adjustments.index', compact('shifts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateShiftAdjustmentRequest $request)
    {

        $shiftAdjustment = $this->shiftAdjustmentService->store($request);

        return response()->json('Shift Adjustment created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->find($id);
        $shifts = $this->shiftService->all();

        return view('hrm::shift_adjustments.ajax_views.edit', compact('shiftAdjustment', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateShiftAdjustmentRequest $request, $id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->update($request, $id);

        return response()->json('Shift Adjustment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->trash($id);

        return response()->json('Shift Adjustment deleted successfully');
    }

    /**
     * Permanent Delete the shiftAdjustment Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->permanentDelete($id);

        return response()->json('Shift Adjustment is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $shiftAdjustment = $this->shiftAdjustmentService->restore($id);

        return response()->json('Shift Adjustment restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->shiftAdjustment_id)) {
            if ($request->action_type == 'move_to_trash') {
                $shiftAdjustment = $this->shiftAdjustmentService->bulkTrash($request->shiftAdjustment_id);

                return response()->json('Shifts Adjustment are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $shiftAdjustment = $this->shiftAdjustmentService->bulkRestore($request->shiftAdjustment_id);

                return response()->json('Shifts Adjustment are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $shiftAdjustment = $this->shiftAdjustmentService->bulkPermanentDelete($request->shiftAdjustment_id);

                return response()->json('Shifts Adjustment are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
