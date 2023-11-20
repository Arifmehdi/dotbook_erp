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
                    <h6>Requests</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'asset_requests_create'" :text="'New Request'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

        </div>

        <div class="p-15">
            @can('asset_requests_view')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <form id="filter_form" class="px-2">
                                    <div class="form-group row align-items-end">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Asset Name </strong></label>
                                            <select name="f_asset_id" class="form-control submit_able" id="f_asset_id"
                                                autofocus>
                                                <option value="">All Asset</option>
                                                @foreach ($assets as $asset)
                                                    <option value="{{ $asset->id }}">{{ $asset->asset_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Request For </strong></label>
                                            <select name="f_request_for_id" class="form-control submit_able"
                                                id="f_request_for_id" autofocus>
                                                <option value="">@lang('menu.all') @lang('menu.users')</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.created_by') </strong></label>
                                            <select name="f_created_by_id" class="form-control submit_able" id="f_created_by_id"
                                                autofocus>
                                                <option value="">All Creator</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="f_date" id="f_date"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i>@lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('asset_requests_view')
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table requestTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">@lang('menu.title')</th>
                                                <th class="text-start">@lang('menu.asset')</th>
                                                <th class="text-end">Request For</th>
                                                <th class="text-end">@lang('menu.date')</th>
                                                <th class="text-end">@lang('menu.description')</th>
                                                <th class="text-end">@lang('menu.created_by')</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Request</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_request_form" action="{{ route('assets.request.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                                <input type="text" required data-name="Title" name="title" class="form-control "
                                    data-name="title" id="title" placeholder="@lang('menu.title')" />
                                <span class="error error_title"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Asset Code And Name </strong></label>
                                <select name="asset_id" required class="form-control submit_able " id="asset_id"
                                    data-name="Asset Code And Name" autofocus>
                                    <option value="">Select Asset</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}">{{ $asset->asset_name }} (
                                            {{ $asset->asset_code }} )</option>
                                    @endforeach
                                </select>
                                <span class="error error_asset_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Request For </strong> </label>
                                <select name="request_for_id" required class="form-control submit_able "
                                    id="request_for_id" autofocus data-name="Request For">
                                    <option value="">Select Request For</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->prefix }} {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_request_for_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.date') </strong></label>
                                <input type="text" name="date" required class="form-control " data-name="Date"
                                    id="request_date" placeholder="Request Date" />
                                <span class="error error_date"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <label><strong>@lang('menu.description')</strong></label>
                            <div class="col-md-12">
                                <textarea name="description" rows="3" class="form-control ckEditor" id="description"
                                    placeholder="Description"></textarea>
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
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-3',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('assets.request.index') }}",
                "data": function(d) {
                    d.f_asset_id = $('#f_asset_id').val();
                    d.f_request_for_id = $('#f_request_for_id').val();
                    d.f_created_by_id = $('#f_created_by_id').val();
                    d.f_date = $('#f_date').val();
                }
            },
            columns: [{
                data: 'action',
                name: 'Asset Code'
            }, {
                data: 'title',
                name: 'Title'
            }, {
                data: 'asset',
                name: 'Asset'
            }, {
                data: 'request_for',
                name: 'Request For'
            }, {
                data: 'date',
                name: 'Date'
            }, {
                data: 'description',
                name: 'Description'
            }, {
                data: 'creator',
                name: 'Created By'
            }],
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

        $(document).on('submit', '#add_request_form', function(e) {
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
                    toastr.success(data);
                    $('#add_request_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('#addModal').modal('hide');
                    $('.requestTable').DataTable().ajax.reload();
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


        new Litepicker({
            singleMode: true,
            element: document.getElementById('request_date'),
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
            element: document.getElementById('f_date'),
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
    </script>
@endpush
