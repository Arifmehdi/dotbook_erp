@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- select 2 --}}
    

    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }
    </style>
@endpush

<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content" style="margin-left: 12%;width: 90%;">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Edit Proposal</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_mail_send" action="{{ route('crm.proposal_template.update', $proposal_template->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row" id="to_area">

                    @php

                        $cc = json_decode($proposal_template->cc, true);
                        $ccCount = count($cc);
                        $ccString = '';
                        foreach ($cc as $key => $value) {
                            $ccString .= $value;
                            if ($key != $ccCount - 1) {
                                $ccString .= ',';
                            }
                        }

                    @endphp


                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"><strong>CC</strong> </label>
                            <div class="col-md-9">
                                <input type="text" name="cc" class="form-control" data-name="To" id="to"
                                    placeholder="CC" value="{{ $ccString }}" />
                                <span><small>Comma separated values of emails</small></span>
                                <span class="error error_to"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"><strong>BCC</strong> </label>
                            <div class="col-md-9">
                                <input type="text" name="bcc" class="form-control" data-name="To" id="bcc"
                                    placeholder="BCC"
                                    value="@php
$bcc = json_decode($proposal_template->bcc, true);
                                    $last_key = count($bcc);
                                    foreach($bcc as $key=> $value){
                                    echo $value;
                                    if($key != $last_key - 1){
                                    echo ',';
                                    }
                                    } @endphp" />
                                <span><small>Comma separated values of emails</small></span>
                                <span class="error error_to"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"><strong>Subject<span
                                        class="text-danger">*</span></strong> </label>
                            <div class="col-md-9">
                                <input required type="text" name="subject" class="form-control" data-name="Subject"
                                    id="subject" placeholder="Subject" value="{{ $proposal_template->subject }}" />
                                <span class="error error_subject"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="rel_type"><strong>Related</strong>
                            </label>
                            <div class="col-md-9">
                                <select name="rel_type" id="rel_type" class="form-control "
                                    data-none-selected-text="Nothing selected">
                                    <option value="lead"
                                        {{ $proposal_template->related == 'lead' ? 'selected' : '' }}>
                                        Lead</option>
                                    <option value="customer"
                                        {{ $proposal_template->related == 'customer' ? 'selected' : '' }}>Customer
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="customer_leads"><strong
                                    class="customer_leads_label">Customer/Leads</strong> </label>
                            <div class="col-md-9">
                                <select name="customer_leads" id="customer_leads" class="form-control"
                                    data-none-selected-text="Nothing selected">
                                    <option
                                        value="{{ $proposal_template->related == 'lead' ? $proposal_template->lead_id : $proposal_template->customer_id }}">
                                        {{ $proposal_template->related == 'lead' ? $proposal_template->lead_id : $proposal_template->customer_id }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="proposal_date"><strong>Date <i
                                        class="fa-regular fa-calendar calendar-icon"></i></strong> </label>
                            <div class="col-md-9">
                                <input type="text" id="proposal_date" name="date" class="form-control"
                                    autocomplete="off" value="{{ $proposal_template->date }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="oprn_till_date"><strong>Open Till <i
                                        class="fa-regular fa-calendar calendar-icon"></i></strong> </label>
                            <div class="col-md-9">
                                <input type="text" id="oprn_till_date" name="open_till" class="form-control"
                                    autocomplete="off" value="{{ $proposal_template->open_till }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="currency"><strong>currency</strong>
                            </label>
                            <div class="col-md-9">
                                <select name="currency" id="currency" class="form-control " data-show-subtext="1"
                                    data-base="1" data-width="100%" data-none-selected-text="Nothing selected"
                                    data-live-search="true">
                                    <option value="1" {{ $proposal_template->currency == 1 ? 'selected' : '' }}
                                        selected data-subtext="$">USD</option>
                                    <option value="2" {{ $proposal_template->currency == 2 ? 'selected' : '' }}
                                        data-subtext="€">EUR</option>
                                    <option value="3" {{ $proposal_template->currency == 3 ? 'selected' : '' }}
                                        data-subtext="DH">MAD</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"
                                for="discount_before_after_tax"><strong>Discount Type</strong> </label>
                            <div class="col-md-9">
                                <select name="discount_before_after_tax" id="discount_before_after_tax"
                                    class="form-control " data-show-subtext="1" data-base="1" data-width="100%"
                                    data-none-selected-text="Nothing selected" data-live-search="true">
                                    <option value="after_tax"
                                        {{ $proposal_template->discount_type == 'after_tax' ? 'selected' : '' }}>After
                                        Tax</option>
                                    <option value="before_tax"
                                        {{ $proposal_template->discount_type == 'before_tax' ? 'selected' : '' }}>
                                        Before
                                        Tax</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"><strong><i class="fa fa-tag"
                                        aria-hidden="true"></i></strong> </label>
                            <div class="col-md-9">
                                <input type="text" class="selectize_tags form-control" id="tags"
                                    name="tags" value="{{ $proposal_template->tags }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="status"><strong>Status</strong>
                            </label>
                            <div class="col-md-9">
                                <select name="status" class=" form-control" data-width="100%"
                                    data-none-selected-text="Nothing selected">
                                    <option value="1" {{ $proposal_template->status == 1 ? 'selected' : '' }}>
                                        Open
                                    </option>
                                    <option value="2" {{ $proposal_template->status == 2 ? 'selected' : '' }}>
                                        Declined</option>
                                    <option value="3" {{ $proposal_template->status == 3 ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="4" {{ $proposal_template->status == 4 ? 'selected' : '' }}>
                                        Sent
                                    </option>
                                    <option value="5" {{ $proposal_template->status == 5 ? 'selected' : '' }}>
                                        Revised</option>
                                    <option value="6" {{ $proposal_template->status == 6 ? 'selected' : '' }}>
                                        Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="assigned"><strong>Assigned</strong>
                            </label>
                            <div class="col-md-9">
                                <select id="assigned" name="assigned" class=" form-control" data-width="100%"
                                    data-none-selected-text="Nothing selected" data-live-search="true">
                                    <option value=""></option>
                                    <option value="1" {{ $proposal_template->assigned == 1 ? 'selected' : '' }}>
                                        Demo User 1</option>
                                    <option value="2" {{ $proposal_template->assigned == 2 ? 'selected' : '' }}>
                                        Demo User 2</option>
                                    <option value="3" {{ $proposal_template->assigned == 3 ? 'selected' : '' }}>
                                        Demo User 3</option>
                                    <option value="4" {{ $proposal_template->assigned == 4 ? 'selected' : '' }}>
                                        Demo User 4</option>
                                    <option value="5" {{ $proposal_template->assigned == 5 ? 'selected' : '' }}>
                                        Demo User 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"><strong>Attachment</strong> </label>
                            <div class="col-md-9">
                                <input type="file" name="proposal_file[]" class="form-control" data-name="file"
                                    id="file" placeholder="Attachments" multiple />
                                <span class="d-block" style="line-height: 20px;font-size: 10px;">Max File Size :
                                    5Mb</span>
                                <span class="d-block" style="line-height: 12px;font-size: 10px;">Allowed File: .pdf,
                                    .csv,
                                    .zip, .doc, .docx, .jpeg, .jpg, .png</span>
                                <span class="error error_file"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="status"><strong>Item</strong>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <select name="item_id" id="item_id" class="form-control form-select">
                                        <option value="">Select Item</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text add_button" id="addMaterialItem"><i
                                            class="fas fa-plus-square text-dark"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="proposal_to"><strong>Name</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="proposal_to" name="proposal_to" class="form-control"
                                    value="{{ $proposal_template->to }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="email"><strong>Email</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="email" name="email" class="form-control"
                                    value="{{ $proposal_template->email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="phone"><strong>Phone</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="phone" name="phone" class="form-control"
                                    value="{{ $proposal_template->phone }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="address"><strong>Address</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="address" name="address" class="form-control"
                                    value="{{ $proposal_template->address }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="city"><strong>City</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="city" name="city" class="form-control"
                                    value="{{ $proposal_template->city }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="state"><strong>State</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="state" name="state" class="form-control"
                                    value="{{ $proposal_template->state }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="zip"><strong>Zip Code</strong>
                            </label>
                            <div class="col-md-9">
                                <input type="text" id="zip" name="zip" class="form-control"
                                    value="{{ $proposal_template->zip }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end" for="country"><strong>country</strong>
                            </label>
                            <div class="col-md-9">
                                <select id="country" name="country" class=" form-control" data-width="100%"
                                    data-none-selected-text="Nothing selected" data-live-search="true">
                                    <option value="1" {{ $proposal_template->country == 1 ? 'selected' : '' }}
                                        data-subtext="BN">Bangladesh</option>
                                    <option value="2" {{ $proposal_template->country == 2 ? 'selected' : '' }}
                                        data-subtext="IN">India</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-end"><strong>Body </strong></label>
                            <div class="col-md-9">
                                <textarea id="editor" name="description" id="description"></textarea>
                                <span class="error error_description"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Short Description</th>
                                    <th>Long Description</th>
                                    <th>Qty</th>
                                    <th>Exc. Rate</th>
                                    <th>Exc./Inc. </th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th><i class="fa fa-cog" aria-hidden="true"></i></th>
                                    {{-- <th><button type="button" class="btn btn-primary btn-sm  mb-2" id="addMoreButton"><i class="fas fa-plus px-1"></i></button></th> --}}
                                </tr>
                            </thead>
                            <tbody id="dup_tr"></tbody>
                            <tfoot>
                                <tr rowspan="3">
                                    <td colspan="8">Sub-total</td>
                                    <td><input type="text" name="sub_total" class="form-control" id="sub_total"
                                            readonly></td>
                                </tr>
                                <tr>
                                    <td colspan="8">Discount</td>
                                    <td><input type="text" name="discount_sub_total" class="form-control"
                                            id="discount"></td>
                                </tr>
                                <tr>
                                    <td colspan="8">Total</td>
                                    <td id=""><input type="text" name="total_calculate"
                                            class="form-control" id="total_calculate" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                        class="fas fa-spinner"></i></button>
                                <button type="submit"
                                    class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
    integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
    integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).on('change', '#item_id', function(e) {
        var item_id = $(this).val();
        var url = "{{ route('crm.proposal_template.product-info', ['item_id' => ':item_id']) }}";
        url = url.replace(':item_id', item_id);
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                if (data.id != null) {
                    const container = document.getElementById('dup_tr');
                    const child = ` <tr class="dup_tr">
                                        <td><textarea name="name[]" class="form-control ckEditor" id="name_short_description" cols="30" rows="3">${data.name}</textarea></td>
                                        <td><textarea name="details[]" class="form-control ckEditor" id="long_description" cols="30" rows="3">${data.product_details}</textarea></td>
                                        <td>
                                            <input type="number" name="qty[]" class="form-control" id="qty_${data.id}" value="1" onkeyup="handleKeyUp(${data.id})">
                                            <input type="hidden" name="item_id[]" value="${data.id}" >
                                        </td>
                                        <td><input type="text" name="rate[]" class="form-control" id="rate_${data.id}" value="${data.product_price}" onkeyup="handleKeyUp(${data.id})"></td>
                                        <td>
                                            <select name="tax_type[]" id="tax_type_${data.id}" class="form-control form-select" tabindex="-1" onchange="handleKeyUp(${data.id})">
                                                <option value="1">@lang('menu.exclusive')</option>
                                                <option value="2">@lang('menu.inclusive')</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-select" name="tax[]" id="tax_${data.id}" onchange="handleKeyUp(${data.id})">
                                                <option value="0"> N/A</option>
                                                <option value="2">Tax 2%</option>
                                                <option value="2.5">Tax 2.5%</option>
                                                <option value="3">Tax 3%</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="discount[]" id="discount_${data.id}" placeholder="@lang('menu.discount')" value="0.00" onkeyup="handleKeyUp(${data.id})">
                                        </td>
                                        <td>
                                            <select class="form-control form-select" name="discount_type[]" id="discount_type_${data.id}" onchange="handleKeyUp(${data.id})">
                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                <option value="2">@lang('menu.percentage')(%)</option>
                                            </select>
                                        </td>

                                        <td><input type="text" name="amount[]" class="form-control sub_total_amount" id="amount_${data.id}" value="${data.product_price * 1}" ></td>
                                        <td>
                                            <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.remove()"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>`;
                    container.insertAdjacentHTML('beforeend', child);
                    amount = calculateItemWiseAmount(data.id);
                    totalAmountCalculations(amount);
                }

            },
            error: function(data) {
                toastr.error(data.responseJSON)
            }
        });
    });
    var in_amount = 0;

    function totalAmountCalculations(amount) {
        var request = $('#proposal_template_send').serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.proposal_template.total') }}",
            data: request,
            success: function(data) {

                $('#sub_total').val(data);
                $('#total_calculate').val(data);
            }
        });
    }

    $('#discount').on('keyup', function() {
        in_discount = $('#sub_total').val() - $(this).val();
        $('#total_calculate').val(in_discount);
    });

    function handleKeyUp(id) {
        amount = calculateItemWiseAmount(id);
        document.getElementById('amount_' + id).value = amount;
        totalAmountCalculations(amount);
    }

    function calculateItemWiseAmount(id) {
        var amount = 0;
        var tax_type = document.getElementById('tax_type_' + id).value;
        var tax = document.getElementById('tax_' + id).value;
        var discount_amount = document.getElementById('discount_' + id).value;
        var discount_type = document.getElementById('discount_type_' + id).value;
        var qty = document.getElementById('qty_' + id).value;
        var rate = document.getElementById('rate_' + id).value;
        amount = rate * qty;
        if (discount_amount > 0) {
            if (discount_type == 1) {
                amount -= discount_amount;
            } else {
                amount -= (amount / 100) * discount_amount;
            }
        }
        if (tax != 0) {
            if (tax_type == 1) {
                amount += amount / 100 * tax;
            } else {
                amount -= amount / 100 * tax;
            }
        }
        return amount;
    }

    $('#addMaterialItem').on('click', function() {
        $.get("{{ route('crm.proposal_template.add.product.modal.view') }}", function(data) {
            $('#add_material_modal_body').html(data);
            $('#addMaterialItemModal').modal('show');
        });
    });

    $(document).on('change', '#rel_type', function(e) {
        var rel_type = $(this).val();
        var url = "{{ route('crm.proposal_template.leads-customers', ['rel_type' => ':rel_type']) }}";
        url = url.replace(':rel_type', rel_type);
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#customer_leads').empty();
                $('.customer_leads_label').text("");
                $('.customer_leads_label').text(data.type);
                $.each(data.leadsUser, function(key, value) {
                    $('#customer_leads').append("<option value=" + value.id + ">" + value
                        .name + "</option>");
                });
            },
            error: function(data) {
                toastr.error(data.responseJSON)
            }
        });
    });

    $(document).on('change', '#customer_leads', function(e) {
        var customer_leads_val = $(this).val();
        var url =
            "{{ route('crm.proposal_template.leads-address', ['customer_leads_val' => ':customer_leads_val', 'rel_type' => ':rel_type']) }}";
        url = url.replace(':customer_leads_val', customer_leads_val);
        url = url.replace(':rel_type', $('.customer_leads_label').text());
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#proposal_to').val("");
                $('#email').val("");
                $('#phone').val("");
                $('#address').val("");
                $('#city').val("");
                $('#state').val("");
                $('#zip').val("");
                $('#country').empty();
                if (data.type == "lead") {
                    $('#proposal_to').val(data.address.name);
                    $('#email').val(data.address.email_addresses);
                    $('#phone').val(data.address.phone_numbers);
                    $('#address').val(data.address.address);
                } else {
                    $('#proposal_to').val(data.address.name);
                    $('#email').val(data.address.email);
                    $('#phone').val(data.address.phone);
                    $('#address').val(data.address.address);
                    $('#city').val(data.address.city);
                    $('#zip').val(data.address.zip_code);
                    $('#country').append("<option value=" + data.address.country + ">" + data
                        .address.country + "</option>");
                    $('#state').val(data.address.state);
                }
            },
            error: function(data) {
                toastr.error(data.responseJSON)
            }
        });
    });

    $(document).on('click', '#country', function(event) {
        var countries = `<option value=""></option>
                        <option value="1" data-subtext="BN">Bangladesh</option>
                        <option value="2" data-subtext="IN">India</option>`;
        $('#country').empty().append(countries);
    })


    ClassicEditor.create(document.querySelector('#e_editor')).then(editor => {

        })
        .catch(error => {
            console.error(error);
        });

    $('.select2').select2({
        placeholder: "Select Leads",
        allowClear: true
    });

    $('.deleteattachment').click(function() {
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                toastr.error(data.message);
            },
            error: function(data) {
                toastr.error(data.message);
                return;
            }
        });
        $(this).parent().hide();
    });

    $('#edit_mail_send').on('submit', function(e) {
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
            success: function(data) {

                $('.loading_button').hide();
                toastr.success(data);
                $('#editModal').modal('hide');
                $('.TemplateTable').DataTable().ajax.reload();
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
