<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ReceiveStockReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $receiveStockProducts = '';

            $query = DB::table('receive_stock_products');

            if ($request->product_id) {

                $query->where('receive_stock_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('receive_stock_products.variant_id', $request->variant_id);
            }

            if (! empty($request->warehouse_id)) {

                $query->where('receive_stocks.warehouse_id', $request->warehouse_id);
            }

            if ($request->supplier_account_id) {

                $query->where('receive_stocks.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->user_id) {

                $query->where('receive_stocks.created_by_id', $request->user_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('receive_stocks.date_ts', $date_range); // Final
            }

            $receiveStockProducts = $query->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                ->leftJoin('warehouses', 'receive_stocks.warehouse_id', 'warehouses.id')
                ->leftJoin('purchase_requisitions', 'receive_stocks.requisition_id', 'purchase_requisitions.id')
                ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
                ->leftJoin('products', 'receive_stock_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'receive_stock_products.variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'receive_stocks.supplier_account_id', 'suppliers.id')
                ->leftJoin('users', 'receive_stocks.created_by_id', 'users.id')
                ->leftJoin('units', 'receive_stock_products.unit_id', 'units.id')
                ->select(
                    'receive_stock_products.receive_stock_id',
                    'receive_stock_products.product_id',
                    'receive_stock_products.variant_id',
                    'receive_stock_products.quantity',
                    'receive_stock_products.unit',
                    'receive_stock_products.lot_number',
                    'receive_stock_products.short_description',
                    'receive_stocks.date',
                    'receive_stocks.date_ts',
                    'receive_stocks.voucher_no',
                    'products.name',
                    'products.product_code',
                    'products.product_price',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'product_variants.variant_price',
                    'suppliers.name as supplier_name',
                    'purchase_requisitions.requisition_no',
                    'departments.name as department_name',
                    'users.prefix as u_prefix',
                    'users.name as u_name',
                    'users.last_name as u_last_name',
                    'warehouses.warehouse_name as w_name',
                    'warehouses.warehouse_code as w_code',
                    'units.code_name as unit_code',
                    'units.base_unit_multiplier',
                )->orderBy('receive_stocks.date_ts', 'desc');

            return DataTables::of($receiveStockProducts)
                ->editColumn('product_name', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return $row->name.$variant;
                })

                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })

                ->editColumn('stored_location', function ($row) use ($generalSettings) {

                    if ($row->w_name) {

                        return $row->w_name.$row->w_code.'<b>(WH)</b>';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'];
                    }
                })

                ->editColumn('quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->quantity / $baseUnitMultiplier).'/<span class="qty" data-value="'.($row->quantity / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('supplier_name', function ($row) {

                    return '<span title="'.$row->supplier_name.'">'.Str::limit($row->supplier_name, 15).'</span>';
                })

                ->editColumn('createdBy', function ($row) {

                    return $row->u_prefix.' '.$row->u_name.' '.$row->u_last_name;
                })

                ->rawColumns(['stored_location', 'product_name', 'supplier_name', 'date', 'date', 'quantity', 'createdBy'])
                ->make(true);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('procurement.reports.received_stock_report.index', compact('supplierAccounts', 'users'));
    }

    public function print(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        $searchProduct = $request->search_product;
        $supplierName = $request->supplier_name;
        $userName = $request->user_name;

        $receiveStockProducts = '';

        $query = DB::table('receive_stock_products');

        if ($request->product_id) {

            $query->where('receive_stock_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('receive_stock_products.variant_id', $request->variant_id);
        }

        if (! empty($request->warehouse_id)) {

            $query->where('receive_stocks.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_account_id) {

            $query->where('receive_stocks.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->user_id) {

            $query->where('receive_stocks.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('receive_stocks.date_ts', $date_range); // Final
        }

        $receiveStockProducts = $query->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
            ->leftJoin('warehouses', 'receive_stocks.warehouse_id', 'warehouses.id')
            ->leftJoin('purchase_requisitions', 'receive_stocks.requisition_id', 'purchase_requisitions.id')
            ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
            ->leftJoin('products', 'receive_stock_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'receive_stock_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'receive_stocks.supplier_account_id', 'suppliers.id')
            ->leftJoin('users', 'receive_stocks.created_by_id', 'users.id')
            ->leftJoin('units', 'receive_stock_products.unit_id', 'units.id')
            ->select(
                'receive_stock_products.receive_stock_id',
                'receive_stock_products.product_id',
                'receive_stock_products.variant_id',
                'receive_stock_products.quantity',
                'receive_stock_products.unit',
                'receive_stock_products.lot_number',
                'receive_stock_products.short_description',
                'receive_stocks.date',
                'receive_stocks.date_ts',
                'receive_stocks.voucher_no',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name',
                'purchase_requisitions.requisition_no',
                'departments.name as department_name',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
            )->orderBy('receive_stocks.date_ts', 'desc')->get();

        $count = count($receiveStockProducts);
        $veryLastDate = $count > 0 ? $receiveStockProducts->last()->date_ts : '';
        $lastRow = $count - 1;

        return view('procurement.reports.received_stock_report.ajax_view.print', compact(
            'receiveStockProducts',
            'fromDate',
            'toDate',
            'searchProduct',
            'supplierName',
            'userName',
            'veryLastDate',
            'lastRow',
        ));
    }
}
