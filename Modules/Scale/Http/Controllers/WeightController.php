<?php

namespace Modules\Scale\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\CodeGenerationServiceInterface;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Scale\Entities\Weight;
use Modules\Scale\Entities\WeightDetails;
use Yajra\DataTables\Facades\DataTables;

class WeightController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('index_weight_scale')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $weightScales = '';

            $query = DB::table('weights')
                ->leftJoin('weight_clients', 'weights.client_id', 'weight_clients.id')
                ->leftJoin('products', 'weights.product_id', 'products.id')
                ->leftJoin('users as created_by', 'weights.created_by_id', 'created_by.id');

            if ($request->client_id) {

                $query->where('weights.client_id', $request->client_id);
            }

            if ($request->status) {

                $query->where('weights.status', $request->status);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('weights.created_at', $date_range); // Final
            }

            $weightScales = $query->select(
                'weights.id',
                'weights.voucher_no',
                'weights.weight_id',
                'weights.challan_date',
                'weights.vehicle_number',
                'weights.driver_name',
                'weights.driver_phone',
                'weights.gross_weight',
                'weights.tare_weight',
                'weights.net_weight',
                'weights.date',
                'weights.status',
                'weight_clients.name as client_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->orderBy('weights.date', 'desc');

            return DataTables::of($weightScales)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('single_view_weight_scale')) {

                        $html .= '<a class="dropdown-item details_button" href="'.route('scale.show', [$row->id]).'"><i class="far fa-eye text-primary"></i> View</a>';
                    }

                    if (auth()->user()->can('delete_weight_scale')) {

                        $html .= '<a class="dropdown-item" id="delete" href="'.route('scale.delete', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('gross_weight', fn ($row) => '<span class="gross weight" data-value="'.$row->gross_weight.'"><strong>'.$this->converter->format_in_bdt($row->gross_weight).'</strong></span>')

                ->editColumn('tare_weight', fn ($row) => '<span class="tare weight" data-value="'.$row->tare_weight.'"><strong>'.$this->converter->format_in_bdt($row->tare_weight).'</strong></span>')

                ->editColumn('net_weight', fn ($row) => '<span class="net_weight" data-value="'.$row->net_weight.'"><strong>'.$this->converter->format_in_bdt($row->net_weight).'</strong></span>')

                ->editColumn('status', function ($row) {

                    if ($row->status == 1) {

                        return '<span class="badge badge-sm bg-success text-white"><b>Completed</b></span>';
                    } elseif ($row->status == 0) {

                        return '<span class="badge badge-sm bg-danger text-white"><b>Running</b></span>';
                    }
                })->editColumn('created_by', function ($row) {

                    return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
                })
                ->rawColumns(['action', 'date', 'gross_weight', 'tare_weight', 'net_weight', 'status', 'created_by'])
                ->make(true);
        }

        $weight_clients = DB::table('weight_clients')->select('id', 'name', 'phone')->orderBy('name', 'asc')->get();

        return view('scale::scale.index', compact('weight_clients'));
    }

    public function create()
    {
        if (! auth()->user()->can('add_weight_scale')) {

            abort(403, 'Access Forbidden.');
        }

        $products = DB::table('products')
            ->where('purchase_type', 2)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select('products.id as product_id', 'products.name as product_name', 'product_variants.id as variant_id', 'product_variants.variant_name')
            ->get();

        $clients = DB::table('weight_clients')->select('id', 'name', 'phone')->orderBy('name', 'asc')->get();

        return view('scale::scale.create', compact('clients', 'products'));
    }

    public function show($weightScaleId)
    {
        if (! auth()->user()->can('single_view_weight_scale')) {

            abort(403, 'Access Forbidden.');
        }

        $weightScale = Weight::with([
            'weightClient',
            'createdBy:id,prefix,name,last_name',
            'product:id,name,unit_id',
        ])->where('id', $weightScaleId)->first();

        return view('scale::scale.ajax_view.weight_scale_details_modal', compact('weightScale'));
    }

    public function saveWeight(Request $request, CodeGenerationServiceInterface $generator)
    {
        if (! auth()->user()->can('add_weight_scale')) {

            abort(403, 'Access Forbidden.');
        }

        $request->validate(
            [
                'vehicle_number' => 'required',
                'weight_type' => 'required',
            ],
            [
                'vehicle_number.required' => 'Vehicle no is required.',
                'weight_type.required' => 'Weight Type is required.',
            ]
        );

        if ($request->weight == 'NULL' || $request->weight <= 0) {

            return response()->json(['errorMsg' => 'Empty or 0 weight is not acceptable.']);
        }

        $weight = '';
        $weightDetails = '';

        if ($request->weight_scale_primary_id) {

            $weight = Weight::where('id', $request->weight_scale_primary_id)
                // ->where('vehicle_number', $request->vehicle_number)
                ->first();

            // if ($weight->gross_weight == $request->tare_weight) {

            //     return response()->json(['errorMsg' => 'Gross weight and tare weight could not be same.']);
            // }

            // if ($weight->gross_weight < $request->tare_weight) {

            //     return response()->json(['errorMsg' => 'Gross weight < Tare weight. Please Check']);
            // }

            // if ($weight && $weight->gross_weight > 0 && $request->weight_type == 2) {

            //     if ($request->weight > $weight->gross_weight || $request->weight == $weight->gross_weight) {

            //         return response()->json(['errorMsg' => 'Tare weight Must be less then gross weight.']);
            //     }
            // }

            // if ($weight && $weight->tare_weight > 0 && $request->weight_type == 1) {

            //     if ($request->weight < $weight->tare_weight || $request->weight == $weight->tare_weight) {

            //         return response()->json(['errorMsg' => 'Gross weight must be greater then tare weight.']);
            //     }
            // }
        }

        try {

            DB::beginTransaction();

            // if ($weight) {

            //     // return "primary key is exist";
            //     if ($request->tare_weight > 0 && $request->gross_weight > $request->tare_weight) {

            //         $request->net_weight = $request->gross_weight - $request->tare_weight;
            //     }
            // } else {

            //     // return "primary key is not found";
            //     $weight = new Weight();
            // }

            if (! $weight) {

                $weight = new Weight();
            }

            $weight->weight_id = $weight->weight_id ?? $generator->generateMonthWise(table: 'weights', column: 'weight_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');
            $weight->weight_type = $request->weight_type;
            $weight->quantity = $request->quantity ? $request->quantity : 0;
            $weight->created_by_id = auth()->user()->id;
            $weight->client_id = $request->client_id;
            $weight->product_id = $request->product_id;
            $weight->vehicle_number = $request->vehicle_number;
            $weight->is_done = 0;
            $weight->serial_no = $request->serial_no;
            $weight->challan_no = $request->inputed_challan_number;
            $weight->challan_date = $request->challan_date;
            $weight->driver_name = $request->driver_name;
            $weight->driver_phone = $request->driver_phone;
            $weight->date = $request->date ? $request->date : date('Y-m-d');

            // $weight->gross_weight = $request->gross_weight ? $request->gross_weight : 0;
            // $weight->tare_weight = $request->tare_weight ? $request->tare_weight : 0;
            // $weight->net_weight = $request->net_weight ? $request->net_weight : 0;

            $weight->tare_weight = $request->weight_type == 2 ? $request->weight : ($weight->tare_weight ?? 0);
            $weight->gross_weight = $request->weight_type == 1 ? $request->weight : ($weight->gross_weight ?? 0);

            $tareWeight = $weight->tare_weight > 0 ? $weight->tare_weight : '';
            $grossWeight = $weight->gross_weight > 0 ? $weight->gross_weight : '';

            $calcNetWeight = 0;

            if ($tareWeight && $grossWeight) {

                $weight->status = 1;
                // $calcNetWeight = $weight->gross_weight - $weight->tare_weight;
            }

            // $weight->status = 1;
            $calcNetWeight = $weight->gross_weight - $weight->tare_weight;

            // $weight->net_weight = $calcNetWeight > 0 ? $calcNetWeight : 0;
            $weight->net_weight = $calcNetWeight;

            $weight->save();

            $weightDetails = WeightDetails::where('weight_scale_id', $weight->id)->where('weight_type', $request->weight_type)->first();

            if (! $weightDetails) {

                $weightDetails = new WeightDetails();
            }

            $weightDetails->weight = $request->weight;
            $weightDetails->weight_type = $request->weight_type;
            $weightDetails->weight_scale_id = $weight->id;
            $weightDetails->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $weight;
    }

    public function idWiseWeightSearch($id)
    {
        return $firstWeightScale = Weight::where('id', $id)->first();
    }

    public function weightScaleVehicleDone($id)
    {
        $weightScale = Weight::where('id', $id)->update([
            'is_done' => 1,
        ]);

        return 'Vehicle is done.';
    }

    public function RandomWeightChallanList()
    {
        $random_weights = DB::table('weights')
            ->where('weights.is_done', 0)
            ->leftJoin('weight_clients', 'weights.client_id', 'weight_clients.id')
            ->leftJoin('products', 'weights.product_id', 'products.id')
            ->select('weights.*', 'weight_clients.name as client_name', 'weight_clients.phone as client_phone')
            ->orderBy('weights.created_at', 'desc')
            ->orderBy('weights.status', 'asc')
            ->limit(200)->get();

        return view('scale::scale.ajax_view.random_weight_scale_table_rows', compact('random_weights'));
    }

    public function searchRandomWeightChallanList($key_word)
    {
        $randomWeightSales = DB::table('weights')
            ->where('weights.weight_id', 'like', "%{$key_word}%")
            ->where('weights.challan_no', 'like', "%{$key_word}%")
            ->orWhere('weights.vehicle_number', 'like', "%{$key_word}%")
            ->orWhere('weights.weight_id', 'like', "%{$key_word}%")
            ->orderBy('weights.id', 'desc')
            ->limit(200)->get();

        if (count($randomWeightSales) > 0) {

            return view('scale::scale.ajax_view.random_weight_scale_search_result', compact('randomWeightSales'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function printWeight($weight_id)
    {
        $weightScaleData = Weight::with([
            'createdBy:id,prefix,name,last_name',
            'weightClient:id,name,phone,address',
            'product:id,name,unit_id',
            'weightDetails',
        ])->where('id', $weight_id)->first();

        return view('scale::scale.ajax_view.print_weight', compact('weightScaleData'));
    }

    public function delete($id)
    {
        if (! auth()->user()->can('delete_weight_scale')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly

            $deleteWeight = Weight::where('id', $id)->first();

            if (! is_null($deleteWeight)) {

                $deleteWeight->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Weight is deleted successfully.');
    }
}
