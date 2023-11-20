<?php

namespace Modules\CRM\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\CRM\Entities\BusinessLead;

class LeadsImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $index = 0;

        foreach ($collection as $c) {
            if ($index != 0) {
                if ($c[1] && $c[2]) {
                    $addCustomer = BusinessLead::create([
                        'name' => $c[0],
                        'location' => $c[1],
                        'email_addresses' => $c[2],
                        'phone_numbers' => $c[3],
                        'total_employees' => $c[4],
                        'description' => $c[5],
                        'additional_information' => $c[6],
                    ]);
                }
            }
            $index++;
        }
    }
}
