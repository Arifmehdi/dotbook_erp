@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        .data_preloader {
            top: 2.3%
        }

        /* Search Product area style */
        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        .search_area {
            position: relative;
        }

        .search_result {
            position: absolute;
            width: 170%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 8px;
            margin-top: 1px;
        }

        .search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 3px;
        }

        .search_result ul li a {
            color: #000;
            font-size: 12px;
            display: block;
            padding: 3px;
        }

        .search_result ul li a:hover {
            color: var(--white-color);
            background-color: #999396;
        }

        /* Search Product area style end */
    </style>
@endpush
@section('title', 'Ordered Item Quantity Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('short.ordered_item_qty_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i
                                    class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span
                                    class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="element-body overflow-visible">
                                <form id="sale_purchase_profit_filter"
                                    action="{{ route('reports.profit.filter.sale.purchase.profit') }}" method="get">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4 search_area">
                                            <label><strong>@lang('menu.search_item') </strong></label>
                                            <input type="text" name="search_product" id="search_product"
                                                class="form-control" placeholder="Search Item By name" autofocus
                                                autocomplete="off">
                                            <input type="hidden" name="product_id" id="product_id" value="">
                                            <input type="hidden" name="variant_id" id="variant_id" value="">
                                            <div class="search_result display-none">
                                                <ul id="list" class="list-unstyled">
                                                    <li><a id="select_product" class="" data-p_id="" data-v_id=""
                                                            href="#">@lang('menu.samsung_a')</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.customer') </strong></label>
                                            <select name="customer_account_id" class="form-control select2 form-select"
                                                id="customer_account_id" autofocus>
                                                <option data-cus_name="All" value="">@lang('menu.all')</option>
                                                @foreach ($customerAccounts as $customerAccount)
                                                    <option
                                                        data-cus_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}"
                                                        value="{{ $customerAccount->id }}">
                                                        {{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if (!auth()->user()->can('view_own_sale'))
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.sr') </strong></label>
                                                <select name="user_id" class="form-control select2 form-select"
                                                    id="user_id" autofocus>
                                                    <option data-user_name="All" value="">@lang('menu.all')</option>
                                                    @foreach ($users as $user)
                                                        <option
                                                            data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}"
                                                            value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . ' (' . $user->phone . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="from_date" class="form-control"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="to_date" class="form-control"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <button type="button" id="filter_button" class="btn  btn-sm btn-info"><i
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
                                                <th>@lang('menu.item')</th>
                                                <th>@lang('menu.ordered_quantity') (@lang('menu.as_base_unit'))</th>
                                                <th>@lang('menu.delivered_quantity') (@lang('menu.as_base_unit')) </th>
                                                <th>@lang('menu.left_quantity') (@lang('menu.as_base_unit'))</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th class="text-end text-white">@lang('menu.total') : </th>
                                                <th class="text-start text-white" id="ordered_qty"></th>
                                                <th class="text-start text-white" id="delivered_qty"></th>
                                                <th class="text-start text-white" id="left_qty"></th>
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
@endsection
@push('scripts')
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.sales.ordered.item.qty.report.index') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.customer_account_id = $('#customer_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'ordered_qty',
                    name: 'product_variants.variant_name',
                    className: 'fw-bold'
                },
                {
                    data: 'delivered_qty',
                    name: 'products.product_code',
                    className: 'fw-bold'
                },
                {
                    data: 'left_qty',
                    name: 'products.product_code',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {
                var ordered_qty = sum_table_col($('.data_tbl'), 'ordered_qty');
                $('#ordered_qty').text(bdFormat(ordered_qty));

                var delivered_qty = sum_table_col($('.data_tbl'), 'delivered_qty');
                $('#delivered_qty').text(bdFormat(delivered_qty));

                var left_qty = sum_table_col($('.data_tbl'), 'left_qty');
                $('#left_qty').text(bdFormat(left_qty));

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
        $(document).on('click', '#filter_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        //Submit filter form by date-range field blur
        $(document).on('click', '#search_product', function() {
            $(this).val('');
            $('#product_id').val('');
            $('#variant_id').val('');
        });

        $('#search_product').on('input', function() {
            $('.search_result').hide();
            $('#list').empty();
            var product_name = $(this).val();

            if (product_name === '') {

                $('.search_result').hide();
                $('#product_id').val('');
                $('#variant_id').val('');
                return;
            }

            $.ajax({
                // url:"{{ url('reports/product/purchases/search/product') }}"+"/"+product_name,
                url: "{{ url('common/ajax/call/only/search/product/for/reports') }}" + "/" + product_name,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.search_result').hide();
                    } else {

                        $('.search_result').show();
                        $('#list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#select_product', function(e) {
            e.preventDefault();
            var product_name = $(this).html();
            $('#search_product').val(product_name.trim());
            var product_id = $(this).data('p_id');
            var variant_id = $(this).data('v_id');
            $('#product_id').val(product_id);
            $('#variant_id').val(variant_id);
            $('.search_result').hide();
        });

        $('body').keyup(function(e) {

            if (e.keyCode == 13 || e.keyCode == 9) {

                $(".selectProduct").click();
                $('.search_result').hide();
                $('#list').empty();
            }
        });

        $(document).on('mouseenter', '#list>li>a', function() {
            $('#list>li>a').removeClass('selectProduct');
            $(this).addClass('selectProduct');
        });

        $(document).on('click', function(e) {

            if ($(e.target).closest(".search_result").length === 0) {

                $('.search_result').hide();
                $('#list').empty();
            }
        });

        //Print purchase report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.sales.ordered.item.qty.report.print') }}";
            var search_product = $('#search_product').val();
            var product_id = $('#product_id').val();
            var variant_id = $('#variant_id').val();
            var customer_account_id = $('#customer_account_id').val();
            var customer_name = $('#customer_account_id').find('option:selected').data('cus_name');
            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    product_id,
                    customer_account_id,
                    variant_id,
                    from_date,
                    to_date,
                    search_product,
                    customer_name,
                    user_name
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
