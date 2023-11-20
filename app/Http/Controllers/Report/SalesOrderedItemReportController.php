<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesOrderedItemReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('ordered_item_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $orderedProducts = '';
            $query = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('units', 'sale_products.unit_id', 'units.id')
                ->where('sales.order_status', 1);

            if ($request->product_id) {

                $query->where('sale_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('sale_products.product_variant_id', $request->variant_id);
            }

            if ($request->customer_account_id) {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }

            if ($request->sale_account_id) {

                $query->where('sales.sale_account_id', $request->sale_account_id);
            }

            if ($request->user_id) {

                $query->where('sales.sr_user_id', $request->user_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sales.order_date', $date_range);
            }

            $query->select(
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.product_variant_id',
                'sale_products.unit_price_inc_tax',
                'sale_products.price_type',
                'sale_products.pr_amount',
                'sale_products.ordered_quantity',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'sale_products.subtotal',
                'sales.order_date',
                'sales.order_id',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'customers.name as customer_name'
            );

            $orderedProducts = $query->orderBy('sales.order_date', 'desc');

            return DataTables::of($orderedProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                    return $row->name . $variant;
                })->editColumn('sku', function ($row) {

                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->order_date));
                })->editColumn('customer', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })->editColumn('ordered_quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->ordered_quantity / $baseUnitMultiplier, 0, 2) . '/<span class="qty" data-value="' . $row->ordered_quantity / $baseUnitMultiplier . '">' . $row->unit_code . '</span>';
                })
                ->editColumn('unit_price_inc_tax', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax * $baseUnitMultiplier . '">' . \App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax * $baseUnitMultiplier) . '</span>';
                })
                ->editColumn('price_type', fn ($row) => '<span class="pr_amount" data-value="' . $row->pr_amount . '">' . $row->price_type . ($row->price_type == 'PR' ? '(' . \App\Utils\Converter::format_in_bdt($row->pr_amount) . ')' : '') . '</span>')

                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . \App\Utils\Converter::format_in_bdt($row->subtotal) . '</span>')

                ->rawColumns(['product', 'sku', 'date', 'ordered_quantity', 'unit_price_inc_tax', 'price_type', 'subtotal'])->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.reports.sales_ordered_items_report.index', compact('customerAccounts', 'saleAccounts', 'users'));
    }

    // Product sale report print
    public function print(Request $request)
    {
        $saleProducts = '';
        $fromDate = '';
        $toDate = '';

        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->where('sales.order_status', 1);

        if ($request->product_id) {

            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_products.product_variant_id', $request->variant_id);
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->sale_account_id) {

            $query->where('sales.sale_account_id', $request->sale_account_id);
        }

        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.order_date', $date_range);
        }

        $saleProducts = $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.product_variant_id',
            'sale_products.unit_price_inc_tax',
            'sale_products.price_type',
            'sale_products.pr_amount',
            'sale_products.ordered_quantity',
            'units.code_name as unit_code',
            'units.base_unit_multiplier',
            'sale_products.subtotal',
            'sales.order_date',
            'sales.order_id',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'customers.name as customer_name'
        );

        $saleProducts = $query->orderBy('sales.order_date', 'desc')->get();

        $count = count($saleProducts);
        $veryLastDate = $count > 0 ? $saleProducts->last()->order_date : '';
        $lastRow = $count - 1;

        return view('sales_app.reports.sales_ordered_items_report.ajax_view.print', compact(
            'saleProducts',
            'fromDate',
            'toDate',
            'veryLastDate',
            'lastRow'
        ));
    }
}
