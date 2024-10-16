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
    </style>
@endpush
@section('title', 'Sms - index')
@section('content')
    <div class="body-wraper">
        <section class="mt-5x">
            <div class="container-fluid">
                <div class="row">
                    <div class="sec-name">
                        <h6>Sms Section</h6>
                        <x-back-button />
                    </div>
                </div>
            </div>
        </section>
        <div class="content-wrapper p-15">
            <section class="content email-content">
                <div class="row g-1">
                    <div class="col-md-3">
                        <div class="card mail-sidebar">
                            <div class="card-body">
                                <button class="btn btn-default rounded-pill px-4 btn-block mb-2" data-bs-toggle="modal"
                                    data-bs-target="#addCompose"><i class="fa-duotone fa-pen-fancy"></i> Compose</button>
                                <ul class="nav nav-pills flex-column">
                                    <li class="nav-item active">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-mailbox"></i></span> Inbox
                                            <span class="count">12</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-envelope"></i></span> Sent
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-file-lines"></i></span> Drafts
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-filters"></i></span> Junk
                                            <span class="count">65</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-trash-arrow-up"></i></span>
                                            Trash
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-9">
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
                                    <form id="all_delete_form" action="{{ route('communication.sms.delete_all') }}"
                                        method="post">
                                        @csrf
                                        <table class="display data_tbl data__table smsTable">
                                            <tbody></tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <div class="modal fade" id="addCompose" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 32%;width: 54%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">New Message</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="mail_send" action="{{ route('communication.sms.send') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-5">
                                <button type="button" class="btn btn-primary btn-sm mb-2 p-1" id="addMoreButton"><i
                                        class="fas fa-plus"></i> Add More Phone Numbers</button>
                            </div>

                            <div class="col-7">
                                <select name="group_id[]" class="form-control select2 form-select" multiple="multiple">
                                    <option disabled>Open this select menu</option>
                                    @foreach ($filtered_contact_numbers as $filteredContactNumber)
                                        <option value="{{ $filteredContactNumber->group->id }}">
                                            {{ $filteredContactNumber->group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" id="to_area">
                            <div class="col-md-12">
                                <label><strong>To</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="to[]" class="form-control add_input" data-name="To"
                                    id="phone_number" placeholder="Phone Number" />
                                <span class="error error_to"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Body </strong></label>
                                <textarea id="editor" name="message" id="message"></textarea>
                                <span class="error error_message"></span>
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
                                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

    <script>
        $('.select2').select2({
            placeholder: "Select Group",
            allowClear: true
        });

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {

            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        var sms_table = $('.data_tbl').DataTable({
            "processing": true,

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.sms.index') }}",
                "data": function(d) {
                    // d.supplier_id = $('#f_supplier_id').val();
                    // d.location_id = $('#f_location_id').val();
                    // d.unit_id = $('#f_unit_id').val();
                    // d.category_id = $('#f_category_id').val();
                    // d.from_date = $('.from_date').val();
                    // d.to_date = $('.to_date').val();
                }
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'to',
                    name: 'to'
                }, {
                    data: 'message',
                    name: 'message'
                }, {
                    data: 'delete',
                    name: 'Delete'
                }, {
                    data: 'time',
                    name: 'time'
                },

            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
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
                        $('.smsTable').DataTable().ajax.reload();
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

        $('#mail_send').on('submit', function(e) {

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
                    toastr.success(data);
                    $('.smsTable').DataTable().ajax.reload();
                    $('#addCompose').modal('hide');
                    $('#mail_send')[0].reset();

                    $('.loading_button').hide();
                    $('#addCompose').hide();
                    $('#addCompose').close();
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
                        if (key === 'to.0') {
                            key = 'to'
                        }
                        let errorMessage = error[0].replace('.0', '');
                        errorMessage += ' if group is not selected!';

                        $('.error_' + key + '').html(errorMessage);
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
                    $('.smsTable').DataTable().ajax.reload();


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


        // adding more To fields
        var child = '';
        // child += '<div class="d-flex justify-content-strech align-items-center gap-2 area-part mb-2">';
        // child += '<div class="" style="width: 10%"><label></label></div>';
        // child += '<div class="" style="width: 80%"><input type="text" name="to[]" class="form-control add_input" data-name="To" id="phone_number" placeholder="Phone Number" /><span class="error error_to"></span></div>';
        // child += '<div class=""><button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px" type="button" onclick="this.parentElement.parentElement.remove()">X</button></div></div>';

        child += '<div class="col-md-12 mt-2">';
        child += '<div class="row">';
        child += '<div class="col-md-10">';
        child +=
            '<input type="text" name="to[]" class="form-control add_input" data-name="To" id="phone_number" placeholder="Phone Number" />';
        child += '<span class="error error_to"></span>';
        child += '</div>';
        child += '<div class="col-md-2 text-end">';
        child +=
            '<button class="btn btn-sm btn-danger deletewarrantyButton" type="button" onclick="this.parentElement.parentElement.parentElement.remove()" style="padding:4px 20px">X</button>';
        child += '</div>';
        child += '</div>';
        child += '</div>';

        var addMoreButton = document.getElementById('addMoreButton');
        var warrantyContainer = document.getElementById('to_area');

        $('#addMoreButton').on('click', function(e) {
            e.preventDefault();
            $('#to_area').append(child);
        });

        // body-wraper
        var height = $(window).height() - 113;
        $('.email-content').height(height);
    </script>
@endpush
