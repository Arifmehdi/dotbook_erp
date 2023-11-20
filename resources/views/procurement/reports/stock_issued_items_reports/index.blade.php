@extends('layout.master')
@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
@endpush
@section('title', 'Stock Issued Items Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.stock_issued_items_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.send_from') </strong></label>
                                        <select name="warehouse_id" class="form-control select2 form-select" id="warehouse_id" autofocus>
                                            <option data-warehouse_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option data-warehouse_name="{{ $warehouse->name . '/' . $warehouse->code }}" value="{{ $warehouse->id }}">
                                                    {{ $warehouse->name . '/' . $warehouse->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.receiver_department') </strong></label>
                                        <select name="department_id" id="department_id" class="form-control select2 form-select">
                                            <option data-department_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($departments as $department)
                                                <option data-department_name="{{ $department->name }}" value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.issue_event') </strong></label>
                                        <select name="stock_event_id" id="stock_event_id" class="form-control select2 form-select">
                                            <option data-event_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($events as $event)
                                                <option data-event_name="{{ $event->name }}" value="{{ $event->id }}">
                                                    {{ $event->name }}</option>
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
                                        <button type="submit" id="filter_button" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.voucher_no')</th>
                                            <th>@lang('menu.item_name')</th>
                                            <th>@lang('menu.send_from')</th>
                                            <th>@lang('menu.receiver_dept')</th>
                                            <th>@lang('menu.issue_note')</th>
                                            <th>@lang('menu.created_by')</th>
                                            <th>@lang('menu.quantity')</th>
                                            <th>@lang('menu.unit_cost')</th>
                                            <th>@lang('menu.sub_total')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="7" class="text-white text-end">@lang('menu.total') :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th class="text-white text-end" id="quantity"></th>
                                            <th class="text-white text-end">---</th>
                                            <th class="text-white text-end" id="subtotal"></th>
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
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.stock.issued.items.report.index') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.department_id = $('#department_id').val();
                    d.stock_event_id = $('#stock_event_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'date',
                    name: 'stock_issues.date'
                },
                {
                    data: 'voucher_no',
                    name: 'stock_issues.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'send_from',
                    name: 'warehouses.warehouse_name'
                },
                {
                    data: 'dep_name',
                    name: 'departments.name'
                },
                {
                    data: 'note',
                    name: 'stock_issues.note'
                },
                {
                    data: 'created_by',
                    name: 'created_by.name'
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    className: 'fw-bold'
                },
                {
                    data: 'unit_cost_inc_tax',
                    name: 'unit_cost_inc_tax',
                    className: 'fw-bold'
                },
                {
                    data: 'subtotal',
                    name: 'subtotal',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var quantity = sum_table_col($('.data_tbl'), 'quantity');
                $('#quantity').text(bdFormat(quantity));

                var subtotal = sum_table_col($('.data_tbl'), 'subtotal');
                $('#subtotal').text(bdFormat(subtotal));

                $('.data_preloader').hide();
            }
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
            $('.data_preloader').show();
            table.ajax.reload();
        });

        //Print stock issue statements
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.stock.issued.items.report.print') }}";

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
