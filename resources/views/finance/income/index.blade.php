@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Income List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.income')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('income.create')" :can="'add_expense'" :is_modal="false" />
                    <x-slot name="after">
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
                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.created_by') </strong></label>
                                        <select name="user_id" class="form-control select2 form-select" id="user_id">
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="datepicker"
                                                class="form-control from_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>

                                            <input type="text" name="to_date" id="datepicker2"
                                                class="form-control to_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                                            <th class="text-start">@lang('menu.actions')</th>
                                            <th class="text-start">@lang('menu.date')</th>
                                            <th class="text-start">@lang('menu.voucher_no')</th>
                                            <th class="text-start">@lang('menu.description')</th>
                                            <th class="text-start">@lang('menu.created_by')</th>
                                            <th class="text-start">@lang('menu.receive_status')</th>
                                            <th class="text-start">@lang('menu.total_amount')</th>
                                            <th class="text-start">@lang('menu.received')</th>
                                            <th class="text-start">@lang('menu.due')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="6" class="text-white">
                                                <p class="text-end">@lang('menu.total') :
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}</p>
                                            </th>
                                            <th id="total_amount" class="text-white"></th>
                                            <th id="received" class="text-white"></th>
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

    <!--Receipt list modal-->
    <div class="modal fade" id="receiptListModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>
    <!--Payment list modal-->

    <!--Add Payment modal-->
    <div class="modal fade" id="receiptAddOrEditModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>
    <!--Add Payment modal-->

    <div class="modal fade" id="receiptDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>

    <div id="details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        @if (Session::has('errorMsg'))
            toastr.error("{{ session('errorMsg') }}");
        @endif

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            }, ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('income.index') }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },

            columns: [{
                data: 'action'
            }, {
                data: 'date',
                name: 'report_date'
            }, {
                data: 'voucher_no',
                name: 'voucher_no'
            }, {
                data: 'descriptions',
                name: 'voucher_no'
            }, {
                data: 'createdBy',
                name: 'users.name'
            }, {
                data: 'payment_status',
                name: 'users.prefix'
            }, {
                data: 'total_amount',
                name: 'users.last_name'
            }, {
                data: 'received',
                name: 'received'
            }, {
                data: 'due',
                name: 'due'
            }, ],
            fnDrawCallback: function() {

                var total_amount = sum_table_col($('.data_tbl'), 'total_amount');
                $('#total_amount').text(bdFormat(total_amount));

                var received = sum_table_col($('.data_tbl'), 'received');
                $('#received').text(bdFormat(received));

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
        $(document).on('click', '#details_button', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        $(document).on('click', '#add_payment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#receiptAddOrEditModal').html(data);
                $('#receiptAddOrEditModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_receipt', function(e) {
            e.preventDefault();

            $('.modal_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('.modal_preloader').hide();
                $('#receiptAddOrEditModal').html(data);
                $('#receiptAddOrEditModal').modal('show');
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_receipts', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('.data_preloader').show();

            $.get(url, function(data) {

                $('#receiptListModal').html(data);
                $('.data_preloader').hide();
                $('#receiptListModal').modal('show');
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#receipt_details', function(e) {
            e.preventDefault();
            $('.modal_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#receiptDetailsModal').html(data);
                $('.modal_preloader').hide();
                $('#receiptDetailsModal').modal('show');
            });
        });

        $(document).on('click', '#delete_receipt', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var button = $(this);
            $('#receipt_deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#receipt_deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-primary',
                        'action': function() {}
                    }
                }
            });
        });

        $(document).on('submit', '#receipt_deleted_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    table.ajax.reload();
                    toastr.error(data);
                    $('#receiptListModal').modal('hide');
                }
            });
        });

        // Print single payment details
        $(document).on('click', '#print_receipt', function(e) {
            e.preventDefault();

            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        // Make print
        $(document).on('click', '#print_income_btn', function(e) {
            e.preventDefault();

            var body = $('.income_details_print_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 700,
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-primary',
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
                async: false,
                data: request,
                success: function(data) {
                    if ($.isEmptyObject(data.errorMsg)) {
                        table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    } else {
                        toastr.error(data.errorMsg);
                    }
                }
            });
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.expenses.print') }}";
            var admin_id = $('#user_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
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
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
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
        $(document).on('change', '#payment_method', function() {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#' + value).show();
        });

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
