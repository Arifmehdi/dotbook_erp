<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Country;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $countrys = Country::orderBy('id', 'DESC')->get();

            return DataTables::of($countrys)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_country')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.country.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_country')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.country.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'name', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::country.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('web_add_country')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $country = new Country();
        $country->name = ucfirst($request->name);
        $country->status = $request->status;
        $country->save();

        return response()->json('Country has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::country.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (auth()->user()->can('web_edit_country')) {
            abort(403, 'Access Forbidden.');
        }

        $country = Country::find($id);

        return view('website::country.edit', compact('country'));
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
            'name' => 'required|string',
        ]);

        $country = Country::find($id);
        $country->name = $request->name;
        $country->status = $request->status;
        $country->save();

        return response()->json('Country has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $country = Country::find($id);
        $country->delete();

        return response()->json('Country has been delete successfully');
    }
}
