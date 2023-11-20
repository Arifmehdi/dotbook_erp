<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\BlogCategories;
use Str;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blog_categorys = BlogCategories::orderBy('id', 'DESC')->get();

            return DataTables::of($blog_categorys)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_blog_category')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.blog-categories.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_blog_category')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.blog-categories.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->editColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->rawColumns(['action', 'name', 'status', 'slug'])
                ->smart(true)
                ->make(true);
        }

        return view('website::blog.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::blog.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('web_add_blog_category')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required|string',
        ]);
        $category = new BlogCategories();
        $category->name = $request->name;
        $category->slug = Str::of($request->name)->slug('-');
        $category->status = $request->status ?? 0;
        $category->save();

        return response()->json('Blog category has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::blog.category.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_blog_category')) {
            abort(403, 'Access Forbidden.');
        }

        $blog_category = BlogCategories::find($id);

        return view('website::blog.category.edit', compact('blog_category'));
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
            'name' => 'required|string',
        ]);
        $category = BlogCategories::find($id);
        $category->name = $request->name;
        $category->status = $request->status ?? 0;
        $category->save();

        return response()->json('Blog category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $category = BlogCategories::find($id);
        $category->delete();

        return response()->json('Blog category has been delete successfully');
    }
}
