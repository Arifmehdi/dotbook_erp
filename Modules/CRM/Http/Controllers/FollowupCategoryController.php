<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
// use App\Models\CRM\FollowupCategory;
use Illuminate\Http\Request;
use Modules\CRM\Entities\FollowupCategory;
use Modules\CRM\Http\Requests\FollowupCategory\FollowupCategoryRequest;
use Yajra\DataTables\Facades\DataTables;

class FollowupCategoryController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $followup_category = FollowupCategory::all()->sortByDesc('id');

            return DataTables::of($followup_category)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.followup.category.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.followup.category.delete', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('crm::followup_category.index');
    }

    public function store(FollowupCategoryRequest $request)
    {
        $followup_category = new FollowupCategory();
        $followup_category->name = $request->name;
        $followup_category->description = $request->description;
        $followup_category->save();

        return response()->json('Followup Category created successfully');
    }

    public function edit(Request $request, $id)
    {
        $followup_category = FollowupCategory::find($id);

        return view('crm::followup_category.ajax_view.index', compact('followup_category'));
    }

    public function update(FollowupCategoryRequest $request, $id)
    {
        $followup_category = FollowupCategory::find($id);
        $followup_category->name = $request->name;
        $followup_category->description = $request->description;
        $followup_category->save();

        return response()->json('Followup Category updated successfully');
    }

    public function delete($id)
    {
        $followup_category = FollowupCategory::find($id);
        $followup_category->delete();

        return response()->json('Followup Category deleted successfully');
    }
}
