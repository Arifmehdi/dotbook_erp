@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }
    </style>
@endpush
@section('title', 'Assets - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Audits</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'asset_audits_create'" :text="'New Audits'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            @can('asset_audits_view')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Assets </strong></label>
                                            <select name="f_asset" class="form-control submit_able form-select" id="f_asset"
                                                autofocus>
                                                <option value="">All Assets</option>
                                                @foreach ($assets as $asset)
                                                    <option value="{{ $asset->id }}">{{ $asset->asset_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Auditors </strong></label>
                                            <select name="f_auditors" class="form-control submit_able" id="f_auditors"
                                                autofocus>
                                                <option value="">All Assets</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->prefix }} {{ $user->name }}
                                                        {{ $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.status') </strong></label>
                                            <select name="f_status" class="form-control submit_able form-select" id="f_status"
                                                autofocus>
                                                <option value="">All Status</option>
                                                <option value="1">Accepted</option>
                                                <option value="2">Rejected</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="f_audit_start_date" id="f_audit_start_date"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="f_audit_end_date" id="f_audit_end_date"
                                                    class="form-control to_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-1 col-md-4">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('asset_audits_view')
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table auditTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">@lang('menu.title')</th>
                                                <th class="text-start">Auditor</th>
                                                <th class="text-end">@lang('menu.asset')</th>
                                                <th class="text-end">Audit Date</th>
                                                <th class="text-end">@lang('menu.status')</th>
                                                <th class="text-end">@lang('menu.created_at')</th>

                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Audit</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_audit_form" action="{{ route('assets.audit.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.title') </strong><span class="text-danger">*</span></label>
                                <input type="text" required name="title" class="form-control" data-name="Title"
                                    id="title" placeholder="@lang('menu.title')" />

                                <span class="error error_title"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Auditor </strong> <span class="text-danger">*</span></label>
                                <select name="auditor_id" required data-name="Auditor" class="form-control submit_able"
                                    id="auditor_id" autofocus>
                                    <option value="">Select Auditor</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->prefix }} {{ $user->name }}
                                            {{ $user->last_name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_auditor_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Asset Code And Name </strong> <span class="text-danger">*</span></label>
                                <select name="asset_id" required data-name="Asset Name" class="form-control submit_able"
                                    id="asset_id" autofocus>
                                    <option value="">Select Asset</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}">{{ $asset->asset_name }} (
                                            {{ $asset->asset_code }} )</option>
                                    @endforeach
                                </select>
                                <span class="error error_asset_id"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Audit Date </strong><span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="in_audit_date"><i
                                                class="fas fa-calendar-week input_f"></i></span>
                                    </div>
                                    <input type="text" required data-name="Audit Date" name="audit_date"
                                        id="audit_date_input" class="form-control from_date date" autocomplete="off">
                                </div>
                                <span class="error error_audit_date"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.status') </strong><span class="text-danger">*</span></label>
                                <select name="status" required data-name="Status" class="form-control submit_able"
                                    id="status" autofocus>
                                    <option value="">@lang('menu.select_status')</option>
                                    <option value="1">Accepted</option>
                                    <option value="2">Rejected</option>
                                </select>
                                <span class="error error_status"></span>
                            </div>

                        </div>

                        <div class="form-group row mt-1">
                            <label><strong>@lang('menu.reason') </strong> </label>
                            <div class="col-md-12">
                                <textarea name="reason" rows="3" class="form-control ckEditor" id="reason"
                                    placeholder="@lang('menu.reason')"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-start submit_button float-end">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">
        </div>
    </div>
    <!-- Edit Modal -->
    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var licenses_table = $('.data_tbl').DataTable({
            "processing": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('assets.audit.index') }}",
                "data": function(d) {
                    d.f_auditors = $('#f_auditors').val();
                    d.f_asset = $('#f_asset').val();
                    d.f_audit_start_date = $('#f_audit_start_date').val();
                    d.f_audit_end_date = $('#f_audit_end_date').val();
                    d.f_status = $('#f_status').val();
                }
            },
            columns: [{
                    data: 'action',
                    name: 'Asset Code'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'auditor',
                    name: 'auditor_id'
                },
                {
                    data: 'asset',
                    name: 'asset_id'
                },
                {
                    data: 'audit_date',
                    name: 'audit_date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });
        licenses_table.buttons().container().appendTo('#exportButtonsContainer');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('submit', '#add_audit_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.error').html('');
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');

                    toastr.success(data);
                    $('#add_audit_form')[0].reset();
                    $('#addModal').modal('hide');
                    $('.auditTable').DataTable().ajax.reload();
                    $('.add_input').addClass('bdr-red');
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            licenses_table.ajax.reload();
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
                    licenses_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });


        $(document).on('click', '#edit_id', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('.data_preloader').hide();
                    $('#edit-content').html(data);
                    $('#editModal').modal('show');
                },
                error: function(err) {
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else if (err.status == 500) {
                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('audit_date_input'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('f_audit_end_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('f_audit_start_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });
    </script>
@endpush
