<?php

namespace App\Utils;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesOrderUtil
{
    public function addOrder($request, $srUserId, $codeGenerationService)
    {
        $orderId = $codeGenerationService->generateMonthWise(table: 'sales', column: 'order_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');

        $addOrder = new Sale();
        $addOrder->order_id = $orderId;
        $addOrder->order_by_id = auth()->user()->id;
        $addOrder->sr_user_id = $srUserId;
        $addOrder->sale_account_id = $request->sale_account_id;
        $addOrder->customer_account_id = $request->customer_account_id;
        $addOrder->status = $request->status;
        $addOrder->order_status = 1;
        $addOrder->date = $request->date;
        $addOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addOrder->order_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addOrder->expire_date = $request->expire_date ? date('Y-m-d H:i:s', strtotime($request->expire_date.$request->expire_time)) : null;
        $addOrder->total_item = $request->total_item ? $request->total_item : 0;
        $addOrder->total_ordered_qty = $request->total_qty ? $request->total_qty : 0;
        $addOrder->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $addOrder->order_discount_type = $request->order_discount_type ? $request->order_discount_type : 0;
        $addOrder->order_discount = $request->order_discount ? $request->order_discount : 0;
        $addOrder->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $addOrder->tax_ac_id = $request->order_tax_ac_id;
        $addOrder->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $addOrder->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addOrder->sale_note = $request->sale_note;
        $addOrder->comment = $request->comment;
        $addOrder->receiver_phone = $request->receiver_phone;
        $addOrder->shipping_address = $request->shipping_address;
        $addOrder->price_adjustment_note = $request->price_adjustment_note;
        $addOrder->payment_note = $request->payment_note;
        $addOrder->all_price_type = $request->all_price_type;
        $addOrder->total_payable_amount = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $addOrder->save();

        return $addOrder;
    }

    public function updateSalesOrder($updateOrder, $request, $srUserId)
    {
        $updateOrder->sr_user_id = $srUserId;
        $updateOrder->order_status = 1;
        $updateOrder->customer_account_id = $request->customer_account_id;
        $updateOrder->sale_account_id = $request->sale_account_id;
        $updateOrder->total_item = $request->total_item ? $request->total_item : 0;
        $updateOrder->total_ordered_qty = $request->total_qty ? $request->total_qty : 0;
        $updateOrder->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $updateOrder->order_discount_type = $request->order_discount_type;
        $updateOrder->order_discount = $request->order_discount ? $request->order_discount : 0;
        $updateOrder->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $updateOrder->tax_ac_id = $request->order_tax_ac_id;
        $updateOrder->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateOrder->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateOrder->total_payable_amount = $request->total_invoice_amount;
        $updateOrder->sale_note = $request->sale_note;
        $updateOrder->payment_note = $request->payment_note;
        $updateOrder->comment = $request->comment;
        $updateOrder->shipping_address = $request->shipping_address;
        $updateOrder->receiver_phone = $request->receiver_phone;
        $updateOrder->price_adjustment_note = $request->price_adjustment_note;
        $updateOrder->date = $request->date;
        $time = date(' H:i:s', strtotime($updateOrder->order_date));
        $updateOrder->order_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $time = date(' H:i:s', strtotime($updateOrder->report_date));
        $updateOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $updateOrder->expire_date = $request->expire_date ? date('Y-m-d', strtotime($request->expire_date)) : null;

        if ($request->status == 7) {

            $updateOrder->do_id = $updateOrder->order_id;
            $updateOrder->do_status = 1;
            $updateOrder->do_by_id = auth()->user()->id;
            $updateOrder->do_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
            $updateOrder->total_do_qty = $updateOrder->total_ordered_qty;
        } else {

            if ($updateOrder->delivery_qty_status == 0) {

                $updateOrder->do_id = null;
                $updateOrder->do_status = 0;
                $updateOrder->do_by_id = null;
                $updateOrder->do_date = null;
                $updateOrder->total_do_qty = 0;
            }
        }

        if ($request->status == 3) {

            $updateOrder->order_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        }

        $updateOrder->all_price_type = $request->all_price_type;
        $updateOrder->save();

        return $updateOrder;
    }

    public function salesOrderTable($request, $customerAccountId, $srUserId)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $sales = '';

        $query = Sale::query()->with(
            'references:id,payment_description_id,sale_id,amount',
            'references.paymentDescription:id,payment_id',
            'references.paymentDescription.payment:id,date,payment_type',
            'references.paymentDescription.payment.descriptions:id,payment_id,account_id,payment_method_id',
            'references.paymentDescription.payment.descriptions.paymentMethod:id,name',
            'references.paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.paymentDescription.payment.descriptions.account.bank:id,name',
            'references.paymentDescription.payment.descriptions.account.group:id,sub_sub_group_number',
        );

        if ($customerAccountId) {

            $query->where('sales.customer_account_id', $customerAccountId);
        }

        if ($srUserId) {

            $query->where('sales.sr_user_id', $srUserId);
        }

