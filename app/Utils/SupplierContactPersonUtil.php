<?php

namespace App\Utils;

use App\Models\SupplierContactPersonDetails;

class SupplierContactPersonUtil
{
    public function addSupplierContactPersons($supplier, $request)
    {
        $check_part = $request->contact_person_name;

        if (isset($check_part)) {

            foreach ($check_part as $key => $item) {

                $addContactPerson = new SupplierContactPersonDetails();
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
                $addContactPerson->supplier_id = $supplier->id;
                $addContactPerson->save();
            }
        }
    }

    public function updateSupplierContactPersons($supplier, $request)
    {
        $supplierContactPerson = $supplier->supplierContactPersonDetails;
        if (count($supplierContactPerson) > 0) {

            $supplierContactPerson = SupplierContactPersonDetails::where('supplier_id', $request->id)->delete();
        }

        $check_part = $request->contact_person_name;

        if (isset($check_part)) {

            foreach ($check_part as $key => $item) {

                $addContactPerson = new SupplierContactPersonDetails();
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
                $addContactPerson->supplier_id = $supplier->id;
                $addContactPerson->save();
            }
        }
    }
}
