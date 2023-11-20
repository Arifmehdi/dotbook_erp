<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Image;
use Modules\Website\Entities\Report;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reports = Report::orderBy('id', 'DESC')->get();

            return DataTables::of($reports)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_report')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.report.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_report')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.report.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('image', function ($row) {
                    return '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->image).'">';
                })
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('status', function ($row) {
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

        return view('website::report.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::report.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('web_add_report')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
        ]);

        $reports = new Report();
        if ($request->hasFile('image')) {
            $reports_image = $request->file('image');
            $reports_imageName = hexdec(uniqid()).'.'.$reports_image->getClientOriginalExtension();
            Image::make($reports_image)->save('uploads/website/report/'.$reports_imageName);
            $reports->image = route('dashboard.dashboard').'/uploads/website/report/'.$reports_imageName;
        }
        $reports->title = $request->title;
        $reports->status = $request->status;
        $reports->save();

        return response()->json('Report has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::report.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (auth()->user()->can('web_edit_report')) {
            abort(403, 'Access Forbidden.');
        }

        $report = Report::find($id);

        return view('website::report.edit', compact('report'));
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
            'title' => 'required|string',
        ]);

        $reports = Report::find($id);
        if ($request->hasFile('image')) {
            $reports_image = $request->file('image');
            $reports_imageName = hexdec(uniqid()).'.'.$reports_image->getClientOriginalExtension();
            Image::make($reports_image)->save('uploads/website/report/'.$reports_imageName);
            $reports->image = route('dashboard.dashboard').'/uploads/website/report/'.$reports_imageName;
        }
        $reports->title = $request->title;
        $reports->status = $request->status;
        $reports->save();

        return response()->json('Report has been created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $reports = Report::find($id);
        $reports->delete();

        return response()->json('Report has been delete successfully');
    }
}