        if ($request->sale_account_id) {

            $query->where('sales.sale_account_id', $request->sale_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.order_date', $date_range); // Final
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.order_by_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query)->where('sales.order_status', 1);

        $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
            ->select(
                'sales.id',
                'sales.order_id',
                'sales.quotation_id',
                'sales.order_date',
                'sales.expire_date',
                'sales.total_payable_amount',
                'sales.total_ordered_qty',
                'sales.total_delivered_qty',
                'sales.do_total_left_qty',
                // 'sales.due',
                'sales.paid',
                'sales.payment_note',
                // 'sales.due',
                'sales.do_approval',
                'sales.order_status',
                'sales.do_status',
                'sales.status',
                'sales.delivery_qty_status',
                'customers.name as customer_name',
                'sr.prefix as sr_prefix',
                'sr.name as sr_name',
                'sr.last_name as sr_last_name',
            )->orderBy('sales.order_date', 'desc');

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__('menu.action').'</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('sales.order.show', [$row->id]).'"> '.__('menu.view').'</a>';

                if (auth()->user()->can('receipts_add')) {

                    $html .= '<a class="dropdown-item" id="add_sale_receipt" href="'.route('sales.receipts.create', $row->id).'"> '.__('menu.add_receipt').'</a>';
                }

                if (auth()->user()->can('sale_order_do_approval')) {

                    $html .= '<a class="dropdown-item" id="do_approval" href="'.route('sales.order.do.approval', [$row->id]).'">'.__('menu.do_approval').'</a>';
                }

                if (auth()->user()->can('do_add')) {

                    if ($row->do_approval == 1) {

                        $html .= '<a class="dropdown-item" id="change_order_status" href="'.route('sales.order.status.change.modal', [$row->id]).'">'.__('menu.change_order_status').'</a>';
                    }
                }

                if (auth()->user()->can('sale_order_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('sales.order.edit', [$row->id]).'">'.__('menu.edit').'</a>';
                }

                if (auth()->user()->can('sale_order_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.delete', [$row->id]).'"> '.__('menu.delete').'</a>';
                }

                // $html .= '<a class="dropdown-item" id="send_notification" href="' . route('sales.notification.form', [$row->id]) . '"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('order_date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->order_date));
            })

            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('sr', fn ($row) => $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name)

            ->editColumn('total_ordered_qty', fn ($row) => '<span class="total_ordered_qty" data-value="'.$row->total_ordered_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_ordered_qty).'</span>')

            ->editColumn('total_delivered_qty', fn ($row) => '<span class="total_delivered_qty" data-value="'.$row->total_delivered_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_delivered_qty).'</span>')

            ->editColumn('do_total_left_qty', fn ($row) => '<span class="do_total_left_qty" data-value="'.$row->do_total_left_qty.'">'.\App\Utils\Converter::format_in_bdt($row->do_total_left_qty).'</span>')

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_payable_amount).'</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid" data-value="'.$row->paid.'">'.\App\Utils\Converter::format_in_bdt($row->paid).'</span>')

            // ->editColumn('due', fn ($row) =>  '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')

            ->editColumn('delivery_status', function ($row) {

                if ($row->delivery_qty_status == 0) {

                    return '<span class="badge bg-danger text-white">Pending</span>';
                } elseif ($row->delivery_qty_status == 1) {

                    return '<span class="badge bg-primary text-white">Partial</span>';
                } elseif ($row->delivery_qty_status == 2) {

                    return '<span class="badge bg-success text-white">Completed</span>';
                }
            })

            ->editColumn('current_status', function ($row) {

                if ($row->do_status == 1) {

                    return '<span class="badge bg-primary text-white">Delivery Order</span>';
                } else {

                    return '<span class="badge bg-dark text-white">Ordered</span>';
                }
            })
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
                        $html .= '<p class="m-0 p-0 fw-bold" style="font-size:10px!important;line-height:13px;">'.$index.'. '.$cashBankAccount->account->name.' '.$accountNo.' '.$bank.' - '.$date.$method.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).' '.json_decode($generalSettings->business, true)['currency'];
                        $index++;
                    }
                }

                return $html.($row->payment_note ? '<p class="m-0 p-0" style="font-size:9px!important;"><strong>P.N.:</strong> '.$row->payment_note.'</p>' : '');
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
            ->editColumn('do_approval', function ($row) {

                if ($row->do_approval == 1) {

                    return '<strong class="text-success">Approved</strong>';
                } else {

                    return '<strong class="text-danger">Pending</strong>';
                }
            })
            ->rawColumns(['action', 'date', 'current_status', 'delivery_status', 'customer', 'total_ordered_qty', 'total_delivered_qty', 'do_total_left_qty', 'total_payable_amount', 'paid', 'receipt_details', 'do_approval'])
            ->make(true);
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

        if ($request->status) {

            $query->where('sales.status', '=', $request->status);
        }

        // if ($request->from_date) {

        //     $from_date = date('Y-m-d', strtotime($request->from_date));
        //     $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
        //     // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
        //     $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
        //     $query->whereBetween('sales.report_date', $date_range); // Final
        // }
        return $query;
    }
}
