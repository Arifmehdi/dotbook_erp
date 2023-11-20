<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RequestedProductReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('requested_product_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $requestedProducts = '';

            $query = DB::table('purchase_requisition_products')
                ->leftJoin('purchase_requisitions', 'purchase_requisition_products.requisition_id', 'purchase_requisitions.id')
                ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
                ->leftJoin('requesters', 'purchase_requisitions.requester_id', 'requesters.id')
                ->leftJoin('products', 'purchase_requisition_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_requisition_products.variant_id', 'product_variants.id')
                ->leftJoin('units', 'purchase_requisition_products.unit_id', 'units.id');

            if ($request->department_id) {

                $query->where('purchase_requisitions.department_id', $request->department_id);
            }

            if ($request->requester_id) {

                $query->where('purchase_requisitions.requester_id', $request->requester_id);
            }

            if ($request->product_id) {

                $query->where('purchase_requisition_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_requisition_products.variant_id', $request->variant_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('purchase_requisitions.report_date', $date_range);
            }

            $requestedProducts = $query->select(
                'purchase_requisition_products.quantity',
                'purchase_requisition_products.purchase_qty',
                'purchase_requisition_products.received_qty',
                'purchase_requisition_products.left_qty',
                'purchase_requisition_products.last_purchase_price',
                'purchase_requisition_products.last_purchase_price_on',
                'purchase_requisition_products.unit',
                'purchase_requisitions.requisition_no',
                'purchase_requisitions.date',
                'purchase_requisitions.report_date',
                'departments.name as department',
                'requesters.name as requester',
                'products.name as product_name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
            )->orderBy('purchase_requisitions.report_date', 'desc');

            return DataTables::of($requestedProducts)

                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return $row->product_name.$variant;
                })
                ->editColumn('requester', function ($row) {

                    return $row->requester ? Str::limit($row->requester, 18, '..') : '...';
                })
                ->editColumn('last_purchase_price', function ($row) {

                    return $row->last_purchase_price.'(<strong>On: </strong>'.($row->last_purchase_price_on ? $row->last_purchase_price_on : 'N/A').')';
                })
                ->editColumn('quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->quantity / $baseUnitMultiplier).'/<span class="quantity" data-value="'.($row->quantity / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('purchase_qty', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->purchase_qty / $baseUnitMultiplier).'/<span class="purchase_qty" data-value="'.($row->purchase_qty / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('received_qty', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->received_qty / $baseUnitMultiplier).'/<span class="received_qty" data-value="'.($row->received_qty / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('left_qty', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->left_qty / $baseUnitMultiplier).'/<span class="left_qty" data-value="'.($row->left_qty / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->rawColumns(['product', 'quantity', 'received_qty', 'purchase_qty', 'left_qty', 'last_purchase_price'])
                ->make(true);
        }

        $departments = DB::table('departments')->orderBy('name', 'asc')->get(['id', 'name', 'phone']);
        $requesters = DB::table('requesters')->orderBy('name', 'asc')->get(['id', 'name', 'phone_number']);

        return view('procurement.reports.requested_product_report.index', compact('departments', 'requesters'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('requested_product_report')) {

            abort(403, 'Access Forbidden.');
        }

        $requestedProducts = '';
        $fromDate = '';
        $toDate = '';
        $search_product = $request->search_product;
        $department_name = $request->department_name;
        $requested_by_name = $request->requested_by_name;

        $query = DB::table('purchase_requisition_products')
            ->leftJoin('purchase_requisitions', 'purchase_requisition_products.requisition_id', 'purchase_requisitions.id')
            ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
            ->leftJoin('requesters', 'purchase_requisitions.requester_id', 'requesters.id')
            ->leftJoin('products', 'purchase_requisition_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_requisition_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'purchase_requisition_products.unit_id', 'units.id');

        if ($request->department_id) {

            $query->where('purchase_requisitions.department_id', $request->department_id);
        }

        if ($request->requester_id) {

            $query->where('purchase_requisitions.requester_id', $request->requester_id);
        }

        if ($request->product_id) {

            $query->where('purchase_requisition_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_requisition_products.variant_id', $request->variant_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchase_requisitions.report_date', $date_range);
        }

        $requestedProducts = $query->select(
            'purchase_requisition_products.quantity',
            'purchase_requisition_products.purchase_qty',
            'purchase_requisition_products.received_qty',
            'purchase_requisition_products.left_qty',
            'purchase_requisition_products.unit',
            'purchase_requisitions.requisition_no',
            'purchase_requisitions.date',
            'purchase_requisitions.report_date',
            'departments.name as department',
            'requesters.name as requester',
            'products.name as product_name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'units.code_name as unit_code',
            'units.base_unit_multiplier',
        )->orderBy('purchase_requisitions.report_date', 'desc')->get();

        return view('procurement.reports.requested_product_report.ajax_view.print', compact(
            'requestedProducts',
            'fromDate',
            'toDate',
            'requested_by_name',
            'search_product',
            'department_name'
        ));
    }
}
