<?php

namespace Modules\Asset\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetServiceConsume;
use Modules\Asset\Entities\AssetWarranty;
use Yajra\DataTables\Facades\DataTables;

class AssetServiceConsumeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $service_consume = '';
            $query = AssetServiceConsume::with([
                'asset:id,asset_name,asset_supplier_id',
                'asset.supplier',
            ]);

            if ($request->f_from_date) {
                $from_date = date('Y-m-d', strtotime($request->f_from_date));
                $to_date = $request->f_to_date ? date('Y-m-d', strtotime($request->f_to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('start_date', $date_range); // Final
            }

            if ($request->f_service_id) {
                $query->where('id', $request->f_service_id);
            }

            if ($request->f_asset_id) {
                $query->where('asset_id', $request->f_asset_id);
            }

            $service_consume = $query->orderBy('id', 'desc')->get();

            return DataTables::of($service_consume)

                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('assets.consume.services.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.consume.services.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->addColumn('supplier_name', function ($row) {
                    return $row->asset->supplier->name ?? 'N/A';
                })

                ->editColumn('asset_name', function ($row) {
                    return $row->asset->asset_name ?? 'N/A';
                })

                ->editColumn('service_type', function ($row) {
                    if ($row->maintenance == 1) {
                        return 'Upgrade';
                    } elseif ($row->maintenance == 2) {
                        return 'Repair';
                    }
                })

                ->editColumn('have_warranty', function ($row) {
                    if ($row->warranty == 0) {
                        return 'Warranty Expired';
                    } else {
                        return 'Warranty Exist';
                    }
                })
                ->rawColumns(['action', 'supplier_name', 'asset_name', 'service_type', 'have_warranty'])
                ->smart(true)
                ->make(true);
        }
        $consume_services = AssetServiceConsume::all();
        $assets = Asset::all();

        return view('asset::consume_service.index', [
            'assets' => $assets,
            'consume_services' => $consume_services,
        ]);
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $request->validate([
            'asset_id' => 'required',
            'service_type' => 'required',
            'start_date' => 'required',
        ]);

        $service_consume = new AssetServiceConsume();
        $asset_warranty = AssetWarranty::where('asset_id', $request->asset_id)->first();

        if (isset($asset_warranty)) {
            $months = intval(($asset_warranty->warranty_month));
            $final_warranty_date = date('Y-m-d', strtotime($asset_warranty['start_date'].' + '.$months.' months'));
            $warranty = 0;
            $cost = 0;
            if (date('Y-m-d', strtotime($request->start_date)) > date('Y-m-d', strtotime($final_warranty_date))) {
                if (! $request->service_cost) {
                    return response()->json(['errorMsg' => 'Add Service Cost']);
                } else {
                    $warranty = 0;
                    $cost = $request->service_cost;
                }
            } else {
                $warranty = 1;
                $cost = 0;
            }
        } else {
            $warranty = 0;
            $cost = $request->service_cost;
        }

        $service_consume->code = $codeGenerationService->generate('asset_service_consumes', 'code', 'ASC');
        $service_consume->asset_id = $request->asset_id;
        $service_consume->cost = $cost;
        $service_consume->warranty = $warranty;
        $service_consume->maintenance = $request->service_type;
        $service_consume->start_date = date('Y-m-d', strtotime($request->start_date));
        $service_consume->end_date = date('Y-m-d', strtotime($request->end_date));
        $service_consume->notes = $request->description;
        $service_consume->save();

        return response()->json('Consume service created successfully');
    }

    public function edit($id)
    {
        $service_consume = AssetServiceConsume::find($id);
        $assets = Asset::all();

        return view('asset::consume_service.ajax_view.edit', [
            'service_consume' => $service_consume,
            'assets' => $assets,
        ]);
    }

    public function update(Request $request, $id)
    {

        $service_consume = AssetServiceConsume::find($id);

        $asset_warranty = AssetWarranty::where('asset_id', $service_consume->asset_id)->first();
        $warranty = 0;
        $cost = 0;
        if (isset($asset_warranty)) {

            $months = intval(($asset_warranty->warranty_month));
            $final_warranty_date = date('Y-m-d', strtotime($asset_warranty['start_date'].' + '.$months.' months'));
            if (date('Y-m-d', strtotime($request->e_start_date)) > date('Y-m-d', strtotime($final_warranty_date))) {

                if (! $request->e_service_cost || $request->e_service_cost == 0) {
                    return response()->json(['errorMsg' => 'Add Service Cost']);
                } else {
                    $warranty = 0;
                    $cost = $request->e_service_cost;
                }
            } else {
                $warranty = 1;
                $cost = 0;
            }
        } else {
            if (! $request->e_service_cost || $request->e_service_cost == 0) {
                return response()->json(['errorMsg' => 'Add Service Cost']);
            } else {
                $warranty = 0;
                $cost = $request->e_service_cost;
            }
        }

        $service_consume->maintenance = $request->e_service_type;
        $service_consume->cost = $cost;
        $service_consume->warranty = $warranty;
        $service_consume->start_date = date('Y-m-d', strtotime($request->e_start_date));
        $service_consume->end_date = date('Y-m-d', strtotime($request->e_end_date));
        $service_consume->notes = $request->e_notes;
        $service_consume->save();

        return response()->json('Consume service updated successfully');
    }

    public function destroy($id)
    {
        $service_consume = AssetServiceConsume::find($id);
        $service_consume->delete();

        return response()->json(['errorMsg' => 'Consume service delete successfully']);
    }
}
