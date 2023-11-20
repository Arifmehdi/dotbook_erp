<?php

namespace App\Utils;

use App\Models\Expanse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ExpenseUtil
{
    public function expenseListTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $expenses = '';

        $query = Expanse::query();

        $this->filteredQuery($request, $query);

        $expenses = $query->leftJoin('purchases', 'expanses.purchase_ref_id', 'purchases.id')
            ->with(
                [
                    'expenseDescriptions',
                    'expenseDescriptions.account:id,name,account_number',
                    'expenseDescriptions.paymentMethod:id,name',
                ]
            )
            ->select(
                'expanses.id',
                'expanses.voucher_no',
                'expanses.debit_total',
                'expanses.credit_total',
                'expanses.date',
                'expanses.report_date',
                'expanses.note',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_invoice_id',
            )->orderBy('expanses.report_date', 'desc');

        return DataTables::of($expenses)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('vouchers.expenses.show', [$row->id]).'">'.__('menu.view').'</a>';

                if (auth()->user()->can('edit_expense')) {

                    $html .= '<a class="dropdown-item" id="edit" href="'.route('vouchers.expenses.edit', [$row->id]).'">'.__('menu.edit').'</a>';
                }

                if (auth()->user()->can('delete_expense')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('vouchers.expenses.delete', [$row->id]).'">'.__('menu.delete').'</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->report_date));
            })

            ->editColumn('note', function ($row) {

                return $row->note ? Str::limit($row->note, 37, '...') : '';
            })

            ->editColumn('reference', function ($row) {

                if ($row->purchase_id) {

                    return 'PI: <a id="details_btn" class="text-black fw-bold" href="'.route('purchases.show', [$row->purchase_id]).'">'.$row->purchase_invoice_id.'</a>';
                }
            })

            ->editColumn('descriptions', function ($row) {

                $html = '';
                foreach ($row->expenseDescriptions as $description) {

                    $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;

                    $transactionDetails = '';
                    if (
                        $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date || $description->remarkable_note
                    ) {

                        $transactionDetails .= $description?->paymentMethod?->name;
                        $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                        $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                        $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                        $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                    }

                    $html .= '<p class="p-0 m-0" style="font-size:11px;"> - '.($description->account ? '<strong>'.$description->account->name.'</strong>'.$__amount : '').'</p>'.($transactionDetails ? '<p class="p-0 m-0 ps-2" style="font-size:11px;"><b>'.Str::limit($transactionDetails, 100, '..').'</b></p>' : '');
                }

                return $html;
            })

            ->editColumn('debit_total', fn ($row) => '<span class="debit_total" data-value="'.$row->debit_total.'">'.\App\Utils\Converter::format_in_bdt($row->debit_total).'</span>')

            ->editColumn('credit_total', fn ($row) => '<span class="credit_total" data-value="'.$row->credit_total.'">'.\App\Utils\Converter::format_in_bdt($row->credit_total).'</span>')

            ->rawColumns(['action', 'date', 'debit_total', 'credit_total', 'descriptions', 'reference'])
            ->make(true);
    }

    public function addExpense($date, $remarks, $mode, $debitTotal, $creditTotal, $isTransactionDetails, $maintainCostCentre, $voucherGenerator, $expenseVoucherPrefix, $purchaseRefId = null)
    {
        $__expenseVoucherPrefix = $expenseVoucherPrefix != null ? $expenseVoucherPrefix : 'EV';
        $voucherNo = $voucherGenerator->generateMonthWise('expanses', 'voucher_no', $__expenseVoucherPrefix, 4, 13, '-', '-');
        $addExpense = new Expanse();
        $addExpense->voucher_no = $voucherNo;
        $addExpense->mode = $mode;
        $addExpense->debit_total = $debitTotal;
        $addExpense->credit_total = $creditTotal;
        $addExpense->date = $date;
        $addExpense->note = $remarks;
        $addExpense->report_date = date('Y-m-d H:i:s', strtotime($date.date(' H:i:s')));
        $addExpense->created_by_id = auth()->user()->id;
        $addExpense->is_transaction_details = $isTransactionDetails;
        $addExpense->maintain_cost_centre = $maintainCostCentre;
        $addExpense->purchase_ref_id = $purchaseRefId;
        $addExpense->save();

        return $addExpense;
    }

    // public function updateExpense($updateExpense, $request)
    // {
    //     $updateExpense->tax_account_id = $request->tax_account_id;
    //     $updateExpense->tax_percent = $request->tax_percent ? $request->tax_percent : 0;
    //     $updateExpense->tax_amount = $request->tax_amount ? $request->tax_amount : 0;
    //     $updateExpense->total_amount = $request->total_amount;
    //     $updateExpense->net_total_amount = $request->net_total_amount;
    //     $updateExpense->date = $request->date;
    //     $time = date(' H:i:s', strtotime($updateExpense->report_date));
    //     $updateExpense->report_date = date('Y-m-d H:i:s', strtotime($request->date . $time));
    //     $updateExpense->note = $request->expense_note;
    //     $updateExpense->save();

    //     return $updateExpense;
    // }

    public function updateExpense($id, $date, $remarks, $debitTotal, $creditTotal, $isTransactionDetails, $maintainCostCentre)
    {
        $updateExpense = Expanse::with(['expenseDescriptions'])->where('id', $id)->first();
        $updateExpense->debit_total = $debitTotal;
        $updateExpense->credit_total = $creditTotal;
        $updateExpense->date = $date;
        $updateExpense->note = $remarks;
        $time = date(' H:i:s', strtotime($updateExpense->report_date));
        $updateExpense->report_date = date('Y-m-d H:i:s', strtotime($date.$time));
        $updateExpense->is_transaction_details = $isTransactionDetails;
        $updateExpense->maintain_cost_centre = $maintainCostCentre;
        $updateExpense->save();

        return $updateExpense;
    }

    public function expenseDelete($deleteExpense)
    {
        if (! is_null($deleteExpense)) {

            $deleteExpense->delete();
        }
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('expanses.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('expanses.report_date', $date_range); // Final
        }

        return $query;
    }
}
