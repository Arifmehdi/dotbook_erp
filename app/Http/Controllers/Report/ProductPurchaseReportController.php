<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductPurchaseReportController extends Controller
{
    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $purchaseProducts = '';

            $query = DB::table('purchase_products')
                ->where('production_id', null)
                ->where('opening_stock_id', null)
                ->where('sale_return_product_id', null)
                ->where('daily_stock_product_id', null)
                ->where('purchases.is_purchased', 1);

            if ($request->product_id) {

                $query->where('purchase_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_products.product_variant_id', $request->variant_id);
            }

            if ($request->supplier_account_id) {

                $query->where('purchases.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('purchases.report_date', $date_range);
            }

            $purchaseProducts = $query->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
                ->leftJoin('products', 'purchase_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
                ->leftJoin('units', 'purchase_products.unit_id', 'units.id')->select(
                    'purchase_products.purchase_id',
                    'purchase_products.product_id',
                    'purchase_products.product_variant_id',
                    'purchase_products.net_unit_cost',
                    'purchase_products.quantity',
                    'units.code_name as unit_code',
                    'units.base_unit_multiplier',
                    'purchase_products.line_total',
                    'purchase_products.selling_price',
                    'purchases.date',
                    'purchases.invoice_id',
                    'products.name',
                    'products.product_code',
                    'products.product_price',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'product_variants.variant_price',
                    'suppliers.name as supplier_name'
                )->orderBy('purchases.report_date', 'desc');

            return DataTables::of($purchaseProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return $row->name.$variant;
                })
                ->editColumn('product_code', function ($row) {

                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->quantity / $baseUnitMultiplier).'/<span class="qty" data-value="'.($row->quantity / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('net_unit_cost', fn ($row) => '<span class="net_unit_cost" data-value="'.$row->net_unit_cost.'">'.\App\Utils\Converter::format_in_bdt($row->net_unit_cost).'</span>')

                ->editColumn('price', function ($row) {

                    if ($row->selling_price > 0) {

                        return \App\Utils\Converter::format_in_bdt($row->selling_price);
                    } else {

                        if ($row->variant_name) {

                            return \App\Utils\Converter::format_in_bdt($row->variant_price);
                        } else {

                            return \App\Utils\Converter::format_in_bdt($row->product_price);
                        }
                    }

                    return '<span class="net_unit_cost" data-value="'.$row->net_unit_cost.'">'.\App\Utils\Converter::format_in_bdt($row->net_unit_cost).'</span>';
                })
                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="'.$row->line_total.'">'.\App\Utils\Converter::format_in_bdt($row->line_total).'</span>')
                ->rawColumns(['product', 'product_code', 'date', 'quantity', 'net_unit_cost', 'price', 'subtotal'])
                ->make(true);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.reports.purchased_product_report.index', compact('supplierAccounts'));
    }

    public function print(Request $request)
    {
        $purchaseProducts = '';
        $fromDate = '';
        $toDate = '';

        $searchProduct = $request->search_product;
        $supplierName = $request->supplier_name;

        $query = DB::table('purchase_products')
            ->where('production_id', null)
            ->where('opening_stock_id', null)
            ->where('sale_return_product_id', null)
            ->where('daily_stock_product_id', null);

        if ($request->product_id) {

            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range);
        }

        $purchaseProducts = $query->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'purchase_products.unit_id', 'units.id')->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'purchase_products.line_total',
                'purchases.date',
                'purchases.report_date',
                'purchases.invoice_id',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'suppliers.name as supplier_name'
            )->orderBy('purchases.report_date', 'desc')->get();

        return view('procurement.reports.purchased_product_report.ajax_view.print', compact(
            'purchaseProducts',
            'fromDate',
            'toDate',
            'searchProduct',
            'supplierName'
        ));
    }
}
