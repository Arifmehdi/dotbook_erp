<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Slider;
use Str;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sliders = Slider::orderBy('id', 'DESC')->get();

            return DataTables::of($sliders)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_slider')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.slider.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_slider')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.slider.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'image', 'title', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::slider.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (auth()->user()->can('web_add_slider')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
            'image' => 'required',
        ]);

        $slider = new Slider();
        if ($request->hasFile('image')) {
            $slider->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/slider');
        }
        $slider->title = $request->title;
        $slider->slug = Str::of($request->title)->slug('-');
        $slider->description = $request->description;
        $slider->status = $request->status;
        $slider->save();

        return response()->json('Slider has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::slider.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (auth()->user()->can('web_edit_slider')) {
            abort(403, 'Access Forbidden.');
        }

        $slider = Slider::find($id);

        return view('website::slider.edit', compact('slider'));
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
        ]);

        $slider = Slider::find($id);
        if ($request->hasFile('image')) {
            $slider->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/slider');
        }
        $slider->title = $request->title;
        $slider->slug = Str::of($request->title)->slug('-');
        $slider->description = $request->description;
        $slider->status = $request->status;
        $slider->save();

        return response()->json('Slider has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        $slider->delete();

        return response()->json('Slider has been delete successfully');
    }
}
