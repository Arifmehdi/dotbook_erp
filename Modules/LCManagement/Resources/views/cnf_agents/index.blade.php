@extends('layout.master')
@push('css')
@endpush
@section('title', 'CNF Agents - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Clearing and Forwarding Agents') }}</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'cnf_agents_create'" :text="'New CNF Agents'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            <div class="row">
                @can('cnf_agents_view')
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
                                                <th>Agent Name</th>
                                                <th>Agent ID</th>
                                                <th>@lang('menu.phone')</th>
                                                <th>Land-Line</th>
                                                <th>@lang('menu.tax_number')</th>
                                                <th>@lang('menu.created_by')</th>
                                                <th>@lang('menu.updated_by')</th>
                                                <th>@lang('menu.opening_balance')</th>
                                                <th>Total Service</th>
                                                <th>@lang('menu.total_paid')</th>
                                                <th>@lang('menu.closing_balance')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="8" class="text-white text-end">@lang('menu.total') :
                                                    ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th id="opening_balance" class="text-white text-end"></th>
                                                <th id="total_service" class="text-white text-end"></th>
                                                <th id="total_paid" class="text-white text-end"></th>
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
                @endcan
            </div>
        </div>

        <!-- Add Modal ---->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Add CNF Agent</h6>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form id="add_cnf_agent_form" action="{{ route('lc.cnf.agents.store') }}">
                            @csrf
                            <div class="form-group row mt-1">
                                <div class="col-xl-3 col-md-6">
                                    <label><b>Agent Name </b> <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control add_input"
                                        data-name="Agent name" id="name" placeholder="Agent Company Name" required />
                                    <span class="error error_name"></span>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control  add_input"
                                        data-name="Phone number" id="phone" placeholder="Phone number" required />
                                    <span class="error error_phone"></span>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <label><b>Agent ID </b></label>
                                    <input type="text" name="agent_id" class="form-control" placeholder="Agent ID" />
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
                                    <label><b>@lang('menu.landline') </b></label>
                                    <input type="text" name="landline" class="form-control "
                                        placeholder="landline number" />
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <label><b>@lang('menu.tax_number') </b></label>
                                    <input type="text" name="tax_number" class="form-control "
                                        placeholder="@lang('menu.tax_number')" />
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-xl-3 col-md-6">
                                    <label><b>@lang('menu.opening_balance') </b></label>
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
                                    <label><b>@lang('menu.state') </b></label>
                                    <input type="text" name="state" class="form-control "
                                        placeholder="@lang('menu.state')" />
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <label><b>@lang('menu.zip_code') </b></label>
                                    <input type="text" name="zip_code" class="form-control "
                                        placeholder="zip_code" />
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <label><b>@lang('menu.country') </b></label>
                                    <input type="text" name="country" class="form-control "
                                        placeholder="@lang('menu.country')" />
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-9">
                                    <label><b>@lang('menu.address') </b></label>
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
                        <h6 class="modal-title" id="exampleModalLabel">Edit CNF Agent</h6>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="edit_modal_body"></div>
                </div>
            </div>
        </div>
        <!-- Edit Modal End-->
    </div>
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
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }, ],
            "processing": true,
            "serverSide": true,
            ajax: "{{ route('lc.cnf.agents.index') }}",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            columns: [{
                data: 'action'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'agent_id',
                name: 'agent_id'
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
                data: 'createdBy',
                name: 'createdBy.name'
            }, {
                data: 'updatedBy',
                name: 'updatedBy.name'
            }, {
                data: 'opening_balance',
                name: 'opening_balance',
                className: 'text-end'
            }, {
                data: 'total_service',
                name: 'total_service',
                className: 'text-end'
            }, {
                data: 'total_paid',
                name: 'total_paid',
                className: 'text-end'
            }, {
                data: 'closing_balance',
                name: 'closing_balance',
                className: 'text-end'
            }, ],
            fnDrawCallback: function() {

                var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#opening_balance').text(bdFormat(opening_balance));
                var total_service = sum_table_col($('.data_tbl'), 'total_service');
                $('#total_service').text(bdFormat(total_service));
                var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(bdFormat(total_paid));
                var closing_balance = sum_table_col($('.data_tbl'), 'closing_balance');
                $('#closing_balance').text(bdFormat(closing_balance));
            }
        });

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
        table.buttons().container().appendTo('#exportButtonsContainer');

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add Supplier by ajax
            $('#add_cnf_agent_form').on('submit', function(e) {
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
                        toastr.success('Successfully CNF agent is created');
                        $('#add_cnf_agent_form')[0].reset();
                        table.ajax.reload();
                        $('.loading_button').hide();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'submit');
                    },
                    error: function(err) {

                        $('.submit_button').prop('type', 'sumbit');
                        $('.loading_button').hide();
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please to the support team.');
                            return;
                        } else if (err.status == 403) {

                            toastr.error('Access Denied');
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
