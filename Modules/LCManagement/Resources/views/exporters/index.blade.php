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
@section('title', 'Exporter - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Expoters') }}</h6>
                </div>
                <div class="d-flex gap-2">
                    <x-table-stat :items="[
                        ['id' => 'totalStat', 'name' => __('Total Supplier'), 'value' => $total_exporters],
                        ['id' => 'activeStat', 'name' => __('Active Supplier'), 'value' => $total_active],
                        [
                            'id' => 'inactiveStat',
                            'name' => __('Inactive Supplier'),
                            'value' => $total_exporters - $total_active,
                        ],
                    ]" />
                    <x-all-buttons>
                        <x-add-button :text="'New Exporters'" :can="'asset_create'" />
                        <x-slot name="after">
                            <x-help-button />
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>
        </div>

        <div class="p-15">
            @can('asset_view')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table exporterTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">Exporter Code</th>
                                                <th class="text-start">@lang('menu.name')</th>
                                                <th class="text-start">@lang('menu.phone')</th>
                                                <th class="text-start">Business</th>
                                                <th class="text-start">@lang('menu.opening_balance')</th>
                                                <th class="text-start">Total Export</th>
                                                <th class="text-start">@lang('menu.total_paid')</th>
                                                <th class="text-start">@lang('menu.total_due')</th>
                                                <th class="text-start">@lang('menu.status')</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Expoters</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_exporter_form" action="{{ route('lc.exporters.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.code') </strong> <i data-bs-toggle="tooltip"
                                        data-bs-placement="right" title="Leave empty to auto generate."
                                        class="fas fa-info-circle tp"></i></label>
                                <input type="text" name="code" class="form-control add_input" data-name="Code"
                                    id="code" placeholder="@lang('menu.code')" />
                                <span class="error error_code"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                                <input type="text" required name="name" class="form-control add_input"
                                    data-name="Full Name" id="name" placeholder="Full Name" />
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.phone') </strong> <span class="text-danger">*</span></label>
                                <input type="tel" required name="phone" class="form-control add_input"
                                    data-name="Phone" id="phone" placeholder="Phone" />
                                <span class="error error_quantity"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.business') </strong> </label>
                                <input type="text" name="business" class="form-control add_input" data-name="Business"
                                    id="business" placeholder="Business" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Alterbative Phone Number </strong> </label>
                                <input type="tel" name="alternative_number" class="form-control add_input"
                                    data-name="alternative_number" class="form-control" id="alternative_number"
                                    placeholder="@lang('menu.alternative_number')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Land Line </strong> </label>
                                <input type="tel" name="land_line" class="form-control add_input"
                                    data-name="land_line" class="form-control" id="land_line" placeholder="Land Line" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.email') </strong> </label>
                                <input type="email" name="email" class="form-control add_input" data-name="email"
                                    id="email" placeholder="Email" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.date_of_birth') </strong> </label>
                                <input type="text" name="date_of_birth" class="form-control add_input"
                                    data-name="date_of_birth" id="date_of_birth" placeholder="Date of Birth" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.id_proof_name') </strong> </label>
                                <input type="text" name="id_proof_name" class="form-control add_input"
                                    data-name="id_proof_name" id="id_proof_name" placeholder="@lang('menu.id_proof_name')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.id_proof_number') </strong> </label>
                                <input type="text" name="id_proof_number" class="form-control add_input"
                                    data-name="id_proof_number" id="id_proof_number" placeholder="@lang('menu.id_proof_number')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.tax_number') </strong> </label>
                                <input type="tel" name="tax_number" class="form-control add_input"
                                    data-name="tax_number" id="tax_number" placeholder="@lang('menu.tax_number')" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.address') </strong> </label>
                                <input type="text" name="address" class="form-control add_input" data-name="address"
                                    id="address" placeholder="Address" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.city')</strong> </label>
                                <input type="text" name="city" class="form-control add_input" data-name="City"
                                    id="name" placeholder="City" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.state') </strong> </label>
                                <input type="text" name="state" class="form-control add_input" data-name="State"
                                    id="state" placeholder="@lang('menu.state')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.zip_code') </strong> </label>
                                <input type="text" name="zip_code" class="form-control add_input"
                                    data-name="zip_code" id="zip_code" placeholder="Zip Code" />
                            </div>

                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.country') </strong> </label>
                                <input type="text" name="country" class="form-control add_input" data-name="country"
                                    id="country" placeholder="@lang('menu.country')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.opening_balance') </strong> </label>
                                <input type="number" name="opening_balance" class="form-control add_input"
                                    data-name="opening_balance" id="opening_balance" min="0" step="0.001"
                                    value="0.00" />
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
        var exporter_table = $('.data_tbl').DataTable({
            "processing": true,
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8]
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('lc.exporters.index') }}",
            },
            columns: [{
                data: 'action',
                name: 'Action'
            }, {
                data: 'exporter_id',
                name: 'exporter_id'
            }, {
                data: 'name',
                name: 'Name'
            }, {
                data: 'phone',
                name: 'phone'
            }, {
                data: 'business',
                name: 'business'
            }, {
                data: 'opening_balance',
                name: 'opening_balance'
            }, {
                data: 'total_export',
                name: 'total_export'
            }, {
                data: 'total_paid',
                name: 'total_paid'
            }, {
                data: 'total_due',
                name: 'total_due'
            }, {
                data: 'status',
                name: 'status'
            }],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        exporter_table.buttons().container().appendTo('#exportButtonsContainer');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('submit', '#add_exporter_form', function(e) {
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
                    $('.error').html('');

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.success(data);
                    $('#add_exporter_form')[0].reset();
                    $('#addModal').modal('hide');
                    $('.exporterTable').DataTable().ajax.reload();
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

        // delete part
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

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    exporter_table.ajax.reload();

                    toastr.error(data.responseJSON);
                },
                error: function(data) {
                    toastr.error(data.responseJSON)
                    exporter_table.ajax.reload();
                }
            });
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('date_of_birth'),
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

        // Pass Editable Data Moon
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',

                success: function(data) {

                    $('.data_preloader').hide();


                    $('#edit-content').empty();
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

        $(document).ready(function() {
            $(document).on('click', '.change_status', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $.confirm({
                    'title': 'Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    success: function(data) {
                                        toastr.success(data);
                                        exporter_table.ajax.reload();
                                        location.ajax.reload();
                                        refreshStat();
                                    }
                                });
                            }
                        },
                        'No': {
                            'class': 'no btn-primary',
                            'action': function() {

                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
