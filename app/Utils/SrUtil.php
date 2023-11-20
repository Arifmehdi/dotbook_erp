<?php

namespace App\Utils;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SrUtil
{
    public function srListTable($request)
    {
        $users = '';
        $query = DB::table('users')->where('is_marketing_user', 1);

        if (auth()->user()->can('view_own_sale')) {

            $query->where('users.id', auth()->user()->id);
        }

        $users = $query->select(
            'users.*',
        )->orderBy('name', 'asc');

        return DataTables::of($users)
            ->addColumn('action', function ($row) {

                if (auth()->user()->can('manage_sr_manage') || auth()->user()->can('manage_sr_edit')) {

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('manage_sr_manage')) {

                        $html .= '<a class="dropdown-item details_button" href="'.route('sales.sr.manage', [$row->id]).'"> Manage</a>';
                    }

                    if (auth()->user()->can('manage_sr_edit')) {

                        $html .= '<a class="dropdown-item" id="edit" href="'.route('users.edit', [$row->id]).'"> Edit </a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                }
            })

            ->editColumn('role_name', function ($row) {

                $user = User::find($row->id);

                return $user?->roles->first()?->name ?? 'N/A';
            })
            ->editColumn('username', function ($row) {

                if ($row->username) {

                    return $row->username;
                } else {

                    return '...';
                }
            })
            ->editColumn('name', function ($row) {

                return $row->prefix.' '.$row->name.' '.$row->last_name;
            })
            ->editColumn('allow_login', function ($row) {

                if ($row->allow_login == 1) {

                    return '<span  class="badge badge-sm bg-success">Allowed</span>';
                } else {

                    return '<span  class="badge badge-sm bg-danger">Not-Allowed</span>';
                }
            })
            ->rawColumns(['action', 'role_name', 'name', 'username', 'allow_login'])
            ->make(true);
    }

    public function srClosingBalance($user_id, $customer_account_id = null, $from_date = null, $to_date = null)
    {
        // return $user_id;
        $converter = new \App\Utils\Converter();
        $amounts = '';

        $query = DB::table('account_ledgers')->where('account_ledgers.user_id', $user_id);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($from_date && $to_date) {

            $gs = DB::table('general_settings')->select('business')->first();
            $accountStartDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $fromDateYmd = Carbon::parse($from_date)->startOfDay();
            $toDateYmd = Carbon::parse($to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($customer_account_id) {

            $query->where('account_ledgers.account_id', $customer_account_id);
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
            );
        } else {

            $query->select(
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),
            );
        }

        $amounts = $query->groupBy('account_ledgers.account_id')->get();

        $openingBalanceDebit = $amounts->sum('opening_total_debit');
        $__openingBalanceDebit = $amounts->sum('opening_total_debit');
        $openingBalanceCredit = $amounts->sum('opening_total_credit');
        $__openingBalanceCredit = $amounts->sum('opening_total_credit');

        $currTotalDebit = $amounts->sum('curr_total_debit');
        $__currTotalDebit = $amounts->sum('curr_total_debit');
        $currTotalCredit = $amounts->sum('curr_total_credit');
        $__currTotalCredit = $amounts->sum('curr_total_credit');

        // $allTotalDebit = $__currTotalDebit + $openingBalanceDebit;
        // $allTotalCredit = $__currTotalCredit + $openingBalanceCredit;

        $currOpeningBalance = 0;
        $currOpeningBalanceSide = 'dr';
        if ($openingBalanceDebit > $openingBalanceCredit) {

            $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            $currOpeningBalanceSide = 'dr';
        } elseif ($openingBalanceCredit > $openingBalanceDebit) {

            $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
            $currOpeningBalanceSide = 'cr';
        }

        $currTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
        $currTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

        $closingBalance = 0;
        $closingBalanceSide = 'dr';
        if ($currTotalDebit > $currTotalCredit) {

            $closingBalance = $currTotalDebit - $currTotalCredit;
            $closingBalanceSide = 'dr';
        } elseif ($currTotalCredit > $currTotalDebit) {

            $closingBalance = $currTotalCredit - $currTotalDebit;
            $closingBalanceSide = 'cr';
        }

        $allTotalDebit = 0;
        $allTotalCredit = 0;
        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $allTotalDebit = $__currTotalDebit + ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
            $allTotalCredit = $__currTotalCredit + ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);
        } else {

            $allTotalDebit = $__currTotalDebit + $__openingBalanceDebit;
            $allTotalCredit = $__currTotalCredit + $__openingBalanceCredit;
        }

        return [
            'opening_balance' => $currOpeningBalance ? $currOpeningBalance : 0,
            'opening_balance_side' => $currOpeningBalanceSide,
            'curr_total_debit' => $__currTotalDebit ? $__currTotalDebit : 0,
            'curr_total_credit' => $__currTotalCredit ? $__currTotalCredit : 0,
            'all_total_debit' => $allTotalDebit ? $allTotalDebit : 0,
            'all_total_credit' => $allTotalCredit ? $allTotalCredit : 0,
            'closing_balance' => $closingBalance,
            'closing_balance_side' => $closingBalanceSide,
            'closing_balance_string' => $converter::format_in_bdt($closingBalance).($closingBalanceSide == 'dr' ? ' Dr.' : ' Cr.'),
        ];
    }
}
