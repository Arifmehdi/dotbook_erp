<style>
    #submit_customer_detailed_form .form-group label {
        text-align: right;
    }

    .add-contact-modal-btn-group {
        display: flex;
        width: 100%;
        margin-bottom: 15px;
    }

    .add-contact-modal-btn-group button {
        width: calc(100% / 8);
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(0, 0, 0, .1);
        padding: 2px 15px;
    }

    .add-contact-modal-btn-group button:not(:last-child) {
        border-right: 0;
    }

    .add-contact-modal-btn-group button .part-icon {
        font-size: 25px;
        height: 50px;
        line-height: 50px;
    }

    .add-contact-modal-btn-group button .part-txt {
        text-align: left;
    }

    .add-contact-modal-btn-group button .part-txt span {
        line-height: 100%;
    }
</style>
<link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Contact with Details <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="btn-group add-contact-modal-btn-group">
                <button>
                    <span class="part-icon">
                        <i class="fa-light fa-calendar-days"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Meetings</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-solid fa-circle-dashed"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Subscriptions</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Sales</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-light fa-futbol"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Tickets</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-light fa-list-check"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Tasks</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-regular fa-circle-dollar"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Purchases</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-light fa-mug"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Poposals</span>
                    </span>
                </button>
                <button>
                    <span class="part-icon">
                        <i class="fa-light fa-calendar-check"></i>
                    </span>
                    <span class="part-txt">
                        <span class="d-block">2</span>
                        <span class="d-block">Appointments</span>
                    </span>
                </button>
            </div>

            <div class="tab_list_area">
                <ul class="nav list-unstyled mb-3 justify-content-center" role="tablist">
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
                        <a id="tab_btn" data-show="tab_address" class="tab_btn" href="#">
                            Address
                        </a>
                    </li>
                    <li>
                        <a id="tab_btn" data-show="aternativeContact" class="tab_btn" href="#">
                            Alternative Contact
                        </a>
                    </li>
                    <li>
                        <a id="tab_btn" data-show="ContactPersons" class="tab_btn" href="#">
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
                    <li>
                        <a id="tab_btn" data-show="SalesPartner" class="tab_btn" href="#">
                            Sales Partner
                        </a>
                    </li>
                </ul>
            </div>

            <form id="submit_customer_detailed_form" action="{{ route('contacts.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                {{-- <div class="tab-content"> --}}
                <div class="tab_contant active basicInformation" id="basicInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Basic Information</h6>
                                <div class="form-group row p-1" id="business_type">
                                    <label class="col-sm-4 ContactType"><b>Type:</b></label>
                                    <div class="col-sm-4 border-box">
                                        <span>Company</span>
                                        <input type="radio" name="contact_type"
                                            class="big_contact_type big_company" id="big_modal_contact_type"
                                            value="company">
                                    </div>
                                    <div class="col-sm-4 border-box">
                                        <span>Individual</span>
                                        <input type="radio" name="contact_type"
                                            class="big_contact_type big_individual" value="individual"
                                            id="big_modal_contact_type">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Contact Name :</b> <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input required type="text" id="name" name="name"
                                            class="form-control big_name" placeholder="Contact Name">
                                        <span class="error error_name"></span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Phone Number :</b> <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input required type="text" id="phone" name="phone"
                                            class="form-control big_phone" placeholder="Phone Number">
                                        <span class="error error_phone"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Contact Type :</b></label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select" name="contact_related"
                                            id="">
                                            <option value="Contacts">{{ __('Contacts') }}</option>
                                            <option value="Leads">{{ __('Leads') }}</option>
                                            <option value="Customers">{{ __('Customers') }}</option>
                                            <option value="Suppliers">{{ __('Suppliers') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1 big_modal_individual_feild d-none">
                                    <label class="col-sm-4"><b>Companies :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="business_name"
                                            class="form-control big_business_name" placeholder="Companies">
                                    </div>
                                </div>
                                <div class="form-group row p-1 big_modal_company_feild">
                                    <label class="col-sm-4 big_modal_company_feild"><b>Company Name :</b></label>
                                    <label class="col-sm-4 big_modal_individual_feild d-none"><b>Companies
                                            :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="business_name"
                                            class="form-control big_business_name" placeholder="Company Name">
                                    </div>
                                </div>
                                <div class="form-group row p-1 big_modal_company_feild">
                                    <label class="col-sm-4"><b>Trade Number :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="trade_license_no"
                                            class="form-control big_trade_license_no" placeholder="Trade Number">
                                        <span class="error error_trade_license_no"></span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4 big_modal_company_feild"><b>Company Address :</b></label>
                                    <label class="col-sm-4 big_modal_individual_feild d-none"><b>Present Address
                                            :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" class="form-control big_address"
                                            placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group row p-1 big_modal_company_feild">
                                    <label class="col-sm-4"><b>Total Employees :</b></label>
                                    <div class="col-sm-8">
                                        <input type="number" name="total_employees" class="form-control"
                                            placeholder="Total Employees">
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none detailInformation" id="detailInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Details Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Date Of Birth</b></label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_of_birth" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Reference :</b></label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select" name="reference_id"
                                            id="customerGroupName">
                                            <option value="">@lang('menu.none')</option>
                                            @foreach ($references as $reference)
                                                <option value="{{ $reference->id }}">
                                                    {{ $reference->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Print Name:</b></label>
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
                                    <label class="col-sm-4"><b>Additional Information :</b></label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control ckEditor" name="additional_information" form="usrform"
                                            placeholder="Enter text here..."></textarea>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Contact Image :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" accept="image/*" name="contact_file" id="contact_file"
                                            class="form-control file-color" value="" />
                                    </div>

                                    <div class="col-sm-2">
                                        <img id="previewImg" src="{{ asset('images/default.jpg') }}" alt="img">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Add Document :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" name="contact_document[]" multiple
                                            id="gallery-photo-add" class="form-control file-color">
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="gallery"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none tab_address" id="tab_address">
                    <div class="tab-content-inner">
                        <div class="row">
                            <h6 class="text-center">Contacts Persone Primary Contact Information</h6>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_mailing_name" class="form-control"
                                            Placeholder="Mailing Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>NID No. :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nid_no" class="form-control"
                                            Placeholder="NID No">
                                        <span class="error error_nid_no"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4"><b>Permanent Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="permanent_address" class="form-control"
                                            Placeholder="Permanent Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_post_office" class="form-control"
                                            Placeholder="Post Office">
                                    </div>

                                    <label class="col-sm-2"><b>Post Code :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="zip_code" class="form-control"
                                            Placeholder="Post Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_police_station" class="form-control"
                                            Placeholder="Police Station">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="state" class="form-control"
                                            Placeholder="State">
                                    </div>

                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="city" class="form-control"
                                            Placeholder="City">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Country :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="country" class="form-control"
                                            Placeholder="Country">
                                    </div>

                                    <label class="col-sm-2"><b>Currency :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_currency" class="form-control"
                                            Placeholder="Currency">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_telephone" class="form-control"
                                            Placeholder="Telephone No ">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_fax" class="form-control"
                                            Placeholder="Fax No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="primary_mobile" class="form-control"
                                            Placeholder="Mobile No">
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
                                            Placeholder="Email Address">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1"></div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Mailing Information</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_name" class="form-control"
                                            Placeholder="Mailing Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_address" class="form-control"
                                            Placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_email" class="form-control"
                                            Placeholder="Email Address">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1"></div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Shipping Information</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mail Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_name" class="form-control"
                                            Placeholder="Mail Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_number" class="form-control"
                                            Placeholder="Mobile No">
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
                                            Placeholder="Email Address">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_address" class="form-control"
                                            Placeholder="Present Address">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none aternativeContact" id="aternativeContact">
                    <div class="tab-content-inner">
                        <div class="row ">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10 ">
                                <h6 class="text-center">Alternative Contact</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_name" class="form-control"
                                            Placeholder="Mailing Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="landline" class="form-control"
                                            Placeholder="Telephone No">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_phone" class="form-control"
                                            Placeholder="Alternative Phone No">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_fax" class="form-control"
                                            Placeholder="Fax No">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="known_person_phone" class="form-control"
                                            Placeholder="Mobile No">
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
                                            Placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_address" class="form-control"
                                            Placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_post_office" class="form-control"
                                            Placeholder="Post Office">
                                    </div>

                                    <label class="col-sm-3"><b>Post Code :</b></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="alternative_zip_code" class="form-control"
                                            Placeholder="Post Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_police_station" class="form-control"
                                            Placeholder="Police Station">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_state" class="form-control"
                                            Placeholder="State">
                                    </div>

                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_city" class="form-control"
                                            Placeholder="City">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Contact Related Image :</b></label>
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

                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none ContactPersons" id="ContactPersons">
                    <div class="tab-content-inner">
                        <div class="row clonedata">

                            <div class="col-lg-10 offset-1">
                                <h6 class="text-center">Related Contact Person</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Name :</b></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="contact_person_name[]" class="form-control"
                                            Placeholder="Name">
                                    </div>

                                    <div class="col-sm-2">
                                        <button type="button" name="addmoreKey[]" value="1"
                                            class="mb-xs mr-xs btn btn-sm btn-info addmore"><i
                                                class="fa fa-plus"></i></button>
                                        <button type="button" name="removemoreKey[]" value="1"
                                            class="mb-xs mr-xs btn btn-sm btn-info removemore"><i
                                                class="fa fa-remove"></i></button>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_phon[]" class="form-control"
                                            Placeholder="Mobile No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Dasignation :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_dasignation[]"
                                            class="form-control" Placeholder="Dasignation">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_landline[]" class="form-control"
                                            Placeholder="Telephone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_alternative_phone[]"
                                            class="form-control" Placeholder="Alternative Phone No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_fax[]" class="form-control"
                                            Placeholder="Fax No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_email[]" class="form-control"
                                            Placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_address[]" class="form-control"
                                            Placeholder="Present Address">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_post_office[]"
                                            class="form-control" Placeholder="Post Office">
                                    </div>

                                    <label class="col-sm-3"><b>Post Code :</b></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="contact_person_zip_code[]" class="form-control"
                                            Placeholder="Post Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_person_police_station[]"
                                            class="form-control" Placeholder="Police Station">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_state[]" class="form-control"
                                            Placeholder="State">
                                    </div>
                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_city[]" class="form-control"
                                            Placeholder="City">
                                    </div>
                                </div>
                            </div>

                            <div class="clonedata p-3" id="packagingappendhere"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none taxInformation" id="taxInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">TAX Information</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>TIN No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tin_number" class="form-control"
                                            Placeholder="TIN No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>TAX No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_number" class="form-control"
                                            Placeholder="TAX No">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_name" class="form-control"
                                            Placeholder="Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Category :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_category" class="form-control"
                                            Placeholder="Category">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_address" class="form-control"
                                            Placeholder="Address">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none bankInformation" id="bankInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Bank Information</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_name" class="form-control"
                                            Placeholder="Bank Name">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank A/c Number :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_A_C_number" class="form-control"
                                            Placeholder="Bank A/C Number">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Currency :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_currency" class="form-control"
                                            Placeholder="Currency">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank Branch :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_branch" class="form-control"
                                            Placeholder="Bank Branch">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none SalesPartner" id="SalesPartner">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">
                                <h6 class="text-center">Sales Partner And Commissions</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Partner :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="
                                        "
                                            class="form-control" Placeholder="Partner Name">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Percentage (%):</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="percentage" class="form-control"
                                            Placeholder="Percentage (%)">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Sales Team:</b></label>
                                    <div class="col-sm-8">
                                        <!-- <input type="text" class="form-control"> -->
                                        <select class="form-control form-select" name="sales_team" style="">
                                            <option value="" selected>Select Sales Team
                                            </option>
                                            <option value="1">Teat Name 1</option>
                                            <option value="2">Teat Name 2</option>
                                            <option value="3">Teat Name 3</option>
                                            <option value="4">Teat Name 4</option>
                                            <option value="5">Teat Name 5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1"></div>
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
                {{-- </div> --}}
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('submit', '#submit_customer_detailed_form', function(e) {
        e.preventDefault();
        $('.c_loading_button').show();
        var url = $(this).attr('action');
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
                toastr.success('Customer added successfully.');
                $('.c_loading_button').hide();
                refresh();
                $('#add_contact_basic_modal').modal('hide');
                $('#add_contact_detailed_modal').modal('hide');
                $('.contacts_table').DataTable().ajax.reload();
                var customerId = $('#customer_id').val();
                $('.submit_button').prop('type', 'submit');
                if (customerId != undefined) {

                    $('#customer_id').append('<option data-customer_name="' + data.name +
                        '" data-customer_phone="' + data.phone + '" value="' + data.id + '">' +
                        data.name + '/' + data.phone + '</option>');
                    $('#customer_id').val(data.id);
                    var user_id = $('#user_id').val();
                    getCustomerAmountsUserWise(user_id, data.id, false);
                    calculateTotalAmount();
                } else {

                    table.ajax.reload();
                    refresh();
                }
            },
            error: function(err) {

                $('.c_loading_button').hide();
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

    $('#big_modal_customer_type').on('change', function() {

        if ($(this).val() == 1) {

            $('.big_modal_term_hide').addClass('d-none');
        } else {

            $('.big_modal_term_hide').removeClass('d-none');
        }
    });

    // file preview code start
    $("#contact_file").change(function() {
        var file = $("#contact_file").get(0).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                $("#previewImg").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $("#alternative_file").change(function() {
        var file = $("#alternative_file").get(0).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                $("#alternative_previewImg").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $(document).on('change', '#big_modal_contact_type', function() {
        if ($(this).val() == 'individual') {
            $('.big_modal_company_feild').addClass('d-none');
            $('.big_modal_individual_feild').removeClass('d-none')
        } else {
            $('.big_modal_company_feild').removeClass('d-none');
            $('.big_modal_individual_feild').addClass('d-none');
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

            alert('only one field will be shown !');
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

        $('.big_name').focus().select();
    }, 500);
</script>
