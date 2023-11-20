<style>
    #submit_supplier_detailed_form .form-group label {
        text-align: right;
    }
</style>
<link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_supplier_with_details') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="tab_list_area">
                <ul class="nav list-unstyled mb-3" role="tablist">

                    <li>
                        <a id="tab_btn" data-show="basicInformation" class="tab_btn tab_active" href="#">
                            Basic Information
                        </a>
                    </li>

                    <li>
                        <a id="tab_btn" data-show="detailInformation" class="tab_btn" href="#">
                            Detail Information
                        </a>
                    </li>

                    <li>
                        <a id="tab_btn" data-show="address" class="tab_btn" href="#">
                            Address
                        </a>
                    </li>

                    <li>
                        <a id="tab_btn" data-show="aternativeContact" class="tab_btn" href="#">
                            Alternative Contact
                        </a>
                    </li>

                    <li>
                        <a id="tab_btn" data-show="ContactParsones" class="tab_btn" href="#">
                            Contact Persons
                        </a>
                    </li>
                    <li>
                        <a id="tab_btn" data-show="taxInformation" class="tab_btn" href="#">
                            TAX Information
                        </a>
                    </li>
                    <li>
                        <a id="tab_btn" data-show="bankInformation" class="tab_btn" href="#">
                            Bank Information
                        </a>
                    </li>
                </ul>
            </div>

            <form id="submit_supplier_detailed_form" action="{{ route('contacts.supplier.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="tab_contant active basicInformation" id="basicInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Basic Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Supplier Name : <span
                                                class="text-danger">*</span></b></label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="name" class="form-control big_name"
                                            id="name" placeholder="Supplier Name">
                                        <span class="error error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Phone Number : <span
                                                class="text-danger">*</span></b></label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="phone" class="form-control big_phone"
                                            id="phone" placeholder="Phone Number">
                                        <span class="error error_phone"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Company Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="business_name"
                                            class="form-control big_business_name" placeholder="Company Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4 shop_name_big_modal"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" class="form-control big_address"
                                            placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Opening Balance:</b></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" name="opening_balance"
                                                class="form-control big_opening_balance" id="opening_balance"
                                                value="0">
                                            <select name="opening_balance_type" class="form-control big_balance_type"
                                                id="opening_balance_type">
                                                <option value="debit">@lang('menu.debit')</option>
                                                <option value="credit">@lang('menu.credit')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none detailInformation" id="detailInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">Details Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Date Of Birth</b></label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_of_birth" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Print Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_name" class="form-control"
                                            placeholder="Print Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Ledger Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_ledger_name" class="form-control"
                                            placeholder="Ledger Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Ledger Code :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_ledger_code" class="form-control"
                                            placeholder="Ledger Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Billing Account No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="billing_account" class="form-control"
                                            placeholder="Billing Account No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Description :</b></label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control ckEditor" name="description" form="usrform" placeholder="Enter text here..."></textarea>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Supplier Image :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" accept="image/*" name="supplier_file"
                                            id="supplier_file" value="" class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        <img id="previewImg" src="{{ asset('images/default.jpg') }}"
                                            alt="Placeholder">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Add Document :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" name="supplier_document[]" multiple
                                            id="gallery-photo-add" class="form-control file-color">
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="gallery"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none address" id="address">
                    <div class="tab-content-inner">
                        <div class="row">
                            <h6 class="text-center">Supplier Primary Contact Information</h6>
                            <div class="col-lg-10 offset-1">
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_mailing_name" class="form-control"
                                            placeholder="Mailing Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>NID No. :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nid_no" class="form-control"
                                            placeholder="NID No">
                                        <span class="error error_nid_no"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Trade Number :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="trade_license_no" class="form-control"
                                            placeholder="Trade Number">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Permanent Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="permanent_address" class="form-control"
                                            placeholder="Permanent Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_post_office" class="form-control"
                                            placeholder="Post Office">
                                    </div>

                                    <label class="col-sm-3"><b>Post Code :</b></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="zip_code" class="form-control"
                                            placeholder="Post Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_police_station" class="form-control"
                                            placeholder="Police Station">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="state" class="form-control"
                                            placeholder="State">
                                    </div>

                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="city" class="form-control"
                                            placeholder="City">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Country :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="country" class="form-control"
                                            placeholder="Country">
                                    </div>
                                    <label class="col-sm-2"><b>Currency :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_currency" class="form-control"
                                            placeholder="Currency">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_telephone" class="form-control"
                                            placeholder="Telephone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_fax" class="form-control"
                                            placeholder="Fax No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="primary_mobile" class="form-control"
                                            placeholder="Mobile No">
                                    </div>
                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="contact_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_email" class="form-control"
                                            placeholder="Email Address">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">Mailing Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_name" class="form-control"
                                            placeholder="Mailing Name">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_address" class="form-control"
                                            placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_email" class="form-control"
                                            placeholder="Email Address">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">Shipping Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mail Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_name" class="form-control"
                                            placeholder="Mail Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_number" class="form-control"
                                            placeholder="Mobile No">
                                    </div>
                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="shipping_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_email" class="form-control"
                                            placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_address" class="form-control"
                                            placeholder="Present Address">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none aternativeContact" id="aternativeContact">
                    <div class="tab-content-inner">
                        <div class="row ">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">Alternative Contact</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_name" class="form-control"
                                            placeholder="Mailing Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="landline" class="form-control"
                                            placeholder="Telephone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_phone" class="form-control"
                                            placeholder="Alternative Phone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_fax" class="form-control"
                                            placeholder="Fax No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="known_person_phone" class="form-control"
                                            placeholder="Mobile No">
                                    </div>

                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="alternative_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_email" class="form-control"
                                            placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_address" class="form-control"
                                            placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_post_office" class="form-control"
                                            placeholder="Post Office">
                                    </div>

                                    <label class="col-sm-3"><b>Post Code :</b></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="alternative_zip_code" class="form-control"
                                            placeholder="Post Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_police_station" class="form-control"
                                            placeholder="Police Station">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_state" class="form-control"
                                            placeholder="State">
                                    </div>

                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_city" class="form-control"
                                            placeholder="City">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Add Image :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" accept="image/*" name="alternative_file"
                                            id="alternative_file" value="" class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        <img id="alternative_previewImg" src="{{ asset('images/default.jpg') }}"
                                            alt="Placeholder">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none ContactParsones" id="ContactParsones">
                    <div class="tab-content-inner">
                        <div class="row clonedata">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center"> Contact Parsone</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Name :</b></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="contact_person_name[]" class="form-control"
                                            placeholder="Name">
                                    </div>

                                    <div class="col-sm-2">
                                        <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i
                                                class="fa fa-plus"></i></button>
                                        <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i
                                                class="fa fa-remove"></i></button>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_phon[]" class="form-control"
                                            placeholder="Mobile No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Dasignation :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_dasignation[]"
                                            class="form-control" placeholder="Dasignation">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_landline[]" class="form-control"
                                            placeholder="Telephone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_alternative_phone[]"
                                            class="form-control" placeholder="Alternative Phone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_fax[]" class="form-control"
                                            placeholder="Fax No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_email[]" class="form-control"
                                            placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_address[]" class="form-control"
                                            placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_post_office[]"
                                            class="form-control" placeholder="Post Office">
                                    </div>

                                    <label class="col-sm-3"><b>Post Code :</b></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="contact_person_zip_code[]" class="form-control"
                                            placeholder="Post Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_police_station[]"
                                            class="form-control" placeholder="Police Station">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_state[]" class="form-control"
                                            placeholder="State">
                                    </div>
                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_city[]" class="form-control"
                                            placeholder="City">
                                    </div>
                                </div>
                            </div>

                            <div class="row clonedata p-3" id="packagingappendhere"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none taxInformation" id="taxInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">TAX Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>TIN No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tin_number" class="form-control"
                                            placeholder="TIN No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>TAX No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_number" class="form-control"
                                            placeholder="TAX No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_name" class="form-control"
                                            placeholder="Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Category :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_category" class="form-control"
                                            placeholder="Category">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_address" class="form-control"
                                            placeholder="Address">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none bankInformation" id="bankInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">Bank Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_name" class="form-control"
                                            placeholder="Bank Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank A/C Number :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_A_C_number" class="form-control"
                                            placeholder="Bank A/C Number">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Currency :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_currency" class="form-control"
                                            placeholder="Currency">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank Branch :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_branch" class="form-control"
                                            placeholder="Bank Branch">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row justify-content-center p-1 mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('submit', '#submit_supplier_detailed_form', function(e) {

        e.preventDefault();

        $('.add_supplier_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.error').html('');
                toastr.success('Supplier added successfully.');
                $('.add_supplier_loading_button').hide();
                $('#add_supplier_basic_modal').modal('hide');
                $('#add_supplier_detailed_modal').modal('hide');

                $('.submit_button').prop('type', 'submit');

                var supplier_account_id = $('#supplier_account_id').val();
                if (supplier_account_id != undefined) {

                    $('#supplier_account_id').append('<option value="' + data.supplier_account_id +
                        '">' + data.name + '/' + data.phone + '</option>');
                    $('#supplier_account_id').val(data.supplier_account_id);
                    getAccountClosingBalance(data.supplier_account_id);
                } else {

                    table.ajax.reload();
                    refresh();
                }
            },
            error: function(err) {

                $('.add_supplier_loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);

                    toastr.error(key + ' => ' + error[0]);
                });
            }
        });
    });

    // file preview code start
    $("#supplier_file").change(function() {
        // alert('ok')

        var file = $("#supplier_file").get(0).files[0];

        if (file) {

            var reader = new FileReader();

            reader.onload = function() {

                $("#previewImg").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }
    });

    $("#alternative_file").change(function() {
        // alert('ok')
        var file = $("#alternative_file").get(0).files[0];

        if (file) {

            var reader = new FileReader();

            reader.onload = function() {

                $("#alternative_previewImg").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }
    });

    // Multiple images preview in browser
    $(function() {
        var imagesPreview = function(input, placeToInsertImagePreview) {

            if (input.files) {

                var filesAmount = input.files.length;
                for (i = 0; i < filesAmount; i++) {

                    var reader = new FileReader();
                    reader.onload = function(event) {

                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(
                            placeToInsertImagePreview).height('50px').width('50px');
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }
        };

        $('#gallery-photo-add').on('change', function() {
            imagesPreview(this, 'div.gallery');
        });
    });

    var i = 1;
    $(document).on('click', '.addmore', function(ev) {

        i++;
        var $clone = $(this).parent().parent().parent().clone(true);
        var $newbuttons =
            "<button type='button' class='mb-xs mr-xs btn btn-info addmore'><i class='fa fa-plus'></i></button><button type='button' class='mb-xs mr-xs btn btn-info removemore'><i class='fa fa-remove'></i></button>";
        $clone.find('.tn-buttons').html($newbuttons).end().appendTo($('#packagingappendhere'));
    });

    $(document).on('click', '.removemore', function() {

        i--;
        if (i > 0) {

            $(this).parent().parent().parent().remove();
        } else {

            if (i <= 1) {

                i = 1;
            }
            alert('only one field is shos !');
        }
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').addClass('d-none');
        var show_content = $(this).data('show');
        $('.' + show_content).removeClass('d-none');
        $(this).addClass('tab_active');
    });

    setTimeout(function() {

        $('.big_name').focus();
    }, 500);
</script>
