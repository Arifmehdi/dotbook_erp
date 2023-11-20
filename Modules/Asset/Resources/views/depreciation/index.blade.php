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
@section('title', 'Depreciation - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Depreciation</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            @can('asset_depreciation_view')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.asset') </strong></label>
                                            <select name="f_asset_id" class="form-control submit_able form-select"
                                                id="f_asset_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($asset as $asset)
                                                    <option value="{{ $asset->id }}">{{ $asset->asset_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Depreciation Method </strong></label>
                                            <select name="f_depreciation_method" class="form-control submit_able form-select"
                                                id="f_depreciation_method" autofocus>
                                                <option class="selected" value="">Select Depreciation Method
                                                </option>
                                                <option value="1">Straight-Line</option>
                                                <option value="2">Declining Balance Method</option>
                                                <option value="3">Sum-of-the-Years' Digits Method</option>
                                                <option value="4">Units of Production Method</option>

                                            </select>
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
            @endcan
            @can('asset_depreciation_view')
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table assetTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">Asset Code</th>
                                                <th class="text-start">Savlage Value</th>
                                                <th class="text-start">Depreciation Method</th>
                                                <th class="text-start">Depreciation Year</th>
                                                <th class="text-start">Daily Depreciation</th>
                                                <th class="text-start">Monthly Depreciation</th>
                                                <th class="text-start">Yearly Depreciation</th>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
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
        var asset_table = $('.data_tbl').DataTable({
            "processing": true,
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1'
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1'
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1'
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('assets.depreciation.index') }}",
                "data": function(d) {
                    d.f_asset_id = $('#f_asset_id').val();
                    d.f_depreciation_method = $('#f_depreciation_method').val();

                }
            },
            columns: [{
                data: 'asset',
                name: 'Asset'
            }, {
                data: 'salvage_value',
                name: 'salvage_value'
            }, {
                data: 'dep_method',
                name: 'dep_method'
            }, {
                data: 'dep_year',
                name: 'dep_year'
            }, {
                data: 'daily_dep',
                name: 'daily_dep'
            }, {
                data: 'monthly_dep',
                name: 'monthly_dep'
            }, {
                data: 'yearly_dep',
                name: 'yearly_dep'
            }, ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });
        asset_table.buttons().container().appendTo('#exportButtonsContainer');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            asset_table.ajax.reload();
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

                    asset_table.ajax.reload();
                    toastr.error(data);
                }
            });
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

        new Litepicker({
            singleMode: true,
            element: document.getElementById('f_end_date'),
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
