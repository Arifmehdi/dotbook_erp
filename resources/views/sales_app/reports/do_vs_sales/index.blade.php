@extends('layout.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'DO Vs Sales Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.do_vs_ales_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row g-1">
                    <div class="col-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
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

                                        <div class="col-xl-4 col-md-4">
                                            <button type="submit" id="filter_button" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                                                <th class="text-start">@lang('short.delivery_date')</th>
                                                <th class="text-start">@lang('menu.do_id')</th>
                                                <th class="text-start">@lang('short.delivery_order_left_qty') (@lang('menu.as_base_unit'))</th>
                                                <th class="text-start">@lang('menu.invoice_date')</th>
                                                <th class="text-start">@lang('menu.invoice_id')</th>
                                                <th class="text-start">@lang('menu.vehicle_no')</th>
                                                <th class="text-start">@lang('menu.sold_quantity') (@lang('menu.as_base_unit'))</th>
                                                <th class="text-start">@lang('menu.sold_net_total')</th>
                                                <th class="text-start">@lang('menu.weight')</th>
                                                <th class="text-start">@lang('menu.net_weight')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <th class="text-end" colspan="6">Total : </th>
                                            <th class="text-start" id="total_sold_qty"></th>
                                            <th class="text-start" id="sold_net_total"></th>
                                            <th class="text-start">---</th>
                                            <th class="text-start" id="net_weight"></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Set accounts in payment and payment edit form

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
                // {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1'},
            ],
            aaSorting: [
                [3, 'desc']
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.do.vs.sales.report.index') }}",
                "data": function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'do_date',
                    name: 'do_date'
                },
                {
                    data: 'do_id',
                    name: 'do_id',
                    className: 'fw-bold'
                },
                {
                    data: 'do_total_left_qty',
                    name: 'do_total_left_qty'
                },
                {
                    data: 'invoice_date',
                    name: 'date'
                },
                {
                    data: 'invoice_id',
                    name: 'invoice_id',
                    className: 'fw-bold'
                },
                {
                    data: 'do_car_number',
                    name: 'do_car_number'
                },
                {
                    data: 'total_sold_qty',
                    name: 'total_sold_qty'
                },
                {
                    data: 'sold_net_total',
                    name: 'sales.net_total_amount'
                },
                {
                    data: 'weight',
                    name: 'sales.net_total_amount'
                },
                {
                    data: 'net_weight',
                    name: 'sales.net_total_amount'
                },
            ],
            fnDrawCallback: function() {

                var total_sold_qty = sum_table_col($('.data_tbl'), 'total_sold_qty');
                $('#total_sold_qty').text(bdFormat(total_sold_qty));

                var sold_net_total = sum_table_col($('.data_tbl'), 'sold_net_total');
                $('#sold_net_total').text(bdFormat(sold_net_total));

                var net_weight = sum_table_col($('.data_tbl'), 'net_weight');
                $('#net_weight').text(bdFormat(net_weight));

                $('.data_preloader').hide();
            },
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

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
            table.ajax.reload();
            $('.data_preloader').show();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.do.vs.sales.report.print') }}";
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date
                },
                success: function(data) {
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
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
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
