<?php

namespace Modules\Contacts\Interfaces;

interface CustomerServiceInterface
{
    public function customerListTable($request);

    public function addCustomer($request, $invoiceVoucherRefIdUtil, $gs);

    public function addCustomerDetails($request, $customer, $fileUploaderService);

    public function getLastId($table);

    public function addCustomerContactPersons($addCustomer, $request);

    public function addAccount($request, $id);

    public function addCustomerOpeningBalance($customer_id, $account_id, $opening_balance, $opening_balance_type, $user_id, $never_show_again = null);

    public function addAccountLedger($voucher_type_id, $date, $account_id, $trans_id, $amount, $amount_type, $user_id = null);
}
