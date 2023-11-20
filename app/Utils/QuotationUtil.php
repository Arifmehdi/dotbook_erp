<?php

namespace App\Utils;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class QuotationUtil
{
    protected $converter;

    public function __construct(Converter $converter)
    {

        $this->converter = $converter;
    }

    public function addQuotation($request, $srUserId, $generator, $invoicePrefix)
    {
        $quotationId = $generator->generateMonthWise(table: 'sales', column: 'quotation_id', prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-');

        $addQuotation = new Sale();
        $addQuotation->quotation_id = $quotationId;
        $addQuotation->quotation_by_id = auth()->user()->id;
        $addQuotation->sr_user_id = $srUserId;
        $addQuotation->customer_account_id = $request->customer_account_id;
        $addQuotation->status = 4;
        $addQuotation->quotation_status = 1;
        $addQuotation->quotation_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addQuotation->expire_date = $request->expire_date ? date('Y-m-d H:i:s', strtotime($request->expire_date.$request->expire_time)) : null;
        $addQuotation->total_item = $request->total_item ? $request->total_item : 0;
        $addQuotation->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $addQuotation->order_discount_type = $request->order_discount_type;
        $addQuotation->order_discount = $request->order_discount ? $request->order_discount : 0;
        $addQuotation->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $addQuotation->tax_ac_id = $request->order_tax_ac_id;
        $addQuotation->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $addQuotation->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addQuotation->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addQuotation->total_payable_amount = $request->total_invoice_payable ? $request->total_invoice_payable : 0;
        $addQuotation->all_price_type = $request->all_price_type;
        $addQuotation->sale_note = $request->sale_note;
        $addQuotation->save();

        return $addQuotation;
    }

    public function saleQuotationTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $quotations = '';

        $query = DB::table('sales')->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users', 'sales.quotation_by_id', 'users.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id');

        $query->select(
            'sales.*',
            'customers.name as customer',
            'users.prefix as u_prefix',
            'users.name as u_name',
            'users.last_name as u_last_name',
            'sr.prefix as sr_prefix',
            'sr.name as sr_name',
            'sr.last_name as sr_last_name',
        );

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.quotation_by_id', auth()->user()->id);
        }

        $quotations = $this->filteredQuery($request, $query)
            ->where('sales.quotation_status', 1)->orderBy('sales.quotation_date', 'desc');

        return DataTables::of($quotations)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('sales.quotations.show', [$row->id]).'"> View</a>';

                if (auth()->user()->can('sale_quotation_edit')) {

                    if ($row->created_by == 1) {

                        $html .= '<a class="dropdown-item" href="'.route('sales.quotations.edit', [$row->id]).'"> Edit</a>';
                    } else {

                        $html .= '<a class="dropdown-item" href="'.route('sales.pos.edit', [$row->id]).'"> Edit</a>';
                    }
                }

                if (auth()->user()->can('sale_quotation_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.delete', [$row->id]).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('quotation_date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->quotation_date));
            })

            ->editColumn('expire_date', function ($row) use ($generalSettings) {

                if ($row->expire_date) {

                    $__date = date('Y-m-d', strtotime($row->expire_date));
                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date('Y-m-d') > $__date ? '<strong class="text-danger">'.date($__date_format, strtotime($row->expire_date)).'</strong>' : date($__date_format, strtotime($row->expire_date));
                } else {

                    return '...';
                }
            })

            ->editColumn('current_status', function ($row) {

                if ($row->order_status == 1) {

                    return '<span class="badge bg-dark text-white">Ordered</span>';
                } else {

                    return '<span class="badge bg-info text-white">Quotation</span>';
                }
            })

            ->editColumn('customer', function ($row) {

                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })

            ->editColumn('quotation_id', function ($row) {

                return '<a href="'.route('sales.quotations.show', [$row->id]).'" id="details_btn" class="fw-bold">'.$row->quotation_id.'</a>';
            })

            ->editColumn('user', function ($row) {

                return $row->u_prefix.' '.$row->u_name.' '.$row->u_last_name;
            })

            ->editColumn('sr', function ($row) {

                return $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name;
            })

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_payable_amount).'</span>')

            ->rawColumns(['action', 'date', 'expire_date', 'current_status', 'customer', 'quotation_id', 'total_payable_amount', 'user', 'sr'])
            ->make(true);
    }

    public function updateQuotationProduct($quotationProduct, $request, $index)
    {

    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.quotation_date', $date_range); // Final
        }

        return $query;
    }
}
