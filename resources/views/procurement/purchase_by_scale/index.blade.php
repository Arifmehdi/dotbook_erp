@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Purchase By Scale List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.purchase_by_sale_list')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :href="route('purchases.create')" :can="'purchase_add'" :text="'Add Purchases'" :is_modal="false" />
                    </x-slot>
                </x-all-buttons>
            </div>
            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            <div class="col-xl-2 col-md-3">
                                                <label><strong>@lang('menu.supplier') </strong></label>
                                                <select name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" autofocus>
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($supplierAccounts as $supplier)
                                                        <option value="{{ $supplier->id }}">
                                                            {{ $supplier->name . '/' . $supplier->phone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-3">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_f"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-3">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_f"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-3">
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fa-solid fa-filter-list"></i> @lang('menu.filter')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-0 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.actions')</th>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.voucher_no')</th>
                                            <th>@lang('menu.supplier')</th>
                                            <th>@lang('menu.challan_no')</th>
                                            <th>{{ __("Challan Date") }}</th>
                                            <th>@lang('menu.vehicle_no')</th>
                                            <th>@lang('menu.driver_name')</th>
                                            <th>@lang('menu.driver_phone')</th>
                                            <th>@lang('menu.status')</th>
                                            <th>{{ __("First Weight") }}</th>
                                            <th>@lang('menu.last_weight')</th>
                                            <th>@lang('menu.net_weight')</th>
                                            <th>@lang('menu.created_by')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <form id="deleted_form" action="" method="post">
                            @method('DELETE')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('
                                successMsg ') }}');
        @endif

        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
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
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('purchases.by.scale.index') }}",
                "data": function(d) {
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                data: 'action'
            }, {
                data: 'date',
                name: 'date'
            }, {
                data: 'voucher_no',
                name: 'voucher_no'
            }, {
                data: 'supplier_name',
                name: 'suppliers.name'
            }, {
                data: 'challan_no',
                name: 'challan_no'
            }, {
                data: 'challan_date',
                name: 'challan_date'
            }, {
                data: 'vehicle_number',
                name: 'vehicle_number'
            }, {
                data: 'driver_name',
                name: 'driver_name'
            }, {
                data: 'driver_phone',
                name: 'driver_phone'
            }, {
                data: 'status',
                name: 'voucher_no'
            }, {
                data: 'first_weight',
                name: 'first_weight'
            }, {
                data: 'last_weight',
                name: 'last_weight'
            }, {
                data: 'net_weight',
                name: 'net_weight'
            }, {
                data: 'created_by',
                name: 'created_by.name'
            }, ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {

                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            })
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
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

                    table.ajax.reload(null, false);
                    toastr.error(data);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            table.ajax.reload();
        });

        // Make print
        $(document).on('click', '#printBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
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
            element: document.getElementById('to_date'),
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

        function getSupplier() {}
    </script>
@endpush
