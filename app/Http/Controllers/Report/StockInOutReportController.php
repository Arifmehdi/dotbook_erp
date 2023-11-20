<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockInOutReportController extends Controller
{
    public $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $stockInOuts = '';
            $query = DB::table('purchase_sale_product_chains')
                ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
                ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id')
                ->leftJoin('sale_return_products', 'purchase_products.sale_return_product_id', 'sale_return_products.id')
                ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->leftJoin('daily_stock_products', 'purchase_products.daily_stock_product_id', 'daily_stock_products.id')
                ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id');

            if ($request->product_id) {

                $query->where('sale_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('sale_products.product_variant_id', $request->variant_id);
            }

            if ($request->customer_account_id) {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sales.report_date', $date_range);
            }

            $query->select(
                'sales.id as sale_id',
                'sales.date',
                'sales.invoice_id',
                'products.name',
                'products.created_at as product_created_at',
                'products.product_cost_with_tax as unit_cost_inc_tax',
                'product_variants.variant_name',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit',
                'purchase_sale_product_chains.sold_qty',
                'customers.name as customer_name',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_inv',
                'productions.id as production_id',
                'productions.reference_no as production_voucher_no',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_invoice',
                'product_opening_stocks.id as pos_id',
                'daily_stocks.id as daily_stock_id',
                'daily_stocks.voucher_no as daily_stock_voucher',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity as stock_in_qty',
                'purchase_products.created_at as stock_in_date',
                'purchase_products.lot_no',
            );

            $stockInOuts = $query->orderBy('sales.report_date', 'desc');

            return DataTables::of($stockInOuts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                    return $row->name . $variant;
                })

                ->editColumn('sale', fn ($row) => '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details" class="text-hover" title="view" >' . $row->invoice_id . '</a>')

                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })

                ->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . $this->converter->format_in_bdt($row->unit_price_inc_tax) . '</span>')

                ->editColumn('sold_qty', function ($row) {

                    return '<span class="sold_qty" data-value="' . $row->sold_qty . '">' . $row->sold_qty . '/' . $row->unit . '</span>';
                })

                ->editColumn('customer_name', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })

                ->editColumn('stock_in_by', function ($row) {

                    if ($row->purchase_inv) {

                        return 'Purchase: ' . '<a href="' . route('purchases.show', [$row->purchase_id]) . '" class="text-hover" id="details" title="view" >' . $row->purchase_inv . '</a>';
                    } elseif ($row->production_voucher_no) {

                        return 'Production: ' . '<a href="' . route('manufacturing.productions.show', [$row->production_id]) . '" class=" text-hover" id="details" title="view" >' . $row->production_voucher_no . '</a>';
                    } elseif ($row->pos_id) {

                        return 'Opening Stock';
                    } elseif ($row->sale_return_id) {

                        return 'Sale Returned Stock : ' . '<a href="#" class="text-hover" id="details" title="view" >' . $row->sale_return_invoice . '</a>';
                    } elseif ($row->daily_stock_id) {

                        return 'Daily Stock : ' . '<a href="' . route('daily.stock.show', [$row->daily_stock_id]) . '" class="text-hover" id="details" title="view" >' . $row->daily_stock_voucher . '</a>';
                    } else {

                        return 'Non-Manageable-Stock';
                    }
                })

                ->editColumn('stock_in_date', function ($row) {
                    if ($row->stock_in_date) {

                        return date('d/m/Y', strtotime($row->stock_in_date));
                    } else {

                        return date('d/m/Y', strtotime($row->product_created_at));
                    }
                })

                // ->editColumn('stock_in_qty', function ($row) {
                //     return '<span class="stock_in_qty" data-value="' . $row->stock_in_qty . '">' . $row->stock_in_qty . '</span>';
                // })

                ->editColumn('net_unit_cost', function ($row) {

                    if ($row->net_unit_cost) {

                        return '<span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . $row->net_unit_cost . '</span>';
                    } else {

                        return '<span class="net_unit_cost" data-value="' . $row->unit_cost_inc_tax . '">' . $row->unit_cost_inc_tax . '</span>';
                    }
                })

                ->rawColumns(
                    [
                        'product',
                        'sale',
                        'date',
                        'unit_price_inc_tax',
                        'sold_qty',
                        'customer_name',
                        'stock_in_by',
                        'stock_in_date',
                        // 'stock_in_qty',
                        'net_unit_cost',
                    ]
                )->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('reports.stock_in_out_report.index', compact('customerAccounts'));
    }

    public function print(Request $request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $stockInOuts = '';
        $fromDate = '';
        $toDate = '';

        $query = DB::table('purchase_sale_product_chains')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
            ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id');

        if ($request->product_id) {

            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_products.product_variant_id', $request->variant_id);
        }

        if ($request->customer_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_id', null);
            } else {

                $query->where('sales.customer_id', $request->customer_id);
            }
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range);
        }

        $query->select(
            'sales.id as sale_id',
            'sales.date',
            'sales.invoice_id',
            'products.name',
            'product_variants.variant_name',
            'sale_products.unit_price_inc_tax',
            'sale_products.unit',
            'purchase_sale_product_chains.sold_qty',
            'customers.name as customer_name',
            'purchases.id as purchase_id',
            'purchases.invoice_id as purchase_inv',
            'productions.id as production_id',
            'productions.reference_no as production_voucher_no',
            'product_opening_stocks.id as pos_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity as stock_in_qty',
            'purchase_products.created_at as stock_in_date',
        );

        $stockInOuts = $query->orderBy('sales.report_date', 'desc')->get();

        return view('reports.stock_in_out_report.ajax_view.print', compact('stockInOuts', 'fromDate', 'toDate'));
    }
}
