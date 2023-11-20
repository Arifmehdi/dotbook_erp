<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderedQuantityReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('ordered_item_qty_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->select('business')->first();
            $orderedProductQty = '';

            $query = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->where('sales.order_status', 1);

            if ($request->customer_account_id) {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }

            if ($request->user_id) {

                $query->where('sales.sr_user_id', $request->user_id);
            }

            if ($request->product_id) {

                $query->where('sale_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('sale_products.product_variant_id', $request->variant_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sales.order_date', $date_range);
            }

            $orderedProductQty = $query->select(
                'products.name as product_name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'units.name as unit',
                DB::raw('SUM(sale_products.ordered_quantity) as ordered_qty'),
                DB::raw('SUM(sale_products.do_delivered_qty) as delivered_qty'),
                DB::raw('SUM(sale_products.do_left_qty) as left_qty'),
            )->groupBy('sale_products.product_id')
                ->groupBy('sale_products.product_variant_id');

            // ->groupBy('products.name')
            // ->groupBy('products.product_code')
            // ->groupBy('product_variants.variant_name')
            // ->groupBy('product_variants.variant_code')
            // ->groupBy('units.name')
            // ->groupBy('sale_products.product_id')
            // ->groupBy('sale_products.product_variant_id');

            return DataTables::of($orderedProductQty)

                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                    return $row->product_name . $variant;
                })
                ->editColumn('ordered_qty', fn ($row) => $this->converter->format_in_bdt($row->ordered_qty) . '/<span class="ordered_qty" data-value="' . $row->ordered_qty . '">' . $row->unit . '</span>')
                ->editColumn('delivered_qty', fn ($row) => $this->converter->format_in_bdt($row->delivered_qty) . '/<span class="delivered_qty" data-value="' . $row->delivered_qty . '">' . $row->unit . '</span>')
                ->editColumn('left_qty', fn ($row) => $this->converter->format_in_bdt(($row->left_qty > 0 ? $row->left_qty : $row->ordered_qty)) . '/<span class="left_qty" data-value="' . ($row->left_qty > 0 ? $row->left_qty : $row->ordered_qty) . '">' . $row->unit . '</span>')
                ->rawColumns(['product', 'ordered_qty', 'delivered_qty', 'left_qty'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->get(['id', 'prefix', 'name', 'last_name', 'phone']);

        return view('sales_app.reports.ordered_quantity_report.index', compact('customerAccounts', 'users'));
    }

    public function index2(Request $request)
    {
        if (!auth()->user()->can('ordered_item_qty_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->select('business')->first();
            $orderedProductQty = '';

            $query = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id');

            if ($request->customer_account_id) {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }

            if ($request->user_id) {

                $query->where('sales.sr_user_id', $request->user_id);
            }

            if ($request->product_id) {

                $query->where('sale_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('sale_products.product_variant_id', $request->variant_id);
            }

            $fromDateYmd = '';
            $toDateYmd = '';

            if ($request->from_date && $request->to_date) {

                $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
                $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            }

            $orderedProductQty = $query->select(
                'products.name as product_name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'units.name as unit',
                DB::raw(
                    "
                        IFNULL(
                            SUM(
                                case when sales.order_status = 1 and
                                timestamp(sales.order_date) > '$fromDateYmd'
                                and timestamp(sales.order_date) < '$toDateYmd' and
                                sale_products.ordered_quantity > 0
                                then sale_products.ordered_quantity end
                        ), 0) as ordered_qty
                    "
                ),
                DB::raw('SUM(sale_products.do_delivered_qty) as delivered_qty'),
                DB::raw(
                    "
                        IFNULL(
                            SUM(
                                case when sales.order_status = 0 and
                                sales.delivery_order_id not null and
                                timestamp(sales.report_date) > '$fromDateYmd'
                                and timestamp(sales.report_date) < '$toDateYmd' and
                                sale_products.quantity > 0
                                then sale_products.quantity end
                        ), 0) as delivered_qty
                    "
                ),
            )->whereRaw('concat(sale_products.quantity,sale_products.ordered_quantity) > 0')
            ->groupBy('sale_products.product_id')
                ->groupBy('sale_products.product_variant_id');

            // ->groupBy('products.name')
            // ->groupBy('products.product_code')
            // ->groupBy('product_variants.variant_name')
            // ->groupBy('product_variants.variant_code')
            // ->groupBy('units.name')
            // ->groupBy('sale_products.product_id')
            // ->groupBy('sale_products.product_variant_id');

            return DataTables::of($orderedProductQty)

                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                    return $row->product_name . $variant;
                })
                ->editColumn('ordered_qty', fn ($row) => $this->converter->format_in_bdt($row->ordered_qty) . '/<span class="ordered_qty" data-value="' . $row->ordered_qty . '">' . $row->unit . '</span>')
                ->editColumn('delivered_qty', fn ($row) => $this->converter->format_in_bdt($row->delivered_qty) . '/<span class="delivered_qty" data-value="' . $row->delivered_qty . '">' . $row->unit . '</span>')
                ->editColumn('left_qty', fn ($row) => $this->converter->format_in_bdt(($row->left_qty > 0 ? $row->left_qty : $row->ordered_qty)) . '/<span class="left_qty" data-value="' . ($row->left_qty > 0 ? $row->left_qty : $row->ordered_qty) . '">' . $row->unit . '</span>')
                ->rawColumns(['product', 'ordered_qty', 'delivered_qty', 'left_qty'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->get(['id', 'prefix', 'name', 'last_name', 'phone']);

        return view('sales_app.reports.ordered_quantity_report.index', compact('customerAccounts', 'users'));
    }

    public function print(Request $request)
    {
        if (!auth()->user()->can('ordered_item_qty_report')) {

            abort(403, 'Access Forbidden.');
        }

        $orderedProductQty = '';
        $fromDate = '';
        $toDate = '';
        $search_product = $request->search_product;
        $customer_name = $request->customer_name;
        $user_name = $request->user_name;

        $orderedProductQty = '';

        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->where('sales.order_status', 1);

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->product_id) {

            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_products.variant_id', $request->variant_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.order_date', $date_range);
        } else {
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $orderedProductQty = $query->select(
            'products.name as product_name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'units.name as unit',
            DB::raw('SUM(sale_products.ordered_quantity) as ordered_qty'),
            DB::raw('SUM(sale_products.do_delivered_qty) as delivered_qty'),
            DB::raw('SUM(sale_products.do_left_qty) as left_qty'),
        )->groupBy('sale_products.product_id')
            ->groupBy('sale_products.product_variant_id')
            ->get();

        return view('sales_app.reports.ordered_quantity_report.ajax_view.print', compact('orderedProductQty', 'fromDate', 'toDate', 'customer_name', 'search_product', 'user_name'));
    }
}
