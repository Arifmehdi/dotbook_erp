<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\PurchaseByScale;
use App\Models\PurchaseByScaleWeight;
use App\Utils\PurchaseByScaleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseByScaleController extends Controller
{
    protected $purchaseByScaleUtil;

    public function __construct(PurchaseByScaleUtil $purchaseByScaleUtil)
    {
        $this->purchaseByScaleUtil = $purchaseByScaleUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_by_scale_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseByScaleUtil->purchaseByScaleList($request);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.purchase_by_scale.index', compact('supplierAccounts'));
    }

    public function show($purchaseByScaleId)
    {
        if (! auth()->user()->can('purchase_by_scale_view')) {

            abort(403, 'Access Forbidden.');
        }

        $purchaseByScale = PurchaseByScale::with([
            'createdBy:id,prefix,name,last_name',
            'supplier:id,name,phone,address',
            'weightsByProduct',
            'weightsByProduct.product:id,name,unit_id',
            'weightsByProduct.variant:id,variant_name',
            'weightsByProduct.product.unit:id,name',
        ])->where('id', $purchaseByScaleId)->first();

        return view('procurement.purchase_by_scale.ajax_view.purchase_by_scale_details_modal', compact('purchaseByScale'));
    }

    public function create()
    {
        if (! auth()->user()->can('purchase_by_scale_create')) {

            abort(403, 'Access Forbidden.');
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.purchase_by_scale.create', compact('supplierAccounts'));
    }

    public function saveWeight(Request $request, CodeGenerationServiceInterface $generator)
    {
        if (! auth()->user()->can('purchase_by_scale_create')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate(
            $request,
            [
                // 'search_challan_no' => 'required',
                'supplier_account_id' => 'required',
                'date' => 'required',
                'vehicle_number' => 'required',
            ],
            [
                // 'search_challan_no.required' => 'Challan No. is required.',
                'supplier_account_id.required' => 'Supplier is required.',
                'vehicle_number.required' => 'Vehicle no is required.',
            ]
        );

        if ($request->weight == 'NULL' || $request->weight <= 0) {

            return response()->json(['errorMsg' => 'Empty or 0 weight is not acceptable.']);
        }

        if ($request->weight == 'NULL' || $request->weight <= 0) {

            return response()->json(['errorMsg' => 'Empty or 0 weight is not acceptable.']);
        }

        $purchaseByScale = '';
        if ($request->purchase_by_scale_id) {

            $purchaseByScale = PurchaseByScale::where('id', $request->purchase_by_scale_id)->first();

            if ($purchaseByScale->last_weight == $request->weight) {

                return response()->json(['errorMsg' => 'Last weight and current weight could not be same.']);
            }
        }

        $details = '';

        try {

            DB::beginTransaction();

            if ($purchaseByScale) {

                $purchaseByScale->challan_no = $request->search_challan_no;
                $purchaseByScale->supplier_account_id = $request->supplier_account_id;
                $purchaseByScale->date_ts = date('Y-m-d H:i:s', strtotime($request->date.' '.date('H:i:s')));
                $purchaseByScale->date = $request->date;
                $purchaseByScale->challan_date = $request->challan_date ? $request->challan_date : date('Y-m-d');
                $purchaseByScale->vehicle_number = $request->vehicle_number;
                $purchaseByScale->driver_name = $request->driver_name;
                $purchaseByScale->last_weight = $request->weight;
                $purchaseByScale->net_weight = $request->net_weight ? $request->net_weight : 0;
                $purchaseByScale->save();

                $details = $purchaseByScale;

                $purchaseByScaleWeight = new PurchaseByScaleWeight();
                $purchaseByScaleWeight->purchase_by_scale_id = $request->purchase_by_scale_id;
                $purchaseByScaleWeight->scale_weight = $request->weight;
                $purchaseByScaleWeight->save();
            } else {

                $addPurchaseByScale = new PurchaseByScale();
                $addPurchaseByScale->voucher_no = $generator->generateMonthWise(table: 'purchase_by_scales', column: 'voucher_no', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');
                $addPurchaseByScale->created_by_id = auth()->user()->id;
                $addPurchaseByScale->challan_no = $request->search_challan_no;
                $addPurchaseByScale->supplier_account_id = $request->supplier_account_id;
                $addPurchaseByScale->date_ts = date('Y-m-d H:i:s', strtotime($request->date.' '.date('H:i:s')));
                $addPurchaseByScale->date = $request->date;
                $addPurchaseByScale->challan_date = $request->challan_date ? $request->challan_date : date('Y-m-d');
                $addPurchaseByScale->vehicle_number = $request->vehicle_number;
                $addPurchaseByScale->driver_name = $request->driver_name;
                $addPurchaseByScale->first_weight = $request->weight;
                $addPurchaseByScale->last_weight = $request->weight;
                $addPurchaseByScale->net_weight = $request->net_weight ? $request->net_weight : 0;

                $addPurchaseByScale->save();

                $details = $addPurchaseByScale;

                $purchaseByScaleWeight = new PurchaseByScaleWeight();
                $purchaseByScaleWeight->purchase_by_scale_id = $addPurchaseByScale->id;
                $purchaseByScaleWeight->scale_weight = $request->weight;
                $purchaseByScaleWeight->is_first_weight = 1;
                $purchaseByScaleWeight->save();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $countPurchaseByScaleWeight = DB::table('purchase_by_scale_weights')->where('purchase_by_scale_id', $details->id)->where('is_first_weight', 0)->count();

        return response()->json(['details' => $details, 'count' => $countPurchaseByScaleWeight]);
    }

    public function getWeightDetails(Request $request)
    {
        if (! auth()->user()->can('purchase_by_scale_view')) {

            abort(403, 'Access Forbidden.');
        }

        $purchaseByScaleId = $request->purchase_by_scale_id;

        $purchaseByScale = DB::table('purchase_by_scales')
            ->leftJoin('accounts as suppliers', 'purchase_by_scales.supplier_account_id', 'suppliers.id')
            ->where('purchase_by_scales.id', $purchaseByScaleId)
            ->select('purchase_by_scales.*', 'suppliers.id as sup_id', 'suppliers.name as sup_name', 'suppliers.phone as sup_phone')
            ->first();

        if (! $purchaseByScaleId) {

            return response()->json(['errorMsg' => 'Please select a challan or vehicle number.']);
        }

        $weights = DB::table('purchase_by_scale_weights')
            ->where('purchase_by_scale_weights.purchase_by_scale_id', $purchaseByScaleId)
            ->orderBy('purchase_by_scale_weights.id', 'asc')->get();

        $isExistsAllDifferWeight = 1;
        $index = 0;
        foreach ($weights as $weight) {

            if ($index != 0) {

                if ($weight->differ_weight == 0) {

                    $isExistsAllDifferWeight = 0;
                }
            }

            $index++;
        }

        $products = DB::table('products')
            ->where('purchase_type', 2)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select('products.id as product_id', 'products.name as product_name', 'product_variants.id as variant_id', 'product_variants.variant_name')
            ->get();

        return view('procurement.purchase_by_scale.ajax_view.weight_details_modal_body', compact('weights', 'products', 'purchaseByScaleId', 'purchaseByScale', 'isExistsAllDifferWeight'));
    }

    public function saveWeightDetails($purchaseByScaleId, Request $request)
    {
        if (! auth()->user()->can('purchase_by_scale_view')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $index = 0;
            foreach ($request->weight_ids as $key => $weight_id) {

                $purchaseByScaleWeight = PurchaseByScaleWeight::where('id', $weight_id)->first();
                $purchaseByScaleWeight->product_id = $request->weight_product_ids[$index];
                $purchaseByScaleWeight->variant_id = $request->weight_variant_ids[$index];
                $purchaseByScaleWeight->differ_weight = $request->differ_weights[$index];
                $purchaseByScaleWeight->wast = $request->wastes[$index] ? $request->wastes[$index] : 0;
                $purchaseByScaleWeight->net_weight = $request->differ_weights[$index] - $request->wastes[$index];
                $purchaseByScaleWeight->remarks = $request->remarks[$index];
                $purchaseByScaleWeight->save();
                $index++;
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(['successMsg' => 'Weight details has been saved successfully.']);
    }

    public function completed(Request $request)
    {
        if (! auth()->user()->can('purchase_by_scale_create')) {

            abort(403, 'Access Forbidden.');
        }

        if (! $request->purchase_by_scale_id || ! $request->voucher_no) {

            return response()->json(['errorMsg' => 'Please select a weight voucher.']);
        }

        try {

            DB::beginTransaction();

            $purchaseByScale = PurchaseByScale::where('id', $request->purchase_by_scale_id)->first();
            $purchaseByScale->status = 1;
            $purchaseByScale->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(['successMsg' => 'Weight scaling is completed successfully.']);
    }

    public function PurchaseByScaleWeightsByItems($purchaseScaleId)
    {
        $purchaseByScaleWeights = DB::table('purchase_by_scale_weights')
            ->where('purchase_by_scale_id', $purchaseScaleId)
            // ->where('purchase_by_scale_weights.product_id', '!=', NULL)
            ->where('purchase_by_scale_weights.is_first_weight', '=', 0)
            ->leftJoin('products', 'purchase_by_scale_weights.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_by_scale_weights.variant_id', 'product_variants.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select('purchase_by_scale_weights.*', 'products.name as product_name', 'product_variants.variant_name', 'units.name as unit')
            ->orderBy('purchase_by_scale_weights.id', 'asc')->get();

        return view('procurement.purchase_by_scale.ajax_view.purchase_by_scale_weight_list_by_items', compact('purchaseByScaleWeights'));
    }

    public function purchaseByScaleVehicleDone($purchaseByScaleId)
    {
        if (! auth()->user()->can('purchase_by_scale_create')) {

            abort(403, 'Access Forbidden.');
        }

        $purchaseByScale = PurchaseByScale::where('id', $purchaseByScaleId)->first();
        $purchaseByScale->is_done = 1;
        $purchaseByScale->save();

        return 'do is done.';
    }

    public function printWeightChallan($purchaseByScaleId)
    {
        if (! auth()->user()->can('purchase_by_scale_view')) {

            abort(403, 'Access Forbidden.');
        }

        if (! $purchaseByScaleId) {

            return response()->json(['errorMsg' => 'Please select a weight voucher.']);
        }

        $purchaseByScale = PurchaseByScale::with([
            'createdBy:id,prefix,name,last_name',
            'supplier:id,name,phone,address',
            'weightsByProduct',
            'weightsByProduct.product:id,name,unit_id',
            'weightsByProduct.variant:id,variant_name',
            'weightsByProduct.product.unit:id,name',
        ])->where('id', $purchaseByScaleId)->first();

        return view('procurement.purchase_by_scale.ajax_view.weight_challan', compact('purchaseByScale'));
    }

    public function printWeight($printType, $purchaseByScaleId)
    {
        if (! auth()->user()->can('purchase_by_scale_view')) {

            abort(403, 'Access Forbidden.');
        }

        $purchaseByScale = PurchaseByScale::with([
            'createdBy:id,prefix,name,last_name',
            'supplier:id,name,phone,address',
            'weights',
            'weights.product:id,name,unit_id',
            'weights.variant:id,variant_name',
            'weights.product.unit:id,name',
        ])->where('id', $purchaseByScaleId)->first();

        if ($printType == 'with_product') {

            return view('procurement.purchase_by_scale.ajax_view.print_weight_with_product', compact('purchaseByScale'));
        } else {

            return view('procurement.purchase_by_scale.ajax_view.print_weight_without_product', compact('purchaseByScale'));
        }
    }

    public function delete($id)
    {
        if (! auth()->user()->can('purchase_by_scale_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $purchaseByScale = PurchaseByScale::where('id', $id)->first();

            if (! is_null($purchaseByScale)) {

                $purchaseByScale->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Purchase By scale data is deleted successfully.');
    }
}
