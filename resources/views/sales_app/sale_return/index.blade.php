@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Sale Returns - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.sale_returns')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span
                                    class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            <div class="row g-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-3 g-xl-3 g-xxl-3">
                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.customer') </strong></label>
                                        <select name="customer_account_id" class="form-control select2 form-select"
                                            id="customer_account_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($customerAccounts as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name . ' (' . $customer->phone . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if (!auth()->user()->can('view_own_sale'))
                                        <div class="col-xl-2 col-md-6">
                                            <label><strong>@lang('menu.sr') </strong></label>
                                            <select name="user_id" class="form-control select2 form-select" id="user_id"
                                                autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_f"></i>
                                                </span>
                                            </div>

                                            <input type="text" name="to_date" id="to_date" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-1 col-md-6">
                                        <label></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-sm  btn-info"><i
                                                    class="fa-solid fa-filter-list"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-startx">@lang('menu.actions')</th>
                                            <th class="text-startx">@lang('menu.date')</th>
                                            <th class="text-startx">@lang('menu.voucher_no')</th>
                                            <th class="text-startx">@lang('menu.parent_sale')</th>
                                            <th class="text-startx">@lang('menu.customer_name')</th>
                                            <th class="text-startx">@lang('menu.sr')</th>
                                            <th class="text-startx">@lang('menu.created_by')</th>
                                            <th class="text-startx">@lang('menu.stored_location')</th>
                                            <th class="text-startx">@lang('menu.total_qty') (@lang('menu.as_base_unit'))</th>
                                            <th class="text-startx">@lang('menu.total_returned_amount')</th>
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
    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        var sales_table = $('.data_tbl').DataTable({
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
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('sales.returns.index') }}",
                "data": function(d) {
                    d.customer_account_id = $('#customer_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'voucher_no',
                    name: 'sale_returns.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'parent_invoice_id',
                    name: 'sales.invoice_id',
                    className: 'fw-bold'
                },
                {
                    data: 'cus_name',
                    name: 'customers.name'
                },
                {
                    data: 'sr',
                    name: 'sr.name'
                },
                {
                    data: 'createdBy',
                    name: 'created_by.name'
                },
                {
                    data: 'stored_location',
                    name: 'warehouses.warehouse_name'
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'total_return_amount',
                    name: 'total_return_amount',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        sales_table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            sales_table.ajax.reload();
        });

        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();

            $.get(url, function(data) {

                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
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

                    sales_table.ajax.reload();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        toastr.error(data);
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();

            var body = $('.print_details').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 1000,
                header: null,
            });
        });

        $(document).on('click', '#delete_payment', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $('#payment_deleted_form').attr('action', url);

            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#payment_deleted_form').submit();
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
    </script>

    <script>
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
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
