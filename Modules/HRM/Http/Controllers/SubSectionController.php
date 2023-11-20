<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\SubSection\CreateSubSectionRequest;
use Modules\HRM\Http\Requests\SubSection\UpdateSubSectionRequest;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class SubSectionController extends Controller
{
    private $subSectionService;

    private $departmentService;

    public function __construct(SubSectionServiceInterface $subSectionService, HrmDepartmentServiceInterface $departmentService)
    {
        $this->subSectionService = $subSectionService;
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
            $subSections = $this->subSectionService->getTrashedItem();
        } else {
            $subSections = $this->subSectionService->all();
        }

        $rowCount = $this->subSectionService->getRowCount();
        $trashedCount = $this->subSectionService->getTrashedCount();
        if ($request->ajax()) {
            $i = 0;

            return DataTables::of($subSections)
                ->addIndexColumn()
                ->addColumn('sectionName', function ($row) {
                    $name = $row->section->name ?? 'No Section';

                    return $name;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="sub_section_id[]" value="' . $row->id . '" class="mt-2 check1">
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

                    if (auth()->user()->can('hrm_sub_sections_update')) {
                        $html .= '<a href="' . route('hrm.subsections.' . $action1, $row->id) . '" class="action-btn c-edit ' . $action1 . '" id="' . $action1 . '_sub_section" title="' . $type . '">' . $icon1 . '</a>';
                    }
                    if (auth()->user()->can('hrm_sub_sections_delete')) {
                        $html .= '<a href="' . route('hrm.subsections.' . $action2, $row->id) . '" class="action-btn c-delete delete" id="delete_sub_section" title="Delete">' . $icon2 . '</a>';
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

        return view('hrm::sub_sections.index', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateSubSectionRequest $request)
    {
        $subSection = $this->subSectionService->store($request->validated());

        return response()->json('Sub Section created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $subSection = $this->subSectionService->find($id);
        $departments = $this->departmentService->all()->get();

        return view('hrm::sub_sections.ajax_views.edit', compact('departments', 'subSection'));
    }

    /**
     * Get Sub Section By Section.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function getSubSectionBySection($id)
    {
        $subSection = $this->subSectionService->getSubSectionBySection($id);

        return $subSection;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateSubSectionRequest $request, $id)
    {
        $subSection = $this->subSectionService->update($request->validated(), $id);

        return response()->json('Sub Section updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $subSection = $this->subSectionService->trash($id);

        return response()->json('Sub Section deleted successfully');
    }

    /**
     * Permanent Delete the subSection Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $subSection = $this->subSectionService->permanentDelete($id);

        return response()->json('Sub Section is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $subSection = $this->subSectionService->restore($id);

        return response()->json('Sub Section restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->sub_section_id)) {
            if ($request->action_type == 'move_to_trash') {
                $subSection = $this->subSectionService->bulkTrash($request->sub_section_id);

                return response()->json('Sub Sections are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $subSection = $this->subSectionService->bulkRestore($request->sub_section_id);

                return response()->json('Sub Sections are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $subSection = $this->subSectionService->bulkPermanentDelete($request->sub_section_id);

                return response()->json('Sub Sections are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function getSubsectionDoPluck(Request $request)
    {
        $id = $request->id;
        $subsection = $this->subSectionService->getSubSectionDoPluck($request);

        return $subsection;
    }
}
