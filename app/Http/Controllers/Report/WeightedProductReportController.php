<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WeightedProductReportController extends Controller
{
    // Index view of supplier report
    public function index(Request $request)
    {
        if (! auth()->user()->can('weighted_product_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $weights = '';

            $query = DB::table('purchase_by_scale_weights')
                ->where('purchase_by_scale_weights.is_first_weight', '=', 0)
                ->leftJoin('purchase_by_scales', 'purchase_by_scale_weights.purchase_by_scale_id', 'purchase_by_scales.id')
                ->leftJoin('products', 'purchase_by_scale_weights.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_by_scale_weights.variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'purchase_by_scales.supplier_account_id', 'suppliers.id')
                ->leftJoin('units', 'products.unit_id', 'units.id');

            if ($request->product_id) {

                $query->where('purchase_by_scale_weights.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_by_scale_weights.variant_id', $request->variant_id);
            }

            if ($request->supplier_account_id) {

                $query->where('purchase_by_scales.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->user_id) {

                $query->where('purchase_by_scales.created_by_id', $request->user_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('purchase_by_scale_weights.created_at', $date_range);
            }

            $weights = $query->select(
                'purchase_by_scale_weights.scale_weight',
                'purchase_by_scale_weights.differ_weight',
                'purchase_by_scale_weights.wast',
                'purchase_by_scale_weights.net_weight',
                'purchase_by_scale_weights.remarks',
                'purchase_by_scale_weights.created_at',
                'units.code_name as unit_code',
                'purchase_by_scales.voucher_no',
                'purchase_by_scales.vehicle_number',
                'purchase_by_scales.challan_no',
                'purchase_by_scales.date_ts',
                'purchase_by_scales.date',
                'products.name as product_name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name'
            )->orderBy('purchase_by_scale_weights.created_at', 'desc');

            return DataTables::of($weights)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return ($row->product_name ? $row->product_name : 'Not yet to be available.').$variant;
                })

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
                    $timeFormat = json_decode($generalSettings->business, true)['time_format'];
                    $__timeFormat = $timeFormat == '12' ? ' h:i:s A' : ' H:i:s';

                    return date($__date_format.$__timeFormat, strtotime($row->created_at));
                })

                ->editColumn('differ_weight', fn ($row) => \App\Utils\Converter::format_in_bdt($row->differ_weight).'/<span class="differ_weight" data-value="'.$row->differ_weight.'">'.$row->unit_code.'</span>')

                ->editColumn('wast', fn ($row) => \App\Utils\Converter::format_in_bdt($row->wast).'/<span class="wast" data-value="'.$row->wast.'">'.$row->unit_code.'</span>')

                ->editColumn('net_weight', fn ($row) => \App\Utils\Converter::format_in_bdt($row->net_weight).'/<span class="net_weight" data-value="'.$row->net_weight.'">'.$row->unit_code.'</span>')

                ->rawColumns(['product', 'date', 'differ_weight', 'differ_weight', 'wast', 'net_weight'])
                ->make(true);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('procurement.reports.weighted_product_report.index', compact('supplierAccounts', 'users'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('weighted_product_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = '';
        $toDate = '';
        $search_product = $request->search_product;
        $supplier_name = $request->supplier_name;
        $user_name = $request->user_name;

        $weights = '';
        $query = DB::table('purchase_by_scale_weights')
            ->where('purchase_by_scale_weights.is_first_weight', '=', 0)
            ->leftJoin('purchase_by_scales', 'purchase_by_scale_weights.purchase_by_scale_id', 'purchase_by_scales.id')
            ->leftJoin('products', 'purchase_by_scale_weights.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_by_scale_weights.variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchase_by_scales.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id');

        if ($request->product_id) {

            $query->where('purchase_by_scale_weights.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_by_scale_weights.variant_id', $request->variant_id);
        }

        if ($request->supplier_id) {

            $query->where('purchase_by_scales.supplier_id', $request->supplier_id);
        }

        if ($request->user_id) {

            $query->where('purchase_by_scales.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchase_by_scale_weights.created_at', $date_range);
        }

        $weights = $query->select(
            'purchase_by_scale_weights.scale_weight',
            'purchase_by_scale_weights.differ_weight',
            'purchase_by_scale_weights.wast',
            'purchase_by_scale_weights.net_weight',
            'purchase_by_scale_weights.remarks',
            'purchase_by_scale_weights.created_at',
            'units.code_name as unit_code',
            'purchase_by_scales.voucher_no',
            'purchase_by_scales.vehicle_number',
            'purchase_by_scales.challan_no',
            'purchase_by_scales.date_ts',
            'purchase_by_scales.date',
            'products.name as product_name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'suppliers.name as supplier_name'
        )->orderBy('purchase_by_scale_weights.created_at', 'desc')->get();

        $search_product = $request->search_product;
        $supplier_name = $request->supplier_name;
        $user_name = $request->user_name;

        return view('procurement.reports.weighted_product_report.ajax_view.print', compact(
            'weights',
            'fromDate',
            'toDate',
            'search_product',
            'supplier_name',
            'user_name',
        ));
    }
}
