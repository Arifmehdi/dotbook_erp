@extends('layout.master')
@push('css')
    
@endpush
@section('title', 'Stock Issue Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Stock Issue Report') }}</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mb-1 mt-0">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.receiver_department') </strong></label>
                                        <select name="department_id" id="department_id" class="form-control select2 form-select">
                                            <option data-department_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($departments as $department)
                                                <option data-department_name="{{ $department->name }}" value="{{ $department->id }}">
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.issue_event') </strong></label>
                                        <select name="stock_event_id" id="stock_event_id" class="form-control select2 form-select">
                                            <option data-event_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($events as $event)
                                                <option data-event_name="{{ $event->name }}" value="{{ $event->id }}">
                                                    {{ $event->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

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
                                        <button type="submit" id="filter_button" class="btn btn-sm btn-info"> <i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table w-100">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.voucher_no')</th>
                                            <th>@lang('menu.receiver_dep').</th>
                                            <th>@lang('menu.event')</th>
                                            <th>@lang('menu.created_by')</th>
                                            <th>@lang('menu.total_item')</th>
                                            <th>@lang('menu.total_qty')</th>
                                            <th>@lang('menu.net_total_value')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="5" class="text-white text-end">@lang('menu.total') :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th class="text-white text-end" id="total_item"></th>
                                            <th class="text-white text-end" id="total_qty"></th>
                                            <th class="text-white text-end" id="net_total_value"></th>
                                        </tr>
                                    </tfoot>
                                </table>
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
        $('.select2').select2();

        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var stock_issue_report_table = $('.data_tbl').DataTable({
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
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.stock.issue.report.index') }}",
                "data": function(d) {
                    d.department_id = $('#department_id').val();
                    d.stock_event_id = $('#stock_event_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no'
                },
                {
                    data: 'dep_name',
                    name: 'departments.name'
                },
                {
                    data: 'event_name',
                    name: 'stock_events.name'
                },
                {
                    data: 'created_by',
                    name: 'created_by.name'
                },
                {
                    data: 'total_item',
                    name: 'total_item',
                    className: 'fw-bold'
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'net_total_value',
                    name: 'net_total_value',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var net_total_value = sum_table_col($('.data_tbl'), 'net_total_value');
                $('#net_total_value').text(bdFormat(net_total_value));

                $('.data_preloader').hide();
            }
        });

        stock_issue_report_table.buttons().container().appendTo('#exportButtonsContainer');

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
            stock_issue_report_table.ajax.reload();
        });

        //Print stock issue statements
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.stock.issue.report.print') }}";

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('warehouse_name');
            var department_id = $('#department_id').val();
            var department_name = $('#department_id').find('option:selected').data('department_name');
            var stock_event_id = $('#stock_event_id').val();
            var event_name = $('#stock_event_id').find('option:selected').data('event_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    warehouse_id,
                    warehouse_name,
                    department_id,
                    department_name,
                    stock_event_id,
                    event_name,
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
                        printDelay: 700,
                        header: "",
                        pageTitle: "",
                        // footer: 'Footer Text',
                        formValues: false,
                        canvas: false,
                        beforePrint: null,
                        afterPrint: null
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
    </script>
@endpush
