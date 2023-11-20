<?php

namespace Modules\Contacts\Services;

use Modules\Contacts\Entities\Contact;
use Modules\Contacts\Entities\ContactPersonDetails;
use Modules\Contacts\Interfaces\ContactServiceInterface;

class ContactService implements ContactServiceInterface
{
    public function all()
    {
        $contacts = Contact::with('contactRelatedPersone')->orderBy('id', 'desc')->get();

        return $contacts;
    }

    public function getTrashedItem()
    {
        $contacts = Contact::with('contactRelatedPersone')->onlyTrashed()->orderBy('id', 'desc')->get();

        return $contacts;
    }

    public function store($request)
    {
        $storeContact = Contact::create([
            'contact_auto_id' => $request->contact_auto_id,
            'contact_type' => $request->contact_type,
            'contact_related' => $request->contact_related,
            'name' => $request->name,
            'ref_id' => $request->reference_id,
            'total_employees' => $request->total_employees,
            'business_name' => $request->business_name,
            'email' => $request->contact_email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'description' => $request->description,
            'additional_information' => $request->additional_information,
            'shipping_address' => $request->shipping_address,
            'nid_no' => $request->nid_no,
            'trade_license_no' => $request->trade_license_no,
            'known_person' => $request->known_person,
            'known_person_phone' => $request->known_person_phone,
            'permanent_address' => $request->permanent_address,
            'contact_file' => $request->contact_file,
            'contact_document' => $request->contact_document,
            'alternative_file' => $request->alternative_file,
            'print_name' => $request->print_name,
            'print_ledger_name' => $request->print_ledger_name,
            'print_ledger_code' => $request->print_ledger_code,
            'billing_account' => $request->billing_account,
            'contact_status' => $request->contact_status,
            'contact_mailing_name' => $request->contact_mailing_name,
            'contact_post_office' => $request->contact_post_office,
            'contact_police_station' => $request->contact_police_station,
            'contact_currency' => $request->contact_currency,
            'contact_fax' => $request->contact_fax,
            'primary_mobile' => $request->primary_mobile,
            'contact_send_sms' => $request->contact_send_sms,
            'contact_email' => $request->contact_email,
            'mailing_name' => $request->mailing_name,
            'mailing_address' => $request->mailing_address,
            'mailing_email' => $request->mailing_email,
            'shipping_name' => $request->shipping_name,
            'shipping_number' => $request->shipping_number,
            'shipping_email' => $request->shipping_email,
            'shipping_send_sms' => $request->shipping_send_sms,
            'alternative_address' => $request->alternative_address,
            'alternative_name' => $request->alternative_name,
            'alternative_post_office' => $request->alternative_post_office,
            'alternative_zip_code' => $request->alternative_zip_code,
            'alternative_police_station' => $request->alternative_police_station,
            'alternative_state' => $request->alternative_state,
            'alternative_city' => $request->alternative_city,
            'alternative_fax' => $request->alternative_fax,
            'alternative_send_sms' => $request->alternative_send_sms,
            'alternative_email' => $request->alternative_email,
            'tin_number' => $request->tin_number,
            'tax_number' => $request->tax_number,
            'tax_name' => $request->tax_name,
            'tax_category' => $request->tax_category,
            'tax_address' => $request->tax_address,
            'bank_name' => $request->bank_name,
            'bank_A_C_number' => $request->bank_A_C_number,
            'bank_currency' => $request->bank_currency,
            'bank_branch' => $request->bank_branch,
            'partner_name' => $request->partner_name,
            'percentage' => $request->percentage,
            'sales_team' => $request->sales_team,
            'contact_telephone' => $request->contact_telephone,
            'created_by_id' => auth()->user()->id,
        ]);

        $check_part = $request->contact_person_name;
        if (isset($check_part)) {
            foreach ($check_part as $key => $item) {
                $addContactPerson = new ContactPersonDetails();
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
                $addContactPerson->contact_id = $storeContact->id;
                $addContactPerson->save();
            }
        }

        return $storeContact;
    }

    public function find($id)
    {
        $contacts = Contact::with('contactRelatedPersone')->find($id);

        return $contacts;
    }

    public function update($attribute, $id)
    {
        $contacts = Contact::with('contactRelatedPersone')->find($id);
        $contacts->update($attribute);

        $contactRelatedPersone = $contacts['contactRelatedPersone'];
        if (count($contactRelatedPersone) > 0) {
            $contactRelatedPersone = ContactPersonDetails::where('contact_id', $id)->delete();
        }

        $check_part = $attribute['contact_person_name'];
        if (isset($check_part)) {
            foreach ($check_part as $key => $item) {
                $addContactPerson = new ContactPersonDetails();
                $addContactPerson->contact_person_name = $attribute['contact_person_name'][$key];
                $addContactPerson->contact_person_phon = $attribute['contact_person_phon'][$key];
                $addContactPerson->contact_person_dasignation = $attribute['contact_person_dasignation'][$key];
                $addContactPerson->contact_person_landline = $attribute['contact_person_landline'][$key];
                $addContactPerson->contact_person_alternative_phone = $attribute['contact_person_alternative_phone'][$key];
                $addContactPerson->contact_person_fax = $attribute['contact_person_fax'][$key];
                $addContactPerson->contact_person_email = $attribute['contact_person_email'][$key];
                $addContactPerson->contact_person_address = $attribute['contact_person_address'][$key];
                $addContactPerson->contact_person_post_office = $attribute['contact_person_post_office'][$key];
                $addContactPerson->contact_person_zip_code = $attribute['contact_person_zip_code'][$key];
                $addContactPerson->contact_person_police_station = $attribute['contact_person_police_station'][$key];
                $addContactPerson->contact_person_state = $attribute['contact_person_state'][$key];
                $addContactPerson->contact_person_city = $attribute['contact_person_city'][$key];
                $addContactPerson->contact_id = $id;
                $addContactPerson->save();
            }
        }

        return $contacts;
    }

    public function trash($id)
    {
        $contacts = Contact::with('contactRelatedPersone')->find($id);
        $contacts->delete($contacts);

        return $contacts;
    }

    public function bulkTrash($ids)
    {
        foreach ($ids as $id) {
            $contacts = Contact::with('contactRelatedPersone')->find($id);
            $contacts->delete($contacts);
        }

        return $contacts;
    }

    public function permanentDelete($id)
    {
        $contacts = Contact::with('contactRelatedPersone')->onlyTrashed()->find($id);
        $existingFiles = $contacts->files;

        $contacts->forceDelete();

        return $contacts;
    }

    public function bulkPermanentDelete($ids)
    {
        foreach ($ids as $id) {
            $contacts = Contact::with('contactRelatedPersone')->onlyTrashed()->find($id);
            $contacts->forceDelete($contacts);
        }

        return $contacts;
    }

    public function restore($id)
    {
        $contacts = Contact::with('contactRelatedPersone')->withTrashed()->find($id)->restore();

        return $contacts;
    }

    public function bulkRestore($ids)
    {
        foreach ($ids as $id) {
            $contacts = Contact::with('contactRelatedPersone')->withTrashed()->find($id);
            $contacts->restore($contacts);
        }

        return $contacts;
    }

    public function getRowCount()
    {
        $count = Contact::with('contactRelatedPersone')->count();

        return $count;
    }

    public function filterWiseCount($collumn, $type)
    {
        $count = Contact::where($collumn, $type)->count();

        return $count;
    }

    public function getTrashedCount()
    {
        $count = Contact::with('contactRelatedPersone')->onlyTrashed()->count();

        return $count;
    }
}
