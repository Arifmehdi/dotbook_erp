<?php

namespace Modules\Contacts\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Contacts\Entities\Contact;

class ContactsImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $index = 0;
        foreach ($collection as $c) {
            if ($index != 0) {
                if ($c[3] && $c[8]) {
                    $addContacts = Contact::create([
                        'contact_auto_id' => $c[0],
                        'contact_type' => $c[1],
                        'contact_related' => $c[2],
                        'name' => $c[3],
                        'ref_id' => $c[4],
                        'total_employees' => $c[5],
                        'business_name' => $c[6],
                        'email' => $c[7],
                        'phone' => $c[8],
                        'alternative_phone' => $c[9],
                        'landline' => $c[10],
                        'date_of_birth' => $c[11],

                        'address' => $c[12],
                        'city' => $c[13],
                        'zip_code' => $c[14],
                        'country' => $c[15],
                        'state' => $c[16],
                        'description' => $c[17],
                        'additional_information' => $c[18],
                        'shipping_address' => $c[19],
                        'nid_no' => $c[20],
                        'trade_license_no' => $c[21],
                        'known_person' => $c[22],
                        'known_person_phone' => $c[23],
                        'permanent_address' => $c[24],

                        'print_name' => $c[25],
                        'print_ledger_name' => $c[26],
                        'print_ledger_code' => $c[27],
                        'billing_account' => $c[28],
                        'contact_status' => $c[29],
                        'contact_mailing_name' => $c[30],
                        'contact_post_office' => $c[31],
                        'contact_police_station' => $c[32],
                        'contact_currency' => $c[33],
                        'contact_fax' => $c[34],
                        'primary_mobile' => $c[35],
                        'contact_send_sms' => $c[36],
                        'contact_email' => $c[37],
                        'mailing_name' => $c[38],
                        'mailing_address' => $c[39],
                        'mailing_email' => $c[40],
                        'shipping_name' => $c[41],
                        'shipping_number' => $c[42],
                        'shipping_email' => $c[43],
                        'shipping_send_sms' => $c[44],
                        'alternative_address' => $c[45],
                        'alternative_name' => $c[46],
                        'alternative_post_office' => $c[47],
                        'alternative_zip_code' => $c[48],
                        'alternative_police_station' => $c[49],
                        'alternative_state' => $c[50],
                        'alternative_city' => $c[51],
                        'alternative_fax' => $c[52],
                        'alternative_send_sms' => $c[53],
                        'alternative_email' => $c[54],
                        'tin_number' => $c[55],
                        'tax_number' => $c[56],
                        'tax_name' => $c[57],
                        'tax_category' => $c[58],
                        'tax_address' => $c[59],
                        'bank_name' => $c[60],
                        'bank_A_C_number' => $c[61],
                        'bank_currency' => $c[62],
                        'bank_branch' => $c[63],
                        'partner_name' => $c[64],
                        'percentage' => $c[65],
                        'sales_team' => $c[66],
                        'contact_telephone' => $c[67],
                    ]);
                }
            }
            $index++;
        }
    }
}
