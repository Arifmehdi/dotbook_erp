<?php

namespace App\Imports;

use App\Models\Customer;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    protected $invoiceVoucherRefIdUtil;

    protected $customerUtil;

    public function collection(Collection $collection)
    {
        $this->invoiceVoucherRefIdUtil = new InvoiceVoucherRefIdUtil;
        $index = 0;
        $generalSettings = DB::table('general_settings')->first('prefix');
        $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];
        $this->customerUtil = new CustomerUtil();
        foreach ($collection as $c) {
            if ($index != 0) {
                if ($c[2] && $c[3]) {
                    $addCustomer = Customer::create([
                        'contact_id' => $c[0] ? $c[0] : $cusIdPrefix.str_pad($this->invoiceVoucherRefIdUtil->getLastId('customers'), 4, '0', STR_PAD_LEFT),
                        'business_name' => $c[1],
                        'name' => $c[2],
                        'phone' => $c[3],
                        'alternative_phone' => $c[4],
                        'landline' => $c[5],
                        'email' => $c[6],
                        'date_of_birth' => $c[7],
                        'tax_number' => $c[8],
                        'opening_balance' => (float) $c[9] ? (float) $c[9] : 0,
                        'address' => $c[10],
                        'city' => $c[11],
                        'state' => $c[12],
                        'country' => $c[13],
                        'zip_code' => $c[14],
                        'shipping_address' => $c[15],
                        'pay_term_number' => (float) $c[16],
                        'pay_term' => (float) $c[17],
                        'credit_limit' => (float) $c[18],
                        'total_sale_due' => (float) $c[9] ? (float) $c[9] : 0,
                    ]);
                }
            }
            $index++;
        }
    }
}
