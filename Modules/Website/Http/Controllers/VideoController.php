<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Video;
use Str;
use Yajra\DataTables\Facades\DataTables;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $videos = Video::orderBy('id', 'DESC')->get();

            return DataTables::of($videos)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_video')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.video.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_video')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.video.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('thumbnail', function ($row) {
                    if ($row->thumbnail) {
                        $html = '<a href="'.$row->link.'" target="_blank"><img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->thumbnail).'"></a>';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
                })
                ->editColumn('title', function ($row) {
                    return $row->title;
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
                ->rawColumns(['action', 'thumbnail', 'title', 'slug', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::video.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::video.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (auth()->user()->can('web_add_video')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
            'link' => 'required|string',
        ]);
        $videos = new Video();
        if ($request->hasFile('thumbnail')) {
            $videos->thumbnail = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/video');
        }
        $videos->slug = Str::of($request->title)->slug('-');
        $videos->title = $request->title;
        $videos->description = $request->description;
        $videos->link = str_replace('https://youtu.be', 'https://www.youtube.com/embed', $request->link);
        $videos->status = $request->status;
        $videos->save();

        return response()->json('Video has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::video.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (auth()->user()->can('web_edit_video')) {
            abort(403, 'Access Forbidden.');
        }

        $video = Video::find($id);

        return view('website::video.edit', compact('video'));
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

        $videos = Video::find($id);
        if ($request->hasFile('thumbnail')) {
            $videos->thumbnail = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/video');
        }
        $videos->title = $request->title;
        $videos->description = $request->description;
        $videos->link = str_replace('https://youtu.be', 'https://www.youtube.com/embed', $request->link);
        $videos->status = $request->status;
        $videos->save();

        return response()->json('Video has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $video = Video::find($id);
        $video->delete();

        return response()->json('Video has been delete successfully');
    }
}
