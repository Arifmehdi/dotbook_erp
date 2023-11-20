@extends('layout.master')
@push('css')
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

        .btnPB {
            /* padding-bottom: 15px */
        }

        .customFormDedign {
            margin: 20px
        }
    </style>
@endpush
@section('title', 'Sms Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>{{ __('Sms Server Setup') }}</h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <form id="mail_server_credential" action="{{ route('communication.sms.server.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-area customFormDedign">
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Provider</label>
                                        <div class="col-9">
                                            <input name="provider_name" id="provider_name" class="form-control"
                                                type="text" placeholder="E.x. GrameenPhone">
                                            <input name="sms_provider_primary_id" id="sms_provider_primary_id"
                                                type="hidden">
                                            <span class="error error_provider_name"></span>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">URL</label>
                                        <div class="col-9">
                                            <input name="url" id="url" name="url" id=""
                                                class="form-control" type="text" placeholder="E.x. www.GrameenPhone.com">
                                            <span class="error error_url"></span>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Api Key</label>
                                        <div class="col-9">
                                            <input name="api_key" id="api_key" class="form-control" type="text"
                                                placeholder="E.x. @username">
                                            <span class="error error_api_key"></span>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Secret Key</label>
                                        <div class="col-9">
                                            <input name="secret_key" id="secret_key" class="form-control" type="password"
                                                placeholder="E.x. ************">
                                            <span class="error error_secret_key"></span>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Sender Number</label>
                                        <div class="col-9">
                                            <input name="sender_number" id="sender_number" class="form-control"
                                                type="text" placeholder="E.x. xyz@example.com">
                                            <span class="error error_sender_number"></span>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Sender Name</label>
                                        <div class="col-9">
                                            <input name="name" id="name" class="form-control" type="text"
                                                placeholder="E.x. Mr. xyz">
                                            <span class="error error_name"></span>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <label for="" class="col-3">HTTP Type</label>
                                        <div class="col-8">
                                            <select name="type" id="type" class="form-control form-select"
                                                placeholder="">
                                                <option value="get">GET</option>
                                                <option value="post">POST</option>
                                            </select>
                                            <span class="error error_type"></span>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary btn-sm  mb-2"
                                                id="addMoreButton"><i class="fas fa-plus px-1"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row g-2" id="keyValueContainer">
                                            <div class="col-12">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-11">
                                                        <div class="row g-2">
                                                            <div class="col-md-6 mt-1">
                                                                <input type="text" name="key[]" class="form-control"
                                                                    placeholder="KEY" autocomplete="off" value="">
                                                            </div>
                                                            <div class="col-md-6 mt-1">
                                                                <input type="text" name="value[]" class="form-control"
                                                                    placeholder="REPLACING VALUE" autocomplete="off"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 mt-0">
                                                        <button class="btn btn-sm btn-danger px-2" type="button"
                                                            onclick="this.parentElement.parentElement.parentElement.remove()">x</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                                    class="fas fa-spinner"></i></button>
                                            <button type="button" id="resetForm"
                                                class="btn btn-sm btn-danger me-2">@lang('menu.reset')</button>
                                            <button type="submit"
                                                class="btn btn-sm btn-success">@lang('menu.save')</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-primary card-outline">
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
                                <form id="all_delete_form" action="{{ route('communication.sms.server.delete_all') }}"
                                    method="post">
                                    @csrf
                                    <table class="display data_tbl data__table smsBodyTable">
                                        <thead>
                                            <tr>
                                                <th>Check</th>
                                                <th>Active</th>
                                                <th>Server</th>
                                                <th>Host</th>
                                                <th>Port</th>
                                                <th>User Name</th>
                                                <th>secret_key</th>
                                                <th>Address</th>
                                                <th>Name</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
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
        const addButton = document.getElementById('addMoreButton');
        addButton.addEventListener('click', function(e) {
            e.preventDefault();
            const container = document.getElementById('keyValueContainer');
            const child = `<div class="col-12">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-11">
                                        <div class="row g-2">
                                            <div class="col-md-6 mt-1">
                                                <input type="text" name="key[]" class="form-control" placeholder="KEY" autocomplete="off">
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <input type="text" name="value[]" class="form-control" placeholder="REPLACING VALUE" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-0">
                                        <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.parentElement.remove()">x</button>
                                    </div>
                                </div>
                        </div>`;
            container.insertAdjacentHTML('beforeend', child);
        });


        var sms_table = $('.data_tbl').DataTable({
            "processing": true,

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.sms.server-setup') }}",
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'server_name',
                    name: 'server_name'
                }, {
                    data: 'host',
                    name: 'host'
                }, {
                    data: 'port',
                    name: 'port'
                }, {
                    data: 'api_key',
                    name: 'api_key'
                }, {
                    data: 'secret_key',
                    name: 'secret_key'
                }, {
                    data: 'address',
                    name: 'address'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'edit',
                    name: 'edit'
                }, {
                    data: 'delete',
                    name: 'Delete'
                },

            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });


        $('#mail_server_credential').on('submit', function(e) {
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

                    $('#server_name').val("");
                    $('#host').val("");
                    $('#port').val("");
                    $('#api_key').val("");
                    $('#secret_key').val("");
                    $('#address').val("");
                    $('#encryption').val("");
                    $('#name').val("");
                    $('#mail_server_primary_id').val("");

                    $('.smsBodyTable').DataTable().ajax.reload();
                    $('.loading_button').hide();
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


        $('#resetForm').on('click', function(e) {
            $('#server_name').val("");
            $('#host').val("");
            $('#port').val("");
            $('#api_key').val("");
            $('#secret_key').val("");
            $('#address').val("");
            $('#encryption').val("");
            $('#name').val("");
            $('#mail_server_primary_id').val("");
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

            $(document).on('click', '#smsServerEdit', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data.status);
                        $('.loading_button').hide();
                        $('#server_name').val(data.serverCredentialVal.server_name);
                        $('#host').val(data.serverCredentialVal.host);
                        $('#port').val(data.serverCredentialVal.port);
                        $('#api_key').val(data.serverCredentialVal.api_key);
                        $('#secret_key').val(data.serverCredentialVal.secret_key);
                        $('#address').val(data.serverCredentialVal.address);
                        $('#encryption').val(data.serverCredentialVal.encryption);
                        $('#name').val(data.serverCredentialVal.name);
                        $('#mail_server_primary_id').val(data.serverCredentialVal.id);
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
                    sms_table.ajax.reload();
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        return;
                    }
                    toastr.success(data.responseJSON);
                },
                error: function(err) {
                    toastr.error(err.responseJSON)
                    sms_table.ajax.reload();
                }
            });
        });
    </script>
@endpush
