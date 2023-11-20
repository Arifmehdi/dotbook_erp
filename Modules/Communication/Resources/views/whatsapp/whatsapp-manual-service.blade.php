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
@section('title', 'Whatsapp Settings - ')
@section('content')
    <div class="body-wraper">


        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Whatsapp Setup & Send Message</h6>
                </div>
                <x-all-buttons>
                    <a href="#" id="import_phone_number_modal" class="btn text-white btn-sm"><span><i
                                class="fa-thin fa-file-arrow-down fa-2x"></i><br>Import Number</span></a>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab_list_area">
                                <ul class="btn-group">
                                    <li>
                                        <a id="tab_btn" data-show="receiver-phone" class="tab_btn tab_active"
                                            href="#">
                                            <i class="fas fa-scroll"></i> Receiver Number
                                        </a>
                                    </li>
                                    <li>
                                        <a id="tab_btn" data-show="inactive-phone" class="tab_btn1" href="#">
                                            <i class="fas fa-info-circle"></i> Inactive Number
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab_contant receiver-phone">
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
                                    <form id="all_phone_checked_form" action="#" method="post">
                                        @csrf
                                        <table class="display data_tbl data__table whatsappNumberBodyTable"
                                            id="whatsappNumberBodyTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Check</th>
                                                    <th class="text-center">Phone</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </form>
                                </div>
                                <span>Double click on Phone to block</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header py-1">
                            <span>Manual Phone</span>
                        </div>
                        <div class="card-body">
                            <form id="manual_whatsapp_send"
                                action="{{ route('communication.whatsapp.manual-service.whatsapp.send') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    {{-- sender selection part --}}
                                    <div class="col-sm-7">
                                        <select name="whatsapp_serve_id" id="" class="form-control form-select">
                                            <option value="">--Select Sender--</option>
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}">{{ $server->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- body selection part --}}
                                    <div class="col-sm-7">
                                        <select name="whatsapp_format_body_id" id=""
                                            class="form-control form-select">
                                            <option value="">--Select Body--</option>
                                            @foreach ($bodes as $body)
                                                <option value="{{ $body->id }}">{{ $body->format_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-7" id="to_area">
                                        <input type="text" name="to[]" data-name="To" id="to"
                                            class="form-control" placeholder="01711222333">
                                        <span class="error error_to"></span>
                                    </div>
                                    <div class="col-sm-5">
                                        <button type="button" class="btn btn-primary btn-sm mb-2 p-1"
                                            id="addMorePhoneField"><i class="fas fa-plus"
                                                style="padding:2px 10px"></i></button>
                                    </div>
                                    <div class="col-7">
                                        <textarea name="body_format" id="body_format" class="form-control body_text_count" rows="4"
                                            placeholder="{{ __('Whatsapp Body Text') }}"></textarea>
                                        <span class="error error_body_format"></span>
                                    </div>
                                    <div class="col-sm-7 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-success">Send SMS</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- import modal start --}}
            <div class="modal fade" id="add_bulk_phone_modal" tabindex="-1" data-bs-backdrop="static"
                data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
            {{-- import modal end --}}
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        //global variable
        var phoneFilterKey = 'all';
        var statusType = 'active';
        var phoneFilterType = 'all';
        var bulkImportedPhoneNumber = 0;
        var total_characters = 0;
        var whatsapp_sms_body = 0;
        var sms_sub = 0;
        var checkedPhoneId = 'all';

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();

            $('.tab_btn').removeClass('tab_active');
            var show_content = $(this).data('show');
            show_content = $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });

        var whatsapp_number_table = $('#whatsappNumberBodyTable').DataTable({
            "processing": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.whatsapp.manual-service') }}",
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                // {data: 'status', name: 'status'},
            ],
            fnDrawCallback: function() {
                $('.data_preloader').hide();
            }
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            var show_content = $(this).data('show');
            if (show_content == 'receiver-phone') {
                statusType = 'active';
            } else if (show_content == "inactive-phone") {
                statusType = 'inactive';
            } else {
                return false;
            }
            whatsapp_number_table.destroy();
            $('.loading_button').show();
            var url =
                "{{ route('communication.whatsapp.manual-service-whatsapp-status-wise-list', ['statusType' => ':statusType']) }}";
            url = url.replace(':statusType', statusType);

            whatsapp_number_table = $('#whatsappNumberBodyTable').DataTable({
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
                        data: 'phone',
                        name: 'phone'
                    },
                ]
            });
        });

        $('.mailFilter').on('change', function() {
            bulkImportedPhoneNumber = 0;
            phoneFilterKey = $(this).val()
            phoneFilterType = $(this).data('type');
            whatsapp_number_table.destroy();
            $('.loading_button').show();
            var url =
                "{{ route('communication.whatsapp.manual-service-whatsapp-list', ['filterType' => ':filterType', 'filterKey' => ':filterKey']) }}";
            url = url.replace(':filterType', phoneFilterType);
            url = url.replace(':filterKey', phoneFilterKey);

            whatsapp_number_table = $('#whatsappNumberBodyTable').DataTable({
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
                        data: 'phone',
                        name: 'phone'
                    },
                ]
            });
        });


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
                    //  whatsapp_number_table.destroy();
                    $('.whatsappNumberBodyTable').DataTable().ajax.reload();
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

        $('#import_phone_number_modal').on('click', function(e) {
            e.preventDefault();
            $.get("{{ route('communication.whatsapp.manual-service.whatsapp-import-modal') }}", function(data) {
                $('#add_bulk_phone_modal').html(data);
                $('#add_bulk_phone_modal').modal('show');

                $('#editModal').empty();
            });
        });

        $('#manual_whatsapp_send').on('submit', function(e) {
            e.preventDefault();
            var checkedNodeList = document.querySelectorAll('input[name="phone_id[]"]:checked');
            var checkedPhoneId = Array.from(checkedNodeList).map(input => input.value);
            // var checkedMail = Array.from(checkedNodeList).map(input => input.mail);
            //
            // return;
            var formData = new FormData(this);
            formData.append('checkedPhoneId', checkedPhoneId);
            formData.append('bulkImportedPhoneNumber', bulkImportedPhoneNumber);
            formData.append('phoneFilterKey', phoneFilterKey);
            formData.append('phoneFilterType', phoneFilterType);

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
                    $('.format_name').val('');
                    $('.body_text_count').val('');
                    $('.whatsapp_subject_count').val('');
                    total_characters = 0;
                    $(".total_characters").text(total_characters)
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
        child += '<input type="text" name="to[]" class="form-control" data-name="To" id="to" placeholder="phone number" />';
        child += '<span class="error error_to"></span>';
        child += '</div>';
        child += '<div class="col-md-2 text-end">';
        child +=
            '<button class="btn btn-sm btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove()" style="padding:4px 20px">X</button>';
        child += '</div>';
        child += '</div>';
        child += '</div>';

        var addMorePhoneField = document.getElementById('addMorePhoneField');
        var warrantyContainer = document.getElementById('to_area');

        $('#addMorePhoneField').on('click', function(e) {
            e.preventDefault();
            $('#to_area').append(child);
        });
    </script>
@endpush
