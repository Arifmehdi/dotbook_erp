<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\ProductCategory;
use Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $product_categorys = ProductCategory::orderBy('id', 'DESC')->get();

            return DataTables::of($product_categorys)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_category')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.categories.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_category')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.categories.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
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

        return view('website::products.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::products.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('web_add_category')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $category = new ProductCategory();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->slug = Str::of($request->name)->slug('-');
        $category->save();

        return response()->json('Product category has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::products.category.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_category')) {
            abort(403, 'Access Forbidden.');
        }

        $category = ProductCategory::find($id);

        return view('website::products.category.edit', compact('category'));
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

        $category = ProductCategory::find($id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();

        return response()->json('Product category has been created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $category = ProductCategory::find($id);
        $category->delete();

        return response()->json('Category has been delete successfully');
    }
}
