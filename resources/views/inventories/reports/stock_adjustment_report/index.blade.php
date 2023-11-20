@extends('layout.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

        .sale_purchase_and_profit_area {position: relative;}
        .data_preloader{top:2.3%}
        .sale_and_purchase_amount_area table tbody tr th{text-align: left;}
        .sale_and_purchase_amount_area table tbody tr td{text-align: left;}
    </style>
@endpush
@section('title', 'Stock Adjustment Reports - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.stock_adjustment_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button class="btn text-white btn-sm" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-lg-8 col-md-9">
                                        <form id="filter_form">
                                            <div class="form-group row align-items-end g-2">
                                                <div class="col-xl-3 col-md-5">
                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_i"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-md-5">
                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-md-2">
                                                    <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sale_purchase_and_profit_area">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6></div>
                    <div id="data_list">
                        <div class="sale_and_purchase_amount_area">
                            <div class="row g-1">
                                <div class="col-md-12 col-sm-12 col-lg-6">
                                    <div class="card mb-1">
                                        <div class="card-body mt-1">
                                            <table class="table modal-table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-start">@lang('menu.total_normal') </th>
                                                        <td class="text-start"> <span class="total_normal"></span></td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start">@lang('menu.total_abnormal') </th>
                                                        <td class="text-start"><span class="total_abnormal"></span></td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start"> @lang('menu.total_stock_adjustment') </th>
                                                        <td class="text-start"> <span class="total_adjustment"></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-lg-6">
                                    <div class="card mb-1">
                                        <div class="card-body ">
                                            <table class="table modal-table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-start">@lang('menu.total_amount') @lang('menu.recovered')</th>
                                                        <td class="text-start"><span class="total_recovered"></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('menu.date')</th>
                                        <th class="text-start">@lang('menu.voucher_no')</th>
                                        <th class="text-start">@lang('menu.created_by')</th>
                                        <th class="text-start">@lang('menu.reason')</th>
                                        <th class="text-start">@lang('menu.type')</th>
                                        <th class="text-start">@lang('menu.total_item')</th>
                                        <th class="text-start">@lang('menu.total_qty')</th>
                                        <th class="text-start">@lang('menu.total_amount')</th>
                                        <th class="text-start">@lang('menu.total_recovered_amount')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <th colspan="5" class="text-white text-end">@lang('menu.total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th id="total_item" class="text-white"></th>
                                    <th id="total_qty" class="text-white"></th>
                                    <th id="net_total_amount" class="text-white"></th>
                                    <th id="recovered_amount" class="text-white"></th>
                                </tfoot>
                            </table>
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
    var __currency_symbol = "{{ json_decode($generalSettings->business, true)['currency'] }}";
    function getAdjustmentAmounts() {
        $('.data_preloader').show();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        $.ajax({
            url: "{{ route('reports.stock.adjustments.index') }}",
            data:{ from_date, to_date },
            type: 'get',
            success: function(data) {
                $('.total_normal').html(__currency_symbol+' '+(data[0].total_normal ? bdFormat(data[0].total_normal) : parseFloat(0).toFixed(2)));
                $('.total_abnormal').html(__currency_symbol+' '+(data[0].total_abnormal ? bdFormat(data[0].total_abnormal) : parseFloat(0).toFixed(2)));
                $('.total_adjustment').html(__currency_symbol+' '+(data[0].t_amount ? bdFormat(data[0].t_amount) : parseFloat(0).toFixed(2)));
                $('.total_recovered').html(__currency_symbol+' '+(data[0].t_recovered_amount ? bdFormat(data[0].t_recovered_amount) : parseFloat(0).toFixed(2)));
                $('.data_preloader').hide();
            }
        });
    }
    getAdjustmentAmounts();

    var adjustment_table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1'},
            {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1'},
        ],
        "processing": true,
        "serverSide": true,
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('reports.stock.adjustments.all') }}",
            "data": function(d) {
                d.type = $('#status').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [
            {data: 'date', name: 'date'},
            {data: 'voucher_no', name: 'voucher_no', className : 'fw-bold'},
            {data: 'created_by', name: 'users.name'},
            {data: 'reason', name: 'reason'},
            {data: 'type', name: 'type'},
            {data: 'total_item', name: 'total_item', className : 'text-end fw-bold'},
            {data: 'total_qty', name: 'total_qty', className : 'text-end fw-bold'},
            {data: 'net_total_amount', name: 'net_total_amount', className : 'text-end fw-bold'},
            {data: 'recovered_amount', name: 'recovered_amount', className : 'text-end fw-bold'},

        ],fnDrawCallback: function() {

            var total_item = sum_table_col($('.data_tbl'), 'total_item');
            $('#total_item').text(bdFormat(total_item));

            var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
            $('#total_qty').text(bdFormat(total_qty));

            var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
            $('#net_total_amount').text(bdFormat(net_total_amount));

            var recovered_amount = sum_table_col($('.data_tbl'), 'recovered_amount');
            $('#recovered_amount').text(bdFormat(recovered_amount));

            $('.data_preloader').hide();
        }
    });

    adjustment_table.buttons().container().appendTo('#exportButtonsContainer');

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
    $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();
        adjustment_table.ajax.reload();
        getAdjustmentAmounts();
    });


    //Print S.Adjustment report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.stock.adjustments.print') }}";
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {from_date, to_date},
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay : 1000,
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
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
