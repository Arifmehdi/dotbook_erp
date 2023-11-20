<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PurchaseByScaleUtil
{
    public function purchaseByScaleList($request)
    {
        $generalSettings = DB::table('general_settings')->first();

        $purchaseByScales = '';

        $query = DB::table('purchase_by_scales')
            ->leftJoin('accounts as suppliers', 'purchase_by_scales.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'purchase_by_scales.created_by_id', 'created_by.id');

        if ($request->supplier_account_id) {

            $query->where('purchase_by_scales.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->status) {

            $query->where('purchase_by_scales.status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchase_by_scales.date_ts', $date_range); // Final
        }

        $purchaseByScales = $query->select(
            'purchase_by_scales.id',
            'purchase_by_scales.voucher_no',
            'purchase_by_scales.challan_no',
            'purchase_by_scales.challan_date',
            'purchase_by_scales.vehicle_number',
            'purchase_by_scales.driver_name',
            'purchase_by_scales.driver_phone',
            'purchase_by_scales.first_weight',
            'purchase_by_scales.last_weight',
            'purchase_by_scales.net_weight',
            'purchase_by_scales.date',
            'purchase_by_scales.date_ts',
            'purchase_by_scales.status',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('purchase_by_scales.date_ts', 'desc');

        return DataTables::of($purchaseByScales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if (auth()->user()->can('purchase_by_scale_view')) {

                    $html .= '<a class="dropdown-item" id="details_btn" href="'.route('purchases.by.scale.show', [$row->id]).'"> View</a>';
                }

                if (auth()->user()->can('purchase_by_scale_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('purchases.by.scale.delete', $row->id).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date_ts));
            })

            ->editColumn('first_weight', fn ($row) => '<span class="first_weight" data-value="'.$row->first_weight.'"><strong>'.\App\Utils\Converter::format_in_bdt($row->first_weight).'</strong></span>')

            ->editColumn('last_weight', fn ($row) => '<span class="last_weight" data-value="'.$row->last_weight.'"><strong>'.\App\Utils\Converter::format_in_bdt($row->last_weight).'</strong></span>')

            ->editColumn('net_weight', fn ($row) => '<span class="net_weight" data-value="'.$row->net_weight.'"><strong>'.\App\Utils\Converter::format_in_bdt($row->net_weight).'</strong></span>')

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    return '<span class="badge badge-sm bg-success text-white"><b>Completed</b></span>';
                } elseif ($row->status == 0) {

                    return '<span class="badge badge-sm bg-danger text-white"><b>Running</b></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'first_weight', 'last_weight', 'net_weight', 'status', 'created_by'])
            ->make(true);
    }
}
