<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SoldItemReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('pro_sale_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $saleProducts = '';
            $query = DB::table('sale_products')->where('sales.status', 1)->where('sale_products.quantity', '>', 0);

            if ($request->product_id) {

                $query->where('sale_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('sale_products.product_variant_id', $request->variant_id);
            }

            if ($request->customer_account_id) {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }

            if ($request->user_id) {

                $query->where('sales.sr_user_id', $request->user_id);
            }

            if ($request->sale_account_id) {

                $query->where('sales.sale_account_id', $request->sale_account_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sales.report_date', $date_range);
            }

            $query->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
                ->leftJoin('sales as do', 'sales.delivery_order_id', '=', 'do.id')
                ->leftJoin('weight_scales', 'sales.id', '=', 'weight_scales.sale_id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('units', 'sale_products.unit_id', 'units.id')->select(
                    'sale_products.sale_id',
                    'sale_products.product_id',
                    'sale_products.product_variant_id',
                    'sale_products.unit_price_inc_tax',
                    'sale_products.quantity',
                    'units.code_name as unit_code',
                    'units.base_unit_multiplier',
                    'sale_products.subtotal',
                    'sales.date',
                    'sales.invoice_id',
                    'weight_scales.do_car_number',
                    'products.name',
                    'products.product_code',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'customers.name as customer_name'
                );

            $saleProducts = $query->orderBy('sales.report_date', 'desc');

            return DataTables::of($saleProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return $row->name.$variant;
                })->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })->editColumn('customer', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })->editColumn('quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->quantity / $baseUnitMultiplier).'/<span class="qty" data-value="'.($row->quantity / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('unit_price_inc_tax', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return '<span class="unit_price_inc_tax" data-value="'.$row->unit_price_inc_tax * $baseUnitMultiplier.'">'.\App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax * $baseUnitMultiplier).'</span>';
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="subtotal" data-value="'.$row->subtotal.'">'.\App\Utils\Converter::format_in_bdt($row->subtotal).'</span>';
                })
                ->rawColumns(['product', 'date', 'quantity', 'unit_price_inc_tax', 'subtotal'])->make(true);
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

        return view('sales_app.reports.sold_items_report.index', compact('customerAccounts', 'users', 'saleAccounts'));
    }

    // Product sale report print
    public function print(Request $request)
    {
        if (! auth()->user()->can('pro_sale_report')) {

            abort(403, 'Access Forbidden.');
        }

        $saleProducts = '';
        $fromDate = '';
        $toDate = '';
        $itemName = $request->search_product;
        $customerName = $request->customer_name;
        $userName = $request->user_name;
        $query = DB::table('sale_products')->where('sales.status', 1)->where('sale_products.quantity', '>', 0);

        if ($request->product_id) {

            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_products.product_variant_id', $request->variant_id);
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->sale_account_id) {

            $query->where('sales.sale_account_id', $request->sale_account_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range);
        }

        $saleProducts = $query->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('weight_scales', 'sales.id', '=', 'weight_scales.sale_id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')->select(
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.product_variant_id',
                'sale_products.unit_price_inc_tax',
                'sale_products.quantity',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'sale_products.subtotal',
                'sales.date',
                'sales.report_date',
                'sales.invoice_id',
                'weight_scales.do_car_number',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'customers.name as customer_name'
            );

        $saleProducts = $query->orderBy('sales.report_date', 'desc')->get();

        $count = count($saleProducts);
        $veryLastDate = $count > 0 ? $saleProducts->last()->report_date : '';
        $lastRow = $count - 1;

        return view(
            'sales_app.reports.sold_items_report.ajax_view.print',
            compact(
                'saleProducts',
                'fromDate',
                'toDate',
                'itemName',
                'customerName',
                'userName',
                'veryLastDate',
                'lastRow',
            )
        );
    }

    // Search product
    public function searchProduct($product_name)
    {
        $products = DB::table('products')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )->get();

        if (count($products) > 0) {

            return view('reports.product_sale_report.ajax_view.search_result', compact('products'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }
}
