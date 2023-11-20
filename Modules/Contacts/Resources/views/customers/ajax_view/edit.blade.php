<link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    #edit_customer_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_customer') -> {{ $customer->name }} || Phone :
                {{ $customer->phone }} </h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="edit-modal-form-body">
            <form id="edit_customer_form" action="{{ route('contacts.customers.update', $customer->id) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                <div class="tab_list_area">
                    <ul class="nav list-unstyled mb-3" role="tablist">
                        <li>
                            <a id="tab_btn" data-show="basicInformation" class="tab_btn tab_active"
                                href="#">Basic Information</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="detailInformation" class="tab_btn" href="#">Detail
                                Information</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="address" class="tab_btn" href="#">Address</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="aternativeContact" class="tab_btn" href="#">Aternative
                                Contact</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="ContactPersons" class="tab_btn" href="#">Contact
                                Persons</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="taxInformation" class="tab_btn" href="#">TAX
                                Information</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="bankInformation" class="tab_btn" href="#">Bank
                                Information</a>
                        </li>
                        <li>
                            <a id="tab_btn" data-show="SalesPartner" class="tab_btn" href="#">Sales Partner</a>
                        </li>
                    </ul>
                </div>
                <div class="tab_contant active basicInformation" id="basicInformation">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1 offset-1">
                                <h6 class="form-title">Basic Information</h6>
                                <div class="form-group row p-1" id="business_type">
                                    <label class="col-sm-4 ContactType"><b>Contact Type:</b></label>
                                    <div class="col-sm-4 border-box">
                                        <span>Company</span>
                                        <input class="contact_type" type="radio" checked value="2"
                                            id="company_big_modal" name="contact_type">
                                    </div>
                                    <div class="col-sm-4 border-box">
                                        <span>Individual</span>
                                        <input class="contact_type" type="radio" value="1" id="shop_big_modal"
                                            name="contact_type">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Customer Name : </b><span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="name" class="form-control"
                                            id="e_name" placeholder="Customer Name" value="{{ $customer->name }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Phone Number : </b><span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="phone" id="e_phone"
                                            class="form-control" placeholder="Phone Number"
                                            value="{{ $customer->phone }}">
                                        <span class="error error_e_phone"></span>
                                    </div>
                                </div>
                                <div class="form-group row p-1 shop_name_big_modal d-none">
                                    <label class="col-sm-4"><b>Companies :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="business_name" class="form-control"
                                            placeholder="Companies">
                                    </div>
                                </div>
                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4"><b>Company Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="business_name" class="form-control"
                                            placeholder="" value="{{ $customer->business_name }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4 trade_hide_big_modal"><b>Company Address :</b></label>
                                    <label class="col-sm-4 shop_name_big_modal d-none"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Address" value="{{ $customer->address }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4"><b>Trade Number :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="trade_license_no" id="e_trade_license_no"
                                            class="form-control" placeholder="Trade Number"
                                            value="{{ $customer->trade_license_no }}">
                                        <span class="error error_e_trade_license_no"></span>
                                    </div>
                                </div>
                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4 "><b>Total Employees :</b></label>
                                    <div class="col-sm-8">
                                        <input type="number" name="total_employees" class="form-control"
                                            placeholder="Total Employees"
                                            value="{{ $customer?->customerDetails?->total_employees }}">
                                    </div>
                                </div>
                                {{-- <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Opening Balance:</b></label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="number" name="opening_balance" class="form-control" id="opening_balance" value="{{ $userOpeningBalance ? $userOpeningBalance->amount : 0 }}">
                                            <select class="form-control form-select" name="opening_balance_type" id="opening_balance_type">
                                                <option {{ $userOpeningBalance?->balance_type == 'debit' ? 'SELECTED' : '' }}
                                                    value="debit">@lang('menu.debit')</option>
                                                <option {{ $userOpeningBalance?->balance_type == 'credit' ? 'SELECTED' : '' }} value="credit">@lang('menu.credit')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Opening Balance (<strong>Sr. Wise</strong>)
                                            :</b></label>
                                    <div class="col-sm-8">
                                        <table id="myTable">
                                            <tbody id="sr_body">
                                                @if (count($customer?->openingBalances ?? []) > 0)
                                                    @foreach ($customer?->openingBalances as $openingBalance)
                                                        <tr id="sr_opening_balance_row">
                                                            <td style="width: 15%" class="align-items-end">
                                                                <input readonly type="text"
                                                                    name="opening_balance_date"
                                                                    class="form-control fw-bold w-100"
                                                                    id="opening_balance_date"
                                                                    value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}"
                                                                    tabindex="-1" />
                                                            </td>
                                                            <td style="width: 30%" class="align-items-end">
                                                                <input readonly type="text"
                                                                    class="form-control w-100"
                                                                    value="{{ $openingBalance->user->prefix . ' ' . $openingBalance->user->name . ' ' . $openingBalance->user->last_name }}">
                                                                <input type="hidden" name="sr_user_ids[]"
                                                                    value="{{ $openingBalance->user_id }}">
                                                            </td>
                                                            <td style="width: 22.5%" class="align-items-end">
                                                                <input type="number" step="any"
                                                                    name="sr_opening_balances[]"
                                                                    class="form-control w-100 sr_initial_opening_balance"
                                                                    id="sr_opening_balance"
                                                                    value="{{ $openingBalance->amount }}"
                                                                    placeholder="0.00" />
                                                            </td>
                                                            <td style="width: 22.5%" class="align-items-end">
                                                                <select name="sr_opening_balance_types[]"
                                                                    class="form-control w-100"
                                                                    id="sr_opening_balance_type">
                                                                    <option value="debit">@lang('menu.debit')</option>
                                                                    <option
                                                                        {{ $openingBalance->balance_type == 'credit' ? 'SELECTED' : '' }}
                                                                        value="credit">@lang('menu.credit')</option>
                                                                </select>
                                                            </td>
                                                            <td style="width: 10%" class="text-center"
                                                                class="align-items-end">
                                                                <div class="row g-0">
                                                                    <div class="col-md-6">
                                                                        <a href="#"
                                                                            onclick="editAddNewRow(this); return false;"
                                                                            id="add_new_opening_balance_row"
                                                                            class="table_tr_add_btn ms-1 d-inline"><i
                                                                                class="fa-solid fa-plus text-success mt-1"></i></a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr id="sr_opening_balance_row">
                                                        <td style="width: 15%" class="align-items-end">
                                                            <input readonly type="text" name="opening_balance_date"
                                                                class="form-control fw-bold w-100"
                                                                id="opening_balance_date"
                                                                value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}"
                                                                tabindex="-1" />
                                                        </td>
                                                        <td style="width: 30%" class="align-items-end">
                                                            <select name="sr_user_ids[]"
                                                                class="form-control w-100 sr_user_id" id="sr_user_id">
                                                                <option value="">@lang('menu.select_sr')</option>
                                                                @foreach ($srUsers as $user)
                                                                    <option value="{{ $user->id }}">
                                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="width: 22.5%" class="align-items-end">
                                                            <input type="number" step="any"
                                                                name="sr_opening_balances[]"
                                                                class="form-control w-100 sr_initial_opening_balance"
                                                                id="sr_opening_balance" value="0.00"
                                                                placeholder="0.00" />
                                                        </td>
                                                        <td style="width: 22.5%" class="align-items-end">
                                                            <select name="sr_opening_balance_types[]"
                                                                class="form-control w-100"
                                                                id="sr_opening_balance_type">
                                                                <option value="debit">@lang('menu.debit')</option>
                                                                <option value="credit">@lang('menu.credit')</option>
                                                            </select>
                                                        </td>
                                                        <td style="width: 10%" class="text-center"
                                                            class="align-items-end">
                                                            <div class="row g-0">
                                                                <div class="col-md-6">
                                                                    <a href="#"
                                                                        onclick="editAddNewRow(this); return false;"
                                                                        id="add_new_opening_balance_row"
                                                                        class="table_tr_add_btn ms-1 d-inline"><i
                                                                            class="fa-solid fa-plus text-success mt-1"></i></a>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <a href="#" class="d-inline"
                                                                        tabindex="-1"><i
                                                                            class="fas fa-trash-alt text-secondary mt-1"></i></a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Customer Type :</b></label>
                                    <div class="col-sm-3">
                                        <select class=" form-control form-select" name="customer_type"
                                            id="customer_type">
                                            <option value="1"
                                                {{ $customer?->customer_type == 1 ? 'SELECTED' : '' }}>
                                                @lang('menu.non_credit')</option>
                                            <option value="2"
                                                {{ $customer?->customer_type == 2 ? 'SELECTED' : '' }}>
                                                @lang('menu.credit')</option>
                                        </select>
                                    </div>

                                    <label
                                        class="col-sm-2 term_hide {{ $customer?->customer_type == 1 ? 'd-none' : '' }}"><b>Credit
                                            Limit :</b></label>
                                    <div
                                        class="col-sm-3 term_hide {{ $customer?->customer_type == 1 ? 'd-none' : '' }}">
                                        <input type="number" name="credit_limit" class="form-control"
                                            id="credit_limit"
                                            value="{{ $customer?->credit_limit ? $customer?->credit_limit : 0 }}">
                                    </div>
                                </div>

                                <div
                                    class="form-group row p-1 term_hide {{ $customer?->customer_type == 1 ? 'd-none' : '' }}">
                                    <label class="col-sm-4"><b>Term :</b></label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <select class="form-control form-select" name="pay_term" id="pay_term">
                                                <option value="2"
                                                    {{ $customer?->pay_term == 2 ? 'SELECTED' : '' }}>Days</option>
                                                <option value="1"
                                                    {{ $customer?->pay_term == 1 ? 'SELECTED' : '' }}>Months</option>
                                            </select>
                                            <input type="text" name="pay_term_number" class="form-control"
                                                placeholder="Number" value="{{ $customer?->pay_term_number }}">
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
                            <div class="col-lg-10 offset-1 offset-1">
                                <h6 class="form-title">Details Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Date Of Birth</b></label>
                                    <div class="col-sm-8">
                                        <input type="date" id="e_date_of_birth" name="date_of_birth"
                                            class="form-control"
                                            value="{{ $customer->date_of_birth ? date('Y-m-d', strtotime($customer->date_of_birth)) : '' }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>@lang('menu.customer_groups') :</b></label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select" name="customer_group_id"
                                            id="customer_group_id">
                                            <option value="" selected>@lang('menu.none')</option>
                                            @foreach ($groups as $group)
                                                <option
                                                    {{ $customer->customer_group_id == $group->id ? 'SELECTED' : '' }}
                                                    value="{{ $group->id }}">{{ $group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Print Name:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->print_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Ledger Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_ledger_name" class="form-control"
                                            placeholder="Ledger Name"
                                            value="{{ $customer?->customerDetails?->print_ledger_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Ledger Code:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="print_ledger_code" class="form-control"
                                            value="{{ $customer?->customerDetails?->print_ledger_code }}"
                                            placeholder="Ledger Code">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Billing Account No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="billing_account" class="form-control"
                                            value="{{ $customer?->customerDetails?->billing_account }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Description :</b></label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control ckEditor" name="description" form="usrform" placeholder="Enter text here...">{{ $customer?->customerDetails?->description }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Customer Image :</b></label>
                                    <div class="col-sm-2">
                                        <input type="file" name="customer_file" id="customer_file"
                                            class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        @if ($customer->customerDetails?->customer_file != null)
                                            <img
                                                src="{{ asset('uploads/customer') . '/' . $customer->customerDetails?->customer_file }}">
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
                                    <label class="col-sm-4"><b> Add Document :</b></label>
                                    <div class="col-sm-4">
                                        <input type="file" name="customer_document[]" multiple
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
                            <h6 class="form-title">Customer Primary Contact Information</h6>

                            <div class="col-lg-10 offset-1">
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_mailing_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_mailing_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>NID No. :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nid_no" class="form-control"
                                            value="{{ $customer->nid_no }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1 trade_hide_big_modal">
                                    <label class="col-sm-4"><b>Permanent Address:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="permanent_address" class="form-control"
                                            value="{{ $customer?->customerDetails?->permanent_address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_post_office" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_post_office }}">
                                    </div>

                                    <label class="col-sm-2"><b>Post Code:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="zip_code" class="form-control"
                                            value="{{ $customer->zip_code }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_police_station" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_police_station }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="state" class="form-control"
                                            value="{{ $customer->state }}">
                                    </div>

                                    <label class="col-sm-2"><b>City:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="city" class="form-control"
                                            value="{{ $customer->city }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Country:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="country" class="form-control"
                                            value="{{ $customer->country }}">
                                    </div>

                                    <label class="col-sm-2"><b>Currency:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_currency" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_currency }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_telephone" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_telephone }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_fax" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_fax }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No:</b></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="primary_mobile" class="form-control"
                                            value="{{ $customer?->customerDetails?->primary_mobile }}">
                                    </div>

                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="contact_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes"
                                                {{ $customer?->customerDetails?->contact_send_sms == 'Yes' ? 'SELECTED' : '' }}>
                                                Yes</option>
                                            <option value="No"
                                                {{ $customer?->customerDetails?->contact_send_sms == 'No' ? 'SELECTED' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact_email" class="form-control"
                                            value="{{ $customer?->customerDetails?->contact_email }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-10 offset-1">
                                <h6 class="form-title">Mailing Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->mailing_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_address" class="form-control"
                                            value="{{ $customer?->customerDetails?->mailing_address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mailing_email" class="form-control"
                                            value="{{ $customer?->customerDetails?->mailing_email }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top:20px;">
                            <div class="col-lg-10 offset-1">
                                <h6 class="form-title">Shipping Information</h6>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mail Name:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->shipping_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_number" class="form-control"
                                            value="{{ $customer?->customerDetails?->shipping_number }}">
                                    </div>
                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="shipping_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes"
                                                {{ $customer?->customerDetails?->shipping_send_sms == 'Yes' ? 'SELECTED' : '' }}>
                                                Yes</option>
                                            <option value="No"
                                                {{ $customer?->customerDetails?->shipping_send_sms == 'No' ? 'SELECTED' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_email" class="form-control"
                                            value="{{ $customer?->customerDetails?->shipping_email }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="shipping_address" class="form-control"
                                            value="{{ $customer->shipping_address }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none aternativeContact" id="aternativeContact">
                    <div class="tab-content-inner">
                        <div class="row ">

                            <div class="col-lg-10 offset-1 ">
                                <h6 class="form-title">Alternative Contact</h6>
                                {{-- duplicate row start --}}

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mailing Name:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Telephone No:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="landline" class="form-control"
                                            value="{{ $customer->landline }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Alternative Phone No:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_phone" class="form-control"
                                            value="{{ $customer->alternative_phone }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Fax No:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_fax" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_fax }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Mobile No:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="known_person_phone" class="form-control"
                                            value="{{ $customer->known_person_phone }}">
                                    </div>

                                    <label class="col-sm-3"><b>Send SMS:</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control form-select" name="alternative_send_sms">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="Yes"
                                                {{ $customer?->customerDetails?->alternative_send_sms == 'Yes' ? 'SELECTED' : '' }}>
                                                Yes</option>
                                            <option value="No"
                                                {{ $customer?->customerDetails?->alternative_send_sms == 'No' ? 'SELECTED' : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Email Address</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_email" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_email }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Present Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_address" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_address }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Post Office:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_post_office" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_post_office }}">
                                    </div>

                                    <label class="col-sm-3"><b>Post Code:</b></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="alternative_zip_code" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_zip_code }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Police Station:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="alternative_police_station" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_police_station }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>State:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_state" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_state }}">
                                    </div>

                                    <label class="col-sm-2"><b>City:</b></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="alternative_city" class="form-control"
                                            value="{{ $customer?->customerDetails?->alternative_city }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b> Add Image :</b></label>
                                    <div class="col-sm-2">
                                        <input type="file" name="alternative_file" id="alternative_file"
                                            class="form-control file-color" />
                                    </div>

                                    <div class="col-sm-2">
                                        @if ($customer?->customerDetails?->alternative_file != null)
                                            <img class="alternative_file"
                                                src="{{ asset('uploads/customer/alternative/' . $customer?->customerDetails?->alternative_file) }}"
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

                <div class="tab_contant d-none ContactPersons" id="ContactPersons">
                    <div class="tab-content-inner">
                        <div class="row clonedata">
                            @if (count($customer->customerContactPersons) > 0)
                                @foreach ($customer->customerContactPersons as $k => $contact_person)
                                    <div class="col-lg-10 offset-1 ">
                                        <h6 class="form-title"> Contact Person</h6>
                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b> Name :</b></label>
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
                                            <label class="col-sm-4"><b>Mobile No :</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_phon[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_phon }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Dasignation:</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_dasignation[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_dasignation }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Telephone No:</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_landline[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_landline }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Alternative Phone No:</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_alternative_phone[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Fax No:</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_fax[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Email Address</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_email[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_email }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Present Address :</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_address[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_address }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Post Office:</b></label>
                                            <div class="col-sm-3">
                                                <input type="text" name="contact_person_post_office[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_post_office }}">
                                            </div>

                                            <label class="col-sm-3"><b>Post Code:</b></label>
                                            <div class="col-sm-2">
                                                <input type="text" name="contact_person_zip_code[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_zip_code }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>Police Station:</b></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="contact_person_police_station[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_police_station }}">
                                            </div>
                                        </div>

                                        <div class="form-group row p-1">
                                            <label class="col-sm-4"><b>State:</b></label>
                                            <div class="col-sm-3">
                                                <input type="text" name="contact_person_state[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_state }}">
                                            </div>
                                            <label class="col-sm-2"><b>City:</b></label>
                                            <div class="col-sm-3">
                                                <input type="text" name="contact_person_city[]"
                                                    class="form-control"
                                                    value="{{ $contact_person->contact_person_city }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-10 offset-1 ">
                                    <h6 class="form-title"> Contact Person</h6>
                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b> Name :</b></label>
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
                                        <label class="col-sm-4"><b>Mobile No :</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_phon[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Dasignation:</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_dasignation[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Telephone No:</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_landline[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Alternative Phone No:</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_alternative_phone[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Fax No:</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_fax[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Email Address</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_email[]" class="form-control"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Present Address :</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_address[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Post Office:</b></label>
                                        <div class="col-sm-3">
                                            <input type="text" name="contact_person_post_office[]"
                                                class="form-control" value="">
                                        </div>

                                        <label class="col-sm-3"><b>Post Code:</b></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="contact_person_zip_code[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>Police Station:</b></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="contact_person_police_station[]"
                                                class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row p-1">
                                        <label class="col-sm-4"><b>State:</b></label>
                                        <div class="col-sm-3">
                                            <input type="text" name="contact_person_state[]" class="form-control"
                                                value="">
                                        </div>

                                        <label class="col-sm-2"><b>City:</b></label>
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
                                    <label class="col-sm-4"><b>TIN No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tin_number" class="form-control"
                                            value="{{ $customer?->customerDetails?->tax_number }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>TAX No :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_number" class="form-control"
                                            value="{{ $customer?->customerDetails?->tin_number }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Name:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->tax_name }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Category :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_category" class="form-control"
                                            value="{{ $customer?->customerDetails?->tax_category }}">
                                    </div>
                                </div>
                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Address :</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tax_address" class="form-control"
                                            value="{{ $customer?->customerDetails?->tax_address }}">
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
                                    <label class="col-sm-4"><b>Bank Name:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->bank_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank A/C Number:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_A_C_number" class="form-control"
                                            value="{{ $customer?->customerDetails?->bank_A_C_number }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Currency:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_currency" class="form-control"
                                            value="{{ $customer?->customerDetails?->bank_currency }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Bank Branch:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_branch" class="form-control"
                                            value="{{ $customer?->customerDetails?->bank_branch }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant d-none SalesPartner" id="SalesPartner">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-lg-10 offset-1">
                                <h6 class="form-title">Sales Partner And Commissions</h6>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Partner:</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="partner_name" class="form-control"
                                            value="{{ $customer?->customerDetails?->partner_name }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Percentage (%):</b></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="percentage" class="form-control"
                                            value="{{ $customer?->customerDetails?->percentage }}">
                                    </div>
                                </div>

                                <div class="form-group row p-1">
                                    <label class="col-sm-4"><b>Sales Team:</b></label>
                                    <div class="col-sm-8">
                                        <!-- <input type="text" class="form-control"> -->
                                        <select class="form-control form-select" name="sales_team" style="">
                                            <option value="" selected>Select Sales Teat</option>
                                            <option value="1">Teat Name 1</option>
                                            <option value="2">Teat Name 2</option>
                                            <option value="3">Teat Name 3</option>
                                            <option value="4">Teat Name 4</option>
                                            <option value="5">Teat Name 5</option>
                                        </select>
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
    var srUsers = @json($srUsers)

    $('#sr_user_id').select2();

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('change', '#customer_type', function() {

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

        // file preview code start
        $("#customer_file").change(function() {
            // alert('ok')
            var file = $("#customer_file").get(0).files[0];

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

        // file preview code end

        // add row code ====

        var i = 1;
        var existContactPerson = "<?php echo count($customer->customerContactPersons); ?>";

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

    $('#edit_customer_form').on('submit', function(e) {
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

                $('.error').html('');
                toastr.success(data);
                $('.loading_button').hide();
                table.ajax.reload(null, false);
                $('#editModal').modal('hide');
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                    toastr.error(error[0]);
                });
            }
        });
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

<script>
    var count = 0;

    function editAddNewRow(val) {

        var tr = '';
        var main = $('.main_select_box').html();
        tr += '<tr id="sr_opening_balance_row" class="user' + count + '">';

        tr += '<td style="width: 15%" class="align-items-end">';
        tr +=
            '<input readonly type="text" name="opening_balance_date" class="form-control fw-bold w-100" id="sr_opening_balance_date" value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1"/>';
        tr += '</td>';

        tr += '<td style="width: 30%" class="align-items-end">';
        tr += '<select required name="sr_user_ids[]" class="form-control my-select2 w-100 sr_user_id" id="sr_user_id' +
            count + '" autofocus>';
        tr += '<option value="">Select Sr.</option>';
        srUsers.forEach(function(user) {
            tr += '<option value="' + user.id + '">' + user.prefix + ' ' + user.name + ' ' + user.last_name +
                '</option>';
        });
        tr += '</select>';
        tr += '</td>';

        tr += '<td style="width: 22.5%" class="align-items-end">';
        tr +=
            '<input required type="number" step="any" name="sr_opening_balances[]" class="form-control w-100" id="sr_opening_balance" value="0.00" placeholder="0.00"/>';
        tr += '</td>';

        tr += '<td style="width: 22.5%" class="align-items-end">';
        tr +=
            '<select name="sr_opening_balance_types[]" class="form-control w-100 form-select" id="sr_opening_balance_type">';
        tr += '<option value="debit">@lang('menu.debit')</option>';
        tr += '<option value="credit">@lang('menu.credit')</option>';
        tr += '</select>';
        tr += '</td>';

        tr += '<td style="width: 10%" class="text-center" class="align-items-end">';
        tr += '<div class="row g-0">';
        tr += '<div class="col-md-6">';
        tr +=
            '<a href="#" onclick="editAddNewRow(this); return false;" id="add_new_opening_balance_row" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
        tr += '</div>';

        tr += '<div class="col-md-6">';
        tr +=
            '<a href="#" id="remove_row_btn" class="table_tr_remove_btn d-inline"><i class="fas fa-trash-alt text-danger mt-1"></i></a>';
        tr += '</div>';
        tr += '</div>';
        tr += '</td>';

        tr += '</tr>';

        $('#sr_body').append(tr);
        $('#sr_user_id' + count, '#sr_body').select2();
        count++;
    }

    $(document).on('click', '#remove_row_btn', function(e) {
        e.preventDefault();

        var tr = $(this).closest('tr');
        previousTr = tr.prev();
        nxtTr = tr.next();
        tr.remove();
    });
</script>
