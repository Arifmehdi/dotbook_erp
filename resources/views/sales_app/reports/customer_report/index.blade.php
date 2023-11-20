@extends('layout.master')
@push('css')
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .report_data_area {
            position: relative;
        }

        .data_preloader {
            top: 2.3%
        }

        .sale_and_purchase_amount_area table tbody tr th {
            text-align: left;
        }

        .sale_and_purchase_amount_area table tbody tr td {
            text-align: left;
        }
    </style>
    
@endpush
@section('title', 'Customer Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.customer_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="btn text-white btn-sm"><span><i
                                    class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <form id="filter_tax_report_form" action="" method="get">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.customer') </strong></label>
                                            <select name="customer_id" class="form-control select2 form-select"
                                                id="customer_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">
                                                        {{ $customer->name . ' (' . $customer->phone . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if (!auth()->user()->can('view_own_sale'))

                                            <div class="col-md-4">
                                                <label><strong>@lang('menu.sr') </strong></label>
                                                <select name="user_id" class="form-control select2 form-select"
                                                    id="user_id" autofocus>
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <input type="hidden" name="user_id" id="user_id"
                                                value="{{ auth()->user()->id }}">
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-0 mt-1">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr class="text-start">
                                            <th>@lang('menu.customer')</th>
                                            <th>@lang('menu.opening_balance')</th>
                                            <th>@lang('menu.total_sale')</th>
                                            <th>@lang('menu.total_collection')</th>
                                            <th>@lang('menu.total_due')</th>
                                            <th>@lang('menu.total_refundable')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th class="text-end text-white">@lang('menu.total') :
                                                {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                            <th id="total_op_blc_due" class="text-white">0.00</th>
                                            <th id="total_sale" class="text-white">0.00</th>
                                            <th id="total_paid" class="text-white">0.00</th>
                                            <th id="total_sale_due" class="text-white">0.00</th>
                                            <th id="total_return_due" class="text-white">0.00</th>
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
    <script>
        $('.select2').select2();

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
                // {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [3, 'asc']
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.customer.index') }}",
                "data": function(d) {
                    d.customer_id = $('#customer_id').val();
                    d.user_id = $('#user_id').val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'opening_balance',
                    name: 'name',
                    className: 'text-end'
                },
                {
                    data: 'total_sale',
                    name: 'name',
                    className: 'text-end'
                },
                {
                    data: 'total_paid',
                    name: 'name',
                    className: 'text-end'
                },
                {
                    data: 'total_sale_due',
                    name: 'name',
                    className: 'text-end'
                },
                {
                    data: 'total_sale_return_due',
                    name: 'name',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {
                var totalSale = sum_table_col($('.data_tbl'), 'total_sale');
                $('#total_sale').text(parseFloat(totalSale).toFixed(2));

                var totalPaid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(parseFloat(totalPaid).toFixed(2));

                var totalOpeningBalance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#total_op_blc_due').text(parseFloat(totalOpeningBalance).toFixed(2));

                var totalDue = sum_table_col($('.data_tbl'), 'total_sale_due');
                $('#total_sale_due').text(parseFloat(totalDue).toFixed(2));

                var totalReturnDue = sum_table_col($('.data_tbl'), 'total_sale_return_due');
                $('#total_return_due').text(parseFloat(totalReturnDue).toFixed(2));
            },
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).on('change', '.submit_able', function() {
            table.ajax.reload();
        });

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

        //Print supplier report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.customer.print') }}";
            var customer_id = $('#customer_id').val();
            var user_id = $('#user_id').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    customer_id,
                    user_id
                },
                success: function(data) {

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
@endpush
