<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Testimonial;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $testimonials = Testimonial::orderBy('id', 'DESC')->get();

            return DataTables::of($testimonials)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_testimonial')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.testimonial.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_testimonial')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.testimonial.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('designation', function ($row) {
                    return $row->designation;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'image', 'title', 'name', 'email', 'phone', 'slug', 'designation', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::testimonial.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_testimonial')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
            'name' => 'required|string',
            'designation' => 'required',
            'rating' => 'required',
        ]);

        $testimonials = new Testimonial();
        $testimonials->title = $request->title;
        $testimonials->name = $request->name;
        $testimonials->designation = $request->designation;
        $testimonials->description = $request->description;
        $testimonials->rating = $request->rating;
        $testimonials->status = $request->status;
        if ($request->hasFile('image')) {
            $testimonials->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/testimonial');
        }
        $testimonials->save();

        return response()->json('Testimonial has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::testimonial.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_testimonial')) {
            abort(403, 'Access Forbidden.');
        }

        $testimonial = Testimonial::find($id);

        return view('website::testimonial.edit', compact('testimonial'));
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
            'title' => 'required|string',
            'name' => 'required|string',
            'designation' => 'required',
            'rating' => 'required',
        ]);

        $testimonials = Testimonial::find($id);
        $testimonials->title = $request->title;
        $testimonials->name = $request->name;
        $testimonials->designation = $request->designation;
        $testimonials->description = $request->description;
        $testimonials->rating = $request->rating;
        $testimonials->status = $request->status;
        if ($request->hasFile('image')) {
            $testimonials->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/testimonial');
        }

        $testimonials->save();

        return response()->json('Testimonial has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        $testimonial->delete();

        return response()->json('Testimonial has been delete successfully');
    }
}
