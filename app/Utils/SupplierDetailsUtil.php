<?php

namespace App\Utils;

use App\Models\SupplierDetails;

class SupplierDetailsUtil
{
    public function addSupplierDetails($request, $supplier, $fileUploaderService)
    {
        $addSupplierDetails = new SupplierDetails();
        $addSupplierDetails->supplier_id = $supplier->id;
        $addSupplierDetails->supplier_type = $request->supplier_type;
        $addSupplierDetails->credit_limit = $request->credit_limit;
        $addSupplierDetails->alternative_phone = $request->alternative_phone;
        $addSupplierDetails->print_supplier_status = $request->print_supplier_status;
        $addSupplierDetails->nid_no = $request->nid_no;
        $addSupplierDetails->trade_license_no = $request->trade_license_no;
        $addSupplierDetails->known_person = $request->known_person;
        $addSupplierDetails->known_person_phone = $request->known_person_phone;
        $addSupplierDetails->total_sale_due = $request->opening_balance ? $request->opening_balance : 0.00;
        $addSupplierDetails->permanent_address = $request->permanent_address;
        $addSupplierDetails->print_name = $request->print_name;
        $addSupplierDetails->print_ledger_name = $request->print_ledger_name;
        $addSupplierDetails->print_ledger_code = $request->print_ledger_code;
        $addSupplierDetails->billing_account = $request->billing_account;
        $addSupplierDetails->description = $request->description;
        $addSupplierDetails->supplier_status = $request->supplier_status;
        $addSupplierDetails->contact_mailing_name = $request->contact_mailing_name;
        $addSupplierDetails->contact_post_office = $request->contact_post_office;
        $addSupplierDetails->contact_police_station = $request->contact_police_station;
        $addSupplierDetails->contact_currency = $request->contact_currency;
        $addSupplierDetails->contact_fax = $request->contact_fax;
        $addSupplierDetails->primary_mobile = $request->primary_mobile;
        $addSupplierDetails->contact_send_sms = $request->contact_send_sms;
        $addSupplierDetails->contact_email = $request->contact_email;
        $addSupplierDetails->mailing_name = $request->mailing_name;
        $addSupplierDetails->mailing_address = $request->mailing_address;
        $addSupplierDetails->mailing_email = $request->mailing_email;
        $addSupplierDetails->shipping_name = $request->shipping_name;
        $addSupplierDetails->shipping_number = $request->shipping_number;
        $addSupplierDetails->shipping_email = $request->shipping_email;
        $addSupplierDetails->shipping_send_sms = $request->shipping_send_sms;
        $addSupplierDetails->alternative_address = $request->alternative_address;
        $addSupplierDetails->alternative_name = $request->alternative_name;
        $addSupplierDetails->alternative_post_office = $request->alternative_post_office;
        $addSupplierDetails->alternative_zip_code = $request->alternative_zip_code;
        $addSupplierDetails->alternative_police_station = $request->alternative_police_station;
        $addSupplierDetails->alternative_state = $request->alternative_state;
        $addSupplierDetails->alternative_city = $request->alternative_city;
        $addSupplierDetails->alternative_fax = $request->alternative_fax;
        $addSupplierDetails->alternative_send_sms = $request->alternative_send_sms;
        $addSupplierDetails->alternative_email = $request->alternative_email;
        $addSupplierDetails->tin_number = $request->tin_number;
        $addSupplierDetails->tax_number = $request->tax_number;
        $addSupplierDetails->tax_name = $request->tax_name;
        $addSupplierDetails->tax_category = $request->tax_category;
        $addSupplierDetails->tax_address = $request->tax_address;
        $addSupplierDetails->bank_name = $request->bank_name;
        $addSupplierDetails->bank_A_C_number = $request->bank_A_C_number;
        $addSupplierDetails->bank_currency = $request->bank_currency;
        $addSupplierDetails->bank_branch = $request->bank_branch;
        $addSupplierDetails->contact_telephone = $request->contact_telephone;
        $addSupplierDetails->created_by_id = auth()->user()->id;
        $addSupplierDetails->save();

        if ($addSupplierDetails) {

            $supplier_file = '';
            $supplier_document = '';
            $alternative_file = '';

            if ($request->hasFile('supplier_file')) {

                $supplier_file = $fileUploaderService->upload($request->file('supplier_file'), 'uploads/supplier/');
            }

            if ($request->hasFile('alternative_file')) {

                $alternative_file = $fileUploaderService->upload($request->file('alternative_file'), 'uploads/supplier/alternative/');
            }

            if ($request->hasFile('supplier_document')) {

                $supplier_document = $fileUploaderService->uploadMultiple($request->file('supplier_document'), 'uploads/supplier/documents');
            }

            $addSupplierDetails->supplier_file = $supplier_file;
            $addSupplierDetails->supplier_document = $supplier_document;
            $addSupplierDetails->alternative_file = $alternative_file;
            $addSupplierDetails->save();
        }
    }

