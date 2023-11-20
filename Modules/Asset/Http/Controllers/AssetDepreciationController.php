<?php

namespace Modules\Asset\Http\Controllers;

use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetDepreciation;
use Yajra\DataTables\Facades\DataTables;

class AssetDepreciationController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_depreciation_index')) {
            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $depreciation = '';
            $query = AssetDepreciation::with([
                'rel_to_asset:id,asset_name,asset_code,unit_price',
            ]);

            if ($request->f_depreciation_method) {
                $query->where('dep_method', $request->f_depreciation_method);
            }

            if ($request->f_asset_id) {
                $query->where('asset_id', $request->f_asset_id);
            }

            $depreciation = $query->get();

            return DataTables::of($depreciation)
                ->editColumn('asset', function ($row) {
                    return $row->rel_to_asset->asset_code ?? 'N/A';
                })
                ->editColumn('daily_dep', function ($row) {
                    $result = 0;
                    $asset_unit_price = $row->rel_to_asset->unit_price;
                    $salvage_value = $row->salvage_value;
                    $year = $row->dep_year;
                    $result = ($asset_unit_price - $salvage_value) / ($year * 365);

                    return Converter::format_in_bdt($result);
                })
                ->editColumn('monthly_dep', function ($row) {
                    $result = 0;
                    $asset_unit_price = $row->rel_to_asset->unit_price;
                    $salvage_value = $row->salvage_value;
                    $year = $row->dep_year;
                    $result = (($asset_unit_price - $salvage_value) / ($year * 365)) * 30;

                    return Converter::format_in_bdt($result);
                })
                ->editColumn('yearly_dep', function ($row) {
                    $result = 0;
                    $asset_unit_price = $row->rel_to_asset->unit_price;
                    $salvage_value = $row->salvage_value;
                    $year = $row->dep_year;
                    $result = ($asset_unit_price - $salvage_value) / $year;

                    return Converter::format_in_bdt($result);
                })
                ->rawColumns(['daily_dep', 'monthly_dep', 'yearly_dep'])
                ->make(true);
        }

        $depreciation = AssetDepreciation::all();
        $asset = Asset::all();

        return view('asset::depreciation.index', [
            'depreciation' => $depreciation,
            'asset' => $asset,
        ]);
    }
}
