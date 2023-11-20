@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />


    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }

        .table-responsive-y {
            max-height: 350px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .form-check {
            padding: 0;
            gap: 5px
        }

        .form-check-input {
            margin-left: 0 !important;
            margin-top: -2px !important;
        }

        .data__table thead tr th.text-center {
            text-align: center !important;
        }
    </style>
@endpush
@section('title', 'Email Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>Sms Text Format</h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body p-1">
                            <form id="sms_body_format" action="{{ route('communication.sms.body-format.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2 mb-3">

                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <label for="" class="col-4">{{ __('Format Name') }}</label>
                                            <div class="col-8">
                                                <input type="text" name="format_name" id="format_name"
                                                    class="form-control format_name" placeholder="{{ __('Format Name') }}">
                                                <input type="hidden" name="format_primary_id" id="format_primary_id" />
                                                <span class="error error_format_name"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <label for="" class="col-4">{{ __('Subject') }}</label>
                                            <div class="col-8">
                                                <input type="text" name="sms_subject" id="sms_subject"
                                                    class="form-control sms_subject_count"
                                                    placeholder="{{ __('Sms Subject') }}">
                                                <span class="error error_sms_subject"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <label for="" class="col-4">{{ __('Body Text') }}</label>
                                            <div class="col-8">
                                                <textarea name="body_format" id="body_format" class="form-control body_text_count" rows="4"
                                                    placeholder="{{ __('Sms Body Text') }}"></textarea>
                                                <span class="error error_body_format"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <h1><span class="badge bg-secondary total_characters">0</span></h1>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                                    class="fas fa-spinner"></i></button>
                                            <button type="submit"
                                                class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                            <button type="reset" data-bs-dismiss="modal"
                                                class="btn btn-sm btn-danger float-end me-2">@lang('menu.reset')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mailbox-controls">
                                <button type="button" class="btn btn-default checkbox-toggle" id="check_all"><i
                                        class="far fa-square"></i></button>
                                <input type="checkbox" id="is_check_all" class="d-none">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default all_delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive mailbox-messages">
                                <form id="all_delete_form" action="{{ route('communication.sms.body.delete_all') }}"
                                    method="post">
                                    @csrf
                                    <table class="display data_tbl data__table smsBodyTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Check</th>
                                                <th class="text-center">Importent</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Subject</th>
                                                <th class="text-center">Delete</th>
                                                <th class="text-center">View</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        var email_table = $('.data_tbl').DataTable({
            "processing": true,

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.sms.body') }}",
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'format_name',
                    name: 'format_name'
                }, {
                    data: 'sms_subject',
                    name: 'sms_subject'
                }, {
                    data: 'delete',
                    name: 'Delete'
                }, {
                    data: 'view',
                    name: 'view'
                },

            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });
        var total_characters = 0;
        var sms_body = 0;
        var sms_sub = 0;
        $(document).ready(function() {
            $(".body_text_count").keyup(function() {
                sms_body = $(this).val();
                sms_body = sms_body.length;
                total_characters = sms_sub + sms_body;

                $(".total_characters").text(total_characters)
            });

            $(".sms_subject_count").keyup(function() {
                sms_sub = $(this).val();
                sms_sub = sms_sub.length;
                total_characters = sms_sub + sms_body;

                $(".total_characters").text(total_characters)
            });


        });

        $(document).ready(function() {

            $(document).on('click', '#status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.loading_button').hide();
                        $('.smsBodyTable').DataTable().ajax.reload();
                        toastr.success(data);
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

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        });



        $(document).ready(function() {

            $(document).on('click', '#smsBodyView', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        total_characters = 0;
                        $(".total_characters").text(total_characters)
                        toastr.success(data.status);
                        $('.loading_button').hide();
                        $('#body_format').val(data.template.body_format);
                        $('#format_name').val(data.template.format_name);
                        $('#sms_subject').val(data.template.sms_subject);
                        $('#format_primary_id').val(data.template.id);
                        total_characters = data.template.sms_subject.length + data.template
                            .body_format.length;
                        $(".total_characters").text(total_characters)
                        $('.smsBodyTable').DataTable().ajax.reload();
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

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        });


        $('#sms_body_format').on('submit', function(e) {
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
                    // toastr.success(data.success);
                    $('.format_name').val('');
                    $('.body_text_count').val('');
                    $('.sms_subject_count').val('');
                    total_characters = 0;
                    $(".total_characters").text(total_characters)
                    $('.smsBodyTable').DataTable().ajax.reload();
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
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });


        $('.all_delete').on('click', function() {

            $('#all_delete_form').submit();
        });

        $('#all_delete_form').on('submit', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.smsBodyTable').DataTable().ajax.reload();

                    toastr.success(data);
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

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });


        $('#check_all').on('click', function() {


            if ($('#is_check_all').is(":checked")) {

                $('#is_check_all').prop("checked", false);
            } else {

                $('#is_check_all').prop("checked", true);
            }

            if ($('#is_check_all').is(":checked")) {

                $('.check1').prop('checked', true);
            } else {

                $('.check1').prop('checked', false);
            }
        });


        // delete
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

        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    email_table.ajax.reload();
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        return;
                    }
                    toastr.success(data.responseJSON);
                },
                error: function(err) {
                    toastr.error(err.responseJSON)
                    email_table.ajax.reload();
                }
            });
        });
    </script>
@endpush