    public function updateSupplierDetails($request, $supplier)
    {
        $updateSupplierDetails = '';
        if ($supplier->supplierDetails) {

            $updateSupplierDetails = $supplier->supplierDetails;
        } else {

            $updateSupplierDetails = new SupplierDetails();
        }

        $updateSupplierDetails->supplier_id = $supplier->id;
        $updateSupplierDetails->alternative_phone = $request->alternative_phone;
        $updateSupplierDetails->print_supplier_status = $request->print_supplier_status;
        $updateSupplierDetails->nid_no = $request->nid_no;
        $updateSupplierDetails->trade_license_no = $request->trade_license_no;
        $updateSupplierDetails->known_person = $request->known_person;
        $updateSupplierDetails->known_person_phone = $request->known_person_phone;
        $updateSupplierDetails->total_sale_due = $request->opening_balance ? $request->opening_balance : 0.00;
        $updateSupplierDetails->permanent_address = $request->permanent_address;
        $updateSupplierDetails->print_name = $request->print_name;
        $updateSupplierDetails->print_ledger_name = $request->print_ledger_name;
        $updateSupplierDetails->print_ledger_code = $request->print_ledger_code;
        $updateSupplierDetails->billing_account = $request->billing_account;
        $updateSupplierDetails->description = $request->description;
        $updateSupplierDetails->supplier_status = $request->supplier_status;
        $updateSupplierDetails->contact_mailing_name = $request->contact_mailing_name;
        $updateSupplierDetails->contact_post_office = $request->contact_post_office;
        $updateSupplierDetails->contact_police_station = $request->contact_police_station;
        $updateSupplierDetails->contact_currency = $request->contact_currency;
        $updateSupplierDetails->contact_fax = $request->contact_fax;
        $updateSupplierDetails->primary_mobile = $request->primary_mobile;
        $updateSupplierDetails->contact_send_sms = $request->contact_send_sms;
        $updateSupplierDetails->contact_email = $request->contact_email;
        $updateSupplierDetails->mailing_name = $request->mailing_name;
        $updateSupplierDetails->mailing_address = $request->mailing_address;
        $updateSupplierDetails->mailing_email = $request->mailing_email;
        $updateSupplierDetails->shipping_name = $request->shipping_name;
        $updateSupplierDetails->shipping_number = $request->shipping_number;
        $updateSupplierDetails->shipping_email = $request->shipping_email;
        $updateSupplierDetails->shipping_send_sms = $request->shipping_send_sms;
        $updateSupplierDetails->alternative_address = $request->alternative_address;
        $updateSupplierDetails->alternative_name = $request->alternative_name;
        $updateSupplierDetails->alternative_post_office = $request->alternative_post_office;
        $updateSupplierDetails->alternative_zip_code = $request->alternative_zip_code;
        $updateSupplierDetails->alternative_police_station = $request->alternative_police_station;
        $updateSupplierDetails->alternative_state = $request->alternative_state;
        $updateSupplierDetails->alternative_city = $request->alternative_city;
        $updateSupplierDetails->alternative_fax = $request->alternative_fax;
        $updateSupplierDetails->alternative_send_sms = $request->alternative_send_sms;
        $updateSupplierDetails->alternative_email = $request->alternative_email;
        $updateSupplierDetails->tin_number = $request->tin_number;
        $updateSupplierDetails->tax_number = $request->tax_number;
        $updateSupplierDetails->tax_name = $request->tax_name;
        $updateSupplierDetails->tax_category = $request->tax_category;
        $updateSupplierDetails->tax_address = $request->tax_address;
        $updateSupplierDetails->bank_name = $request->bank_name;
        $updateSupplierDetails->bank_A_C_number = $request->bank_A_C_number;
        $updateSupplierDetails->bank_currency = $request->bank_currency;
        $updateSupplierDetails->bank_branch = $request->bank_branch;
        $updateSupplierDetails->contact_telephone = $request->contact_telephone;
        $updateSupplierDetails->save();

        if ($updateSupplierDetails) {

            $supplier_file = '';
            $supplier_document = '';
            $alternative_file = '';

            if ($request->hasFile('supplier_file')) {

                if (is_file(public_path('uploads/supplier/'.$supplier?->supplierDetails?->supplier_file))) {

                    unlink(public_path('uploads/supplier/'.$supplier->supplierDetails->supplier_file));
                }

                $supplier_file = $fileUploaderService->upload($request->file('supplier_file'), 'uploads/supplier/');
                // $column_name = 'supplier_file';
                // $value = $supplier_file;
                // $this->updateFile($request->id, $column_name, $value);
            }

            if ($request->hasFile('alternative_file')) {

                if (is_file(public_path('uploads/supplier/alternative/'.$supplier?->supplierDetails?->alternative_file))) {

                    unlink(public_path('uploads/supplier/alternative/'.$supplier->supplierDetails->alternative_file));
                }

                $alternative_file = $fileUploaderService->upload($request->file('alternative_file'), 'uploads/supplier/alternative/');
                // $column_name = 'alternative_file';
                // $value = $alternative_file;

                // $this->updateFile($request->id, $column_name, $value);
            }

            if ($request->hasFile('supplier_document')) {

                $newSupplierDocumentsString = $fileUploaderService->uploadMultiple($request->file('supplier_document'), 'uploads/supplier/documents/');
                $newSupplierDocumentsArray = json_decode($newSupplierDocumentsString);

                if ($supplier?->supplierDetails?->supplier_document) {

                    $oldSupplierDocumentsArray = \json_decode($supplier->supplierDetails->supplier_document, true);
                    $mergedFilesArray = array_merge($oldSupplierDocumentsArray, $newSupplierDocumentsArray);
                    // $supplier->supplierDetails->supplier_document = json_encode($mergedFilesArray);
                    $supplier_document = json_encode($mergedFilesArray);
                } else {

                    if ($supplier?->supplierDetails?->supplier_document) {

                        // $supplier->supplierDetails->supplier_document = json_encode($newSupplierDocumentsArray);
                        $supplier_document = json_encode($newSupplierDocumentsArray);
                    }
                }
            }

            $updateSupplierDetails->supplier_file = $supplier_file;
            $updateSupplierDetails->supplier_document = $supplier_document;
            $updateSupplierDetails->alternative_file = $alternative_file;
            $updateSupplierDetails->save();
        }
    }

    public function updateFile($id, $column_name, $value)
    {
        SupplierDetails::where('supplier_id', $id)->update([
            $column_name => $value,
        ]);
    }
}
