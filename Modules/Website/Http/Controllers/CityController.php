<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\City;
use Modules\Website\Entities\Country;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $citys = City::orderBy('id', 'DESC')->get();

            return DataTables::of($citys)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('website.city.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('website.city.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->city_name;
                })
                ->editColumn('country', function ($row) {
                    return $row->country->name;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'name', 'country', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::city.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $countrys = Country::where('status', 1)->get();

        return view('website::city.create', compact('countrys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'country_name' => 'required',
        ]);

        $city = new City();
        $city->city_name = ucfirst($request->name);
        $city->country_id = $request->country_name;
        $city->status = $request->status;
        $city->save();

        return response()->json('City has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::city.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $countrys = Country::where('status', 1)->get();
        $city = City::find($id);

        return view('website::city.edit', compact('countrys', 'city'));
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
            'country_name' => 'required',
        ]);

        $city = City::find($id);
        $city->city_name = ucfirst($request->name);
        $city->country_id = $request->country_name;
        $city->status = $request->status;
        $city->save();

        return response()->json('City has been update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $city = City::find($id);
        $city->delete();

        return response()->json('City has been delete successfully');
    }
}
