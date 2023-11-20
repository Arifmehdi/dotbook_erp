<?php

namespace App\Utils;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PaymentUtil
{
    public function addPayment($date, $paymentType, $remarks, $voucherGenerator, $voucherPrefix, $debitTotal, $creditTotal, $isTransactionDetails = 1, $saleRefId = null, $purchaseRefId = null, $stockAdjustmentRefId = null, $mode = 1)
    {
        $voucherNo = $voucherGenerator->generateMonthAndTypeWise(table: 'payments', column: 'voucher_no', typeColName: 'payment_type', typeValue: $paymentType, prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-');
        $addPayment = new Payment();
        $addPayment->sale_ref_id = $saleRefId;
        $addPayment->purchase_ref_id = $purchaseRefId;
        $addPayment->stock_adjustment_ref_id = $stockAdjustmentRefId;
        $addPayment->payment_type = $paymentType;
        $addPayment->mode = $mode;
        $addPayment->voucher_no = $voucherNo;
        $addPayment->debit_total = $debitTotal;
        $addPayment->credit_total = $creditTotal;
        $addPayment->date = $date;
        $addPayment->remarks = $remarks;
        $addPayment->date_ts = date('Y-m-d H:i:s', strtotime($date.date(' H:i:s')));
        $addPayment->created_by_id = auth()?->user()?->id ?? 1;
        $addPayment->is_transaction_details = $isTransactionDetails;
        $addPayment->save();

        return $addPayment;
    }

    public function updatePayment($id, $date, $remarks, $debitTotal, $creditTotal, $isTransactionDetails)
    {
        $updatePayment = Payment::with(['descriptions'])->where('id', $id)->first();
        $updatePayment->debit_total = $debitTotal;
        $updatePayment->credit_total = $creditTotal;
        $updatePayment->date = $date;
        $updatePayment->remarks = $remarks;
        $previousTime = date(' H:i:s', strtotime($updatePayment->date_ts));
        $updatePayment->date_ts = date('Y-m-d H:i:s', strtotime($date.$previousTime));
        $updatePayment->is_transaction_details = $isTransactionDetails;
        $updatePayment->save();

        return $updatePayment;
    }

    public function deletePayment($id)
    {
        $deletePayment = Payment::where('id', $id)->first();

        if (! is_null($deletePayment)) {

            $deletePayment->delete();
        }
    }

    public function list($request, $paymentType, $converter)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $payments = '';

        $query = Payment::query()->where('payments.payment_type', $paymentType);

        $this->filteredQuery($request, $query);

        $payments = $query->leftJoin('sales', 'payments.sale_ref_id', 'sales.id')
            ->leftJoin('purchases', 'payments.purchase_ref_id', 'purchases.id')
            ->leftJoin('stock_adjustments', 'payments.stock_adjustment_ref_id', 'stock_adjustments.id')
            ->with(
                [
                    'descriptions',
                    'descriptions.paymentMethod:id,name',
                    'descriptions.account:id,name,phone,account_number,account_group_id',
                    'descriptions.account.group:id,name',
                    'descriptions.user:id,prefix,name,last_name,phone',
                    'descriptions.paymentMethod:id,name',
                ]
            )
            ->select(
                'payments.id',
                'payments.payment_type',
                'payments.voucher_no',
                'payments.debit_total',
                'payments.credit_total',
                'payments.date',
                'payments.date_ts',
                'payments.remarks',
                'payments.created_by_id',
                // 'users.prefix as user_prefix',
                // 'users.name as user_name',
                // 'users.last_name as user_last_name',
                'sales.id as sales_id',
                'sales.invoice_id as sales_invoice_id',
                'sales.order_id as sales_order_id',
                'sales.status as sales_status',
                'sales.order_status as sales_order_status',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_invoice_id',
                'purchases.purchase_status',
                'stock_adjustments.id as stock_adjustments_id',
                'stock_adjustments.voucher_no as stock_adjustments_voucher_no',
            )->orderBy('payments.date_ts', 'desc');

        return DataTables::of($payments)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if ($row->payment_type == 1) {

                    $html .= '<a class="dropdown-item" id="details_btn" href="'.route('vouchers.receipts.show', [$row->id]).'">'.__('menu.view').'</a>';

                    if (auth()->user()->can('receipts_edit')) {

                        $html .= '<a class="dropdown-item" href="'.route('vouchers.receipts.edit', [$row->id]).'">'.__('menu.edit').'</a>';
                    }

                    if (auth()->user()->can('receipts_delete')) {

                        $html .= '<a class="dropdown-item" id="delete" href="'.route('vouchers.receipts.delete', [$row->id]).'"> '.__('menu.delete').'</a>';
                    }
                } else {

                    $html .= '<a class="dropdown-item" id="details_btn" href="'.route('vouchers.payments.show', [$row->id]).'"> '.__('menu.view').'</a>';

                    if (auth()->user()->can('payments_edit')) {

                        $html .= '<a class="dropdown-item" href="'.route('vouchers.payments.edit', [$row->id]).'"> '.__('menu.edit').'</a>';
                    }

                    if (auth()->user()->can('payments_delete')) {

                        $html .= '<a class="dropdown-item" id="delete" href="'.route('vouchers.payments.delete', [$row->id]).'"> '.__('menu.delete').'</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->date_ts));
            })

            ->editColumn('remarks', function ($row) {

                return $row->remarks ? Str::limit($row->remarks, 37, '...') : '';
            })

            ->editColumn('reference', function ($row) {

                if ($row->sales_id) {

                    if ($row->sales_order_status == 1) {

                        return 'Sales-Order: '.'<a href="'.route('sales.order.show', $row->sales_id).'" id="details_btn">'.$row->sales_order_id.'</a>';
                    }

                    if ($row->sales_status == 1) {

                        return 'Sales: '.'<a href="'.route('sales.show', $row->sales_id).'" id="details_btn">'.$row->sales_invoice_id.'</a>';
                    }
                }

                if ($row->purchase_id) {

                    if ($row->purchase_status == 1) {

                        return 'PI: '.'<a href="'.route('purchases.show', $row->purchase_id).'" id="details_btn">'.$row->purchase_invoice_id.'</a>';
                    } else {

                        return 'PO: '.'<a href="'.route('purchases.show.order', $row->purchase_id).'" id="details_btn">'.$row->purchase_invoice_id.'</a>';
                    }
                }

                if ($row->stock_adjustments_id) {

                    return 'S. Adjustment: '.'<a href="'.route('stock.adjustments.show', $row->stock_adjustments_id).'" id="details_btn">'.$row->stock_adjustments_voucher_no.'</a>';
                }
            })

            // ->editColumn('createdBy',  function ($row) {

            //     return $row->user_name ? $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name : '';
            // })

            ->editColumn('descriptions', function ($row) use ($converter) {

                $html = '';
                foreach ($row->descriptions->sortByDesc('amount_type') as $description) {

                    $amount = $converter->format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;
                    $assignedUser = $description->user ? (' - <strong>SR</strong> '.$description->user->prefix.' '.$description->user->name.' '.$description->user->last_name) : '';

                    $transactionDetails = '';
                    if (
                        $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                    ) {
                        $transactionDetails = '<p class="p-0 m-0 ps-2">';
                        $transactionDetails .= $description?->paymentMethod?->name;
                        $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                        $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                        $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                        $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                        $transactionDetails .= '</p>';
                    }

                    $html .= '<p class="p-0 m-0" style="font-size:12px;"> - '.($description->account ? '<strong>'.$description->account->name.'</strong>'.$assignedUser.$__amount : '').'</p>'.$transactionDetails;
                }

                return $html;
            })

            ->editColumn('debit_total', fn ($row) => '<span class="debit_total" data-value="'.$row->debit_total.'">'.$converter->format_in_bdt($row->debit_total).'</span>')

            ->editColumn('credit_total', fn ($row) => '<span class="credit_total" data-value="'.$row->credit_total.'">'.$converter->format_in_bdt($row->credit_total).'</span>')

            ->rawColumns(['action', 'date', 'debit_total', 'credit_total', 'reference', 'descriptions'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('payments.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('payments.date_ts', $date_range); // Final
        }

        return $query;
    }
}
