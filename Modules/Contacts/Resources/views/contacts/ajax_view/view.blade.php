<style>
    #update_customer_detailed_form .form-group label {
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
            <h6 class="modal-title" id="exampleModalLabel">View Contact Details <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
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

            <form id="update_customer_detailed_form" action="{{ route('contacts.update', $findContact->id)}}" method="POST" enctype="multipart/form-data">
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
                                        <input type="radio" name="contact_type" class="big_contact_type big_company" disabled {{ $findContact->contact_type == 'company'? "checked" : "" }} id="big_modal_contact_type" value="company">
                                    </div>
                                    <div class="col-sm-4 border-box">
                                        <span>Individual</span>
                                        <input type="radio" name="contact_type" class="big_contact_type big_individual" disabled {{ $findContact->contact_type == 'individual'? "checked" : "" }} value="individual" id="big_modal_contact_type">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Contact Name :</b> <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Phone Number :</b> <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->phone }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Contact Type :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->contact_related }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1 big_modal_company_feild">
                                    <label class="col-sm-4 big_modal_company_feild"><b>Company Name :</b></label>
                                    <label class="col-sm-4 big_modal_individual_feild d-none"><b>Companies :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->business_name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1 big_modal_company_feild">
                                    <label class="col-sm-4"><b>Trade Number :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->trade_license_no }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4 big_modal_company_feild"><b>Company Address :</b></label>
                                    <label class="col-sm-4 big_modal_individual_feild d-none"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->address }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1 big_modal_company_feild">
                                    <label class="col-sm-4"><b>Total Employees :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->total_employees }}</span>
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
                                        <span>{{ $findContact->date_of_birth }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Reference :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->ref_id }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Print Name:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->print_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Ledger Name :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->print_ledger_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Ledger Code :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->print_ledger_code }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Billing Account No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->billing_account }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Description :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->description }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Additional Information :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->additional_information }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Contact Image :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" accept="image/*" name="contact_file" id="contact_file" class="form-control file-color" value="" />
                                    </div>

                                    <div class="col-sm-2">
                                        <img id="previewImg" src="{{ asset('images/default.jpg') }}" alt="img">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Add Document :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" name="contact_document[]" multiple id="gallery-photo-add" class="form-control file-color">
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
                                        <span>{{ $findContact->contact_mailing_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>NID No. :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->nid_no }}</span>
                                        <span class="error error_nid_no"></span>
                                    </div>
                                </div>

                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4"><b>Permanent Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->permanent_address }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->contact_post_office }}</span>
                                    </div>

                                    <label class="col-sm-2"><b>Post Code :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->zip_code }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->contact_police_station }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->state }}</span>
                                    </div>

                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->city }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Country :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->country }}</span>
                                    </div>

                                    <label class="col-sm-2"><b>Currency :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->contact_currency }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->contact_telephone }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->contact_fax }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->primary_mobile }}</span>
                                    </div>

                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <span>{{ $findContact->contact_send_sms }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->contact_email }}</span>
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
                                        <span>{{ $findContact->mailing_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->mailing_address }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->mailing_email }}</span>
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
                                        <span>{{ $findContact->shipping_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->shipping_number }}</span>
                                    </div>
                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <span>{{ $findContact->shipping_send_sms }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->shipping_email }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->shipping_address }}</span>
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
                                        <span>{{ $findContact->alternative_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->landline }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->alternative_phone }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->alternative_fax }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->known_person_phone }}</span>
                                    </div>

                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <span>{{ $findContact->alternative_send_sms }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->alternative_email }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->alternative_address }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->alternative_post_office }}</span>
                                    </div>

                                    <label class="col-sm-3"><b>Post Code :</b></label>
                                    <div class="col-sm-2">
                                        <span>{{ $findContact->alternative_zip_code }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->alternative_police_station }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->alternative_state }}</span>
                                    </div>

                                    <label class="col-sm-2"><b>City :</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $findContact->alternative_city }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Contact Related Image :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" accept="image/*" name="alternative_file" id="alternative_file" value="" class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        <img id="alternative_previewImg" src="{{ asset('images/default.jpg') }}" alt="Placeholder">
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
                            @forelse ( $findContact->contactRelatedPersone as $k => $contact_person )
                            <div class="col-lg-10 offset-1 ">
                                <h6 class="form-title"> Contact Person</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Name :</b></label>
                                    <div class="col-sm-4">
                                        <span>{{ $contact_person->contact_person_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_phon }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Dasignation:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_dasignation }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_landline }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_alternative_phone }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_alternative_phone }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_email }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_address }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office:</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $contact_person->contact_person_post_office }}</span>
                                    </div>

                                    <label class="col-sm-3"><b>Post Code:</b></label>
                                    <div class="col-sm-2">
                                        <span>{{ $contact_person->contact_person_zip_code }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $contact_person->contact_person_police_station }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State:</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $contact_person->contact_person_state }}</span>
                                    </div>
                                    <label class="col-sm-2"><b>City:</b></label>
                                    <div class="col-sm-3">
                                        <span>{{ $contact_person->contact_person_city }}</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <Span>Contacts Related Persone Not Found!</Span>
                            @endforelse


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
                                        <span>{{ $findContact->tin_number }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>TAX No :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->tax_number }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Name :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->tax_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Category :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->tax_category }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Address :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->tax_address }}</span>
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
                                        <span>{{ $findContact->bank_name }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank A/c Number :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->bank_A_C_number }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Currency :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->bank_currency }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank Branch :</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->bank_branch }}</span>
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
                                        <span>{{ $findContact->partner_name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Percentage (%):</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->percentage }}</span>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Sales Team:</b></label>
                                    <div class="col-sm-8">
                                        <span>{{ $findContact->sales_team }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>

                {{-- </div> --}}
            </form>
        </div>
    </div>
</div>

<script>
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
