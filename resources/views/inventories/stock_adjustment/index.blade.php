@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'All Stock Adjustment - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.stock_adjustments')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('stock.adjustments.create')" :can="'stock_adjustments_add'" :is_modal="false" />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.type') </strong></label>
                                        <select name="type" id="type" class="form-control submit_able form-select"
                                            autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="1">@lang('menu.normal')</option>
                                            <option value="2">@lang('menu.abnormal')</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <input name="from_date" class="form-control" id="from_date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <input name="to_date" class="form-control" id="to_date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
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
                                            <th class="text-start">@lang('menu.reason')</th>
                                            <th class="text-start">@lang('menu.created_by')</th>
                                            <th class="text-start">@lang('menu.type')</th>
                                            <th class="text-start">@lang('menu.total_item')</th>
                                            <th class="text-start">@lang('menu.total_qty')</th>
                                            <th class="text-start">@lang('menu.total_amount')</th>
                                            <th class="text-start">@lang('menu.recovered_amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="6" class="text-white text-end">@lang('menu.total') :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th id="total_item" class="text-white"></th>
                                            <th id="total_qty" class="text-white"></th>
                                            <th id="net_total_amount" class="text-white"></th>
                                            <th id="recovered_amount" class="text-white"></th>
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

    <div class="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var adjustment_table = $('.data_tbl').DataTable({
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
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:first-child)'
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
                "url": "{{ route('stock.adjustments.index') }}",
                "data": function(d) {
                    d.type = $('#type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                data: 'action'
            }, {
                data: 'date',
                name: 'date'
            }, {
                data: 'voucher_no',
                name: 'voucher_no',
                className: 'fw-bold'
            }, {
                data: 'reason',
                name: 'reason'
            }, {
                data: 'created_by',
                name: 'users.name'
            }, {
                data: 'type',
                name: 'voucher_no'
            }, {
                data: 'total_item',
                name: 'total_item',
                className: 'fw-bold'
            }, {
                data: 'total_qty',
                name: 'total_qty',
                className: 'fw-bold'
            }, {
                data: 'net_total_amount',
                name: 'net_total_amount',
                className: 'fw-bold'
            }, {
                data: 'recovered_amount',
                name: 'recovered_amount',
                className: 'fw-bold'
            }, ],
            fnDrawCallback: function() {

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
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            adjustment_table.ajax.reload();
        });

        // Pass sale details in the details modal
        function adjustmentDetails(url) {
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('.details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            adjustmentDetails(url);
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
                data: request,
                success: function(data) {
                    adjustment_table.ajax.reload();
                    toastr.error(data);
                }
            });
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
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });


        $(document).on('click', '#extra_details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#extra_details').html(data);
                    $('.data_preloader').hide();
                    $('.extra_show_class').modal('show');
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
    </script>

    <script>
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
        })

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
        })
    </script>
@endpush
