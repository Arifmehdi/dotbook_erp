@extends('layout.master')
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
@section('title', 'CRM - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Proposal Template For Send</h6>
                </div>
                <x-all-buttons>
                    <x-add-button />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-12">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table TemplateTable">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.action')</th>
                                            <th class="text-start">Name</th>
                                            <th class="text-start">Type</th>
                                            <th class="text-start">@lang('crm.subject')</th>
                                            <th class="text-start">@lang('crm.body')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 12%;width: 90%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">New Proposal</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="proposal_template_send" action="{{ route('crm.proposal.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row" id="to_area">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>CC</strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="cc" class="form-control" data-name="To"
                                            id="to" placeholder="CC" />
                                        <span><small>Comma separated values of emails</small></span>
                                        <span class="error error_to"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>BCC</strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="bcc" class="form-control" data-name="To"
                                            id="bcc" placeholder="BCC" />
                                        <span><small>Comma separated values of emails</small></span>
                                        <span class="error error_to"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>Subject<span
                                                class="text-danger">*</span></strong> </label>
                                    <div class="col-md-9">
                                        <input required type="text" name="subject" class="form-control"
                                            data-name="Subject" id="subject" placeholder="Subject" />
                                        <span class="error error_subject"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="rel_type"><strong>Related</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <select name="rel_type" id="rel_type" class="form-control "
                                            data-none-selected-text="Nothing selected">
                                            <option value=""></option>
                                            <option value="lead">Lead</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="customer_leads"><strong
                                            class="customer_leads_label">Customer/Leads</strong> </label>
                                    <div class="col-md-9">
                                        <select name="customer_leads" id="customer_leads" class="form-control"
                                            data-none-selected-text="Nothing selected">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="proposal_date"><strong>Date <i
                                                class="fa-regular fa-calendar calendar-icon"></i></strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" id="proposal_date" name="date" class="form-control"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="oprn_till_date"><strong>Open Till
                                            <i class="fa-regular fa-calendar calendar-icon"></i></strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" id="oprn_till_date" name="open_till" class="form-control"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"
                                        for="currency"><strong>currency</strong> </label>
                                    <div class="col-md-9">
                                        <select name="currency" id="currency" class="form-control "
                                            data-show-subtext="1" data-base="1" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value=""></option>
                                            <option value="1" selected data-subtext="$">USD</option>
                                            <option value="2" data-subtext="€">EUR</option>
                                            <option value="3" data-subtext="DH">MAD</option>
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
                                            <option value="after_tax">After Tax</option>
                                            <option value="before_tax">Before Tax</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong><i class="fa fa-tag"
                                                aria-hidden="true"></i></strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" class="selectize_tags form-control" id="tags"
                                            name="tags" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="status"><strong>Status</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <select name="status" class=" form-control" data-width="100%"
                                            data-none-selected-text="Nothing selected">
                                            <option value="1">Open</option>
                                            <option value="2">Declined</option>
                                            <option value="3">Accepted</option>
                                            <option value="4">Sent</option>
                                            <option value="5">Revised</option>
                                            <option value="6">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"
                                        for="assigned"><strong>Assigned</strong> </label>
                                    <div class="col-md-9">
                                        <select id="assigned" name="assigned" class=" form-control" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>Attachment</strong> </label>
                                    <div class="col-md-9">
                                        <input type="file" name="proposal_file[]" class="form-control"
                                            data-name="file" id="file" placeholder="Attachments" multiple />
                                        <span class="d-block" style="line-height: 20px;font-size: 10px;">Max File Size :
                                            5Mb</span>
                                        <span class="d-block" style="line-height: 12px;font-size: 10px;">Allowed File:
                                            .pdf, .csv,
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
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="email"><strong>Email</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="email" name="email" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="phone"><strong>Phone</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="address"><strong>Address</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="address" name="address" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="city"><strong>City</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="city" name="city" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="state"><strong>State</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="state" name="state" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="zip"><strong>Zip
                                            Code</strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" id="zip" name="zip" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="country"><strong>country</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <select id="country" name="country" class=" form-control" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value="0">Select Country</option>
                                            <option value="1" data-subtext="BN">Bangladesh</option>
                                            <option value="2" data-subtext="IN">India</option>
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
                                        </tr>
                                    </thead>
                                    <tbody id="dup_tr"></tbody>
                                    <tfoot>
                                        <tr rowspan="3">
                                            <td colspan="7"></td>
                                            <td>Sub-total</td>
                                            <td><input type="text" name="sub_total" class="form-control"
                                                    id="sub_total" readonly></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td>Discount</td>
                                            <td><input type="text" name="discount_sub_total" class="form-control"
                                                    id="discount"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td>Total</td>
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
                                        <button type="submit" id="save_and_sent"
                                            class="w-auto btn btn-sm btn-success me-2 submit_button" data-status="4"
                                            value="save_and_sent"> Sent</button>
                                        <button type="submit" class="w-auto btn btn-sm btn-danger submit_button"
                                            data-status="1" value="save">@lang('menu.reset_data')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Item Modal -->
    <div class="modal fade" id="addMaterialItemModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Item</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_material_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Add Material Item Modal End-->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.onkeyup = function(e) {
            //var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {
                $('#save_and_sent').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {
                $('#save').click();
                return false;
            }
        }

        ClassicEditor.create(document.querySelector('#editor')).then(editor => {

            })
            .catch(error => {
                console.error(error);
            });

        $('.select2').select2({
            placeholder: "Select Leads",
            allowClear: true
        });

        $('#addMaterialItem').on('click', function() {
            $.get("{{ route('crm.proposal_template.add.product.modal.view') }}", function(data) {
                $('#add_material_modal_body').html(data);
                $('#addMaterialItemModal').modal('show');
            });
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('oprn_till_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 2,
                maxYear: new Date().getFullYear() + 10,
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

        new Litepicker({
            singleMode: true,
            element: document.getElementById('proposal_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 2,
                maxYear: new Date().getFullYear() + 10,
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

        $(document).ready(function() {
            var table = $('.TemplateTable').DataTable({
                processing: true,
                dom: "lBfrtip",
                serverSide: true,
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>Save as Excel',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, ],
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: "{{ route('crm.proposal.index') }}",
                columns: [{
                    data: 'action',
                    name: 'action'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'type',
                    name: 'type'
                }, {
                    data: 'subject',
                    name: 'subject'
                }, {
                    data: 'body',
                    name: 'body'
                }, ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            $('#proposal_template_send').on('submit', function(e) {
                e.preventDefault();
                var d = $(this);
                $('.loading_button').show();
                var url = $(this).attr('action');
                // var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#addModal').modal('hide');
                        $('#proposal_template_send')[0].reset();
                        $('.loading_button').hide();
                        $('.TemplateTable').DataTable().ajax.reload();
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');
                        $('.submit_button').prop('type', 'submit');

                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#editModal').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();

                });
            });

            // delete part
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-primary',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-danger',
                            'action': function() {


                            }
                        }
                    }
                });
            });

            //data delete by ajax
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        table.ajax.reload();
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg);
                            return;
                        }
                        toastr.error(data.responseJSON);
                    },
                    error: function(data) {
                        toastr.error(data.responseJSON)
                        asset_table.ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
