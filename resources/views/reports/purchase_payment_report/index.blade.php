@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Purchase Payment Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.purchase_payment_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
            <div class="p-15">
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row">
                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.supplier') </strong></label>
                                            <select name="supplier_id" class="form-control submit_able select2 form-select" id="supplier_id" autofocus>
                                                <option data-supplier_name="All" value="">@lang('menu.all')</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option data-supplier_name="{{ $supplier->name . ' (' . $supplier->phone . ')' }}" value="{{ $supplier->id }}">
                                                        {{ $supplier->name . ' (' . $supplier->phone . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>@lang('menu.created_by') </strong></label>
                                            <select name="user_id" class="form-control submit_able select2 form-select" id="user_id" autofocus>
                                                <option data-user_name="All" value="">@lang('menu.all')</option>
                                                @foreach ($users as $user)
                                                    <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . ' (' . $user->phone . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn btn-sm btn-info float-start"><i class="fa-solid fa-filter-list"></i>
                                                            @lang('menu.filter')</button>
                                                    </div>
                                                </div>
                                            </div>
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
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table payments_table w-100">
                                    <thead>
                                        <tr class="text-start">
                                            <th class="text-start">@lang('menu.date')</th>
                                            <th class="text-start">@lang('menu.voucher_no')</th>
                                            <th class="text-start">@lang('menu.reference')</th>
                                            <th class="text-start">@lang('menu.supplier')</th>
                                            <th class="text-start">@lang('menu.against_purchase_po')</th>
                                            <th class="text-start">@lang('menu.payment_status')</th>
                                            <th class="text-start">@lang('menu.method')</th>
                                            <th class="text-start">@lang('menu.account')</th>
                                            <th class="text-end">@lang('menu.paid_amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th class="text-end text-white" colspan="8">@lang('menu.total') : </th>
                                            <th class="text-end text-white" id="amount"></th>
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

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            "ajax": {
                "url": "{{ route('reports.purchase.payments.index') }}",
                "data": function(d) {
                    d.supplier_id = $('#supplier_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },

            columns: [{
                    data: 'date',
                    name: 'supplier_ledgers.date'
                },
                {
                    data: 'voucher_no',
                    name: 'supplier_payments.voucher_no'
                },
                {
                    data: 'reference',
                    name: 'supplier_payments.reference'
                },
                {
                    data: 's_name',
                    name: 'suppliers.name'
                },
                {
                    data: 'against_invoice',
                    name: 'purchases.invoice_id'
                },
                {
                    data: 'type',
                    name: 'type',
                    name: 'pp_account.name'
                },
                {
                    data: 'method',
                    name: 'sp_pay_method.name'
                },
                {
                    data: 'account',
                    name: 'sp_account.name'
                },
                {
                    data: 'amount',
                    name: 'purchase_payments.invoice_id'
                },
            ],
            fnDrawCallback: function() {

                var amount = sum_table_col($('.data_tbl'), 'amount');
                $('#amount').text(bdFormat(amount));

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

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.purchase.payments.print') }}";
            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var supplier_id = $('#supplier_id').val();
            var supplier_name = $('#supplier_id').find('option:selected').data('supplier_name');
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    user_name,
                    supplier_id,
                    supplier_name,
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
                        header: null,
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
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
            element: document.getElementById('datepicker2'),
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
