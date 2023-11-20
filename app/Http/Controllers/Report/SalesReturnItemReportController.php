<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesReturnItemReportController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('sales_returned_items_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $salesReturnProducts = '';
            $query = DB::table('sale_return_products')->where('sale_return_products.return_qty', '>', 0);

            if ($request->product_id) {

                $query->where('sale_return_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('sale_return_products.product_variant_id', $request->variant_id);
            }

            if ($request->customer_account_id) {

                $query->where('sale_returns.customer_account_id', $request->customer_account_id);
            }

            if ($request->user_id) {

                $query->where('sale_return_products.sr_user_id', $request->user_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sale_returns.report_date', $date_range);
            }

            $query->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->leftJoin('products', 'sale_return_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_return_products.product_variant_id', 'product_variants.id')
                ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
                ->leftJoin('units', 'sale_return_products.unit_id', 'units.id')->select(
                    'sale_return_products.sale_return_id',
                    'sale_return_products.product_id',
                    'sale_return_products.product_variant_id',
                    'sale_return_products.unit_price_inc_tax',
                    'sale_return_products.return_qty',
                    'sale_return_products.return_subtotal',
                    'units.code_name as unit_code',
                    'units.base_unit_multiplier',
                    'sale_returns.date',
                    'sale_returns.report_date',
                    'sale_returns.voucher_no',
                    'products.name',
                    'products.product_code',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'customers.name as customer_name'
                );

            $salesReturnProducts = $query->orderBy('sale_returns.report_date', 'desc');

            return DataTables::of($salesReturnProducts)
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
                ->editColumn('unit_price_inc_tax', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return '<span class="unit_price_inc_tax" data-value="'.$row->unit_price_inc_tax * $baseUnitMultiplier.'">'.\App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax * $baseUnitMultiplier).'</span>';
                })
                ->editColumn('return_subtotal', function ($row) {
                    return '<span class="return_subtotal" data-value="'.$row->return_subtotal.'">'.\App\Utils\Converter::format_in_bdt($row->return_subtotal).'</span>';
                })
                ->rawColumns(['product', 'date', 'return_qty', 'unit_price_inc_tax', 'return_subtotal'])->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.reports.sales_returned_items_report.index', compact('customerAccounts', 'users'));
    }

    public function print(Request $request)
    {
        $fromDate = '';
        $toDate = '';
        $itemName = $request->search_product;
        $customerName = $request->customer_name;
        $userName = $request->user_name;

        $salesReturnProducts = '';
        $query = DB::table('sale_return_products')->where('sale_return_products.return_qty', '>', 0);

        if ($request->product_id) {

            $query->where('sale_return_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_return_products.product_variant_id', $request->variant_id);
        }

        if ($request->customer_account_id) {

            $query->where('sale_returns.customer_account_id', $request->customer_account_id);
        }

        if ($request->user_id) {

            $query->where('sale_returns.sr_user_id', $request->user_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sale_returns.report_date', $date_range);
        }

        $query->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
            ->leftJoin('products', 'sale_return_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_return_products.product_variant_id', 'product_variants.id')
            ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
            ->leftJoin('units', 'sale_return_products.unit_id', 'units.id')->select(
                'sale_return_products.sale_return_id',
                'sale_return_products.product_id',
                'sale_return_products.product_variant_id',
                'sale_return_products.unit_price_inc_tax',
                'sale_return_products.return_qty',
                'sale_return_products.return_subtotal',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'sale_returns.date',
                'sale_returns.report_date',
                'sale_returns.voucher_no',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'customers.name as customer_name'
            );

        $salesReturnProducts = $query->orderBy('sale_returns.report_date', 'desc')->get();

        return view(
            'sales_app.reports.sales_returned_items_report.ajax_view.print',
            compact(
                'salesReturnProducts',
                'fromDate',
                'toDate',
                'itemName',
                'customerName',
                'userName',
            )
        );
    }
}
