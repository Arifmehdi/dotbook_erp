<?php

namespace App\Utils;

use App\Models\UserActivityLog;
use Illuminate\Support\Facades\DB;

class UserActivityLogUtil
{
    public function subjectTypes()
    {
        return [
            26 => 'Product',
            1 => 'Customers',
            2 => 'Suppliers',
            3 => 'Users',
            18 => 'User Login',
            19 => 'User Logout',
            27 => 'Receive Payment',
            28 => 'Payment',
            4 => 'Purchase',
            5 => 'Purchase Order',
            31 => 'Stock Issue',
            6 => 'Purchase Return',
            29 => 'Quotation',
            30 => 'Delivery Order',
            7 => 'Sales',
            8 => 'Sales Order',
            9 => 'Sale Return',
            20 => 'POS Sale',
            10 => 'Transfer B.Location To Warehouse',
            11 => 'Transfer Warehouse To B.Location',
            12 => 'Transfer B.Location To B.Location',
            13 => 'Stock Adjustments',
            // 14 => 'Stock Adjustment From Warehouse',
            15 => 'Expense',
            16 => 'Bank',
            17 => 'Accounts',
            20 => 'Categories',
            21 => 'Sub-Categories',
            22 => 'Brands',
            23 => 'Units',
            24 => 'Variants',
            25 => 'Warranties',
            32 => 'Incomes',
            33 => 'Income Receipt',
            36 => 'Receive Stock',
        ];
    }

    public function actions()
    {
        return [
            1 => 'Added',
            2 => 'Updated',
            3 => 'Deleted',
            4 => 'User Login',
            5 => 'User Logout',
        ];
    }

