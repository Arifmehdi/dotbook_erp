<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnItemReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $purchaseReturnProducts = '';
            $query = DB::table('purchase_return_products')->where('purchase_return_products.return_qty', '>', 0);

            if ($request->product_id) {

                $query->where('purchase_return_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_return_products.product_variant_id', $request->variant_id);
            }

            if ($request->supplier_account_id) {

                $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->user_id) {

                $query->where('purchase_returns.created_by_id', $request->user_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('purchase_returns.report_date', $date_range);
            }

            $query->leftJoin('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->leftJoin('products', 'purchase_return_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_return_products.product_variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
                ->leftJoin('units', 'purchase_return_products.unit_id', 'units.id')->select(
                    'purchase_return_products.purchase_return_id',
                    'purchase_return_products.product_id',
                    'purchase_return_products.product_variant_id',
                    'purchase_return_products.return_qty',
                    'purchase_return_products.unit_cost_exc_tax',
                    'purchase_return_products.unit_discount_type',
                    'purchase_return_products.unit_discount_amount',
                    'purchase_return_products.unit_tax_percent',
                    'purchase_return_products.unit_tax_amount',
                    'purchase_return_products.unit_cost_inc_tax',
                    'purchase_return_products.return_subtotal',
                    'units.code_name as unit_code',
                    'units.base_unit_multiplier',
                    'purchase_returns.date',
                    'purchase_returns.report_date',
                    'purchase_returns.voucher_no',
                    'products.name',
                    'products.product_code',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'suppliers.name as supplier_name'
                );

            $purchaseReturnProducts = $query->orderBy('purchase_returns.report_date', 'desc');

            return DataTables::of($purchaseReturnProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return $row->name.$variant;
                })->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->date));
                })->editColumn('return_qty', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->return_qty / $baseUnitMultiplier).'/<span class="return_qty" data-value="'.($row->return_qty / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })

                ->editColumn('unit_cost_exc_tax', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->unit_cost_exc_tax * $baseUnitMultiplier);
                })

                ->editColumn('unit_discount_amount', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->unit_discount_amount * $baseUnitMultiplier);
                })

                ->editColumn('unit_tax_amount', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return '('.\App\Utils\Converter::format_in_bdt($row->unit_tax_percent).'%)='.\App\Utils\Converter::format_in_bdt($row->unit_tax_amount * $baseUnitMultiplier);
                })

                ->editColumn('unit_cost_inc_tax', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax * $baseUnitMultiplier);
                })

                ->editColumn('return_subtotal', function ($row) {
                    return '<span class="return_subtotal" data-value="'.$row->return_subtotal.'">'.\App\Utils\Converter::format_in_bdt($row->return_subtotal).'</span>';
                })
                ->rawColumns(['product', 'date', 'return_qty', 'unit_cost_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'unit_cost_inc_tax', 'return_subtotal'])->make(true);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('procurement.reports.purchase_returned_items_report.index', compact('supplierAccounts', 'users'));
    }

    public function print(Request $request)
    {
        $fromDate = '';
        $toDate = '';
        $itemName = $request->search_product;
        $supplierName = $request->supplier_name;
        $userName = $request->user_name;

        $purchaseReturnProducts = '';
        $query = DB::table('purchase_return_products')->where('purchase_return_products.return_qty', '>', 0);

        if ($request->product_id) {

            $query->where('purchase_return_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_return_products.product_variant_id', $request->variant_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->user_id) {

            $query->where('purchase_returns.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchase_returns.report_date', $date_range);
        }

        $query->leftJoin('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('products', 'purchase_return_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_return_products.product_variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'purchase_return_products.unit_id', 'units.id')->select(
                'purchase_return_products.purchase_return_id',
                'purchase_return_products.product_id',
                'purchase_return_products.product_variant_id',
                'purchase_return_products.return_qty',
                'purchase_return_products.unit_cost_exc_tax',
                'purchase_return_products.unit_discount_type',
                'purchase_return_products.unit_discount_amount',
                'purchase_return_products.unit_tax_percent',
                'purchase_return_products.unit_tax_amount',
                'purchase_return_products.unit_cost_inc_tax',
                'purchase_return_products.return_subtotal',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'purchase_returns.date',
                'purchase_returns.report_date',
                'purchase_returns.voucher_no',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'suppliers.name as supplier_name'
            );

        $purchaseReturnProducts = $query->orderBy('purchase_returns.report_date', 'desc')->get();

        return view(
            'procurement.reports.purchase_returned_items_report.ajax_view.print',
            compact(
                'purchaseReturnProducts',
                'fromDate',
                'toDate',
                'itemName',
                'supplierName',
                'userName',
            )
        );
    }
}
