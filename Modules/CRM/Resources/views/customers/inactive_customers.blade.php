@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                    <h6>Customers</h6>
                </div>
                <div class="d-flex">
                    <div id="exportButtonsContainer">

                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white btn-sm"><i
                                class="fa-thin fa-circle-plus fa-2x"></i><br>Add New</a>

                    </div>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span
                            class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                            class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                    </a>
                </div>
            </div>

        </div>

        <div class="p-15">
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table customerTable">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.action')</th>
                                            <th class="text-start">@lang('menu.customer_id')</th>
                                            <th class="text-start">@lang('menu.name')</th>
                                            <th class="text-start">@lang('menu.phone')</th>
                                            <th class="text-start">@lang('menu.group')</th>
                                            <th class="text-start">@lang('menu.customer_type')</th>
                                            <th class="text-start">@lang('menu.credit_limit')</th>
                                            <th class="text-start">@lang('menu.opening_balance')</th>
                                            <th class="text-start">@lang('menu.debit')</th>
                                            <th class="text-start">@lang('menu.credit')</th>
                                            <th class="text-start">@lang('menu.closing_balance')</th>
                                            <th class="text-start">@lang('menu.status')</th>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_customer_form" action="{{ route('crm.customers.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                                <input type="text" required name="name" class="form-control add_input"
                                    data-name="Name" id="name" placeholder="@lang('menu.name')" />
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.phone') </strong> <span class="text-danger">*</span></label>
                                <input type="phone" required name="phone" class="form-control add_input"
                                    data-name="Phone" id="phone" placeholder="Phone" />
                                <span class="error error_phone"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.customer_id') </strong> <i data-bs-toggle="tooltip"
                                        data-bs-placement="right" title="Leave empty to auto generate."
                                        class="fas fa-info-circle tp"></i></label>
                                <input type="text" name="contact_id" class="form-control"
                                    placeholder="@lang('menu.customer_id')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.business_name')</strong></label>
                                <input type="text" name="business_name" class="form-control"
                                    placeholder="@lang('menu.business_name')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.alternative_number') </strong> </label>
                                <input type="text" name="alternative_phone" class="form-control"
                                    placeholder="Alternative phone number" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.landline') </strong></label>
                                <input type="text" name="landline" class="form-control"
                                    placeholder="Landline Number" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.email') </strong></label>
                                <input type="text" name="email" class="form-control"
                                    placeholder="@lang('menu.email_address')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.tax_number') </strong></label>
                                <input type="text" name="tax_number" class="form-control"
                                    placeholder="@lang('menu.tax_number')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.nid_no') </strong> </label>
                                <input type="text" name="nid_no" id="nid_no" class="form-control"
                                    placeholder="@lang('menu.nid_no')" />
                                <span class="error error_nid_no"></span>
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.trade_license_no') </strong></label>
                                <input type="text" name="trade_license_no" id="trade_license_no" class="form-control"
                                    placeholder="@lang('menu.trade_license_no')" />
                                <span class="error error_trade_license_no"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.known_person') </strong></label>
                                <input type="text" name="known_person" class="form-control"
                                    placeholder="@lang('menu.known_person')" />
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.known_person_phone') </strong></label>
                                <input type="text" name="known_person_phone" class="form-control"
                                    placeholder="@lang('menu.known_person')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.opening_balance') </strong> </label>
                                <div class="input-group">
                                    <input type="number" step="any" name="opening_balance"
                                        class="form-control w-65" id="opening_balance"
                                        placeholder="@lang('menu.opening_balance')" />
                                    <select name="balance_type" class="form-control w-35 form-select">
                                        <option value="debit">@lang('menu.debit')</option>
                                        <option value="credit">@lang('menu.credit')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.customer_type') </strong></label>
                                <select name="customer_type" class="form-control form-select" id="customer_type">
                                    <option value="1">@lang('menu.non_credit')</option>
                                    <option value="2">@lang('menu.credit')</option>
                                </select>
                            </div>

                            <div class="col-xl-3 col-md-6 hidable d-none">
                                <label><strong>@lang('menu.credit_limit') </strong> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="credit_limit" id="credit_limit"
                                    class="form-control" data-name="Credit limit" placeholder="@lang('menu.credit_limit')" />
                                <span class="error error_credit_limit"></span>
                            </div>

                            <div class="col-xl-3 col-md-6 hidable d-none">
                                <label><strong>@lang('menu.pay_term') </strong> </label>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="pay_term_number" class="form-control"
                                                placeholder="Number" />
                                        </div>

                                        <div class="col-md-7">
                                            <select name="pay_term" class="form-control form-select">
                                                <option value="1">@lang('menu.select_term')</option>
                                                <option value="2">@lang('menu.days') </option>
                                                <option value="3">@lang('menu.months')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.customer_group') </strong> </label>
                                <select name="customer_group_id" class="form-control form-select" id="customer_group_id">
                                    <option value="">@lang('menu.none')</option>
                                    @foreach ($customer_group as $group)
                                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.date_of_birth') </strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-calendar-week input_f"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control"
                                        autocomplete="off" placeholder="YYYY-MM-DD">
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <label><strong>@lang('menu.address') </strong> </label>
                                <input type="text" name="address" class="form-control" placeholder="Address">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.city')</strong> </label>
                                <input type="text" name="city" class="form-control" placeholder="City" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.state') </strong> </label>
                                <input type="text" name="state" class="form-control"
                                    placeholder="@lang('menu.state')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.country') </strong> </label>
                                <input type="text" name="country" class="form-control"
                                    placeholder="@lang('menu.country')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.zip_code') </strong> </label>
                                <input type="text" name="zip_code" class="form-control" placeholder="zip_code" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-6">
                                <label><strong>@lang('menu.shipping_address') </strong> </label>
                                <input type="text" name="shipping_address" class="form-control"
                                    placeholder="@lang('menu.shipping_address')" />
                            </div>
                        </div>

                        <div class="form-group row mt-3">
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            // Data Table

            var table = $('.customerTable').DataTable({
                processing: true,
                dom: "lBfrtip",
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                ],
                serverSide: true,
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: "{{ route('crm.customers.index') }}",
                columns: [{
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'contact_id',
                        name: 'contact_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'customer_group_id',
                        name: 'customer_group_id'
                    },
                    {
                        data: 'customer_type',
                        name: 'customer_type'
                    },
                    {
                        data: 'credit_limit',
                        name: 'credit_limit'
                    },
                    {
                        data: 'opening_balance',
                        name: 'opening_balance'
                    },
                    {
                        data: 'debit',
                        name: 'debit'
                    },
                    {
                        data: 'credit',
                        name: 'credit'
                    },
                    {
                        data: 'closing_balance',
                        name: 'closing_balance'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            // Show and hide input field
            $(document).on('change', '#customer_type', function() {
                if ($(this).val() == 2) {

                    $('.hidable').slideToggle("slow").removeClass('d-none');
                    $('#credit_limit').addClass('add_input');
                } else {

                    $('.hidable').slideToggle("slow").addClass('d-none');
                    $('#credit_limit').removeClass('add_input');
                }
            });

            // add customer form
            $('#add_customer_form').on('submit', function(e) {
                e.preventDefault();

                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $('.submit_button').prop('type', 'button');

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        toastr.success('Customer added successfully.');
                        $('#add_customer_form')[0].reset();
                        // table.ajax.reload();
                        $('.loading_button').hide();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'submit');
                        $('.customerTable').DataTable().ajax.reload();

                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please contact to the support team.');
                            return;
                        }

                        toastr.error('Please check again all form fields.',
                            'Some thing went wrong.');

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });


            $(document).on('click', '.change_status', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $.confirm({
                    'title': 'Changes Status Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    success: function(data) {

                                        if (!$.isEmptyObject(data.errorMsg)) {

                                            toastr.error(data.errorMsg);
                                            return;
                                        }

                                        toastr.success(data);
                                        table.ajax.reload();
                                        refresh();
                                    },
                                    error: function(err) {

                                    }
                                });
                            }
                        },
                        'No': {
                            'class': 'no btn-primary',
                            'action': function() {

                            }
                        }
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.data_preloader').hide();
                        $('#edit-content').html(data);
                        $('#editModal').modal('show');
                    },
                    error: function(err) {
                        $('.data_preloader').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else if (err.status == 500) {
                            toastr.error('Server Error, Please contact to the support team.');
                        }
                    }
                });
            });

        });
    </script>
@endpush
