<?php

namespace App\Utils;

use App\Models\Contra;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ContraUtil
{
    public function addContra($date, $remarks, $voucherGenerator, $voucherPrefix, $debitTotal, $creditTotal, $isTransactionDetails, $saleRefId = null, $purchaseRefId = null, $mode = 1)
    {
        $voucherNo = $voucherGenerator->generateMonthWise(table: 'contras', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-');
        $addContra = new Contra();
        $addContra->mode = $mode;
        $addContra->voucher_no = $voucherNo;
        $addContra->debit_total = $debitTotal;
        $addContra->credit_total = $creditTotal;
        $addContra->date = $date;
        $addContra->remarks = $remarks;
        $addContra->report_date = date('Y-m-d H:i:s', strtotime($date.date(' H:i:s')));
        $addContra->user_id = auth()->user()->id;
        $addContra->is_transaction_details = $isTransactionDetails;
        $addContra->save();

        return $addContra;
    }

    public function updateContra($id, $date, $remarks, $debitTotal, $creditTotal, $isTransactionDetails)
    {
        $updateContra = Contra::with(['descriptions'])->where('id', $id)->first();
        $updateContra->debit_total = $debitTotal;
        $updateContra->credit_total = $creditTotal;
        $updateContra->date = $date;
        $updateContra->remarks = $remarks;
        $previousTime = date(' H:i:s', strtotime($updateContra->report_date));
        $updateContra->report_date = date('Y-m-d H:i:s', strtotime($date.$previousTime));
        $updateContra->is_transaction_details = $isTransactionDetails;
        $updateContra->save();

        return $updateContra;
    }

    public function deleteContra($id)
    {
        $deleteContra = Contra::where('id', $id)->first();

        if (! is_null($deleteContra)) {

            $deleteContra->delete();
        }
    }

    public function list($request, $converter)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $contras = '';

        $query = Contra::query();

        $this->filteredQuery($request, $query);

        $contras = $query->leftJoin('users', 'contras.user_id', 'users.id')
            ->with(
                [
                    'descriptions',
                    'descriptions.paymentMethod:id,name',
                    'descriptions.account:id,name,phone,account_number,account_group_id',
                    'descriptions.account.group:id,name',
                    'descriptions.paymentMethod:id,name',
                ]
            )->select(
                'contras.id',
                'contras.voucher_no',
                'contras.debit_total',
                'contras.credit_total',
                'contras.date',
                'contras.report_date',
                'contras.remarks',
                'contras.user_id',
                'users.prefix as user_prefix',
                'users.name as user_name',
                'users.last_name as user_last_name',
            )->orderBy('contras.report_date', 'desc');

        return DataTables::of($contras)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('vouchers.contras.show', [$row->id]).'">'.__('menu.view').'</a>';

                if (auth()->user()->can('contras_edit')) {

                    $html .= '<a class="dropdown-item" id="edit" href="'.route('vouchers.contras.edit', [$row->id]).'">'.__('menu.edit').'</a>';
                }

                if (auth()->user()->can('contras_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('vouchers.contras.delete', [$row->id]).'">'.__('menu.delete').'</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->report_date));
            })

            ->editColumn('remarks', function ($row) {

                return $row->remarks ? Str::limit($row->remarks, 37, '...') : '';
            })

            ->editColumn('createdBy', function ($row) {

                return $row->user_name ? $row->user_prefix.' '.$row->user_name.' '.$row->user_last_name : '';
            })

            ->editColumn('descriptions', function ($row) use ($converter) {

                $html = '';
                foreach ($row->descriptions as $description) {

                    $amount = $converter->format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;

                    $transactionDetails = '';
                    if (
                        $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                    ) {
                        $transactionDetails = '<p class="p-0 m-0" style="font-size:11px;">';
                        $transactionDetails .= $description?->paymentMethod?->name;
                        $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                        $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                        $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                        $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                        $transactionDetails .= '</p>';
                    }

                    $html .= '<p class="p-0 m-0" style="font-size:11px;"> - '.($description->account ? '<strong>'.$description->account->name.'</strong>'.$__amount : '').'</p>'.$transactionDetails;
                }

                return $html;
            })

            ->editColumn('debit_total', fn ($row) => '<span class="debit_total" data-value="'.$row->debit_total.'">'.$converter->format_in_bdt($row->debit_total).'</span>')

            ->editColumn('credit_total', fn ($row) => '<span class="credit_total" data-value="'.$row->credit_total.'">'.$converter->format_in_bdt($row->credit_total).'</span>')

            ->rawColumns(['action', 'date', 'debit_total', 'credit_total', 'createdBy', 'descriptions'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('contras.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('contras.report_date', $date_range); // Final
        }

        return $query;
    }
}
