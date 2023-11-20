<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Award;
use Str;
use Yajra\DataTables\Facades\DataTables;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $awards = Award::orderBy('id', 'DESC')->get();

            return DataTables::of($awards)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_award')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.award.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_award')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.award.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('link', function ($row) {
                    return $row->link;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status = 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'image', 'title', 'link', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::award.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::award.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_award')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
            'link' => 'required|string',
        ]);

        $award = new Award();

        if ($request->hasFile('image')) {
            $award->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/award');
        }

        $award->title = $request->title;
        $award->slug = Str::of($request->title)->slug('-');
        $award->link = $request->link;
        $award->description = $request->description;
        $award->status = $request->status ?? 0;
        $award->save();

        return response()->json('Award has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::award.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_award')) {
            abort(403, 'Access Forbidden.');
        }

        $award = Award::find($id);

        return view('website::award.edit', compact('award'));
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
            'link' => 'required|string',
        ]);

        $award = Award::find($id);
        if ($request->hasFile('image')) {
            $award->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/award');
        }

        $award->title = $request->title;
        $award->slug = Str::of($request->title)->slug('-');
        $award->link = $request->link;
        $award->description = $request->description;
        $award->status = $request->status ?? 0;
        $award->save();

        return response()->json('Award has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $award = Award::find($id);
        $award->delete();

        return response()->json('Award has been delete successfully');
    }
}
