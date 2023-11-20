@extends('layout.master')
@push('css')
@endpush
@section('title', 'Payment Methods - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.payment_methods')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row g-1">
                    <div class="col-md-4">
                        <div class="card" id="add_form">
                            <div class="section-header">
                                <div class="col-md-7">
                                    <h6>@lang('menu.add_payment') @lang('menu.method')</h6>
                                </div>
                            </div>

                            <form id="add_payment_method_form" class="p-2"
                                action="{{ route('settings.payment.method.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label><b>@lang('menu.method') @lang('menu.name') </b> <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="@lang('menu.payment_method')" required />
                                        <span class="error error_name"></span>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
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

                        <div class="card display-none" id="edit_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('menu.edit') @lang('menu.payment_method')</h6>
                                </div>
                            </div>

                            <div class="form-area px-3 pb-2" id="edit_form_body"></div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-header mb-0">
                                    <h6>@lang('menu.all_payment_methods')</h6>
                                </div>
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.serial')</th>
                                                <th>@lang('menu.payment_method') @lang('menu.name')</th>
                                                <th>@lang('menu.actions')</th>
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
            searchable: true,
            ajax: "{{ route('settings.payment.method.index') }}",
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'action',
                name: 'action'
            }, ],
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).on('submit', '#add_payment_method_form', function(e) {
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
                    toastr.success(data);
                    $('#add_payment_method_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');
                    $('#add_form').show();
                    $('#edit_form').hide();
                    table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_form_body').html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                }
            });
        });

        $(document).on('submit', '#edit_payment_method_form', function(e) {
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
                    $('.error').html('');
                    $('#add_form').show();
                    $('#edit_form').hide();
                    table.ajax.reload();
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
    </script>
@endpush
