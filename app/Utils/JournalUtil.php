<?php

namespace App\Utils;

use App\Models\Journal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class JournalUtil
{
    protected $accountUtil;

    protected $supplierUtil;

    protected $customerUtil;

    protected $converter;

    public function __construct(
        AccountUtil $accountUtil,
        SupplierUtil $supplierUtil,
        CustomerUtil $customerUtil,
        Converter $converter,
    ) {

        $this->accountUtil = $accountUtil;
        $this->supplierUtil = $supplierUtil;
        $this->customerUtil = $customerUtil;
        $this->converter = $converter;
    }

    public function addJournal($date, $remarks, $debitTotal, $creditTotal, $isTransactionDetails, $maintainCostCentre, $voucherGenerator)
    {
        $voucherNo = $voucherGenerator->generateMonthWise(table: 'journals', column: 'voucher_no', prefix: 'J', splitter: '-', suffixSeparator: '-');
        $addJournal = new Journal();
        $addJournal->voucher_no = $voucherNo;
        $addJournal->debit_total = $debitTotal;
        $addJournal->credit_total = $creditTotal;
        $addJournal->date = $date;
        $addJournal->remarks = $remarks;
        $addJournal->date_ts = date('Y-m-d H:i:s', strtotime($date.date(' H:i:s')));
        $addJournal->created_by_id = auth()->user()->id;
        $addJournal->is_transaction_details = $isTransactionDetails;
        $addJournal->maintain_cost_centre = $maintainCostCentre;
        $addJournal->save();

        return $addJournal;
    }

    public function updateJournal($id, $date, $remarks, $debitTotal, $creditTotal, $isTransactionDetails, $maintainCostCentre)
    {
        $updateJournal = Journal::with(['entries'])->where('id', $id)->first();

        $updateJournal->debit_total = $debitTotal;
        $updateJournal->credit_total = $creditTotal;
        $updateJournal->date = $date;
        $updateJournal->remarks = $remarks;
        $previousTime = date(' H:i:s', strtotime($updateJournal->date_ts));
        $updateJournal->date_ts = date('Y-m-d H:i:s', strtotime($date.$previousTime));
        $updateJournal->is_transaction_details = $isTransactionDetails;
        $updateJournal->maintain_cost_centre = $maintainCostCentre;
        $updateJournal->save();

        return $updateJournal;
    }

    public function deleteJournal($id)
    {
        $deleteJournal = Journal::where('id', $id)->first();

        if ($deleteJournal) {

            $deleteJournal->delete();
        }
    }

    public function list($request, $converter)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $journals = '';

        $query = Journal::query();

        $this->filteredQuery($request, $query);

        $journals = $query->leftJoin('users', 'journals.created_by_id', 'users.id')
            ->with(
                [
                    'entries',
                    'entries.assignedUser:id,prefix,name,last_name,phone',
                    'entries.account:id,name,account_number',
                ]
            )
            ->select(
                'journals.id',
                'journals.voucher_no',
                'journals.debit_total',
                'journals.credit_total',
                'journals.date',
                'journals.date_ts',
                'journals.remarks',
                'journals.created_by_id',
                // 'users.prefix as user_prefix',
                // 'users.name as user_name',
                // 'users.last_name as user_last_name',
            )->orderBy('journals.date_ts', 'desc');

        return DataTables::of($journals)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('vouchers.journals.show', [$row->id]).'">'.__('menu.view').'</a>';

                if (auth()->user()->can('journals_edit')) {

                    $html .= '<a class="dropdown-item" id="edit" href="'.route('vouchers.journals.edit', [$row->id]).'">'.__('menu.edit').'</a>';
                }

                if (auth()->user()->can('journals_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('vouchers.journals.delete', [$row->id]).'">'.__('menu.delete').'</a>';
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

                return $row->remarks ? '<span title="'.$row->remarks.'">'.Str::limit($row->remarks, 37, '...').'</span>' : '';
            })

            // ->editColumn('createdBy',  function ($row) {

            //     return $row->user_name ? $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name : '';
            // })

            ->editColumn('entries', function ($row) use ($converter) {

                $html = '';
                foreach ($row->entries as $entry) {

                    $amount = $converter->format_in_bdt($entry->amount);
                    $amount_type = $entry->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;
                    $assignedUser = $entry->assignedUser ? (' - SR '.$entry->assignedUser->prefix.' '.$entry->assignedUser->name.' '.$entry->assignedUser->last_name) : '';

                    $transactionDetails = '';
                    if (
                        $entry->payment_method_id || $entry->transaction_no || $entry->cheque_no || $entry->cheque_serial_no || $entry->cheque_issue_date || $entry->remarkable_note
                    ) {

                        $transactionDetails .= $entry?->paymentMethod?->name;
                        $transactionDetails .= ' - TransNo: '.$entry->transaction_no;
                        $transactionDetails .= ' - ChequeNo: '.$entry->cheque_no;
                        $transactionDetails .= ' - SerialNo: '.$entry->cheque_serial_no;
                        $transactionDetails .= ' - IssueDate: '.$entry->cheque_issue_date;
                        $transactionDetails .= ' - R.Note : '.$entry->remarkable_note;
                    }

                    $html .= '<p class="p-0 m-0" style="font-size:11px;"> - '.($entry->account ? '<strong>'.$entry->account->name.'</strong>'.$assignedUser.$__amount : '').'</p>'.($transactionDetails ? '<p class="p-0 m-0 ps-2">'.Str::limit($transactionDetails, 100, '..').'</p>' : '');
                }

                return $html;
            })
            ->editColumn('debit_total', fn ($row) => '<span class="debit_total" data-value="'.$row->debit_total.'">'.$this->converter->format_in_bdt($row->debit_total).'</span>')
            ->editColumn('credit_total', fn ($row) => '<span class="credit_total" data-value="'.$row->credit_total.'">'.$this->converter->format_in_bdt($row->credit_total).'</span>')
            ->rawColumns(['action', 'date', 'remarks', 'debit_total', 'credit_total', 'entries'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('journals.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('journals.date_ts', $date_range); // Final
        }

        return $query;
    }
}
