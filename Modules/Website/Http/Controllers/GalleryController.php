<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Gallery;
use Modules\Website\Entities\GalleryCategory;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $gallerys = Gallery::orderBy('id', 'DESC')->get();

            return DataTables::of($gallerys)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_gallery')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.gallery.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_gallery')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.gallery.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->image).'">';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
                })
                ->editColumn('category', function ($row) {
                    if ($row->gallery_category) {
                        return $row->gallery_category->name;
                    } else {
                        return 'Nothing Found';
                    }
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
                ->rawColumns(['action', 'image', 'category', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::gallery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $gallery_categories = GalleryCategory::where('status', 1)->get();

        return view('website::gallery.create', compact('gallery_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_gallery')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'image' => 'required',
            'category_id' => 'required',
        ]);

        $gallery = new Gallery();
        if ($request->hasFile('image')) {
            $gallery->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/gallery');
        }

        $gallery->gallery_category_id = $request->category_id;
        $gallery->status = $request->status ?? 0;
        $gallery->save();

        return response()->json('Gallery has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::gallery.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_gallery')) {
            abort(403, 'Access Forbidden.');
        }

        $gallery_categories = GalleryCategory::where('status', 1)->get();
        $gallery = Gallery::find($id);

        return view('website::gallery.edit', compact('gallery_categories', 'gallery'));
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
            'category_id' => 'required',
        ]);
        $gallery = Gallery::find($id);
        if ($request->hasFile('image')) {
            $gallery->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/gallery');
        }

        $gallery->gallery_category_id = $request->category_id;
        $gallery->status = $request->status ?? 0;
        $gallery->save();

        return response()->json('Gallery has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        $gallery->delete();

        return response()->json('Gallery has been delete successfully');
    }
}
