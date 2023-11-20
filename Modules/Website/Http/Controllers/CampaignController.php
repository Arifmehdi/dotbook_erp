<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Campaign;
use Str;
use Yajra\DataTables\Facades\DataTables;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $campaigns = Campaign::orderBy('id', 'DESC')->get();

            return DataTables::of($campaigns)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_campaign')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.campaign.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_campaign')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.campaign.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('thumbnail', function ($row) {
                    if ($row->thumbnail) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->thumbnail).'">';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
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
                ->rawColumns(['action', 'thumbnail', 'title', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::campaign.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::campaign.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_campaign')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
        ]);

        $campaign = new Campaign();
        if ($request->hasFile('thumbnail')) {
            $banners->thumbnail = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/campaign');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $$uploadname = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/campaign');
                array_push($images, $uploadname);
            }
            $campaign->image = json_encode($images);
        }

        $campaign->title = $request->title;
        $campaign->slug = Str::of($request->title)->slug('-');
        $campaign->description = $request->description;
        $campaign->status = $request->status;
        $campaign->save();

        return response()->json('Campaign has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::campaign.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_campaign')) {
            abort(403, 'Access Forbidden.');
        }

        $campaign = Campaign::find($id);

        return view('website::campaign.edit', compact('campaign'));
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

        $campaign = Campaign::find($id);

        if ($request->hasFile('thumbnail')) {
            $banners->thumbnail = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/campaign');
        } elseif ($request->get('thumbnail')) {
            $campaign->thumbnail = $request->get('thumbnail');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $$uploadname = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/campaign');
                array_push($images, $uploadname);
            }
            $campaign->image = json_encode($images);
        } elseif ($request->get('images')) {
            foreach ($request->get('images') as $key => $uploadname) {
                array_push($images, $uploadname);
            }
        }

        $campaign->image = json_encode($images);
        $campaign->title = $request->title;
        $campaign->slug = Str::of($request->title)->slug('-');
        $campaign->description = $request->description;
        $campaign->status = $request->status;
        $campaign->save();

        return response()->json('Campaign has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        $campaign->delete();

        return response()->json('Campaign has been delete successfully');
    }
}
