@extends('layout.master')
@push('css')
@endpush
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.invoice_schema')</h6>
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
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-startx">@lang('menu.name')</th>
                                            <th class="text-startx">@lang('menu.prefix')</th>
                                            <th class="text-startx">@lang('menu.start_from')</th>
                                            <th class="text-startx">@lang('menu.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add') @lang('menu.invoice_schema')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_schema_form" action="{{ route('invoices.schemas.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label><b>@lang('menu.preview') <span id="schema_preview"></span></label>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" id="name"
                                    placeholder="Schema name" required />
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <p class="checkbox_input_wrap mt-4"> <input type="checkbox" name="set_as_default"
                                            autocomplete="off" id="set_as_default">&nbsp;&nbsp;<b>@lang('menu.set_as_default').</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>Format </b> <span class="text-danger">*</span></label>
                                <select name="format" class="form-control form-control-sm form-select" id="format"
                                    required>
                                    <option value="1">FORMAT-XXXX</option>
                                    <option value="2">FORMAT-{{ date('Y') }}/XXXX</option>
                                </select>
                                <span class="error error_format"></span>
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('menu.prefix') </b> <span class="text-danger">*</span></label>
                                <input type="text" name="prefix" class="form-control form-control-sm" id="prefix"
                                    placeholder="@lang('menu.prefix')" required />
                                <span class="error error_prefix"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('menu.start_from') </b></label>
                                <input type="number" name="start_from" class="form-control form-control-sm" id="start_from"
                                    placeholder="@lang('menu.start_from')" value="0" />
                            </div>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                            <button type="submit" class="btn btn-sm btn-success me-0 float-end">@lang('menu.save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger float-end">@lang('menu.close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_invoice_schema')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End-->
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
            processing: true,
            serverSide: true,
            aaSorting: [
                [3, 'asc']
            ],
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            ajax: "{{ route('invoices.schemas.index') }}",
            columns: [{
                data: 'name',
                name: 'name'
            }, {
                data: 'prefix',
                name: 'prefix'
            }, {
                data: 'start_from',
                name: 'start_from'
            }, {
                data: 'action',
                name: 'action'
            }, ]
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).on('change', '#format', function() {
            var val = $(this).val();
            if (val == 2) {
                $('#prefix').val("{{ date('Y') }}" + '/');
                $('#prefix').prop('readonly', true);
            } else {
                $('#prefix').val("");
                $('#prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('change', '#e_format', function() {
            var val = $(this).val();
            if (val == 2) {
                $('#e_prefix').val("{{ date('Y') }}" + '/');
                $('#e_prefix').prop('readonly', true);
            } else {
                $('#e_prefix').val("");
                $('#e_prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('input', '#prefix', function() {
            previewInvoieId();
        });

        $(document).on('input', '#e_prefix', function() {
            previewInvoieId();
        });

        $(document).on('input', '#start_from', function() {
            previewInvoieId();
        });

        $(document).on('input', '#e_start_from', function() {
            previewInvoieId();
        });

        function previewInvoieId() {
            var prefix = $('#prefix').val();
            var start_from = $('#start_from').val();
            $('#schema_preview').html('#' + prefix + start_from);

            var prefix = $('#e_prefix').val();
            var start_from = $('#e_start_from').val();
            $('#e_schema_preview').html('#' + prefix + start_from);
        }

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add category by ajax
            $(document).on('submit', '#add_schema_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_schema_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                        $('#schema_preview').html('');
                        $('#prefix').prop('readonly', false);
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        $('.error').html('');
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
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.data_preloader').hide();
                        $('#edit_modal_body').html(data);
                        $('#editModal').modal('show');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#set_default_btn', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        table.ajax.reload();
                        toastr.success(data);
                        $('.data_preloader').hide();
                    }
                });
            });

            // edit category by ajax
            $(document).on('submit', '#edit_schema_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        $('.error').html('');
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_e_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
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
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {
                        toastr.error(data);
                        table.ajax.reload();
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush
