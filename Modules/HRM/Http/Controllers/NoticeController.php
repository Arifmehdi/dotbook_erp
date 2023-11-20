<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Notice;
use Modules\HRM\Http\Requests\Notice\CreateNoticeRequest;
use Modules\HRM\Http\Requests\Notice\UpdateNoticeRequest;
use Modules\HRM\Interface\NoticeServiceInterface;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class NoticeController extends Controller
{
    protected $noticeService;

    public function __construct(NoticeServiceInterface $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $notice = $this->noticeService->getTrashedItem();
        } else {
            $notice = $this->noticeService->allNotice($request);
        }

        $rowCount = $this->noticeService->getRowCount();
        $trashedCount = $this->noticeService->getTrashedCount();

        if ($request->ajax()) {
            $generalSettings = GeneralSetting::first();

            return DataTables::of($notice)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="notice_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('created_at', function ($row) use ($generalSettings) {
                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {

                    if ($row->trashed()) {
                        $html = '<div class="btn-group" role="group">';
                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        // if (auth()->user()->can('hrm_salary_statement_view')) {
                        // }
                        // if (auth()->user()->can('hrm_salary_settlement_create')) {
                        $html .= '<a class="dropdown-item restore" href="'.route('hrm.notices.restore', $row->id).'" id="restore"><i class="fa-duotone fa-edit"></i> Restore</a>';
                        // }
                        // if (auth()->user()->can('hrm_salary_statement_view')) {
                        // }
                        // $html .= '</div>';
                        $html .= '<a class="dropdown-item delete" href="'.route('hrm.notices.permanent-delete', $row->id).'"><i class="fa-regular fa-trash"></i> Permanent Delete</a>';
                        $html .= '</div>';
                    } else {
                        $html = '<div class="btn-group" role="group">';
                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        // if (auth()->user()->can('hrm_salary_statement_view')) {
                        // }
                        // if (auth()->user()->can('hrm_salary_settlement_create')) {
                        $html .= '<a class="dropdown-item" style="border-bottom: 1px solid #d7d7d7;" href="'.route('hrm.notices.show', $row->id).'" id="view"><i class="fa-duotone fa-eye"></i> View</a>';
                        $html .= '<a class="dropdown-item edit" href="'.route('hrm.notices.edit', $row->id).'" id="edit"><i class="fa-duotone fa-edit"></i> Edit</a>';
                        // }
                        // if (auth()->user()->can('hrm_salary_statement_view')) {
                        // }
                        // $html .= '</div>';
                        $html .= '<a class="dropdown-item delete" href="'.route('hrm.notices.destroy', $row->id).'"><i class="fa-regular fa-trash"></i> Delete</a>';
                        $html .= '</div>';
                    }

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input change_status" data-url="'.route('hrm.notice.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked value="1"/>';
                        $html .= '</div>';

                        return $html;
                    } else {
                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input change_status" data-url="'.route('hrm.notice.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" value="2"/>';
                        $html .= '</div>';

                        return $html;
                    }
                })
                ->rawColumns(['action', 'status', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->make(true);
        }

        return view('hrm::notice.index');
    }

    public function store(CreateNoticeRequest $request)
    {
        $notice = $this->noticeService->store($request->validated());
        if ($notice) {
            return response()->json('Notice created successfully!');
        }
    }

    public function show($id)
    {
        $notice = Notice::find($id);

        return view('hrm::notice.ajax_views.show', compact('notice'));
    }

    public function edit($id)
    {
        $notice = Notice::find($id);

        return view('hrm::notice.ajax_views.edit', compact('notice'));
    }

    public function update(UpdateNoticeRequest $request, $id)
    {
        $notice = $this->noticeService->update($request->validated(), $id);
        if ($notice) {
            return response()->json('Notice updated successfully!');
        }
    }

    public function destroy($id)
    {
        $this->noticeService->trash($id);

        return response()->json('Notice deleted successfully!');
    }

    public function noticePrint($id)
    {
        $notice = Notice::find($id);
        $pdf = PDF::loadView('hrm::notice.ajax_views.print', compact('notice'));
        $pdf->stream('notice.pdf');
    }

    public function noticeStatus(Request $request, $id)
    {
        if ($request['status'] == 1) {
            $notice = Notice::find($id);
            $notice->update([
                'is_active' => 2,
            ]);

            return response()->json('Your notice status inactive now');
        } else {
            $notice = Notice::find($id);
            $notice->update([
                'is_active' => 1,
            ]);

            return response()->json('Your notice status active now');
        }
    }

    public function permanentDelete($id)
    {
        $notice = $this->noticeService->permanentDelete($id);

        return response()->json('Notice is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $notice = $this->noticeService->restore($id);

        return response()->json('Notice restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->notice_id)) {
            if ($request->action_type == 'move_to_trash') {
                $notice = $this->noticeService->bulkTrash($request->notice_id);

                return response()->json('Notice are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $notice = $this->noticeService->bulkRestore($request->notice_id);

                return response()->json('Notice are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $notice = $this->noticeService->bulkPermanentDelete($request->notice_id);

                return response()->json('Notice are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
