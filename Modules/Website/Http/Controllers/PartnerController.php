<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Partner;
use Yajra\DataTables\Facades\DataTables;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $partners = Partner::orderBy('id', 'DESC')->get();

            return DataTables::of($partners)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_partner')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.partners.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_partner')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.partners.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->rawColumns(['action', 'image'])
                ->smart(true)
                ->make(true);
        }

        return view('website::clients.partners.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::clients.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_partner')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif',
        ]);

        $partner = new Partner();
        if ($request->hasFile('image')) {
            $partner->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/partners');
        }
        $partner->save();

        return response()->json('Partner has been create successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::clients.partners.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_partner')) {
            abort(403, 'Access Forbidden.');
        }
        $partner = Partner::find($id);

        return view('website::clients.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id, FileUploadUtil $FileUploadUtil)
    {
        $partner = Partner::find($id);
        if ($request->hasFile('image')) {
            $partner->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/partners');
        }
        $partner->save();

        return response()->json('Partner has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $partner = Partner::find($id);
        $partner->delete();

        return response()->json('Partner has been delete successfully');
    }
}
