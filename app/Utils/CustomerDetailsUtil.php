<?php

namespace App\Utils;

use App\Models\CustomerDetails;

class CustomerDetailsUtil
{
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

    public function updateCustomerDetails($request, $customer, $fileUploaderService)
    {
        $customerDetails = '';
        if ($customer->customerDetails) {

            $customerDetails = $customer->customerDetails;
        } else {

            $customerDetails = new CustomerDetails();
        }

        $customerDetails->customer_id = $customer->id;
        $customerDetails->contact_type = $request->contact_type;
        $customerDetails->total_employees = $request->total_employees;
        $customerDetails->permanent_address = $request->permanent_address;
        $customerDetails->print_name = $request->print_name;
        $customerDetails->print_ledger_name = $request->print_ledger_name;
        $customerDetails->print_ledger_code = $request->print_ledger_code;
        $customerDetails->billing_account = $request->billing_account;
        $customerDetails->description = $request->description;
        $customerDetails->customer_status = $request->customer_status;
        $customerDetails->contact_mailing_name = $request->contact_mailing_name;
        $customerDetails->contact_post_office = $request->contact_post_office;
        $customerDetails->contact_police_station = $request->contact_police_station;
        $customerDetails->contact_currency = $request->contact_currency;
        $customerDetails->contact_fax = $request->contact_fax;
        $customerDetails->primary_mobile = $request->primary_mobile;
        $customerDetails->contact_send_sms = $request->contact_send_sms;
        $customerDetails->contact_email = $request->contact_email;
        $customerDetails->mailing_name = $request->mailing_name;
        $customerDetails->mailing_address = $request->mailing_address;
        $customerDetails->mailing_email = $request->mailing_email;
        $customerDetails->shipping_name = $request->shipping_name;
        $customerDetails->shipping_number = $request->shipping_number;
        $customerDetails->shipping_email = $request->shipping_email;
        $customerDetails->shipping_send_sms = $request->shipping_send_sms;
        $customerDetails->alternative_address = $request->alternative_address;
        $customerDetails->alternative_name = $request->alternative_name;
        $customerDetails->alternative_post_office = $request->alternative_post_office;
        $customerDetails->alternative_zip_code = $request->alternative_zip_code;
        $customerDetails->alternative_police_station = $request->alternative_police_station;
        $customerDetails->alternative_state = $request->alternative_state;
        $customerDetails->alternative_city = $request->alternative_city;
        $customerDetails->alternative_fax = $request->alternative_fax;
        $customerDetails->alternative_send_sms = $request->alternative_send_sms;
        $customerDetails->alternative_email = $request->alternative_email;
        $customerDetails->tin_number = $request->tin_number;
        $customerDetails->tax_number = $request->tax_number;
        $customerDetails->tax_name = $request->tax_name;
        $customerDetails->tax_category = $request->tax_category;
        $customerDetails->tax_address = $request->tax_address;
        $customerDetails->bank_name = $request->bank_name;
        $customerDetails->bank_A_C_number = $request->bank_A_C_number;
        $customerDetails->bank_currency = $request->bank_currency;
        $customerDetails->bank_branch = $request->bank_branch;
        $customerDetails->contact_telephone = $request->contact_telephone;
        $customerDetails->partner_name = $request->partner_name;
        $customerDetails->percentage = $request->percentage;
        $customerDetails->sales_team = $request->sales_team;
        $customerDetails->save();

        $customer_file = '';
        $customer_document = '';
        $alternative_file = '';

        if ($request->hasFile('customer_file')) {

            if (is_file(public_path('uploads/customer/'.$customer?->customerDetails?->customer_file))) {

                unlink(public_path('uploads/customer/'.$customer?->customerDetails?->customer_file));
            }

            $customer_file = $fileUploaderService->upload($request->file('customer_file'), 'uploads/customer/');
            $column_name = 'customer_file';
            $value = $customer_file;
            $this->updateFile($id, $column_name, $value);
        }

        if ($request->hasFile('alternative_file')) {

            if (is_file(public_path('uploads/customer/alternative/'.$customer?->customerDetails?->alternative_file))) {

                unlink(public_path('uploads/customer/alternative/'.$customer?->customerDetails?->alternative_file));
            }

            $alternative_file = $fileUploaderService->upload($request->file('alternative_file'), 'uploads/customer/alternative/');
            $column_name = 'alternative_file';
            $value = $alternative_file;
            $this->updateFile($id, $column_name, $value);
        }

        if ($request->hasFile('customer_document')) {

            $newCustomerDocumentsString = $fileUploaderService->uploadMultiple($request->file('customer_document'), 'uploads/customer/documents/');
            $newCustomerDocumentsArray = json_decode($newCustomerDocumentsString);

            if ($customer?->customerDetails?->customer_document) {

                $oldCustomerDocumentsArray = \json_decode($customer->customerDetails->customer_document, true);
                $mergedFilesArray = array_merge($oldCustomerDocumentsArray, $newCustomerDocumentsArray);
                $customer->customerDetails->customer_document = json_encode($mergedFilesArray);
            } else {

                if ($customer?->customerDetails?->customer_document) {

                    $customer->customerDetails->customer_document = json_encode($newCustomerDocumentsArray);
                }
            }
        }
    }
}
