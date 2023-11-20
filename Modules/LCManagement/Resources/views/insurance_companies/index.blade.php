@extends('layout.master')
@push('css')
@endpush
@section('title', 'Insurance Companies - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('role.insurance_companies')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'insurance_companies_create'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            @can('insurance_companies_view')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6>
                                        <i class="fas fa-spinner"></i> @lang('menu.processing')
                                    </h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr class="text-start">
                                                <th>@lang('menu.actions')</th>
                                                <th>Company Name</th>
                                                <th>Branch Name</th>
                                                <th>Company ID</th>
                                                <th>@lang('menu.phone')</th>
                                                <th>Land-Line</th>
                                                <th>@lang('menu.tax_number')</th>
                                                <th>@lang('menu.opening_balance')</th>
                                                <th>Total Policy Amount</th>
                                                <th>Total Premium Paid</th>
                                                <th>@lang('menu.closing_balance')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="7" class="text-white text-end">@lang('menu.total') :
                                                    ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th id="opening_balance" class="text-white text-end"></th>
                                                <th id="total_policy" class="text-white text-end"></th>
                                                <th id="total_premium_paid" class="text-white text-end"></th>
                                                <th id="closing_balance" class="text-white text-end"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <!-- Add Modal ---->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Insurance Company</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_insurance_company_form" action="{{ route('lc.insurance.companies.store') }}">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><b>Company Name </b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control add_input" data-name="Company name"
                                    id="name" placeholder="Insurance Company Name" required />
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>Branch </b> <span class="text-danger">*</span></label>
                                <input type="text" name="branch" id="branch" class="form-control add_input"
                                    data-name="Branch" placeholder="Branch Name" required />
                                <span class="error error_branch"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control  add_input"
                                    data-name="Phone number" id="phone" placeholder="Phone number" required />
                                <span class="error error_phone"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>Company ID </b></label>
                                <input type="text" name="contact_id" class="form-control" placeholder="Contact ID" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><b>@lang('menu.alternative_number') </b></label>
                                <input type="text" name="alternative_phone" class="form-control "
                                    placeholder="Alternative phone number" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>@lang('menu.email') </b></label>
                                <input type="text" name="email" class="form-control "
                                    placeholder="@lang('menu.email_address')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <b>@lang('menu.landline') </b>
                                <input type="text" name="landline" class="form-control " placeholder="landline number" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <b>@lang('menu.tax_number') </b>
                                <input type="text" name="tax_number" class="form-control "
                                    placeholder="@lang('menu.tax_number')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <b>@lang('menu.opening_balance') </b>
                                <input type="number" name="opening_balance" class="form-control "
                                    placeholder="@lang('menu.opening_balance')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><b>@lang('menu.city') </b></label>
                                <input type="text" name="city" class="form-control "
                                    placeholder="@lang('menu.city')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>State </b></label>
                                <input type="text" name="state" class="form-control " placeholder="State" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>@lang('menu.zip_code') </b></label>
                                <input type="text" name="zip_code" class="form-control " placeholder="zip_code" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><b>@lang('menu.country') </b></label>
                                <input type="text" name="country" class="form-control "
                                    placeholder="@lang('menu.country')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-9">
                                <label><b>@lang('menu.address') : </b></label>
                                <textarea name="address" class="form-control ckEditor" cols="10" rows="4" placeholder="Address"></textarea>
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
    <!-- Add Modal End---->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Insurance Company</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Edit Modal End-->
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                }
            }, ],
            "processing": true,
            "serverSide": true,
            // aaSorting: [[1, 'asc']],
            ajax: "{{ route('lc.insurance.companies.index') }}",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            // columnDefs: [{"targets": [0, 12], "orderable": false, "searchable": false }],
            columns: [{
                data: 'action'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'branch',
                name: 'branch'
            }, {
                data: 'company_id',
                name: 'company_id'
            }, {
                data: 'phone',
                name: 'phone'
            }, {
                data: 'landline',
                name: 'landline'
            }, {
                data: 'tax_number',
                name: 'tax_number'
            }, {
                data: 'opening_balance',
                name: 'opening_balance',
                className: 'text-end'
            }, {
                data: 'total_policy',
                name: 'total_policy',
                className: 'text-end'
            }, {
                data: 'total_premium_paid',
                name: 'total_premium_paid',
                className: 'text-end'
            }, {
                data: 'closing_balance',
                name: 'closing_balance',
                className: 'text-end'
            }, ],
            fnDrawCallback: function() {

                var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#opening_balance').text(bdFormat(opening_balance));
                var total_policy = sum_table_col($('.data_tbl'), 'total_policy');
                $('#total_policy').text(bdFormat(total_policy));
                var total_premium_paid = sum_table_col($('.data_tbl'), 'total_premium_paid');
                $('#total_premium_paid').text(bdFormat(total_premium_paid));
                var closing_balance = sum_table_col($('.data_tbl'), 'closing_balance');
                $('#closing_balance').text(bdFormat(closing_balance));
            }
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        function sum_table_col(table, class_name) {
            var sum = 0;

            table.find('tbody').find('tr').each(function() {

                if (parseFloat($(this).find('.' + class_name).data('value'))) {

                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add Supplier by ajax
            $('#add_insurance_company_form').on('submit', function(e) {
                e.preventDefault();

                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;

                $.each(inputs, function(key, val) {

                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();

                    if (idValue == '') {

                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {

                    $('.loading_button').hide();
                    return;
                }

                $('.submit_button').prop('type', 'button');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success('Successfully Insurance Company is created');
                        $('#add_insurance_company_form')[0].reset();
                        table.ajax.reload();
                        $('.loading_button').hide();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'submit');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();

                $('.data_preloader').show();

                var url = $(this).attr('href');

                $.get(url, function(data) {

                    $('#edit_modal_body').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-primary',
                            'action': function() {}
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
                    async: false,
                    data: request,
                    success: function(data) {

                        table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#addModal').modal('show');
                setTimeout(function() {

                    $('#name').focus();
                }, 500);
                //return false;
            }
        }
    </script>
@endpush
