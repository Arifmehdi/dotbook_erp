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
    </style>
@endpush
@section('title', 'Email Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Email Setup & Settings') }}</h6>
                </div>
                <x-all-buttons>
                    <a href="#" id="import_mail_modal" class="btn text-white btn-sm"><span><i
                                class="fa-thin fa-file-arrow-down fa-2x"></i><br>{{ __('Import Mail') }}</span></a>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab_list_area">
                                <ul class="btn-group">
                                    <li>
                                        <a id="tab_btn" data-show="receiver-email" class="tab_btn tab_active"
                                            href="#">
                                            <i class="fas fa-scroll"></i> Receiver Email
                                        </a>
                                    </li>
                                    <li>
                                        <a id="tab_btn" data-show="inactive-email" class="tab_btn" href="#">
                                            <i class="fas fa-info-circle"></i> Inactive Email
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab_contant receiver-email">
                                <form action="" class="mb-2">
                                    <div class="d-flex gap-3 mb-2">

                                        <div class="form-check">
                                            <input name="mailFilter" id="mailFilterAll" class="form-check-input mailFilter"
                                                type="radio" checked value="all" data-type="all">
                                            <label class="form-check-label" for="allCheck">
                                                ALL
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input name="mailFilter" id="mailFilterCustomer"
                                                class="form-check-input mailFilter" type="radio" value="customers"
                                                data-type="customers">
                                            <label class="form-check-label" for="customerCheck">
                                                Customer
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input name="mailFilter" id="mailFilterSupplier"
                                                class="form-check-input mailFilter" type="radio" value="suppliers"
                                                data-type="suppliers">
                                            <label class="form-check-label" for="supplierCheck">
                                                Supplier
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input name="mailFilter" id="mailFilterSupplier"
                                                class="form-check-input mailFilter" type="radio" value="users"
                                                data-type="users">
                                            <label class="form-check-label" for="supplierCheck">
                                                User
                                            </label>
                                        </div>

                                        @foreach ($ContactGroup as $key => $contact)
                                            <div class="form-check">
                                                <input name="mailFilter" id="mailFilterSupplier"
                                                    class="form-check-input mailFilter" type="radio"
                                                    data-type="contact_groups" value="{{ $contact->id }}">
                                                <label class="form-check-label" for="supplierCheck">
                                                    {{ $contact->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                                <div class="table-responsive-y">
                                    <form id="all_mail_checked_form" action="#" method="post">
                                        @csrf
                                        <table class="display data_tbl data__table emailBodyTable" id="emailBodyTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Check</th>
                                                    <th class="text-center">Email</th>
                                                    {{-- <th class="text-center">Active</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </form>
                                </div>
                                <span>Double click on email to block</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header py-1">
                            <span>Manual Mail</span>
                        </div>
                        <div class="card-body">
                            <form id="manual_mail_send" action="{{ route('communication.email.manual-service.mail.send') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    {{-- sender selection part --}}
                                    <div class="col-sm-6">
                                        <select name="email_serve_id" id="" class="form-control form-select">
                                            <option value="">--Select Sender--</option>
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}">{{ $server->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- body selection part --}}
                                    <div class="col-sm-6">
                                        <select name="email_format_body_id" id=""
                                            class="form-control form-select">
                                            <option value="">--Select Body--</option>
                                            @foreach ($bodes as $body)
                                                <option value="{{ $body->id }}">{{ $body->format_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-6" id="to_area">
                                        <input type="email" name="to[]" data-name="To" id="to"
                                            class="form-control" placeholder="To: E.x. abs@example.com">
                                        <span class="error error_to"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-primary btn-sm mb-2 p-1"
                                            id="addMoreMailField"><i class="fas fa-plus"
                                                style="padding:2px 10px"></i></button>
                                    </div>

                                    <div class="col-sm-6">
                                        <input type="email" name="email_cc" class="form-control"
                                            placeholder="CC: E.x. abs@example.com, xyz@wxample.com">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" name="email_bcc" class="form-control"
                                            placeholder="BCC: E.x. abs@example.com, xyz@wxample.com">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="email_subject" id="email_subject"
                                            class="form-control"placeholder="Email Subject">
                                        <span class="error error_email_subject"></span>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <textarea class="text-editor ckEditor" name="email_body"></textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="specificNumberCheck">
                                            <label class="form-check-label" for="specificNumberCheck">
                                                {{ __('Specific Number') }}?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-success">@lang('menu.send_mail')</button>
                                    </div>
                                    <div class="col-sm-6 specific-number-field">
                                        <input type="tel" class="form-control" placeholder="Number">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            {{-- import modal start --}}
            <div class="modal fade" id="add_bulk_mail_modal" tabindex="-1" data-bs-backdrop="static"
                data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
            {{-- import modal end --}}
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        $('.specific-number-field').hide();

        $('#specificNumberCheck').on('change', function() {
            if ($(this).is(':checked')) {
                $('.specific-number-field').slideDown();
            } else {
                $('.specific-number-field').slideUp();
            }
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();

            $('.tab_btn').removeClass('tab_active');
            // $('.tab_contant').addClass('d-none');
            var show_content = $(this).data('show');
            show_content = $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });

        var email_table = $('#emailBodyTable').DataTable({
            "processing": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.email.manual-service') }}",
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                // {data: 'status', name: 'status'},
            ],
            fnDrawCallback: function() {
                $('.data_preloader').hide();
            }
        });

        //global variable
        var mailFilterKey = 'all';
        var statusType = 'active';
        var mailFilterType = 'all';
        var bulkImportedMailVar = 0;
        var checkedMailId = 'all';


        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            var show_content = $(this).data('show');
            if (show_content == 'receiver-email') {
                statusType = 'active';
            } else if (show_content == "inactive-email") {
                statusType = 'inactive';
            } else {
                return false;
            }
            email_table.destroy();
            $('.loading_button').show();
            var url =
                "{{ route('communication.email.manual-service-mail-status-wise-list', ['statusType' => ':statusType']) }}";
            url = url.replace(':statusType', statusType);

            email_table = $('#emailBodyTable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "dataType": 'json'
                },
                columns: [{
                        data: 'check',
                        name: 'check'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                ]
            });
        });

        $('.mailFilter').on('change', function() {
            bulkImportedMailVar = 0;
            mailFilterKey = $(this).val()
            mailFilterType = $(this).data('type');
            email_table.destroy();
            $('.loading_button').show();
            var url =
                "{{ route('communication.email.manual-service-mail-list', ['filterType' => ':filterType', 'filterKey' => ':filterKey']) }}";
            url = url.replace(':filterType', mailFilterType);
            url = url.replace(':filterKey', mailFilterKey);

            email_table = $('#emailBodyTable').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "dataType": 'json'
                },
                columns: [{
                        data: 'check',
                        name: 'check'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                ]
            });
        });


        // $(document).ready(function(){
        //     $('.active_inactive_status').on('click', function (e) {
        //         e.preventDefault();
        //         var url = $(this).attr('href');
        //         $('.loading_button').show();
        //         $.ajax({
        //             url: url,
        //             type: 'get',
        //             dataType: 'json',
        //             success: function(data) {
        //                 $('.loading_button').hide();
        //                 toastr.success(data);
        //                 //  email_table.destroy();
        //                 $('.emailBodyTable').DataTable().ajax.reload();
        //             },
        //             error: function(err) {
        //                 $('.loading_button').hide();
        //                 $('.error').html('');
        //                 if (err.status == 0) {
        //                     toastr.error('Net Connetion Error. Reload This Page.');
        //                     return;
        //                 } else if (err.status == 500) {
        //                     toastr.error('Server error. Please contact to the support team.');
        //                     return;
        //                 }
        //                 $.each(err.responseJSON.errors, function(key, error) {
        //                     $('.error_' + key + '').html(error[0]);
        //                 });
        //             }
        //         });
        //     });
        // });

        jQuery(document).on('click', '.active_inactive_status', function(ev) {
            ev.preventDefault();
            var url = $(this).attr('href');
            $('.loading_button').show();
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $('.loading_button').hide();
                    toastr.success(data);
                    //  phone_number_table.destroy();
                    $('.emailBodyTable').DataTable().ajax.reload();
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

        $('#import_mail_modal').on('click', function(e) {
            e.preventDefault();
            $.get("{{ route('communication.email.manual-service.mail-import-modal') }}", function(data) {
                $('#add_bulk_mail_modal').html(data);
                $('#add_bulk_mail_modal').modal('show');

                $('#editModal').empty();
            });
        });

        $('#manual_mail_send').on('submit', function(e) {
            e.preventDefault();
            var checkedNodeList = document.querySelectorAll('input[name="email_id[]"]:checked');
            var checkedMailId = Array.from(checkedNodeList).map(input => input.value);
            // var checkedMail = Array.from(checkedNodeList).map(input => input.mail);
            //
            // return;
            var formData = new FormData(this);
            formData.append('checkedMailId', checkedMailId);
            formData.append('bulkImportedMailVar', bulkImportedMailVar);
            formData.append('mailFilterKey', mailFilterKey);
            formData.append('mailFilterType', mailFilterType);

            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.loading_button').hide();
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

        // adding more To fields
        var child = '';
        child += '<div class="col-md-12 mt-2">';
        child += '<div class="row">';
        child += '<div class="col-md-10">';
        child += '<input type="email" name="to[]" class="form-control" data-name="To" id="to" placeholder="Email" />';
        child += '<span class="error error_to"></span>';
        child += '</div>';
        child += '<div class="col-md-2 text-end">';
        child +=
            '<button class="btn btn-sm btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove()" style="padding:4px 20px">X</button>';
        child += '</div>';
        child += '</div>';
        child += '</div>';

        var addMoreMailField = document.getElementById('addMoreMailField');
        var warrantyContainer = document.getElementById('to_area');

        $('#addMoreMailField').on('click', function(e) {
            e.preventDefault();
            $('#to_area').append(child);
        });
    </script>
@endpush
