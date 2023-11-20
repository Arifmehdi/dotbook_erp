<?php

namespace App\Utils;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeliveryOrderUtil
{
    public function updateDo($updateDo, $request)
    {
        $updateDo->sale_account_id = $request->sale_account_id;
        $updateDo->total_item = $request->total_item;
        $updateDo->total_ordered_qty = $request->total_qty;
        $updateDo->total_do_qty = $request->total_qty;
        $updateDo->net_total_amount = $request->net_total_amount;
        $updateDo->order_discount_type = $request->order_discount_type;
        $updateDo->order_discount = $request->order_discount;
        $updateDo->order_discount_amount = $request->order_discount_amount;
        $updateDo->tax_ac_id = $request->order_tax_ac_id;
        $updateDo->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0.00;
        $updateDo->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateDo->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updateDo->total_payable_amount = $request->total_invoice_amount;
        $updateDo->do_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $updateDo->expire_date = $request->expire_date ? date('Y-m-d H:i:s', strtotime($request->expire_date.' '.$request->expire_time)) : null;
        $updateDo->all_price_type = $request->all_price_type;
        $updateDo->sale_note = $request->sale_note;
        $updateDo->payment_note = $request->payment_note;
        $updateDo->comment = $request->comment;
        $updateDo->shipping_address = $request->shipping_address;
        $updateDo->receiver_phone = $request->receiver_phone;
        $updateDo->price_adjustment_note = $request->price_adjustment_note;
        $updateDo->save();

        return $updateDo;
    }

    public function addDoSalesInvoice($weight, $do, $request)
    {
        $addSale = new Sale();
        $addSale->invoice_id = $weight->reserve_invoice_id;
        $addSale->status = 1;
        $addSale->customer_account_id = $do->customer_account_id;
        $addSale->sale_account_id = $do->sale_account_id;
        $addSale->sr_user_id = $do->sr_user_id;
        $addSale->sale_by_id = auth()->user()->id;
        $addSale->total_item = $request->total_item;
        $addSale->total_sold_qty = $request->total_qty;
        $addSale->total_delivered_qty = $request->total_qty;
        $addSale->date = $request->date;
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addSale->date = $request->date;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->total_payable_amount = $request->net_total_amount;
        $addSale->delivery_order_id = $do->id;
        $addSale->all_price_type = $do->all_price_type;
        $addSale->do_total_left_qty = $do->do_total_left_qty;
        $addSale->shipping_address = $request->shipping_address;
        $addSale->receiver_phone = $request->receiver_phone;
        $addSale->do_to_inv_challan_no = $request->do_to_inv_challan_no;
        $addSale->do_to_inv_challan_date = $request->do_to_inv_challan_date ? $request->do_to_inv_challan_date.' '.date('h:i A') : date('d-m-Y h:i A');
        $addSale->save();

        return $addSale;
    }

