@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Assets - ')
@section('content')

    <div class="body-wraper">
        <div class="sec-name">
            <div class="section-header">
                <h6>Consume Services</h6>
            </div>
            <x-all-buttons>
                <x-add-button :can="'asset_allocation_create'" :text="'New Consume'" />
                <x-slot name="after">
                    <x-help-button />
                </x-slot>
            </x-all-buttons>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>Service Code </strong></label>
                                        <select name="service_id" class="form-control submit_able" id="f_service_id"
                                            autofocus>
                                            <option value="">Service Code</option>
                                            @foreach ($consume_services as $service)
                                                <option value="{{ $service->id }}">{{ $service->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>Asset Code </strong></label>
                                        <select name="asset_id" class="form-control submit_able form-select" id="f_asset_id"
                                            autofocus>
                                            <option value="">All Assets</option>
                                            @foreach ($assets as $asset)
                                                <option value="{{ $asset->id }}">{{ $asset->asset_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="f_from_date"
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
                                            <input type="text" name="f_to_date" id="f_to_date"
                                                class="form-control f_to_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @can('asset_allocation_view')
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table serviceConsumeTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">Service Code</th>
                                                <th class="text-start">Asset Name</th>
                                                <th class="text-start">@lang('menu.supplier_name')</th>
                                                <th class="text-start">Service Type</th>
                                                <th class="text-start">@lang('menu.warranty')</th>
                                                <th class="text-start">Service Cost</th>
                                                <th class="text-start">@lang('menu.start_date')</th>
                                                <th class="text-start">@lang('menu.end_date')</th>
                                                <th class="text-start">@lang('menu.description')</th>
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
    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Service</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_service_consume" action="{{ route('assets.consume.services.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Asset Name </strong> <span class="text-danger">*</span></label>
                                <select required name="asset_id" class="form-control submit_able" id="asset_id"
                                    autofocus>
                                    <option value="">Select Asset Name</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}">{{ $asset->asset_name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_asset_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Service Type </strong> <span class="text-danger">*</span></label>
                                <select name="service_type" required class="form-control submit_able" id="service_type"
                                    autofocus>
                                    <option value="">Select Service Type</option>
                                    <option value="1">Upgrade</option>
                                    <option value="2">Repair</option>

                                </select>
                                <span class="error error_service_type"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Service Cost </strong><span class="text-danger">*</span></label>
                                <input type="number" required name="service_cost" class="form-control"
                                    data-name="service_cost" id="service_cost" placeholder="Service Cost" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Service Start </strong> <span class="text-danger">*</span></label>
                                <input type="text" required name="start_date" class="form-control"
                                    data-name="start_date" id="start_date" placeholder="Date" autocomplete="off" />
                                <span class="error error_start_date"></span>
                            </div>


                        </div>
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Service End </strong></label>
                                <input type="text" name="end_date" class="form-control" data-name="end_date"
                                    id="end_date" placeholder="Date" autocomplete="off" />
                            </div>
                            <div class="col-xl-3 col-md-12">
                                <label><strong>@lang('menu.description')</strong></label>
                                <textarea name="description" rows="3" class="form-control ckEditor" id="description"
                                    placeholder="Asset Description"></textarea>
                            </div>

                        </div>

                        <div class="form-group row mt-3">
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
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
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
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Setup ajax for csrf token end.

        // code for data table show
        var consume_table = $('.serviceConsumeTable').DataTable({
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
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            ajax: {
                "url": "{{ route('assets.consume.services.index') }}",
                "data": function(d) {
                    d.f_service_id = $('#f_service_id').val();
                    d.f_asset_id = $('#f_asset_id').val();
                    d.f_from_date = $('#f_from_date').val();
                    d.f_to_date = $('#f_to_date').val();
                }
            },
            columns: [{
                data: 'action'
            }, {
                data: 'code',
                name: 'code'
            }, {
                data: 'asset_name',
                name: 'Asset Name'
            }, {
                data: 'supplier_name',
                name: 'supplier_name'
            }, {
                data: 'service_type',
                name: 'Maintenance'
            }, {
                data: 'have_warranty',
                name: 'warranty'
            }, {
                data: 'cost',
                name: 'service_cost'
            }, {
                data: 'start_date',
                name: 'start_date'
            }, {
                data: 'end_date',
                name: 'end_date'
            }, {
                data: 'notes',
                name: 'notes'
            }],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });
        // Border end for avobe code data table show
        // Allocation Add start
        consume_table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).on('submit', '#add_service_consume', function(e) {
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

                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');

                    toastr.success(data);
                    $('#addModal').modal('hide');
                    $('.serviceConsumeTable').DataTable().ajax.reload();
                    $('#add_service_consume')[0].reset();
                },
                error: function(err) {
                    $('.submit_button').prop('type', 'submit');
                    alert('ERROR')
                    return;
                }
            });
        });
        // Allocation add end
        // Date picker
        new Litepicker({
            singleMode: true,
            element: document.getElementById('end_date'),
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
            element: document.getElementById('start_date'),
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
            element: document.getElementById('f_from_date'),
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
            element: document.getElementById('f_to_date'),
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
        // Date picker
        // Delete Part Start
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
                    consume_table.ajax.reload();

                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.success(data.responseJSON);
                },
                error: function(err) {
                    toastr.error(err.responseJSON)
                    asset_table.ajax.reload();
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
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
        // edit Part end

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            consume_table.ajax.reload();
        });
    </script>
@endpush
