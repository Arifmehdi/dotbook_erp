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
@section('title', 'Supplier Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.supplier_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mb-1 mt-0">
                            <div class="element-body">
                                <form id="filter_tax_report_form" action="" method="get">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <select name="supplier_id" class="form-control submit_able form-select" id="supplier_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">
                                                        {{ $supplier->name . ' (' . $supplier->phone . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label></label>
                                    <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> @lang('menu.print')</a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="report_data_area">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="report_data">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="display data_tbl data__table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('menu.supplier')</th>
                                                            <th>@lang('menu.opening_balance')</th>
                                                            <th>@lang('menu.total_purchase')</th>
                                                            <th>@lang('menu.total_paid')</th>
                                                            <th>@lang('menu.total_return')</th>
                                                            <th>Current Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="bg-secondary">
                                                            <th class="text-end text-white">@lang('menu.total') :
                                                                ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                            </th>
                                                            <th id="total_op_blc_due" class="text-white">0.00</th>
                                                            <th id="total_purchase" class="text-white">0.00</th>
                                                            <th id="total_paid" class="text-white">0.00</th>
                                                            <th id="total_return_due" class="text-white">0.00</th>
                                                            <th id="total_purchase_due" class="text-white">0.00</th>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
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
                "url": "{{ route('reports.supplier.index') }}",
                "data": function(d) {
                    d.supplier_id = $('#supplier_id').val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'opening_balance',
                    name: 'opening_balance'
                },
                {
                    data: 'total_purchase',
                    name: 'total_purchase'
                },
                {
                    data: 'total_paid',
                    name: 'total_paid'
                },
                {
                    data: 'total_return',
                    name: 'total_purchase_return_due'
                },
                {
                    data: 'total_purchase_due',
                    name: 'total_purchase_due'
                },
            ],
            fnDrawCallback: function() {

                var totalOpeningBalance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#total_op_blc_due').text(bdFormat(totalOpeningBalance));
                var totalPurchase = sum_table_col($('.data_tbl'), 'total_purchase');
                $('#total_purchase').text(bdFormat(totalPurchase));
                var totalPaid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(bdFormat(totalPaid));
                var total_return = sum_table_col($('.data_tbl'), 'total_return');
                $('#total_return').text(bdFormat(total_return));
                var totalDue = sum_table_col($('.data_tbl'), 'total_purchase_due');
                $('#total_purchase_due').text(bdFormat(totalDue));
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
            var url = "{{ route('reports.supplier.print') }}";
            var supplier_id = $('#supplier_id').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    supplier_id
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
