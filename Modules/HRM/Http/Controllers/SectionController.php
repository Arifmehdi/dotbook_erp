<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Section;
use Modules\HRM\Http\Requests\Section\CreateSectionRequest;
use Modules\HRM\Http\Requests\Section\UpdateSectionRequest;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    private $sectionService;

    private $departmentService;

    public function __construct(SectionServiceInterface $sectionService, HrmDepartmentServiceInterface $departmentService)
    {
        $this->sectionService = $sectionService;
        $this->departmentService = $departmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $departments = $this->departmentService->all()->get();

        if ($request->showTrashed == 'true') {
            $sections = $this->sectionService->getTrashedItem();
        } else {
            $sections = $this->sectionService->all();
        }

        $rowCount = $this->sectionService->getRowCount();
        $trashedCount = $this->sectionService->getTrashedCount();

        if ($request->ajax()) {
            $i = 0;

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('departmentName', function ($row) {
                    return $row->hrmDepartment->name ?? 'No HrmDepartment';
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="section_id[]" value="' . $row->id . '" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_sections_update')) {
                        $html .= '<a href="' . route('hrm.sections.' . $action1, $row->id) . '" class="action-btn c-edit ' . $action1 . '" id="' . $action1 . '_section" title="' . $type . '">' . $icon1 . '</a>';
                    }
                    if (auth()->user()->can('hrm_sections_delete')) {
                        $html .= '<a href="' . route('hrm.sections.' . $action2, $row->id) . '" class="action-btn c-delete delete" id="delete_section" title="Delete">' . $icon2 . '</a>';
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

        return view('hrm::sections.index', compact('sections', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateSectionRequest $request)
    {
        $section = $this->sectionService->store($request->validated());

        return response()->json('Section created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $section = $this->sectionService->find($id);
        $departments = $this->departmentService->all()->get();

        return view('hrm::sections.ajax_views.edit', compact('section', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function getSectionByHrmDepartment($id)
    {
        $section = $this->sectionService->getSectionByHrmDepartment($id);

        return $section;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateSectionRequest $request, $id)
    {
        $section = $this->sectionService->update($request->validated(), $id);

        return response()->json('Section updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $section = $this->sectionService->trash($id);

        return response()->json('Section deleted successfully');
    }

    /**
     * Permanent Delete the section Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $section = $this->sectionService->permanentDelete($id);

        return response()->json('Section is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $section = $this->sectionService->restore($id);

        return response()->json('Section restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->section_id)) {
            if ($request->action_type == 'move_to_trash') {
                $section = $this->sectionService->bulkTrash($request->section_id);

                return response()->json('Sections are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $section = $this->sectionService->bulkRestore($request->section_id);

                return response()->json('Sections are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $section = $this->sectionService->bulkPermanentDelete($request->section_id);

                return response()->json('Sections are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
