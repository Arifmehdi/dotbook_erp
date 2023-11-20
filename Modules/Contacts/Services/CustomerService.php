<?php

namespace Modules\Contacts\Services;

use App\Models\Account;
use App\Models\AccountLedger;
use App\Models\Customer;
use App\Models\CustomerContactPersonDetails;
use App\Models\CustomerDetails;
use App\Models\CustomerOpeningBalance;
use App\Models\Sale;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Contacts\Interfaces\CustomerServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class CustomerService implements CustomerServiceInterface
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
            )
            ->orderBy('customers.name', 'asc');

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

                    $html .= '<a class="dropdown-item" id="money_receipt_list" href="'.route('money.receipt.voucher.list', [$row->id]).'"> Payment Receipt Voucher</a>';
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

                return $row->credit_limit ? Converter::format_in_bdt($row->credit_limit) : 'No Limit';
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

                return Converter::format_in_bdt($currOpeningBalance).' '.ucfirst($currOpeningBalanceSide);
            })

            ->editColumn('debit', function ($row) {

                return Converter::format_in_bdt($row->curr_total_debit);
            })

            ->editColumn('credit', function ($row) {

                return Converter::format_in_bdt($row->curr_total_credit);
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

                return Converter::format_in_bdt($closingBalance).' '.ucfirst($closingBalanceSide);
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

    public function addCustomer($request, $customerService, $gs)
    {
        $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
        $cusIdPrefix = json_decode($gs->prefix, true)['customer_id'];
        $creditLimit = $request->credit_limit ? $request->credit_limit : 0;

        $addCustomer = Customer::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix.str_pad($customerService->getLastId('customers'), 4, '0', STR_PAD_LEFT),
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

    public function addCustomerDetails($request, $customer, $fileUploaderService)
    {
        $addCustomerDetails = new CustomerDetails();
        $addCustomerDetails->customer_id = $customer->id;
        $addCustomerDetails->contact_type = $request->contact_type == 'company' ? 2 : 1;
        $addCustomerDetails->total_employees = $request->total_employees;
        $addCustomerDetails->permanent_address = $request->permanent_address;
        $addCustomerDetails->print_name = $request->print_name;
        $addCustomerDetails->print_ledger_name = $request->print_ledger_name;
        $addCustomerDetails->print_ledger_code = $request->print_ledger_code;
        $addCustomerDetails->billing_account = $request->billing_account;
        $addCustomerDetails->description = $request->description;
        $addCustomerDetails->customer_status = $request->customer_status;
        $addCustomerDetails->contact_mailing_name = $request->contact_mailing_name;
        $addCustomerDetails->contact_post_office = $request->contact_post_office;
        $addCustomerDetails->contact_police_station = $request->contact_police_station;
        $addCustomerDetails->contact_currency = $request->contact_currency;
        $addCustomerDetails->contact_fax = $request->contact_fax;
        $addCustomerDetails->primary_mobile = $request->primary_mobile;
        $addCustomerDetails->contact_send_sms = $request->contact_send_sms;
        $addCustomerDetails->contact_email = $request->contact_email;
        $addCustomerDetails->mailing_name = $request->mailing_name;
        $addCustomerDetails->mailing_address = $request->mailing_address;
        $addCustomerDetails->mailing_email = $request->mailing_email;
        $addCustomerDetails->shipping_name = $request->shipping_name;
        $addCustomerDetails->shipping_number = $request->shipping_number;
        $addCustomerDetails->shipping_email = $request->shipping_email;
        $addCustomerDetails->shipping_send_sms = $request->shipping_send_sms;
        $addCustomerDetails->alternative_address = $request->alternative_address;
        $addCustomerDetails->alternative_name = $request->alternative_name;
        $addCustomerDetails->alternative_post_office = $request->alternative_post_office;
        $addCustomerDetails->alternative_zip_code = $request->alternative_zip_code;
        $addCustomerDetails->alternative_police_station = $request->alternative_police_station;
        $addCustomerDetails->alternative_state = $request->alternative_state;
        $addCustomerDetails->alternative_city = $request->alternative_city;
        $addCustomerDetails->alternative_fax = $request->alternative_fax;
        $addCustomerDetails->alternative_send_sms = $request->alternative_send_sms;
        $addCustomerDetails->alternative_email = $request->alternative_email;
        $addCustomerDetails->tin_number = $request->tin_number;
        $addCustomerDetails->tax_number = $request->tax_number;
        $addCustomerDetails->tax_name = $request->tax_name;
        $addCustomerDetails->tax_category = $request->tax_category;
        $addCustomerDetails->tax_address = $request->tax_address;
        $addCustomerDetails->bank_name = $request->bank_name;
        $addCustomerDetails->bank_A_C_number = $request->bank_A_C_number;
        $addCustomerDetails->bank_currency = $request->bank_currency;
        $addCustomerDetails->bank_branch = $request->bank_branch;
        $addCustomerDetails->contact_telephone = $request->contact_telephone;
        $addCustomerDetails->partner_name = $request->partner_name;
        $addCustomerDetails->percentage = $request->percentage;
        $addCustomerDetails->sales_team = $request->sales_team;
        $addCustomerDetails->save();

        if (isset($addCustomerDetails)) {

            $customer_file = '';
            $customer_document = '';
            $alternative_file = '';

            if ($request->hasFile('customer_file')) {

                $customer_file = $fileUploaderService->upload($request->file('customer_file'), 'uploads/customer/');
            }

            if ($request->hasFile('alternative_file')) {

                $alternative_file = $fileUploaderService->upload($request->file('alternative_file'), 'uploads/customer/alternative/');
            }

            if ($request->hasFile('customer_document')) {

                $customer_document = $fileUploaderService->uploadMultiple($request->file('customer_document'), 'uploads/customer/documents');
            }

            $addCustomerDetails->customer_file = $customer_file;
            $addCustomerDetails->customer_document = $customer_document;
            $addCustomerDetails->alternative_file = $alternative_file;
            $addCustomerDetails->save();
        }
    }

    public function getLastId($table)
    {
        $id = 1;
        $lastEntry = DB::table($table)->orderBy('id', 'desc')->first(['id']);

        if ($lastEntry) {

            $id = ++$lastEntry->id;
        }

        return $id;
    }

    public function addCustomerContactPersons($customer, $request)
    {
        $check_part = $request->contact_person_name;

        if (isset($check_part)) {

            foreach ($check_part as $key => $item) {

                $addContactPerson = new CustomerContactPersonDetails();
                $addContactPerson->contact_person_name = $request->contact_person_name[$key];
                $addContactPerson->contact_person_phon = $request->contact_person_phon[$key];
                $addContactPerson->contact_person_dasignation = $request->contact_person_dasignation[$key];
                $addContactPerson->contact_person_landline = $request->contact_person_landline[$key];
                $addContactPerson->contact_person_alternative_phone = $request->contact_person_alternative_phone[$key];
                $addContactPerson->contact_person_fax = $request->contact_person_fax[$key];
                $addContactPerson->contact_person_email = $request->contact_person_email[$key];
                $addContactPerson->contact_person_address = $request->contact_person_address[$key];
                $addContactPerson->contact_person_post_office = $request->contact_person_post_office[$key];
                $addContactPerson->contact_person_zip_code = $request->contact_person_zip_code[$key];
                $addContactPerson->contact_person_police_station = $request->contact_person_police_station[$key];
                $addContactPerson->contact_person_state = $request->contact_person_state[$key];
                $addContactPerson->contact_person_city = $request->contact_person_city[$key];
                $addContactPerson->customer_id = $customer->id;
                $addContactPerson->save();
            }
        }
    }

    public function addAccount($request, $customerId = null, $supplierId = null)
    {
        $addAccount = new Account();
        $addAccount->name = $request->name;
        $addAccount->phone = $request->phone;
        $addAccount->address = $request->address;
        $addAccount->account_number = $request->account_number ? $request->account_number : null;
        $addAccount->bank_id = $request->bank_id ? $request->bank_id : null;
        $addAccount->bank_code = $request->bank_code ? $request->bank_code : null;
        $addAccount->swift_code = $request->swift_code ? $request->swift_code : null;
        $addAccount->bank_branch = $request->bank_branch ? $request->bank_branch : null;
        $addAccount->bank_address = $request->bank_address ? $request->bank_address : null;
        $addAccount->tax_percent = $request->tax_percent ? $request->tax_percent : 0;
        $addAccount->customer_id = $customerId;
        $addAccount->supplier_id = $supplierId;
        $addAccount->account_group_id = $request->account_group_id;
        $addAccount->account_type = 0;
        $addAccount->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $addAccount->balance = $request->opening_balance ? $request->opening_balance : 0;
        $addAccount->opening_balance_type = $request->opening_balance_type;
        $addAccount->remark = $request->remark;
        $addAccount->created_by_id = auth()->user()->id;
        $addAccount->created_at = Carbon::now();
        $addAccount->save();

        return $addAccount;
    }

    public function addCustomerOpeningBalance($customer_id, $account_id, $opening_balance, $opening_balance_type, $user_id, $never_show_again = null)
    {
        $addCustomerOpeningBalance = CustomerOpeningBalance::insert([
            'customer_id' => $customer_id,
            'account_id' => $account_id,
            'user_id' => $user_id,
            'amount' => $opening_balance ? $opening_balance : 0.00,
            'balance_type' => $opening_balance_type,
            'is_show_again' => isset($never_show_again) ? 0 : 1,
        ]);
    }

    public function addAccountLedger(
        $voucher_type_id,
        $date,
        $account_id,
        $trans_id,
        $amount,
        $amount_type,
        $user_id = null
    ) {
        $voucherType = $this->voucherType($voucher_type_id);
        $add = new AccountLedger();
        $add->user_id = $user_id;
        $add->date = date('Y-m-d H:i:s', strtotime($date.date(' H:i:s')));
        $add->account_id = $account_id;
        $add->voucher_type = $voucher_type_id;
        $add->{$voucherType['id']} = $trans_id;
        $add->{$amount_type} = $amount;
        $add->amount_type = $amount_type;
        $add->save();
    }

    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => ['name' => 'Opening Balance', 'id' => 'account_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'account_id', 'link' => ''],
            1 => ['name' => 'Sales', 'id' => 'sale_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'sale_id', 'link' => 'sales.show'],
            2 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher', 'details_id' => 'sale_return_id', 'link' => 'sales.returns.show'],
            3 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show'],
            4 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'purchase_return_voucher', 'details_id' => 'purchase_return_id', 'link' => 'purchases.returns.show'],
            5 => ['name' => 'Expenses', 'id' => 'expense_description_id', 'voucher_no' => 'expense_voucher', 'details_id' => 'expense_id', 'link' => ''],
            7 => ['name' => 'Stock Adjustment', 'id' => 'adjustment_id', 'voucher_no' => 'stock_adjustment_voucher', 'details_id' => 'adjustment_id', 'link' => ''],
            8 => ['name' => 'Receipt', 'id' => 'payment_description_id', 'voucher_no' => 'payment_voucher', 'details_id' => 'payment_id', 'link' => 'vouchers.receipts.show'],
            9 => ['name' => 'Payment', 'id' => 'payment_description_id', 'voucher_no' => 'payment_voucher', 'details_id' => 'payment_id', 'link' => 'vouchers.payments.show'],
            12 => ['name' => 'Contra', 'id' => 'contra_description_id', 'voucher_no' => 'contra_voucher', 'details_id' => 'contra_id', 'link' => 'vouchers.contras.show'],
            13 => ['name' => 'Journal', 'id' => 'journal_entry_id', 'voucher_no' => 'journal_voucher', 'details_id' => 'journal_id', 'link' => 'vouchers.journals.show'],
            15 => ['name' => 'Income Receipt', 'id' => 'income_receipt_id', 'voucher_no' => 'income_receipt_voucher', 'details_id' => 'income_receipt_id', 'link' => ''],
            16 => ['name' => 'Sales', 'id' => 'sale_product_id', 'voucher_no' => 'product_sale_voucher', 'details_id' => 'product_sale_id', 'link' => ''],
            17 => ['name' => 'Purchase', 'id' => 'purchase_product_id', 'voucher_no' => 'product_purchase_voucher', 'details_id' => 'product_purchase_id', 'link' => 'purchases.show'],
            18 => ['name' => 'Sales Return', 'id' => 'sale_return_product_id', 'voucher_no' => 'product_sale_return_voucher', 'details_id' => 'product_sale_return_id', 'link' => 'sales.returns.show'],
            19 => ['name' => 'Purchase Return', 'id' => 'purchase_return_product_id', 'voucher_no' => 'product_purchase_return_voucher', 'details_id' => 'product_purchase_return_id', 'link' => 'purchases.returns.show'],
            20 => ['name' => 'Daily Stock', 'id' => 'daily_stock_product_id', 'voucher_no' => 'daily_stock_voucher', 'details_id' => 'product_daily_stock_id', 'link' => ''],
        ];

        return $data[$voucher_type_id];
    }

    public function ledgerEntries($request, $id, $by = 'accountId')
    {
        $ledgers = '';
        $settings = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($settings->business, true)['start_date']));
        $ledgers = $this->ledgerEntriesQuery($request, $id, $by);
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }
        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $userId = $request->user_id ? $request->user_id : null;
            $accountOpeningBalance = '';
            if ($by == 'accountId') {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.account_id', $id);
            } else {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.user_id', $id);
            }
            if ($request->user_id) {

                $accountOpeningBalanceQ->where('account_ledgers.user_id', $request->user_id);
            }
            $accountOpeningBalance = $accountOpeningBalanceQ->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
            )->groupBy('account_ledgers.account_id')->get();

            $openingBalanceDebit = $accountOpeningBalance->sum('opening_total_debit');
            $openingBalanceCredit = $accountOpeningBalance->sum('opening_total_credit');

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';
            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                $currOpeningBalanceSide = 'dr';
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $arr = [
                'id' => 0,
                'user_id' => $request->user_id ? $request->user_id : null,
                'user' => $request->user_id ? (object) ['id' => $request->user_id, 'prefix' => null, 'name' => $request->user_name, 'last_name' => null] : null,
                'voucher_type' => 0,
                'sales_voucher' => null,
                'date' => null,
                'account_id' => $id,
                'amount_type' => $currOpeningBalanceSide == 'dr' ? 'debit' : 'credit',
                'debit' => $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0.00,
                'credit' => $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0.00,
                'running_balance' => 0,
                'balance_type' => ' Dr',
            ];

            $stdArr = (object) $arr;
            $ledgers->prepend($stdArr);
        }
        // return $ledgers;
        $runningDebit = 0;
        $runningCredit = 0;
        foreach ($ledgers as $ledger) {
            $runningDebit += $ledger->debit;
            $runningCredit += $ledger->credit;
            if ($runningDebit > $runningCredit) {
                $ledger->running_balance = $runningDebit - $runningCredit;
                $ledger->balance_type = ' Dr.';
            } elseif ($runningCredit > $runningDebit) {
                $ledger->running_balance = $runningCredit - $runningDebit;
                $ledger->balance_type = ' Cr.';
            }
        }

        return DataTables::of($ledgers)
            ->editColumn('date', function ($row) use ($settings) {
                $dateFormat = json_decode($settings->business, true)['date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return $row->date ? date($__date_format, strtotime($row->date)) : '';
            })

            ->editColumn('particulars', function ($row) use ($request, $by) {
                $voucherType = $row->voucher_type;
                $ledgerParticularsUtil = new \App\Utils\LedgerParticularsUtil();

                return $ledgerParticularsUtil->particulars($request, $row->voucher_type, $row, $by);
            })

            ->editColumn('voucher_type', function ($row) {
                $type = $this->voucherType($row->voucher_type);

                return $row->voucher_type != 0 ? '<strong>'.$type['name'].'</strong>' : '';
            })

            ->editColumn('voucher_no', function ($row) {
                $type = $this->voucherType($row->voucher_type);

                return '<a href="'.(! empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#').'" id="details_btn" class="fw-bold">'.$row->{$type['voucher_no']}.'</a>';
            })
            ->editColumn('debit', fn ($row) => '<span class="debit fw-bold" data-value="'.$row->debit.'">'.($row->debit > 0 ? Converter::format_in_bdt($row->debit) : '').'</span>')
            ->editColumn('credit', fn ($row) => '<span class="credit fw-bold" data-value="'.$row->credit.'">'.($row->credit > 0 ? Converter::format_in_bdt($row->credit) : '').'</span>')
            ->editColumn('running_balance', function ($row) {
                return '<span class="running_balance fw-bold">'.Converter::format_in_bdt(abs($row->running_balance)).$row->balance_type.'</span>';
            })
            ->rawColumns(['date', 'particulars', 'voucher_type', 'voucher_no', 'debit', 'credit', 'running_balance'])
            ->make(true);
    }

    public function ledgerEntriesQuery($request, $id, $by)
    {
        $query = AccountLedger::query()
            ->whereRaw('concat(account_ledgers.debit,account_ledgers.credit) > 0');

        if ($by == 'accountId') {

            $query->where('account_ledgers.account_id', $id);
        } elseif ($by == 'userId') {

            $query->where('account_ledgers.user_id', $id);
        }

        if ($request->customer_account_id) {

            $query->where('account_ledgers.account_id', $request->customer_account_id);
        }

        if ($request->user_id) {

            $query->where('account_ledgers.user_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range);
        }

        $query->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
            ->leftJoin('sale_products', 'account_ledgers.sale_product_id', 'sale_products.id')
            ->leftJoin('sales as productSale', 'sale_products.sale_id', 'productSale.id')
            ->leftJoin('sale_return_products', 'account_ledgers.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns as productSaleReturn', 'sale_return_products.sale_return_id', 'productSaleReturn.id')
            ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_products', 'account_ledgers.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases as productPurchase', 'purchase_products.purchase_id', 'productPurchase.id')
            ->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_return_products', 'account_ledgers.purchase_return_product_id', 'purchase_return_products.id')
            ->leftJoin('purchase_returns as productPurchaseReturn', 'purchase_return_products.purchase_return_id', 'productPurchaseReturn.id')
            ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
            ->leftJoin('contra_descriptions', 'account_ledgers.contra_description_id', 'contra_descriptions.id')
            ->leftJoin('contras', 'contra_descriptions.contra_id', 'contras.id')
            ->leftJoin('journal_entries', 'account_ledgers.journal_entry_id', 'journal_entries.id')
            ->leftJoin('journals', 'journal_entries.journal_id', 'journals.id')
            ->leftJoin('payment_descriptions', 'account_ledgers.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->leftJoin('expense_descriptions', 'account_ledgers.expense_description_id', 'expense_descriptions.id')
            ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
            ->with(
                [
                    'account:id,name,account_number,account_group_id',
                    'account.group:id,name,sub_group_number,sub_sub_group_number',
                    'user:id,prefix,name,last_name',
                    'journalEntry',
                    'journalEntry.journal:id,remarks',
                    'journalEntry.journal.entries',
                    'journalEntry.journal.entries.assignedUser:id,prefix,name,last_name',
                    'journalEntry.journal.entries.account:id,name,phone,account_number,account_group_id',
                    'journalEntry.journal.entries.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'journalEntry.journal.entries.paymentMethod:id,name',

                    'contraDescription',
                    'contraDescription.contra:id,remarks',
                    'contraDescription.contra.descriptions',
                    'contraDescription.contra.descriptions.account:id,name,account_number',
                    'contraDescription.contra.descriptions.paymentMethod:id,name',

                    'paymentDescription',
                    'paymentDescription.user:id,prefix,name,last_name',
                    'paymentDescription.payment:id,remarks',
                    'paymentDescription.payment.descriptions',
                    'paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id',
                    'paymentDescription.payment.descriptions.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'paymentDescription.payment.descriptions.user:id,prefix,name,last_name',
                    'paymentDescription.payment.descriptions.paymentMethod:id,name',
                    'paymentDescription.payment.descriptions.references:id,payment_description_id,sale_id,purchase_id,stock_adjustment_id,amount',
                    'paymentDescription.payment.descriptions.references.sale:id,invoice_id,order_id,order_status',
                    'paymentDescription.payment.descriptions.references.purchase:id,invoice_id,purchase_status',

                    'sale:id,customer_account_id,total_payable_amount,sale_note,payment_note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'sale.salesAccount:id,name',
                    'sale.customer:id,name,phone,address',
                    'sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,unit,unit_price_inc_tax,subtotal',
                    'sale.saleProducts.product:id,name',
                    'sale.saleProducts.variant:id,variant_name',

                    'salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount,return_note',
                    'salesReturn.salesAccount:id,name',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturn.returnProducts:id,sale_return_id,product_id,unit_price_inc_tax,return_qty,return_subtotal',
                    'salesReturn.returnProducts.product:id,name',
                    'salesReturn.returnProducts.variant:id,variant_name',

                    'purchase:id,supplier_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount,total_purchase_amount,purchase_note,payment_note,purchase_account_id',
                    'purchase.purchaseAccount:id,name',
                    'purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,unit,quantity,net_unit_cost,line_total',
                    'purchase.purchaseProducts.product:id,name',

                    'purchaseProduct:id,purchase_id,tax_ac_id',
                    'purchaseProduct.purchase:id,supplier_account_id,total_purchase_amount,purchase_note,payment_note,purchase_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount',
                    'purchaseProduct.purchase.purchaseAccount:id,name',
                    'purchaseProduct.purchase.supplier:id,name',
                    'purchaseProduct.purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,quantity,unit,tax_ac_id,unit_tax_percent,unit_tax_amount,net_unit_cost,line_total',

                    'purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturn.returnProducts:id,purchase_return_id,product_id,unit,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturn.returnProducts.variant:id,variant_name',

                    'stockAdjustment:id,expense_account_id,total_qty,net_total_amount,recovered_amount,type,reason',
                    'stockAdjustment:account:id,name',
                    'stockAdjustment:adjustmentProducts:id,adjustmentProducts,product_id,product_variant_id,quantity,unit,unit_cost_inc_tax,subtotal',
                    'stockAdjustment:adjustmentProducts.product:id,name',
                    'stockAdjustment:adjustmentProducts.variant:id,variant_name',

                    'saleProduct:id,sale_id,tax_ac_id',
                    'saleProduct.sale:id,customer_account_id,total_payable_amount,sale_note,payment_note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'saleProduct.sale.salesAccount:id,name',
                    'saleProduct.sale.customer:id,name',
                    'saleProduct.sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,unit,tax_ac_id,unit_tax_percent,unit_tax_amount,unit_price_inc_tax,subtotal',

                    'purchaseReturnProduct:id,purchase_return_id,tax_ac_id',
                    'purchaseReturnProduct:purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturnProduct.purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturnProduct.purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturnProduct.purchaseReturn.returnProducts:id,purchase_return_id,product_id,product_variant_id,unit,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.variant:id,variant_name',

                    'salesReturnProduct:id,sale_return_id,tax_ac_id',
                    'salesReturnProduct:salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'salesReturnProduct.salesReturn.salesAccount:id,name',
                    'salesReturnProduct.salesReturn.customer:id,name,phone,address',
                    'salesReturnProduct.salesReturn.returnProducts:id,sale_return_id,product_id,product_variant_id,unit,unit_price_inc_tax,unit_tax_percent,unit_tax_amount,return_qty,return_subtotal',
                    'salesReturnProduct.salesReturn.returnProducts.product:id,name',
                    'salesReturnProduct.salesReturn.returnProducts.variant:id,variant_name',

                    'expenseDescription',
                    'expenseDescription.expense:id,note,purchase_ref_id',
                    'expenseDescription.expense.expenseDescriptions:id,expense_id,account_id,amount_type,amount',
                    'expenseDescription.expense.expenseDescriptions.account:id,name,account_group_id',
                    'expenseDescription.expense.expenseDescriptions.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'expenseDescription.expense.purchase:id,invoice_id',
                ]
            )
            ->select(
                'account_ledgers.user_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.sale_id',
                'account_ledgers.sale_product_id',
                'account_ledgers.sale_return_id',
                'account_ledgers.sale_return_product_id',
                'account_ledgers.purchase_id',
                'account_ledgers.purchase_product_id',
                'account_ledgers.purchase_return_id',
                'account_ledgers.purchase_return_product_id',
                'account_ledgers.adjustment_id',
                'account_ledgers.payment_description_id',
                'account_ledgers.journal_entry_id',
                'account_ledgers.expense_description_id',
                'account_ledgers.debit',
                'account_ledgers.credit',
                'account_ledgers.running_balance',
                'account_ledgers.amount_type',
                'account_ledgers.contra_description_id',
                'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_voucher',
                'productSaleReturn.id as product_sale_return_id',
                'productSaleReturn.voucher_no as product_sale_return_voucher',
                'productSale.id as product_sale_id',
                'productSale.invoice_id as product_sale_voucher',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_voucher',
                'productPurchase.id as product_purchase_id',
                'productPurchase.invoice_id as product_purchase_voucher',
                'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                'productPurchaseReturn.id as product_purchase_return_id',
                'productPurchaseReturn.voucher_no as product_purchase_return_voucher',
                'stock_adjustments.id as adjustment_id',
                'stock_adjustments.voucher_no as stock_adjustment_voucher',
                'contras.id as contra_id',
                'contras.voucher_no as contra_voucher',
                'journals.id as journal_id',
                'journals.voucher_no as journal_voucher',
                'payments.id as payment_id',
                'payments.voucher_no as payment_voucher',
                'expanses.id as expense_id',
                'expanses.voucher_no as expense_voucher',
            );

        return $query->orderBy('account_ledgers.date', 'asc')->get();
    }

    public function addSaleTable($request, $customerAccountId, $srUserId)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        // $converter = $this->converter;
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

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.sr_user_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query)->where('sales.status', 1)->where('sales.created_by', 1);

        $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('sales as do', 'sales.delivery_order_id', 'do.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
            ->leftJoin('weight_scales', 'sales.id', 'weight_scales.sale_id')
            ->select(
                'sales.id',
                'sales.invoice_id',
                'sales.do_to_inv_challan_no',
                'sales.date',
                'sales.report_date',
                'sales.total_sold_qty',
                'sales.total_payable_amount',
                'sales.delivery_order_id',
                'sales.paid',
                'sales.payment_note',
                'do.do_id',
                'sr.prefix as sr_prefix',
                'sr.name as sr_name',
                'sr.last_name as sr_last_name',
                'weight_scales.first_weight',
                'weight_scales.second_weight',
                'customers.name as customer_name',
            )->orderBy('sales.report_date', 'desc');

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a id="details_btn" class="dropdown-item" href="'.route('sales.show', [$row->id]).'"> View</a>';

                if (auth()->user()->can('receipts_add')) {

                    $html .= '<a class="dropdown-item" id="add_sale_receipt" href="'.route('sales.receipts.create', $row->id).'"> Add Receipt</a>';
                }

                if (auth()->user()->can('edit_sale')) {

                    $html .= '<a class="dropdown-item" href="'.route('sales.edit', [$row->id]).'"> Edit</a>';
                }

                if (auth()->user()->can('delete_sale')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.delete', [$row->id]).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {
                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->report_date));
            })
            ->editColumn('net_weight', function ($row) {
                if ($row->first_weight) {
                    $netWeight = $row->second_weight - $row->first_weight;

                    return '<span class="net_weight" data-value="'.$netWeight.'">'.Converter::format_in_bdt($netWeight).'</span>';
                }
            })
            ->editColumn('invoice_id', function ($row) {
                return '<a href="'.route('sales.show', [$row->id]).'" id="details_btn" class="fw-bold">'.$row->invoice_id.'</a>';
            })
            ->editColumn('do_id', function ($row) {
                if ($row->delivery_order_id) {
                    return '<a href="'.route('sales.delivery.order.show', [$row->delivery_order_id]).'" id="details_btn" class="fw-bold">'.$row->do_id.'</a>';
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
                        $html .= '<p class="m-0 p-0 fw-bold" style="font-size:10px!important;line-height:13px;">'.$index.'. '.$cashBankAccount->account->name.' '.$accountNo.' '.$bank.' - '.$date.$method.' = '.Converter::format_in_bdt($reference->amount).' '.json_decode($generalSettings->business, true)['currency'];
                        $index++;
                    }
                }

                return $html.($row->payment_note ? '<p class="m-0 p-0" style="font-size:9px!important;"><strong>P.N.:</strong> '.$row->payment_note.'</p>' : '');
            })
            ->editColumn('sr', fn ($row) => $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name)
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')
            ->editColumn('total_sold_qty', fn ($row) => '<span class="total_sold_qty" data-value="'.$row->total_sold_qty.'">'.Converter::format_in_bdt($row->total_sold_qty).'</span>')
            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.Converter::format_in_bdt($row->total_payable_amount).'</span>')
            ->editColumn('paid', fn ($row) => '<span class="paid" data-value="'.$row->paid.'">'.Converter::format_in_bdt($row->paid).'</span>')
            ->rawColumns(['action', 'date', 'invoice_id', 'do_id', 'from', 'customer', 'total_sold_qty', 'net_weight', 'total_payable_amount', 'paid', 'receipt_details'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->payment_status) {

            if ($request->payment_status == 1) {

                $query->where('sales.due', '=', 0);
            } else {

                $query->where('sales.due', '>', 0);
            }
        }

        return $query;
    }

    public function accountVoucherList($request, $id, $by)
    {
        $ledgers = '';

        $settings = DB::table('general_settings')->select('business')->first();

        $query = AccountLedger::query();

        if ($by == 'accountId') {

            $query->where('account_ledgers.account_id', $id);
        } else {

            $query->where('account_ledgers.user_id', $id);
        }

        if ($request->voucher_type) {

            $query->where('account_ledgers.voucher_type', $request->voucher_type);
        }

        if ($request->user_id) {

            $query->where('account_ledgers.user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('account_ledgers.account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range);
        }

        $query->whereIn('account_ledgers.voucher_type', [8, 9, 13])
            ->leftJoin('journal_entries', 'account_ledgers.journal_entry_id', 'journal_entries.id')
            ->leftJoin('journals', 'journal_entries.journal_id', 'journals.id')
            ->leftJoin('payment_descriptions', 'account_ledgers.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->with(
                [
                    'account',
                    'user:id,prefix,name,last_name',
                    'journalEntry',
                    'journalEntry.journal',
                    'journalEntry.journal.entries',
                    'journalEntry.journal.entries.assignedUser:id,prefix,name,last_name',
                    'journalEntry.journal.entries.account:id,name,phone,account_number,account_group_id',
                    'journalEntry.journal.entries.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'journalEntry.journal.entries.paymentMethod:id,name',
                    'paymentDescription.user:id,prefix,name,last_name',
                    'paymentDescription.payment',
                    'paymentDescription.payment.descriptions',
                    'paymentDescription.payment.descriptions.account',
                    'paymentDescription.payment.descriptions.user:id,prefix,name,last_name',
                    'paymentDescription.payment.descriptions.paymentMethod:id,name',
                    'paymentDescription.payment.descriptions.references',
                    'paymentDescription.payment.descriptions.references.sale:id,invoice_id,total_payable_amount',
                    'paymentDescription.payment.descriptions.references.purchase:id,invoice_id,total_purchase_amount',
                ]
            )
            ->select(
                'account_ledgers.user_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.payment_description_id',
                'account_ledgers.journal_entry_id',
                'account_ledgers.debit',
                'account_ledgers.credit',
                'account_ledgers.amount_type',
                'journals.id as journal_id',
                'journals.voucher_no as journal_voucher',
                'payments.id as payment_id',
                'payments.voucher_no as payment_voucher',
            );

        $ledgers = $query->orderBy('account_ledgers.date', 'desc')->get();

        return DataTables::of($ledgers)
            ->editColumn('date', function ($row) use ($settings) {

                $dateFormat = json_decode($settings->business, true)['date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('descriptions', function ($row) use ($request, $by) {

                $voucherType = $row->voucher_type;
                $ledgerParticularsUtil = new \App\Utils\LedgerParticularsUtil();

                return $ledgerParticularsUtil->particulars($request, $row->voucher_type, $row, $by);
            })

            ->editColumn('voucher_type', function ($row) {

                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                $type = $accountLedgerUtil->voucherType($row->voucher_type);

                return '<strong>'.$type['name'].'</strong>';
            })

            ->editColumn('voucher_no', function ($row) {

                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                $type = $accountLedgerUtil->voucherType($row->voucher_type);

                return '<a href="#" class="fw-bold">'.$row->{$type['voucher_no']}.'</a>';
            })
            ->editColumn('debit', fn ($row) => '<span class="voucher_debit fw-bold" data-value="'.$row->debit.'">'.($row->debit > 0 ? Converter::format_in_bdt($row->debit) : '').'</span>')
            ->editColumn('credit', fn ($row) => '<span class="voucher_credit fw-bold" data-value="'.$row->credit.'">'.($row->credit > 0 ? Converter::format_in_bdt($row->credit) : '').'</span>')
            ->rawColumns(['date', 'descriptions', 'voucher_type', 'voucher_no', 'debit', 'credit'])
            ->make(true);
    }

    public function ledgerEntriesPrint($request, $id, $by = 'accountId')
    {
        $ledgers = '';
        $settings = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($settings->business, true)['start_date']));

        $ledgers = $this->ledgerEntriesQuery($request, $id, $by);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $userId = $request->user_id ? $request->user_id : null;
            $accountOpeningBalance = '';

            if ($by == 'accountId') {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.account_id', $id);
            } else {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.user_id', $id);
            }

            if ($request->user_id) {

                $accountOpeningBalanceQ->where('account_ledgers.user_id', $request->user_id);
            }

            $accountOpeningBalance = $accountOpeningBalanceQ->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
            )->groupBy('account_ledgers.account_id')->get();

            $openingBalanceDebit = $accountOpeningBalance->sum('opening_total_debit');
            $openingBalanceCredit = $accountOpeningBalance->sum('opening_total_credit');

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';
            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                $currOpeningBalanceSide = 'dr';
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $arr = [
                'id' => 0,
                'user_id' => $request->user_id ? $request->user_id : null,
                'user' => $request->user_id ? (object) ['id' => $request->user_id, 'prefix' => null, 'name' => $request->user_name, 'last_name' => null] : null,
                'voucher_type' => 0,
                'sales_voucher' => null,
                'date' => null,
                'account_id' => $id,
                'amount_type' => $currOpeningBalanceSide == 'dr' ? 'debit' : 'credit',
                'debit' => $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0.00,
                'credit' => $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0.00,
                'running_balance' => 0,
                'balance_type' => ' Dr',
            ];

            $stdArr = (object) $arr;

            $ledgers->prepend($stdArr);
        }

        $runningDebit = 0;
        $runningCredit = 0;
        foreach ($ledgers as $ledger) {

            $runningDebit += $ledger->debit;
            $runningCredit += $ledger->credit;

            if ($runningDebit > $runningCredit) {

                $ledger->running_balance = $runningDebit - $runningCredit;
                $ledger->balance_type = ' Dr.';
            } elseif ($runningCredit > $runningDebit) {

                $ledger->running_balance = $runningCredit - $runningDebit;
                $ledger->balance_type = ' Cr.';
            }
        }

        return $ledgers;
    }

    public function accountClosingBalance($account_id, $user_id = null, $from_date = null, $to_date = null)
    {
        $amounts = '';
        $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $account_id);

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

        if ($user_id) {

            $query->where('account_ledgers.user_id', $user_id);
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
            'closing_balance_string' => Converter::format_in_bdt($closingBalance).($closingBalanceSide == 'dr' ? ' Dr.' : ' Cr.'),
        ];
    }
}
