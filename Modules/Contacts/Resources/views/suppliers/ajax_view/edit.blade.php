<link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    #edit_supplier_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_supplier') -> {{ $supplier->name }} || Phone :
                {{ $supplier->phone }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="edit_modal_body">
            <form id="edit_supplier_form" action="{{ route('contacts.supplier.update') }}">
                <input type="hidden" name="id" id="id" value="{{ $supplier->id }}">
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

                <div class="tab_contant active basicInformation" id="basicInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1">
                                <h6 class="form-title">Basic Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">supplier Name: <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="name" class="form-control"
                                            id="e_name" placeholder="supplier Name" value="{{ $supplier->name }}">
                                        <span class="error error_e_name"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Phone Number: <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="phone" class="form-control"
                                            id="e_phone" placeholder="Phone Number" value="{{ $supplier->phone }}">
                                        <span class="error error_e_phone"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4">Company Name :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="business_name" class="form-control"
                                            placeholder="Company Name" value="{{ $supplier->business_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4">Trade Number :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="trade_license_no" class="form-control"
                                            placeholder="Trade Number"
                                            value="{{ $supplier?->supplierDetails?->trade_license_no }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4 shop_name_big_modal">Present Address :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Present Address" value="{{ $supplier->address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Opening Balance:</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="number" id="opening_balance" name="opening_balance"
                                                class="form-control" value="{{ $supplier->opening_balance }}">
                                            <select class="form-control" name="opening_balance_type"
                                                id="opening_balance_type">
                                                <option
                                                    {{ $supplier->opening_balance_type == 'credit' ? 'SELECTED' : '' }}
                                                    value="credit">@lang('menu.credit')</option>
                                                <option
                                                    {{ $supplier->opening_balance_type == 'debit' ? 'SELECTED' : '' }}
                                                    value="debit">@lang('menu.debit')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Supplier Type :</label>
                                    <div class="col-sm-3">
                                        <select class=" form-control" name="supplier_type" id="supplier_type"
                                            style="">
                                            <option
                                                {{ $supplier?->supplierDetails?->supplier_type == 1 ? 'SELECTED' : '' }}
                                                value="1">@lang('menu.non_credit')</option>
                                            <option
                                                {{ $supplier?->supplierDetails?->supplier_type == 2 ? 'SELECTED' : '' }}
                                                value="2">@lang('menu.credit')</option>
                                        </select>
                                    </div>

                                    <label
                                        class="col-sm-2 term_hide {{ $supplier?->supplierDetails?->supplier_type != 2 ? 'd-none' : '' }}">Credit
                                        Limit :</label>
                                    <div
                                        class="col-sm-3 term_hide {{ $supplier?->supplierDetails?->supplier_type != 2 ? 'd-none' : '' }}">
                                        <input type="number" id="credit_limit" name="credit_limit"
                                            class="form-control"
                                            value="{{ $supplier?->supplierDetails?->credit_limit }}">
                                    </div>
                                </div>

                                <div
                                    class="form-group row p-1 term_hide {{ $supplier?->supplierDetails?->supplier_type != 2 ? 'd-none' : '' }}">
                                    <label class="col-sm-4">Term :</label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="pay_term" id="supplier-type"
                                            style="">
                                            <option value="">@lang('menu.days')/@lang('menu.months')</option>
                                            <option {{ $supplier?->pay_term == 2 ? 'SELECTED' : '' }} value="2">
                                                @lang('menu.days')</option>
                                            <option {{ $supplier?->pay_term == 1 ? 'SELECTED' : '' }} value="1">
                                                @lang('menu.months')</option>
                                        </select>
                                    </div>

                                    <label class="col-sm-2">Pay Term :</label>
                                    <div class="col-sm-3">
                                        <input type="number" step="any" name="pay_term_number"
                                            class="form-control" id="e_pay_term_number"
                                            value="{{ $supplier?->pay_term_number }}" placeholder="Number" />
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
                                <h6 class="form-title">Details Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Date Of Birth</label>
                                    <div class="col-sm-8">
                                        <input type="date" id="e_date_of_birth" name="date_of_birth"
                                            class="form-control"
                                            value="{{ $supplier->date_of_birth ? date('Y-m-d', strtotime($supplier->date_of_birth)) : '' }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Print Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->print_name }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Ledger Name :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_ledger_name" class="form-control"
                                            placeholder=""
                                            value="{{ $supplier?->supplierDetails?->print_ledger_name }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Ledger Code:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_ledger_code" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->print_ledger_code }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Billing Account No :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="billing_account" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->billing_account }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Description :</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control ckEditor" name="description" form="usrform" placeholder="Enter text here...">{{ $supplier?->supplierDetails?->description }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Supplier Status:</label>
                                    <div class="col-sm-3">
                                        <div class="button-active">
                                            <select class="form-control form-select" name="supplier_status"
                                                id="">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="Active"
                                                    {{ $supplier?->supplierDetails?->supplier_status == 'Active' ? 'SELECTED' : '' }}>
                                                    Active</option>
                                                <option value="Deactive"
                                                    {{ $supplier?->supplierDetails?->supplier_status == 'Deactive' ? 'SELECTED' : '' }}>
                                                    Deactive</option>
                                            </select>
                                            <span class="active-p"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Supplier Image :</label>
                                    <div class="col-sm-2">
                                        <input type="file" name="supplier_file" id="supplier_file" value=""
                                            class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        @if ($supplier?->supplierDetails?->supplier_file != null)
                                            <img
                                                src="{{ asset('uploads/supplier') . '/' . $supplier?->supplierDetails?->supplier_file }}">
                                        @else
                                            <img src="{{ asset('images/default.jpg') }}" alt="Placeholder">
                                        @endif
                                    </div>

                                    <div class="col-sm-2 d-none previewImg">
                                        <img id="previewImg" src="{{ asset('images/default.jpg') }}"
                                            alt="Placeholder">
                                    </div>

                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"> Add Document :</label>
                                    <div class="col-sm-4">
                                        <input type="file" name="supplier_document[]" multiple
                                            id="gallery-photo-add" value="" class="form-control file-color">
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
                            <h6 class="form-title">Supplier Primary Contact Information</h6>
                            <div class="col-lg-10">
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mailing Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_mailing_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_mailing_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">NID No. :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nid_no" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->nid_no }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4">Permanent Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="permanent_address" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->permanent_address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Post Office:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_post_office" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_post_office }}">
                                    </div>
                                    <label class="col-sm-3">Post Code:</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="zip_code" class="form-control"
                                            value="{{ $supplier->zip_code }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Police Station:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_police_station" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_police_station }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">State:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="state" class="form-control"
                                            value="{{ $supplier->state }}">
                                    </div>
                                    <label class="col-sm-2">City:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="city" class="form-control"
                                            value="{{ $supplier->city }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Country:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="country" class="form-control"
                                            value="{{ $supplier->country }}">
                                    </div>
                                    <label class="col-sm-2">Currency:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_currency" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_currency }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Telephone No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_telephone" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_telephone }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Fax No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_fax" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_fax }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mobile No:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="primary_mobile" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->primary_mobile }}">
                                    </div>

                                    <label class="col-sm-3">Send SMS:</label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="contact_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes"
                                                {{ $supplier?->supplierDetails?->contact_send_sms == 'Yes' ? 'SELECTED' : '' }}>
                                                Yes</option>
                                            <option value="No"
                                                {{ $supplier?->supplierDetails?->contact_send_sms == 'No' ? 'SELECTED' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Email Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_email" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->contact_email }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-10 offset-1">
                                <h6 class="form-title">Mailing Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mailing Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->mailing_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Present Address :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_address" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->mailing_address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Email Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_email" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->mailing_email }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="form-title">Shipping Information</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mail Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->shipping_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mobile No:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_number" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->shipping_number }}">
                                    </div>
                                    <label class="col-sm-3">Send SMS:</label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="shipping_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes"
                                                {{ $supplier?->supplierDetails?->shipping_send_sms == 'Yes' ? 'SELECTED' : '' }}>
                                                Yes</option>
                                            <option value="No"
                                                {{ $supplier?->supplierDetails?->shipping_send_sms == 'No' ? 'SELECTED' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Email Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_email" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->shipping_email }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Present Address :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_address" class="form-control"
                                            value="{{ $supplier->shipping_address }}">
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
                                <h6 class="form-title">Alternative Contact</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mailing Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Telephone No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="landline" class="form-control"
                                            value="{{ $supplier->landline }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Alternative Phone No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_phone" class="form-control"
                                            value="{{ $supplier->alternative_phone }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Fax No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_fax" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_fax }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Mobile No:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="known_person_phone" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->known_person_phone }}">
                                    </div>

                                    <label class="col-sm-3">Send SMS:</label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="alternative_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes"
                                                {{ $supplier?->supplierDetails?->alternative_send_sms == 'Yes' ? 'SELECTED' : '' }}>
                                                Yes</option>
                                            <option value="No"
                                                {{ $supplier?->supplierDetails?->alternative_send_sms == 'No' ? 'SELECTED' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Email Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_email" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_email }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Present Address :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_address" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Post Office:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_post_office" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_post_office }}">
                                    </div>
                                    <label class="col-sm-3">Post Code:</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="alternative_zip_code" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_zip_code }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Police Station:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_police_station" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_police_station }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">State:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_state" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_state }}">
                                    </div>
                                    <label class="col-sm-2">City:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_city" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->alternative_city }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"> Add Image :</label>
                                    <div class="col-sm-2">
                                        <input type="file" name="alternative_file" id="alternative_file"
                                            value="" class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        @if ($supplier?->supplierDetails?->alternative_file != null)
                                            <img class="alternative_file"
                                                src="{{ asset('uploads/supplier/alternative/' . $supplier?->supplierDetails?->alternative_file) }}"
                                                alt="">
                                        @else
                                            <img src="{{ asset('images/default.jpg') }}" alt="Placeholder">
                                        @endif
                                    </div>

                                    <div class="col-sm-2 d-none alternative_previewImg">
                                        <img id="alternative_previewImg" src="{{ asset('images/default.jpg') }}"
                                            alt="Placeholder">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none ContactParsones" id="ContactPersons">
                    <div class="tab-content-inner">
                        <div class="row clonedata">
                            @if (count($supplier->supplierContactPersonDetails) > 0)

                                @foreach ($supplier->supplierContactPersonDetails as $k => $contact_person)
                                    <div class="col-lg-10 offset-1">
                                        <h6 class="form-title"> Contact Person</h6>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"> Name :</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="contact_person_name[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_name }}">
                                            </div>

                                            <div class="col-sm-2">
                                                <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i
                                                        class="fa fa-plus"></i></button>
                                                <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i
                                                        class="fa fa-remove"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Mobile No :</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_phon[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_phon }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Dasignation:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_dasignation[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_dasignation }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Telephone No:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_landline[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_landline }}">
                                            </div>
                                        </div>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Alternative Phone No:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_alternative_phone[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                            </div>
                                        </div>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Fax No:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_fax[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                            </div>
                                        </div>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Email Address</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_email[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_email }}">
                                            </div>
                                        </div>


                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Present Address :</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_address[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_address }}">
                                            </div>
                                        </div>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Post Office:</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="contact_person_post_office[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_post_office }}">
                                            </div>
                                            <label class="col-sm-3">Post Code:</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="contact_person_zip_code[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_zip_code }}">
                                            </div>
                                        </div>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">Police Station:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_police_station[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_police_station }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4">State:</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="contact_person_state[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_state }}">
                                            </div>

                                            <label class="col-sm-2">City:</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="contact_person_city[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_city }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-10 ">
                                    <h6 class="form-title"> Contact Person</h6>
                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"> Name :</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="contact_person_name[]" class="form-control"
                                                value="">
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i
                                                    class="fa fa-plus"></i></button>
                                            <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i
                                                    class="fa fa-remove"></i></button>
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Mobile No :</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_phon[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Dasignation:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_dasignation[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Telephone No:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_landline[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Alternative Phone No:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_alternative_phone[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Fax No:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_fax[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Email Address</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_email[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Present Address :</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_address[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Post Office:</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="contact_person_post_office[]"
                                                class="form-control" value="">
                                        </div>
                                        <label class="col-sm-3">Post Code:</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="contact_person_zip_code[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">Police Station:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_police_station[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4">State:</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="contact_person_state[]" class="form-control"
                                                value="">
                                        </div>
                                        <label class="col-sm-2">City:</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="contact_person_city[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row clonedata p-3" id="packagingappendhere"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none taxInformation" id="taxInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1">
                                <h6 class="form-title">TAX Information</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">TIN No :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tin_number" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->tax_number }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">TAX No :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_number" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->tin_number }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->tax_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Category :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_category" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->tax_category }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Address :</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_address" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->tax_address }}">
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
                                <h6 class="form-title">Bank Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Bank Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_name" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->bank_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Bank A/C Number:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_A_C_number" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->bank_A_C_number }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Currency:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_currency" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->bank_currency }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4">Bank Branch:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_branch" class="form-control"
                                            value="{{ $supplier?->supplierDetails?->bank_branch }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button">@lang('menu.save_changes')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // var request = $('#add_supplier_form').serialize();

        $(document).on('change', '#supplier_type', function() {

            if ($(this).val() == 2) {
                $('#credit_limit').val('');
                $('.term_hide').removeClass('d-none');
            } else {
                $('#credit_limit').val(0);
                $('.term_hide').addClass('d-none');
            }
        });

        $('.contact_type').change(function() {
            if ($('#shop_big_modal').prop('checked')) {
                $('.trade_hide_big_modal').addClass('d-none');
                $('.shop_name_big_modal').removeClass('d-none')
            } else {
                $('.trade_hide_big_modal').removeClass('d-none');
                $('.shop_name_big_modal').addClass('d-none');
            }
        });

        $('#supplierGroupName').change(function() {
            if ($(this).val() == 0) {
                $('#supplier_input_fill').removeClass('d-none');
            } else {
                $('#supplier_input_fill').addClass('d-none');
            }
        });

        // file preview code start
        $("#supplier_file").change(function() {

            var file = $("#supplier_file").get(0).files[0];

            $('.previewImg').removeClass('d-none');

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

            $('.alternative_previewImg').removeClass('d-none');

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
        var existContactPerson = "<?php echo count($supplier->supplierContactPersonDetails); ?>";
        if (existContactPerson > 0) {
            i = existContactPerson;
        }
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
    });

    // edit category by ajax
    $('#edit_supplier_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            enctype: 'multipart/form-data',
            success: function(data) {

                toastr.success(data);
                $('.loading_button').hide();
                table.ajax.reload();
                $('.error').html('');
                $('#editModal').modal('hide');
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                    toastr.error(error[0]);
                });
            }
        });
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_date_of_birth'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
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

        $('#e_name').focus();
    }, 500);
</script>
