@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'All Delivery Orders - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.manage_delivery_order')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        @if (auth()->user()->can('create_add_sale'))
                            <a href="{{ route('sales.create') }}" id="add_btn" class="btn text-white btn-sm">
                                <span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.new_order')</span>
                            </a>
                        @endif
                    </x-slot>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="row g-0">
                <div class="col-md-12 p-15 pb-0">
                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <form id="filter_form" class="px-2">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.customer') </strong></label>
                                        <select name="customer_account_id" class="form-control select2 form-select" id="customer_account_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($customerAccounts as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name . ' (' . $customer->phone . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if (!auth()->user()->can('view_own_sale'))
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>{{ __("Sr.") }}</strong></label>
                                            <select name="user_id" class="form-control submit_able select2 form-select" id="user_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-sm btn-info">
                                                <i class="fa-solid fa-filter-list"></i> @lang('menu.filter')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row margin_row">
            <div class="col-12 p-15">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl table-sm sale_or_order_table">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.actions')</th>
                                        <th>@lang('menu.date')</th>
                                        <th>@lang('menu.do_id')</th>
                                        <th>@lang('menu.customer')</th>
                                        <th>{{ __("Sr.") }}</th>
                                        <th>@lang('menu.delivery_status')</th>
                                        <th>@lang('menu.do_qty') (@lang('menu.as_base_unit'))</th>
                                        <th>@lang('menu.delivered_qty') (@lang('menu.as_base_unit'))</th>
                                        <th>@lang('menu.left_qty') (@lang('menu.as_base_unit'))</th>
                                        <th>@lang('menu.total_amount')</th>
                                        <th>@lang('menu.received_amount')</th>
                                        <th>@lang('menu.receipt_details')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="6" class="text-white text-end">@lang('menu.total') :
                                            ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                        <th id="total_ordered_qty" class="text-white"></th>
                                        <th id="total_delivered_qty" class="text-white"></th>
                                        <th id="do_total_left_qty" class="text-white"></th>
                                        <th id="total_payable_amount" class="text-white"></th>
                                        <th id="paid" class="text-white"></th>
                                        <th class="text-white">---</th>
                                    </tr>
                                </tfoot>
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

    <div id="details"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        $('.select2').select2();

        var do_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
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
                "url": "{{ route('sales.delivery.order.list') }}",
                "data": function(d) {
                    d.customer_account_id = $('#customer_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'action',
                    name: 'do_date'
                },
                {
                    data: 'do_date',
                    name: 'do_date'
                },
                {
                    data: 'do_id',
                    name: 'do_id',
                    className: 'fw-bold'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'sr',
                    name: 'sr.name'
                },
                {
                    data: 'delivery_status',
                    name: 'due',
                },
                {
                    data: 'total_ordered_qty',
                    name: 'total_ordered_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'total_delivered_qty',
                    name: 'sr.prefix',
                    className: 'fw-bold'
                },
                {
                    data: 'do_total_left_qty',
                    name: 'sr.last_name',
                    className: 'fw-bold'
                },
                {
                    data: 'total_payable_amount',
                    name: 'sales.quotation_id',
                    className: 'fw-bold'
                },
                {
                    data: 'paid',
                    name: 'sales.quotation_id',
                    className: 'fw-bold'
                },
                {
                    data: 'receipt_details',
                    name: 'sales.quotation_id'
                },
            ],
            fnDrawCallback: function() {

                var total_ordered_qty = sum_table_col($('.data_tbl'), 'total_ordered_qty');
                $('#total_ordered_qty').text(bdFormat(total_ordered_qty));

                var total_delivered_qty = sum_table_col($('.data_tbl'), 'total_delivered_qty');
                $('#total_delivered_qty').text(bdFormat(total_delivered_qty));

                var do_total_left_qty = sum_table_col($('.data_tbl'), 'do_total_left_qty');
                $('#do_total_left_qty').text(bdFormat(do_total_left_qty));

                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));
                $('.data_preloader').hide();
            }
        });

        do_table.buttons().container().appendTo('#exportButtonsContainer');

        function sum_table_col(table, class_name) {
            var sum = 0;
            table.find('tbody').find('tr').each(function() {

                if (parseFloat($(this).find('.' + class_name).data('value'))) {

                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            do_table.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();

            var body = $('.print_details').html();
            var header = $('.heading_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
                footer: null,
            });
        });

        // Make print
        $(document).on('click', '#print_gate_pass', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.get(url, function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: null,
                    footer: null,
                });
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
                    do_table.ajax.reload(null, false);
                    toastr.error(data);
                }
            });
        });

        $(document).on('click', '.print_challan_btn', function(e) {
            e.preventDefault();

            var body = $('.challan_print_template').html();
            var header = $('.heading_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 800,
                header: null,
                footer: null,
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
            format: 'DD-MM-YYYY',
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_view_modal_body').html(data);
                $('#paymentViewModal').modal('show');
            });
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {

                // $('#add_btn').click();
                window.location = $('#add_btn').attr('href');
                return false;
            }
        }

        function getCustomer() {}

        // Make print
        $(document).on('click', '.final_print_btn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.get(url, function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: null,
                    footer: null,
                });
            });
        });
    </script>
@endpush
