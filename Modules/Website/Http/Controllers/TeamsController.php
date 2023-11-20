<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Team;
use Str;
use Yajra\DataTables\Facades\DataTables;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teams = Team::orderBy('id', 'DESC')->get();

            return DataTables::of($teams)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_team')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.teams.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_team')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.teams.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('designation', function ($row) {
                    return $row->designation;
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
                ->rawColumns(['action', 'image', 'name', 'email', 'phone', 'designation', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::clients.teams.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::clients.teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_team')) {
            abort(403, 'Access Forbidden.');
        }
        $request->validate([
            'name' => 'required|string',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $teams = new Team();
        if ($request->hasFile('image')) {
            $teams->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/teams');
        }
        $teams->name = $request->name;
        $teams->phone = $request->phone;
        $teams->email = $request->email;
        $teams->designation = $request->designation;
        $teams->slug = Str::of($request->name)->slug('-');
        $teams->status = $request->status ?? 0;
        $teams->save();

        return response()->json('Team has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::clients.teams.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_team')) {
            abort(403, 'Access Forbidden.');
        }

        $team = Team::find($id);

        return view('website::clients.teams.edit', compact('team'));
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
            'name' => 'required|string',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $teams = Team::find($id);
        if ($request->hasFile('image')) {
            $teams->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/teams');
        }
        $teams->name = $request->name;
        $teams->phone = $request->phone;
        $teams->email = $request->email;
        $teams->designation = $request->designation;
        $teams->status = $request->status ?? 0;
        $teams->save();

        return response()->json('Team has been update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $team = Team::find($id);
        $team->delete();

        return response()->json('Team has been delete successfully');
    }
}
