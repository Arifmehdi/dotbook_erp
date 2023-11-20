<?php

namespace App\Utils;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierUtil
{
    public function addSupplier($request, $invoiceVoucherRefIdUtil, $gs)
    {
        $firstLetterOfSupplier = str_split($request->name)[0];
        $supIdPrefix = json_decode($gs->prefix, true)['supplier_id'];

        $addSupplier = Supplier::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $supIdPrefix.str_pad($invoiceVoucherRefIdUtil->getLastId('suppliers'), 4, '0', STR_PAD_LEFT),
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->contact_email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'prefix' => $request->prefix ? $request->prefix : $firstLetterOfSupplier.$invoiceVoucherRefIdUtil->getLastId('suppliers'),
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'opening_balance_type' => $request->opening_balance_type,
            'created_by_id' => auth()->user()->id,
        ]);

        return $addSupplier;
    }

    public function updateSupplier($request, $supplier)
    {
        $supplier->name = $request->name;
        $supplier->business_name = $request->business_name;
        $supplier->email = $request->contact_email;
        $supplier->phone = $request->phone;
        $supplier->alternative_phone = $request->alternative_phone;
        $supplier->landline = $request->landline;
        $supplier->date_of_birth = $request->date_of_birth;
        $supplier->tax_number = $request->tax_number;
        $supplier->pay_term = $request->pay_term;
        $supplier->pay_term_number = $request->pay_term_number;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->zip_code = $request->zip_code;
        $supplier->country = $request->country;
        $supplier->state = $request->state;
        $supplier->shipping_address = $request->shipping_address;
        $supplier->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $supplier->opening_balance_type = $request->opening_balance_type;
        $supplier->save();

        return $supplier;
    }

    public function supplierList()
    {
        $suppliers = '';
        $query = DB::table('suppliers')
            ->leftJoin('accounts', 'suppliers.id', 'accounts.supplier_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        $suppliers = $query
            ->select(
                'suppliers.id',
                'suppliers.contact_id',
                'suppliers.prefix',
                'suppliers.name',
                'suppliers.tax_number',
                'suppliers.business_name',
                'suppliers.phone',
                'suppliers.status',
                DB::raw('SUM(CASE WHEN account_ledgers.voucher_type = 0 THEN account_ledgers.debit END) AS opening_total_debit'),
                DB::raw('SUM(CASE WHEN account_ledgers.voucher_type = 0 THEN account_ledgers.credit END) AS opening_total_credit'),
                DB::raw('SUM(CASE WHEN account_ledgers.voucher_type != 0 THEN account_ledgers.debit END) AS curr_total_debit'),
                DB::raw('SUM(CASE WHEN account_ledgers.voucher_type != 0 THEN account_ledgers.credit END) AS curr_total_credit'),
            )
            ->groupBy('suppliers.id', 'suppliers.contact_id', 'suppliers.name', 'suppliers.prefix', 'suppliers.business_name', 'suppliers.phone', 'suppliers.status')
            ->orderBy('suppliers.name', 'asc');

        return DataTables::of($suppliers)
            ->addColumn('action', function ($row) {

                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="'.route('contacts.supplier.manage', [$row->id]).'"> Manage</a>';

                $html .= '<a class="dropdown-item" href="'.route('contacts.supplier.view.details', [$row->id]).'" id=""> View</a>';

                if (auth()->user()->can('supplier_edit')) {
                    $html .= '<a class="dropdown-item" href="'.route('contacts.supplier.edit', [$row->id]).'" id="edit"> Edit</a>';
                }

                if (auth()->user()->can('supplier_delete')) {
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('contacts.supplier.delete', [$row->id]).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('business_name', function ($row) {
                return $row->business_name ? $row->business_name : '...';
            })
            ->editColumn('opening_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'cr';

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                return '<strong>'.\App\Utils\Converter::format_in_bdt($currOpeningBalance).' '.ucfirst($currOpeningBalanceSide).'</strong>';
            })

            ->editColumn('debit', function ($row) {
                return '<strong>'.\App\Utils\Converter::format_in_bdt($row->curr_total_debit).'</strong>';
            })

            ->editColumn('credit', function ($row) {
                return '<strong>'.\App\Utils\Converter::format_in_bdt($row->curr_total_credit).'</strong>';
            })

            ->editColumn('closing_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $CurrTotalDebit = (float) $row->curr_total_debit;
                $CurrTotalCredit = (float) $row->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'cr';

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
                $closingBalanceSide = 'cr';
                if ($CurrTotalDebit > $CurrTotalCredit) {

                    $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                    $closingBalanceSide = 'dr';
                } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                    $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                    $closingBalanceSide = 'cr';
                }

                return '<strong>'.\App\Utils\Converter::format_in_bdt($closingBalance).' '.ucfirst($closingBalanceSide).'</strong>';
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input change_status" data-url="'.route('contacts.supplier.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; background-color: #2ea074; margin-left: -7px" type="checkbox" checked/>';
                    $html .= '</div>';

                    return $html;
                } else {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input change_status" data-url="'.route('contacts.supplier.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px" type="checkbox"/>';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->rawColumns(['action', 'business_name', 'opening_balance', 'debit', 'credit', 'closing_balance', 'status'])
            ->make(true);
    }
}