    public function doTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $do = '';
        $query = Sale::query()->with(
            'references:id,payment_description_id,sale_id,amount',
            'references.paymentDescription:id,payment_id',
            'references.paymentDescription.payment:id,date',
            'references.paymentDescription.payment.descriptions:id,payment_id,account_id,payment_method_id',
            'references.paymentDescription.payment.descriptions.paymentMethod:id,name',
            'references.paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.paymentDescription.payment.descriptions.account.bank:id,name',
            'references.paymentDescription.payment.descriptions.account.group:id,sub_sub_group_number',
        );

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.do_date', $date_range); // Final
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query)->where('sales.do_status', 1);

        $do = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
            ->select(
                'sales.id',
                'sales.do_id',
                'sales.do_date',
                'sales.total_payable_amount',
                'sales.total_ordered_qty',
                'sales.total_delivered_qty',
                'sales.do_total_left_qty',
                'sales.paid',
                'sales.payment_note',
                // 'sales.due',
                // 'sales.expire_date',
                'sales.do_status',
                'sales.delivery_qty_status',
                'customers.name as customer_name',
                'sr.prefix as sr_prefix',
                'sr.name as sr_name',
                'sr.last_name as sr_last_name',
            )->orderBy('sales.do_date', 'desc');

        return DataTables::of($do)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" id="details_btn" href="'.route('sales.delivery.order.show', [$row->id]).'">'.__('menu.view').'</a>';

                if (auth()->user()->can('do_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('sales.delivery.order.edit', [$row->id]).'"> Edit</a>';
                }

                if (auth()->user()->can('do_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.delete', [$row->id]).'"> Delete</a>';
                }

                // $html .= '<a class="dropdown-item" id="send_notification" href="' . route('sales.notification.form', [$row->id]) . '"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('do_date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->do_date));
            })

            // ->editColumn('expire_date', function ($row) use ($generalSettings) {

            //     if ($row->expire_date) {

            //         $__date = date('Y-m-d', strtotime($row->expire_date));
            //         $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

            //         return date('Y-m-d') > $__date ? '<strong class="text-danger">' . date($__date_format, strtotime($row->expire_date)) . '</strong>' : date($__date_format, strtotime($row->expire_date));
            //     } else {

            //         return '...';
            //     }
            // })

            ->editColumn('sr', fn ($row) => $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name)

            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_ordered_qty', fn ($row) => '<span class="total_ordered_qty" data-value="'.$row->total_ordered_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_ordered_qty).'</span>')

            ->editColumn('total_delivered_qty', fn ($row) => '<span class="total_delivered_qty" data-value="'.$row->total_delivered_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_delivered_qty).'</span>')

            ->editColumn('do_total_left_qty', fn ($row) => '<span class="do_total_left_qty" data-value="'.$row->do_total_left_qty.'">'.\App\Utils\Converter::format_in_bdt($row->do_total_left_qty).'</span>')

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_payable_amount).'</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid" data-value="'.$row->paid.'">'.\App\Utils\Converter::format_in_bdt($row->paid).'</span>')

            // ->editColumn('due', fn ($row) =>  '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

            ->editColumn('delivery_status', function ($row) {

                if ($row->delivery_qty_status == 0) {

                    return '<span class="badge bg-danger text-white">Pending</span>';
                } elseif ($row->delivery_qty_status == 1) {

                    return '<span class="badge bg-primary text-white">Partial</span>';
                } elseif ($row->delivery_qty_status == 2) {

                    return '<span class="badge bg-success text-white">Completed</span>';
                }
            })

            // ->editColumn('paid_status', function ($row) {

            //     $payable = $row->total_payable_amount;

            //     if ($row->due <= 0) {

            //         return '<span class="text-success"><b>Paid</b></span>';
            //     } elseif ($row->due > 0 && $row->due < $payable) {

            //         return '<span class="text-primary"><b>Partial</b></span>';
            //     } elseif ($payable == $row->due) {

            //         return '<span class="text-danger"><b>Due</b></span>';
            //     }
            // })

            ->editColumn('receipt_details', function ($row) use ($generalSettings) {

                $html = '';
                if (count($row->references)) {

                    $index = 1;
                    foreach ($row->references as $reference) {

                        $date = $reference?->paymentDescription->payment?->date;
                        $descriptions = $reference?->paymentDescription?->payment?->descriptions;

                        $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                            return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
                        });

                        $cashBankAccount = $filteredCashOrBankAccounts->first();
                        $accountNo = $cashBankAccount->account->account_number ? '-'.substr($cashBankAccount->account->account_number, -4) : '';
                        $bankBranch = $cashBankAccount?->account?->bank_branch ? '('.$cashBankAccount?->account?->bank_branch.')' : '';
                        $bank = $cashBankAccount?->account?->bank ? '-'.$cashBankAccount?->account?->bank->name.$bankBranch : '';
                        $method = $cashBankAccount?->paymentMethod ? '-'.$cashBankAccount?->paymentMethod->name : '';
                        $html .= '<p class="m-0 p-0 fw-bold" style="font-size:9px!important;">'.$index.'. '.$cashBankAccount->account->name.' '.$accountNo.' '.$bank.' - '.$date.$method.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).' '.json_decode($generalSettings->business, true)['currency'];
                        $index++;
                    }
                }

                return $html.($row->payment_note ? '<p class="m-0 p-0" style="font-size:9px!important;"><strong>P.N.:</strong> '.$row->payment_note.'</p>' : '');
            })

            ->rawColumns(['action', 'date', 'customer', 'total_payable_amount', 'sr', 'total_ordered_qty', 'delivery_status', 'total_delivered_qty', 'do_total_left_qty', 'do_approval', 'paid', 'receipt_details'])
            ->make(true);
    }

    public function calculateDoLeftQty($do)
    {
        $totalDeliveredQty = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sales.delivery_order_id', $do->id)
            ->select(DB::raw('SUM(quantity) as total_delivered_qty'))
            ->groupBy('sales.delivery_order_id')->get();

        $do->total_delivered_qty = $totalDeliveredQty->sum('total_delivered_qty');
        $doTotalLeftQty = $do->total_do_qty - $do->total_delivered_qty;
        $do->do_total_left_qty = $doTotalLeftQty;
        $do->save();

        $sale = Sale::with('saleProducts')->where('id', $do->id)->first();

        foreach ($sale->saleProducts as $saleProduct) {

            $soldProducts = DB::table('sale_products')
                ->where('product_id', $saleProduct->product_id)
                ->where('product_variant_id', $saleProduct->product_variant_id)
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sales.delivery_order_id', $sale->id)
                ->select(DB::raw('sum(sale_products.quantity) as qty'))
                ->groupBy('sales.delivery_order_id')->get();

            $saleProduct->do_qty = $saleProduct->ordered_quantity;
            $saleProduct->do_delivered_qty = $soldProducts->sum('qty');
            $calcLeftQty = $saleProduct->do_qty - $saleProduct->do_delivered_qty;
            $saleProduct->do_left_qty = $calcLeftQty;
            $saleProduct->save();
        }

        if ($do->total_delivered_qty <= 0) {

            $do->delivery_qty_status = 0; // Pending
        } elseif ($do->total_delivered_qty > 0 && $sale->total_delivered_qty < $sale->total_ordered_qty) {

            $do->delivery_qty_status = 1; // Partial
        } elseif ($do->total_delivered_qty >= $sale->total_ordered_qty) {

            $do->delivery_qty_status = 2; // Completed
        }

        $do->save();
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_account_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        return $query;
    }
}
