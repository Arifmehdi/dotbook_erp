<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\GalleryCategory;
use Str;
use Yajra\DataTables\Facades\DataTables;

class GalleryCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $gallery_categories = GalleryCategory::orderBy('id', 'DESC')->get();

            return DataTables::of($gallery_categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_gallery_category')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.gallery-categories.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_gallery_category')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.gallery-categories.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('icon', function ($row) {
                    if ($row->icon) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->icon).'">';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
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
                ->rawColumns(['action', 'name', 'slug', 'icon', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::gallery.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::gallery.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (auth()->user()->can('web_add_gallery_category')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $categories = new GalleryCategory();
        if ($request->hasFile('icon')) {
            $clients->image = $FileUploadUtil->upload($request->file('icon'), 'uploads/website/gallery');
        }

        $categories->name = $request->name;
        $categories->slug = Str::of($request->name)->slug('-');
        $categories->status = $request->status ?? 0;
        $categories->save();

        return response()->json('Category has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (auth()->user()->can('web_edit_gallery_category')) {
            abort(403, 'Access Forbidden.');
        }

        $gallery_categories = GalleryCategory::find($id);

        return view('website::gallery.category.edit', compact('gallery_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id, FileUploadUtil $FileUploadUtil)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $categories = GalleryCategory::find($id);
        if ($request->hasFile('icon')) {
            $clients->image = $FileUploadUtil->upload($request->file('icon'), 'uploads/website/gallery');
        }
        $categories->name = $request->name;
        $categories->slug = Str::of($request->name)->slug('-');
        $categories->status = $request->status ?? 0;
        $categories->save();

        return response()->json('Category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $category = GalleryCategory::find($id);
        $category->delete();

        return response()->json('Gallery category has been delete successfully');
    }
}
