@extends('layout.master')
@push('css')

@endpush
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.warehouses')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-4">
                    <div class="card" id="add_form">
                        <div class="card-body p-3">
                            <div class="section-header p-0">
                                <div class="col-md-12">
                                    <h6>@lang('menu.add_warehouse') </h6>
                                </div>
                            </div>

                            <div class="form-area">
                                <form id="add_warehouse_form" action="{{ route('settings.warehouses.store') }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label><b>@lang('menu.warehouse_name') </b> <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control add_input"
                                            data-name="Warehouse name" id="name" placeholder="@lang('menu.warehouse_name')"
                                            required />
                                        <span class="error error_name"></span>
                                    </div>

                                    <div class="form-group mt-1">
                                        <label><b>@lang('menu.warehouse_code') </b> <span class="text-danger">*</span> <i
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Warehouse code must be unique."
                                                class="fas fa-info-circle tp"></i></label>
                                        <input type="text" name="code" class="form-control add_input"
                                            data-name="Warehouse code" id="code" placeholder="@lang('menu.warehouse_code')"
                                            required />
                                        <span class="error error_code"></span>
                                    </div>

                                    <div class="form-group mt-1">
                                        <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control add_input"
                                            data-name="Phone number" id="phone" placeholder="Phone number" required />
                                        <span class="error error_phone"></span>
                                    </div>

                                    <div class="form-group mt-1">
                                        <label><b>@lang('menu.address') </b> </label>
                                        <textarea name="address" class="form-control ckEditor" placeholder="Warehouse address" rows="3"></textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <div class="d-flex justify-content-end">
                                            <div class="loading-btn-box">
                                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                                        class="fas fa-spinner"></i></button>
                                                <button type="submit"
                                                    class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                                <button type="reset"
                                                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.reset')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card display-none" id="edit_form">
                        <div class="card-body p-3">
                            <div class="section-header">
                                <div class="col-md-12">
                                    <h6>@lang('menu.edit_warehouse') </h6>
                                </div>
                            </div>

                            <div class="form-area" id="edit_form_body">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="section-header mb-0">
                                <div class="col-md-6">
                                    <h6>@lang('menu.all_warehouse')</h6>
                                </div>
                            </div>

                            <div>
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-startx">@lang('menu.sl')</th>
                                                <th class="text-startx">@lang('menu.name')</th>
                                                <th class="text-startx">@lang('menu.warehouse_code')</th>
                                                <th class="text-startx">@lang('menu.phone')</th>
                                                <th class="text-startx">@lang('menu.address')</th>
                                                <th class="text-startx">@lang('menu.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
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
            aaSorting: [
                [2, 'desc']
            ],
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('settings.warehouses.index') }}",
            },
            // columnDefs: [{"targets": [0, 6],"orderable": false,"searchable": false}],
            columns: [{
                data: 'DT_RowIndex'
            }, {
                data: 'name',
                name: 'warehouses.warehouse_name'
            }, {
                data: 'code',
                name: 'warehouses.warehouse_code'
            }, {
                data: 'phone',
                name: 'phone'
            }, {
                data: 'address',
                name: 'address'
            }, {
                data: 'action'
            }, ],
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function() {

            table.ajax.reload();
        });

        // Setup CSRF Token for ajax request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add Warehouse by ajax
            $('#add_warehouse_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                $('.submit_button').prop('type', 'button');
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;

                $.each(inputs, function(key, val) {

                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val()

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

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        toastr.success(data);
                        $('#add_warehouse_form')[0].reset();
                        $('.loading_button').hide();

                        table.ajax.reload();

                        $('.submit_button').prop('type', 'submit');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#edit_form_body').html(data);
                        $('#add_form').hide();
                        $('#edit_form').show();
                        $('.data_preloader').hide();
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).data('id');
                $('#deleted_form').attr('action', url);
                $('#deleteId').val(id);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
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
                    type: 'delete',
                    data: request,
                    success: function(data) {

                        if ($.isEmptyObject(data.errorMsg)) {

                            toastr.error(data);
                            table.ajax.reload();
                        } else {

                            toastr.error(data.errorMsg, 'Error');
                        }
                    }
                });
            });

            $(document).on('click', '#close_form', function() {

                $('#add_form').show();
                $('#edit_form').hide();
            });
        });
    </script>
@endpush
