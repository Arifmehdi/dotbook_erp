@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Purchase List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.manage_purchases')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :href="route('purchases.create')" :can="'purchase_add'" :is_modal="false" />
                    </x-slot>

                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <button id="print_summary" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print_summary')</span></button>
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
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.supplier') </strong></label>
                                                <select name="supplier_account_id" class="form-control submit_able select2 form-select" id="supplier_account_id" autofocus>
                                                    <option data-supplier_name="All" value="">@lang('menu.all')
                                                    </option>
                                                    @foreach ($supplierAccounts as $supplierAccount)
                                                        <option data-supplier_name="{{ $supplierAccount->name . '/' . $supplierAccount->phone }}" value="{{ $supplierAccount->id }}">
                                                            {{ $supplierAccount->name . '/' . $supplierAccount->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.purchase_ledger_ac') </strong></label>
                                                <select name="purchase_account_id" id="purchase_account_id" class="form-control select2 form-select">
                                                    <option data-purchase_account_name="All" value="">
                                                        @lang('menu.all')</option>
                                                    @foreach ($purchaseAccounts as $purchaseAccount)
                                                        <option data-purchase_account_name="{{ $purchaseAccount->name }}" value="{{ $purchaseAccount->id }}">{{ $purchaseAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong>@lang('menu.created_by') </strong></label>
                                                <select name="user_id" class="form-control select2 form-select" id="user_id" autofocus>
                                                    <option data-user_name="All" value="">@lang('menu.all')</option>
                                                    @foreach ($users as $user)
                                                        <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . ' (' . $user->phone . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_f"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="from_date" id="datepicker" class="form-control from_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_f"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
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
                                <table class="display data_tbl data__table purchase_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.actions')</th>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('short.p_invoice_id')</th>
                                            <th>@lang('menu.requisition_no')</th>
                                            <th>@lang('menu.po_id')</th>
                                            <th>@lang('menu.rs_voucher')</th>
                                            <th>@lang('menu.note')</th>
                                            <th>@lang('menu.departments')</th>
                                            <th>@lang('menu.supplier')</th>
                                            <th>@lang('menu.additional_expense_short')</th>
                                            <th>@lang('menu.total_qty')</th>
                                            <th>@lang('menu.total_invoice_amount')</th>
                                            <th>@lang('menu.paid')</th>
                                            <th>@lang('menu.due')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="9" class="text-end text-white">@lang('menu.total')
                                                {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                            <th id="total_additional_expense" class="text-white"></th>
                                            <th id="total_qty" class="text-white"></th>
                                            <th class="text-white">
                                                <span class="d-block" id="total_purchase_amount"></span>
                                                <span class="d-block mt-2" id="average_unit_cost"></span>
                                            </th>
                                            <th id="paid" class="text-white"></th>
                                            <th id="due" class="text-white"></th>
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
    </div>

    <div id="details"></div>
    <div id="extra_details"></div>

    @if (auth()->user()->can('payments_add'))
        <!--Add Payment modal-->
        <div class="modal fade" id="purchasePaymentModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
        <!--Add Payment modal-->
    @endif
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
                },
                // {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('purchases.index') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.purchase_account_id = $('#purchase_account_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columns: [{
                data: 'action'
            }, {
                data: 'date',
                name: 'date'
            }, {
                data: 'invoice_id',
                name: 'purchases.invoice_id',
                className: 'fw-bold'
            }, {
                data: 'requisition_no',
                name: 'rs_requisitions.requisition_no'
            }, {
                data: 'po_id',
                name: 'po.invoice_id'
            }, {
                data: 'rs_voucher_no',
                name: 'receive_stocks.voucher_no'
            }, {
                data: 'purchase_note',
                name: 'purchases.purchase_note'
            }, {
                data: 'department',
                name: 'departments.name'
            }, {
                data: 'supplier_name',
                name: 'suppliers.name'
            }, {
                data: 'total_additional_expense',
                name: 'purchase_requisitions.requisition_no',
                className: 'fw-bold'
            }, {
                data: 'total_qty',
                name: 'purchase_requisitions.requisition_no',
                className: 'fw-bold'
            }, {
                data: 'total_purchase_amount',
                name: 'expanses.voucher_no',
                className: 'fw-bold'
            }, {
                data: 'paid',
                name: 'paid',
                name: 'rs_requisitions.requisition_no',
                className: 'fw-bold'
            }, {
                data: 'due',
                name: 'due',
                name: 'receive_stocks.voucher_no',
                className: 'fw-bold'
            }, ],
            fnDrawCallback: function() {

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var total_additional_expense = sum_table_col($('.data_tbl'), 'total_additional_expense');
                $('#total_additional_expense').text(bdFormat(total_additional_expense));

                var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                $('#total_purchase_amount').text(bdFormat(total_purchase_amount));

                var averageUnitCost = total_purchase_amount / total_qty;
                var totalPurchaseAmtWithAvgUnitCost = ' (Avg. U/c: ' + bdFormat(averageUnitCost) + ')';
                $('#average_unit_cost').text(totalPurchaseAmtWithAvgUnitCost);

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));

                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));

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

        $(document).on('click', '#extra_details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {

                $('#extra_details').html(data);
                $('.data_preloader').hide();
                $('.extra_show_class').modal('show');
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

                    } {
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
        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();
            var body = $('.print_details').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#add_purchase_payment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#purchasePaymentModal').html(data);
                $('#purchasePaymentModal').modal('show');
                $('.data_preloader').hide();

                setTimeout(function() {

                    $('input[name="paying_amount"]').focus().select();
                }, 500);
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_list_modal_body').html(data);
                $('#paymentViewModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('.payment_details_area').html(data);
                $('#paymentDetailsModal').modal('show');
            });
        });

        $(document).on('click', '#print_payment', function(e) {
            e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.purchases.report.print') }}";
            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var supplier_account_id = $('#supplier_account_id').val();
            var supplier_name = $('#supplier_account_id').find('option:selected').data('supplier_name');
            var purchase_account_id = $('#purchase_account_id').val();
            var purchase_account_name = $('#purchase_account_id').find('option:selected').data(
                'purchase_account_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    user_name,
                    supplier_account_id,
                    supplier_name,
                    purchase_account_id,
                    purchase_account_name,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                        removeInline: false,
                        printDelay: 750,
                    });
                }
            });
        });

        $(document).on('click', '#print_summary', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.purchases.report.print.summary') }}";
            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var supplier_account_id = $('#supplier_account_id').val();
            var supplier_name = $('#supplier_account_id').find('option:selected').data('supplier_name');
            var purchase_account_id = $('#purchase_account_id').val();
            var purchase_account_name = $('#purchase_account_id').find('option:selected').data(
                'purchase_account_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    user_name,
                    supplier_account_id,
                    supplier_name,
                    purchase_account_id,
                    purchase_account_name,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                        removeInline: false,
                        printDelay: 750,
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
            format: 'DD-MM-YYYY'
        });

        function getSupplier() {}
    </script>
@endpush
