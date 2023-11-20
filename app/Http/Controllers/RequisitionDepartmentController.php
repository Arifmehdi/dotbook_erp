<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RequisitionDepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $departments = DB::table('departments')->orderBy('departments.name', 'asc')->get();

            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('requisitions.departments.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('requisitions.departments.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })->rawColumns(['action'])->smart(true)->make(true);
        }

        return view('procurement.requisitions.departments.index');
    }

    public function create()
    {
        return view('procurement.requisitions.departments.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $addDepartment = new Department();
        $addDepartment->name = $request->name;
        $addDepartment->phone = $request->phone;
        $addDepartment->address = $request->address;
        $addDepartment->save();

        return $addDepartment;
    }

    public function edit($id)
    {
        $dep = DB::table('departments')->where('id', $id)->first();

        return view('procurement.requisitions.departments.ajax_view.edit', compact('dep'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $updateDepartment = Department::where('id', $id)->first();

        $updateDepartment->name = $request->name;
        $updateDepartment->phone = $request->phone;
        $updateDepartment->address = $request->address;
        $updateDepartment->save();

        return response()->json('Department updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $deleteDepartment = Department::with(['requisitions', 'stockIssues'])->where('id', $id)->first();

        if (count($deleteDepartment->requisitions) > 0) {

            return response()->json(['errorMsg' => 'Department can\'t be deleted. One or more requisitons are belonging in the department.']);
        }

        if (count($deleteDepartment->stockIssues) > 0) {

            return response()->json(['errorMsg' => 'Department can\'t be deleted. One or more stock issues are belonging in the department.']);
        }

        if (! is_null($deleteDepartment)) {

            // $this->userActivityLogUtil->addLog(action: 3, subject_type: 20, data_obj: $deleteCategory);
            $deleteDepartment->delete();
        }

        DB::statement('ALTER TABLE departments AUTO_INCREMENT = 1');

        return response()->json('Department deleted successfully!');
    }

    public function print()
    {
        $departments = DB::table('departments')->get();

        return view('procurement.requisitions.departments.ajax_view.print', compact('departments'));
    }
}
