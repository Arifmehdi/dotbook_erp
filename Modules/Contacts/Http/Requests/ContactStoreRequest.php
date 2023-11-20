<?php

namespace Modules\Contacts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,',
            'nid_no' => 'nullable|unique:customers,nid_no,',
            'trade_license_no' => 'nullable|unique:customers,trade_license_no,',

            'contact_type_id' => 'nullable',
            'contact_type' => 'nullable',
            'business_name' => 'nullable',
            'alternative_phone' => 'nullable',
            'alternate_phone' => 'nullable',
            'landline' => 'nullable',
            'email' => 'nullable',
            'date_of_birth' => 'nullable',
            'tax_number' => 'nullable',
            'address' => 'nullable',
            'shipping_address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip_code' => 'nullable',
            'status' => 'nullable',
            'contact_id' => 'nullable',
            'print_name' => 'nullable',
            'additional_information' => 'nullable',
            'print_ledger_code' => 'nullable',
            'print_status' => 'nullable',
            'permanent_address' => 'nullable',
            'credit_limit' => 'nullable',
            'print_ledger_name' => 'nullable',
            'billing_account' => 'nullable',
            'description' => 'nullable',
            'contact_status' => 'nullable',
            'contact_mailing_name' => 'nullable',
            'contact_post_office' => 'nullable',
            'contact_police_station' => 'nullable',
            'contact_currency' => 'nullable',
            'contact_fax' => 'nullable',
            'primary_mobile' => 'nullable',
            'contact_send_sms' => 'nullable',
            'contact_email' => 'nullable',
            'mailing_name' => 'nullable',
            'mailing_address' => 'nullable',
            'mailing_email' => 'nullable',
            'shipping_name' => 'nullable',
            'shipping_number' => 'nullable',
            'shipping_email' => 'nullable',
            'shipping_send_sms' => 'nullable',
            'alternative_address' => 'nullable',
            'alternative_name' => 'nullable',
            'alternative_post_office' => 'nullable',
            'alternative_zip_code' => 'nullable',
            'alternative_police_station' => 'nullable',
            'alternative_state' => 'nullable',
            'alternative_city' => 'nullable',
            'alternative_fax' => 'nullable',
            'alternative_send_sms' => 'nullable',
            'alternative_email' => 'nullable',
            'contact_file' => 'nullable',
            'contact_document' => 'nullable',
            'alternative_file' => 'nullable',
            'tin_number' => 'nullable',
            'tax_name' => 'nullable',
            'tax_category' => 'nullable',
            'tax_address' => 'nullable',
            'bank_name' => 'nullable',
            'bank_A_C_number' => 'nullable',
            'bank_currency' => 'nullable',
            'bank_branch' => 'nullable',
            'contact_telephone' => 'nullable',
            'known_person' => 'nullable',
            'known_person_phone' => 'nullable',
            'total_sale_due' => 'nullable',
            'created_by_id' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
