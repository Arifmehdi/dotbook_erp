<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\JobCategories;
use Str;
use Yajra\DataTables\Facades\DataTables;

class JobCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $job_categories = JobCategories::orderBy('id', 'DESC')->get();

            return DataTables::of($job_categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_job_category')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.job-categories.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_job_category')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.job-categories.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'name', 'slug', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::jobs.jod_categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::jobs.jod_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('web_add_job_category')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $category = new JobCategories();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->status = $request->status ?? 0;
        $category->save();

        return response()->json('Job category has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::jobs.jod_categories.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_job_category')) {
            abort(403, 'Access Forbidden.');
        }

        $job_categories = JobCategories::find($id);

        return view('website::jobs.jod_categories.edit', compact('job_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category = JobCategories::find($id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();

        return response()->json('Job category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $category = JobCategories::find($id);
        $category->delete();

        return response()->json('Job category has been delete successfully');
    }
}
