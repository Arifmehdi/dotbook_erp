<?php

namespace App\Utils;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerUtil
{
    public function customerListTable($request)
    {
        $userId = $request->user_id;

        if (auth()->user()->is_marketing_user == 1) {

            $sumQueryUserId = auth()->user()->id;
        } else {

            $sumQueryUserId = $userId ? $userId : null;
        }

        $customers = '';
        $query = DB::table('customers')
            ->leftJoin('customer_groups', 'customers.customer_group_id', 'customer_groups.id')
            ->leftJoin('accounts', 'customers.id', 'accounts.customer_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        // if ($sumQueryUserId) {

        //     $query->where('account_ledgers.user_id', $sumQueryUserId);
        // }

        $customers = $query
            ->select(
                'customers.id',
                'customers.contact_id',
                'customers.customer_group_id',
                'customers.name',
                'customers.tax_number',
                'customers.business_name',
                'customers.phone',
                'customers.status',
                'customers.customer_type',
                'customers.credit_limit',
                'customer_groups.group_name',
                DB::raw(
                    "
                        SUM(
                            CASE
                                WHEN account_ledgers.voucher_type = 0
                                    AND (IFNULL('$sumQueryUserId', 0) = 0 OR account_ledgers.user_id = '$sumQueryUserId')
                                THEN account_ledgers.debit
                                ELSE 0
                            END
                        ) AS opening_total_debit,
                        SUM(
                            CASE
                                WHEN account_ledgers.voucher_type = 0
                                    AND (IFNULL('$sumQueryUserId', 0) = 0 OR account_ledgers.user_id = '$sumQueryUserId')
                                THEN account_ledgers.credit
                                ELSE 0
                            END
                        ) AS opening_total_credit,
                        SUM(
                            CASE
                                WHEN account_ledgers.voucher_type != 0
                                    AND (IFNULL('$sumQueryUserId', 0) = 0 OR account_ledgers.user_id = '$sumQueryUserId')
                                THEN account_ledgers.debit
                                ELSE 0
                            END
                        ) AS curr_total_debit,
                        SUM(
                            CASE
                                WHEN account_ledgers.voucher_type != 0
                                    AND (IFNULL('$sumQueryUserId', 0) = 0 OR account_ledgers.user_id = '$sumQueryUserId')
                                THEN account_ledgers.credit
                                ELSE 0
                            END
                        ) AS curr_total_credit
                    "
                ),
            )
            ->groupBy(
                'customers.id',
                'customers.contact_id',
                'customers.customer_group_id',
                'customers.name',
                'customers.business_name',
                'customers.phone',
                'customers.status',
                'customers.customer_type',
                'customers.credit_limit',
            )->orderBy('customers.name', 'asc');

        return DataTables::of($customers)

            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if (auth()->user()->can('customer_manage')) {

                    $html .= '<a class="dropdown-item" href="'.route('customers.manage', [$row->id]).'"> Manage</a>';
                    $html .= '<a class="dropdown-item" id="view" href="'.route('contacts.customers.view', [$row->id]).'"> View</a>';
                }

                if (auth()->user()->can('customer_payment_receive_voucher')) {

                    $html .= '<a class="dropdown-item" id="money_receipt_list" href="'.route('money.receipt.voucher.index', [$row->id]).'"> Money Receipt Vouchers</a>';
                }

                if (auth()->user()->can('customer_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('contacts.customers.edit', [$row->id]).'" id="edit"> Edit</a>';
                }

                if (auth()->user()->can('customer_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('contacts.customers.delete', [$row->id]).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('business_name', function ($row) {

                return $row->business_name ? $row->business_name : '...';
            })

            ->editColumn('customer_type', function ($row) {

                return $row->customer_type == 1 ? 'Non-Credit' : 'Credit';
            })

            ->editColumn('credit_limit', function ($row) {

                return $row->credit_limit ? \App\Utils\Converter::format_in_bdt($row->credit_limit) : 'No Limit';
            })

            ->editColumn('group_name', function ($row) {

                return $row->group_name ? $row->group_name : '...';
            })

            ->editColumn('opening_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'dr';

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                return \App\Utils\Converter::format_in_bdt($currOpeningBalance).' '.ucfirst($currOpeningBalanceSide);
            })

            ->editColumn('debit', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->curr_total_debit);
            })

            ->editColumn('credit', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->curr_total_credit);
            })

            ->editColumn('closing_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $CurrTotalDebit = (float) $row->curr_total_debit;
                $CurrTotalCredit = (float) $row->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'dr';

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                $closingBalance = 0;
                $closingBalanceSide = 'dr';
                if ($CurrTotalDebit > $CurrTotalCredit) {

                    $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                    $closingBalanceSide = 'dr';
                } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                    $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                    $closingBalanceSide = 'cr';
                }

                return \App\Utils\Converter::format_in_bdt($closingBalance).' '.ucfirst($closingBalanceSide);
            })

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input change_status" data-url="'.route('contacts.customers.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    $html .= '</div>';

                    return $html;
                } else {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input change_status" data-url="'.route('contacts.customers.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->rawColumns(['action', 'customer_type', 'credit_limit', 'business_name', 'group_name', 'opening_balance', 'debit', 'credit', 'closing_balance', 'status'])
            ->make(true);
    }

    public function addCustomer($request, $invoiceVoucherRefIdUtil, $gs)
    {
        $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
        $cusIdPrefix = json_decode($gs->prefix, true)['customer_id'];
        $creditLimit = $request->credit_limit ? $request->credit_limit : 0;

        $addCustomer = Customer::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix.str_pad($invoiceVoucherRefIdUtil->getLastId('customers'), 4, '0', STR_PAD_LEFT),
            'name' => $request->name,
            'phone' => $request->phone,
            'business_name' => $request->business_name,
            'email' => $request->contact_email,
            'nid_no' => $request->nid_no,
            'trade_license_no' => $request->trade_license_no,
            'known_person' => $request->known_person,
            'known_person_phone' => $request->known_person_phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'customer_group_id' => $request->customer_group_id,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0.00,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
            'customer_type' => $request->customer_type,
            'credit_limit' => $request->credit_limit,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'created_by_id' => auth()->user()->id,
        ]);

        return $addCustomer;
    }

    public function updateCustomer($request, $customer)
    {
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->business_name = $request->business_name;
        $customer->email = $request->contact_email;
        $customer->nid_no = $request->nid_no;
        $customer->trade_license_no = $request->trade_license_no;
        $customer->known_person = $request->known_person;
        $customer->known_person_phone = $request->known_person_phone;
        $customer->alternative_phone = $request->alternative_phone;
        $customer->landline = $request->landline;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->tax_number = $request->tax_number;
        $customer->customer_group_id = $request->customer_group_id;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->zip_code = $request->zip_code;
        $customer->country = $request->country;
        $customer->state = $request->state;
        $customer->shipping_address = $request->shipping_address;
        $customer->opening_balance = $request->opening_balance ? $request->opening_balance : 0.00;
        $customer->total_sale_due = $request->opening_balance ? $request->opening_balance : 0.00;
        $customer->customer_type = $request->customer_type;
        $customer->credit_limit = $request->credit_limit;
        $customer->pay_term = $request->pay_term;
        $customer->pay_term_number = $request->pay_term_number;
        $customer->save();

        return $customer;
    }
}