    public function descriptionModel()
    {
        return [
            1 => [ // Customers
                'fields' => ['name', 'phone', 'contact_id', 'total_sale_due'],
                'texts' => ['Name : ', 'Phone : ', 'Customer ID : ', 'Balance Due : '],
            ],
            2 => [ // Suppliers
                'fields' => ['name', 'phone', 'contact_id', 'total_sale_due'],
                'texts' => ['Name : ', 'Phone : ', 'Supplier ID : ', 'Balance Due : '],
            ],
            3 => [ // Users
                'fields' => ['prefix', 'name', 'last_name', 'username'],
                'texts' => ['prefix : ', 'Name : ', 'Last Lame : ', 'Username'],
            ],
            4 => [ // Purchase
                'fields' => ['date', 'invoice_id', 'total_purchase_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'P.Invoice ID : ', 'Total Purchase Amount : ', 'Paid : ', 'Due : '],
            ],
            5 => [ // Purchase Order
                'fields' => ['date', 'invoice_id', 'total_purchase_amount', 'paid', 'due'],
                'texts' => ['Order Date : ', 'Purchase Order ID : ', 'Total Ordered Amt : ', 'Paid : ', 'Due : '],
            ],
            6 => [ // Purchase Return
                'fields' => ['date', 'voucher_no', 'total_item', 'total_qty', 'total_return_amount'],
                'texts' => ['Date : ', 'Voucher No : ', 'Total Item : ', 'Total Qtyt: ', 'Total Returned Amt : '],
            ],
            7 => [ // Sales
                'fields' => ['date', 'invoice_id', 'total_payable_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'Invoice ID : ', 'Total Payable Amount : ', 'Paid : ', 'Due : '],
            ],
            8 => [ // Sales Order
                'fields' => ['date', 'order_id', 'total_payable_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'Order ID : ', 'Total Payable Amt : ', 'Paid : ', 'Pending Amount : '],
            ],
            9 => [ // Sales Return
                'fields' => ['date', 'voucher_no', 'total_return_amount'],
                'texts' => ['Date : ', 'Return Voucher No : ', 'Total Returned Amount. : '],
            ],
            10 => [ // Transfer B.Location To Warehouse
                'fields' => ['date', 'invoice_id', 'total_send_qty', 'total_received_qty'],
                'texts' => ['Date : ', 'Reference ID : ', 'Total Send Quantity : ', 'Total Received Quantity : '],
            ],
            11 => [ // Transfer Warehouse To B.Location
                'fields' => ['date', 'invoice_id', 'total_send_qty', 'total_received_qty'],
                'texts' => ['Date : ', 'Reference ID : ', 'Total Send Quantity : ', 'Total Received Quantity : '],
            ],
            12 => [ // Transfer B.Location To Warehouse
                'fields' => ['date', 'ref_id', 'total_send_qty', 'total_received_qty'],
                'texts' => ['Date : ', 'Reference ID : ', 'Total Send Quantity : ', 'Total Received Quantity : '],
            ],
            13 => [ // Stock Adjustments
                'fields' => ['date', 'voucher_no', 'net_total_amount', 'recovered_amount'],
                'texts' => ['Date : ', 'Voucher No : ', 'Net Total Amt. : ', 'Recovered Amount : '],
            ],
            // 14 => [ // Stock Adjustment From Warehouse
            //     'fields' => ['date', 'invoice_id', 'net_total_amount', 'recovered_amount',],
            //     'texts' => ['Date : ', 'Reference ID : ', 'Total Adjusted Amt. : ', 'Total Recovered Amount : ',]
            // ],
            15 => [ // Expenses
                'fields' => ['date', 'voucher_no', 'debit_total', 'credit_total'],
                'texts' => ['Date : ', 'Voucher No : ', 'Total Debit : ', 'total Credit'],
            ],
            16 => [ // Bank
                'fields' => ['name'],
                'texts' => ['Bank Name : '],
            ],
            17 => [ // Accounts
                'fields' => ['name', 'account_number', 'opening_balance', 'balance'],
                'texts' => ['Account Name : ', 'Account Number : ', 'Opening Balance : ', 'Balance : '],
            ],
            18 => [ // User login
                'fields' => ['username'],
                'texts' => ['Username : '],
            ],
            19 => [ // User Logout
                'fields' => ['username'],
                'texts' => ['Username : '],
            ],
            20 => [ // Categories
                'fields' => ['id', 'name'],
                'texts' => ['Category ID : ', 'Category Name : '],
            ],
            21 => [ // Sub-Categories
                'fields' => ['id', 'name'],
                'texts' => ['Sub-Category ID : ', 'Sub-Category Name : '],
            ],
            22 => [ // Brands
                'fields' => ['id', 'name'],
                'texts' => ['Brand ID: ', 'Brand Name : '],
            ],
            23 => [ // UNITS
                'fields' => ['name', 'code_name'],
                'texts' => ['Unit Name : ', 'Short Name : '],
            ],
            24 => [ // Variants
                'fields' => ['id', 'bulk_variant_name'],
                'texts' => ['ID : ', 'Variant Name : '],
            ],
            25 => [ // Warranties
                'fields' => ['name', 'duration', 'duration_type'],
                'texts' => ['Warranty Name : ', 'Duration : ', 'Duration Type : '],
            ],
            26 => [ // Product
                'fields' => ['name', 'product_code', 'product_cost_with_tax', 'product_price'],
                'texts' => ['Name : ', 'P.Code(SKU) : ', 'Cost.inc Tax : ', 'Price.Exc Tax : '],
            ],
            27 => [ // Receive Payment
                'fields' => ['date', 'voucher_no', 'ags', 'customer', 'phone', 'method', 'paid_amount'],
                'texts' => ['Date : ', 'Voucher : ', 'AGS : ', 'Customer : ', 'Phn No : ', 'Type : ', 'Paid : '],
            ],
            28 => [ // Payment
                'fields' => ['date', 'voucher_no', 'agp', 'supplier', 'phone', 'method', 'paid_amount'],
                'texts' => ['Date : ', 'Voucher : ', 'AGP : ', 'Supplier : ', 'Phn No : ', 'Type : ', 'Paid : '],
            ],
            29 => [ // Quotation
                'fields' => ['date', 'quotation_id', 'total_payable_amount'],
                'texts' => ['Date : ', 'Quotation ID : ', 'Total Payable Amt :'],
            ],
            30 => [ // Delivery Order
                'fields' => ['date', 'invoice_id', 'total_payable_amount', 'paid'],
                'texts' => ['Date : ', 'Do ID : ', 'Total Payable Amt', 'Paid'],
            ],
            31 => [ // Delivery Order
                'fields' => ['date', 'voucher_no', 'total_item', 'total_qty', 'net_total_value'],
                'texts' => ['Date : ', 'Voucher No : ', 'Total Item : ', 'Total Qty : ', 'Net Total Value : '],
            ],
            32 => [ // Incomes
                'fields' => ['report_date', 'voucher_no', 'total_amount', 'received', 'due'],
                'texts' => ['Date : ', 'Voucher No : ', 'Total Amt. : ', 'Received : ', 'Due : '],
            ],
            33 => [ // Income Receipt
                'fields' => ['report_date', 'receipt_voucher', 'received_amount', 'income_voucher'],
                'texts' => ['Date : ', 'Receipt Voucher No : ', 'Received Amt. : ', 'Income Voucher : '],
            ],
            36 => [ // Receive Stock
                'fields' => ['date_ts', 'voucher_no', 'total_item', 'total_qty'],
                'texts' => ['Date : ', 'Voucher No : ', 'total_item : ', 'total_qty : '],
            ],
        ];
    }

    public function addLog($action, $subject_type, $data_obj, $user_id = null)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();

        $dateFormat = json_decode($generalSettings->business, true)['date_format'];

        $__dateFormat = str_replace('y', 'Y', $dateFormat);

        $descriptionModel = $this->descriptionModel();
        $addLog = new UserActivityLog();
        $addLog->user_id = $user_id ? $user_id : auth()->user()->id;
        $addLog->action = $action;
        $addLog->subject_type = $subject_type;
        $addLog->date = date($__dateFormat);
        $addLog->report_date = date('Y-m-d H:i:s');

        // prepare the descriptions
        $description = '';

        $index = 0;
        foreach ($descriptionModel[$subject_type]['fields'] as $field) {

            $description .= $descriptionModel[$subject_type]['texts'][$index].(isset($data_obj->{$field}) ? $data_obj->{$field} : 'N/A').', ';
            $index++;
        }

        $addLog->descriptions = $description;
        $addLog->save();
    }
}
