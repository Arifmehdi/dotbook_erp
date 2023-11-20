<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Area;
use Modules\Core\Entities\BdDistrict;
use Modules\Core\Entities\BdDivision;
use Modules\Core\Entities\BdUnion;
use Modules\Core\Entities\BdUpazila;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $divisions = BdDivision::all();
        $districts = BdDistrict::all();
        $upazilas = BdUpazila::all();
        $union = BdUnion::all();

        $divisions = BdDivision::with('districts')->get(); // districts hasMany
        $districts = BdDistrict::with('division', 'upazilas')->get(); //division belongsTo and upazilas hasMany
        $upazilas = BdUpazila::with('district', 'unions')->get(); //district belongsTo and unions hasMany;
        $union = BdUnion::all();

        $district = BdDistrict::find(1);
        // $upazilas = $district->upazilas;

        if ($request->ajax()) {

            $areas = Area::with(['realation_Division'])->get();
            $areas = Area::with(['realation_district'])->get();
            $areas = Area::with(['relation_thana'])->get();
            $areas = Area::with(['relation_union'])->get();

            $generalSettings = DB::table('general_settings')->first();

            return DataTables::of($areas)
            // ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown" id="accordion">';
                    $html .= '<a href="'.route('core.area.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href=" ' . route('core.area.show', $row->id) . '" class="action-btn details_button c-show" title="show"><span class="far fa-eye text-success"></span></a>';
                    $html .= '<a href="'.route('core.area.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('division_name', function ($row) {

                    return $row->realation_Division->name ?? 'N/A';
                })
                ->editColumn('district_name', function ($row) {
                    \Log::info($row->realation_district);

                    return $row->realation_district->name ?? 'N/A';
                })
                ->editColumn('upazilas_name', function ($row) {
                    \Log::info($row->relation_thana);

                    return $row->relation_thana->name ?? 'N/A';
                })
                ->editColumn('unions_name', function ($row) {
                    \Log::info($row->relation_union);

                    return $row->relation_union->name ?? 'N/A';
                })
                ->editColumn('created_at', function ($row) use ($generalSettings) {
                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->created_at));
                })
                ->rawColumns(['action', 'division_name', 'district_name', 'upazilas_name', 'unions_name', 'created_at'])
                ->make(true);
        }
        $area = DB::table('areas')->get();

        return view('core.area.index', compact('bd_divisions', 'area'));
    }

    public function create()
    {
        $divisions = BdDivision::all();
        $area = DB::table('areas')->get();

        return view('area.create', compact('area', 'bd_divisions'));
    }

    public function district()
    {
        $division_id = request('district');
        $districts = BdDistrict::where('division_id', $division_id)->get();
        $option = "<option value=''>>>--Select district--<<</option>";
        foreach ($districts as $district) {
            $option .= '<option value="'.$district->id.'">'.$district->name.'</option>';
        }

        return $option;
    }

    public function upazilas()
    {
        $district_id = request('upazilas');
        $upazilas = BdUpazila::where('district_id', $district_id)->get();
        $option = "<option value=''>>>--Select thana/upazilla--<<</option>";
        foreach ($upazilas as $thana) {
            $option .= '<option value="'.$thana->id.'">'.$thana->name.'</option>';
        }

        return $option;
    }

    public function multiThanas()
    {
        // return $districtString = $arahmanArea->district;
        // $districtArray = json_decode($districtString, true);
        // $upazilas = \App\Models\BdUpazila::whereIn('district_id', $districtArray)->pluck('name');

        // $district_id = request('upazilas');
        // $upazilas = BdUpazila::where('district_id', $district_id)->get();
        // $option = "<option value=''>Select thana</option>";
        // foreach ($upazilas as $thana){
        //     $option .= '<option value="'.$thana->id.'">'.$thana->name.'</option>';
        // }
        // return $option;
    }

    public function unions()
    {
        $thana_id = request('unions');
        $unions = BdUnion::where('thana_id', $thana_id)->get();
        $option = "<option value=''>Select union</option>";
        foreach ($unions as $union) {
            $option .= '<option value="'.$union->id.'">'.$union->name.'</option>';
        }

        return $option;
    }

    public function store(Request $request)
    {
        // return $request;
        $area = new Area();
        $area->division = $request->division;
        $area->district = json_encode($request->district);
        $area->upazilas = json_encode($request->upazilas);
        $area->unions = $request->unions;
        $area->postalcode = $request->postalcode;
        $area->area = $request->area;
        $area->save();

        return response()->json('Area created successfully!');
    }

    public function edit($id)
    {
        $divisions = BdDivision::all();
        $districts = BdDistrict::all();
        $upazilas = BdUpazila::all();
        $unions = BdUnion::all();
        $area = DB::table('areas')->where('id', $id)->first();

        return view('area.ajax_view.edit', compact('area', 'bd_divisions', 'districts', 'upazilas', 'unions'));
    }

    public function update(Request $request, $id)
    {
        $update_area = Area::find($id);
        $update_area->division = $request->division;
        $update_area->district = $request->district;
        $update_area->upazilas = $request->upazilas;
        $update_area->unions = $request->unions;
        $update_area->postalcode = $request->postalcode;
        $update_area->area = $request->area;
        $update_area->save();

        return response()->json('Area updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $area = Area::find($id);
        $area->delete();

        return response()->json('Area deleted successfully!');
    }
}
