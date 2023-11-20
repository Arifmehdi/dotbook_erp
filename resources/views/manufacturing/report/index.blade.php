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
            width: 100%;
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
            color: #6b6262;
            font-size: 12px;
            display: block;
            padding: 3px;
        }

        .search_result ul li a:hover {
            color: var(--white-color);
            background-color: #999396;
        }

        /* Search Product area style end */

        /* .menu-txt {
                display: block;
            }
            @media screen and (max-width: 991px) {
                .menu-txt {
                    display: none;
                }
                .sec-name .btn {
                    font-size: 7px;
                }
            } */
    </style>
@endpush
@section('title', 'Manufacturing Report- ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div>
                    <h6>@lang('menu.production_report')</h6>
                </div>
                <div class="d-md-flex d-none">
                    @if (auth()->user()->can('production_add'))
                        <div>
                            <a href="{{ route('manufacturing.productions.create') }}" class="btn text-white btn-sm">
                                <i class="fa-thin fa-circle-plus fa-2x"></i>
                                <br>@lang('menu.add_new')
                            </a>
                        </div>
                    @endif
                    @if (auth()->user()->can('process_view'))
                        <div>
                            <a href="{{ route('manufacturing.process.index') }}" class="text-white btn text-white btn-sm"><i
                                    class="fa-thin  fa-dumpster-fire  fa-2x"></i><br> @lang('menu.process')</a>
                        </div>
                    @endif
                    @if (auth()->user()->can('production_view'))
                        <div>
                            <a href="{{ route('manufacturing.productions.index') }}"
                                class="text-white btn text-white btn-sm"><i class="fa-thin fa-shapes fa-2x"></i><br>
                                @lang('menu.productions')</a>
                        </div>
                    @endif
                    @if (auth()->user()->can('manuf_report'))
                        <div>
                            <a href="{{ route('manufacturing.report.index') }}" class="text-white btn text-white btn-sm"><i
                                    class="fa-thin fa-file-lines fa-2x"></i><br> @lang('menu.manufacturing_report')</a>
                        </div>
                    @endif
                    @if (auth()->user()->can('manuf_settings'))
                        <div>
                            <a href="{{ route('manufacturing.settings.index') }}"
                                class="text-white btn text-white btn-sm"><i class="fa-thin fa-sliders fa-2x"></i><br>
                                @lang('menu.manufacturing_setting')</a>
                        </div>
                    @endif
                    <div class="d-lg-block d-none">
                        <div id="exportButtonsContainer"></div>
                    </div>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none">
                        <span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                            class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                    </a>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form id="filter_form" class="px-2">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4 search_area">
                                        <label><strong>@lang('menu.search_item')</strong></label>
                                        <input type="text" name="search_product" id="search_product" class="form-control"
                                            placeholder="Search Product By name" autofocus autocomplete="off">
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
                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                            <label><strong>@lang('menu.warehouse') </strong></label>
                                            <select name="warehouse_id" class="form-control submit_able form-select"
                                                id="warehouse_id" autofocus>
                                                <option value="">@lang('menu.select_business_location_first')</option>
                                            </select>
                                        @else
                                            @php
                                                $wh = DB::table('warehouses')->get(['id', 'warehouse_name', 'warehouse_code']);
                                            @endphp

                                            <label><strong>@lang('menu.warehouse') </strong></label>
                                            <select name="warehouse_id" class="form-control submit_able form-select"
                                                id="warehouse_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($wh as $row)
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->warehouse_name . '/' . $row->warehouse_code }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.category') </strong></label>
                                        <select name="category_id" class="form-control submit_able" id="category_id">
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.sub_category') </strong></label>
                                        <select name="sub_category_id" class="form-control submit_able form-select"
                                            id="sub_category_id">
                                            <option value="">@lang('menu.all')</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.status') </strong></label>
                                        <div class="input-group">
                                            <select name="status" class="form-control form-select" id="status"
                                                autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">@lang('menu.final')</option>
                                                <option value="0">@lang('menu.hold')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_i"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="from_date" id="datepicker"
                                                class="form-control from_date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_i"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="to_date" id="datepicker2"
                                                class="form-control to_date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <button type="button" id="filter_button" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')
                                        </button>
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
                            <div class="table-responsive">
                                <form id="update_product_cost_form" action="">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-black">@lang('menu.date')</th>
                                                <th class="text-black">@lang('menu.voucher_no')</th>
                                                <th class="text-black">@lang('menu.business_location')</th>
                                                <th class="text-black">@lang('menu.product')</th>
                                                <th class="text-black">@lang('menu.status')</th>
                                                <th class="text-black">@lang('menu.per_unit_cost')(Inc.Tax)</th>
                                                <th class="text-black">@lang('menu.selling_price')(@lang('menu.exc_tax'))</th>
                                                <th class="text-black">@lang('menu.output_qty')</th>
                                                <th class="text-black">@lang('menu.wasted_qty')</th>
                                                <th class="text-black">@lang('menu.final_qty')</th>
                                                <th class="text-black">@lang('menu.total_ingredient_cost')</th>
                                                <th class="text-black">@lang('menu.production_cost')</th>
                                                <th class="text-black">@lang('menu.total_cost')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="7" class="text-white text-end">@lang('menu.total') :
                                                    ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th id="quantity" class="text-white text-end"></th>
                                                <th id="wasted_quantity" class="text-white text-end"></th>
                                                <th id="total_final_quantity" class="text-white text-end"></th>
                                                <th id="total_ingredient_cost" class="text-white text-end"></th>
                                                <th id="production_cost" class="text-white text-end"></th>
                                                <th id="total_cost" class="text-white text-end"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="production_details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script>
        var production_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
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
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('manufacturing.report.index') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.warehouse_id = $('#warehouse_id').val();
                    d.category_id = $('#category_id').val();
                    d.sub_category_id = $('#sub_category_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'reference_no',
                    name: 'reference_no'
                },
                {
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'status',
                    name: 'productions.is_final'
                },
                {
                    data: 'unit_cost_inc_tax',
                    name: 'unit_cost_inc_tax',
                    className: 'text-end'
                },
                {
                    data: 'price_exc_tax',
                    name: 'price_exc_tax',
                    className: 'text-end'
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    className: 'text-end'
                },
                {
                    data: 'wasted_quantity',
                    name: 'wasted_quantity',
                    className: 'text-end'
                },
                {
                    data: 'total_final_quantity',
                    name: 'total_final_quantity',
                    className: 'text-end'
                },
                {
                    data: 'total_ingredient_cost',
                    name: 'total_ingredient_cost',
                    className: 'text-end'
                },
                {
                    data: 'production_cost',
                    name: 'production_cost',
                    className: 'text-end'
                },
                {
                    data: 'total_cost',
                    name: 'total_cost',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                var quantity = sum_table_col($('.data_tbl'), 'quantity');
                $('#quantity').text(bdFormat(quantity));
                var wasted_quantity = sum_table_col($('.data_tbl'), 'wasted_quantity');
                $('#wasted_quantity').text(bdFormat(wasted_quantity));
                var total_final_quantity = sum_table_col($('.data_tbl'), 'total_final_quantity');
                $('#total_final_quantity').text(bdFormat(total_final_quantity));
                var total_ingredient_cost = sum_table_col($('.data_tbl'), 'total_ingredient_cost');
                $('#total_ingredient_cost').text(bdFormat(total_ingredient_cost));
                var production_cost = sum_table_col($('.data_tbl'), 'production_cost');
                $('#production_cost').text(bdFormat(production_cost));
                var total_cost = sum_table_col($('.data_tbl'), 'total_cost');
                $('#total_cost').text(bdFormat(total_cost));
                $('.data_preloader').hide();
            }
        });
        production_table.buttons().container().appendTo('#exportButtonsContainer');

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
            production_table.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {

                $('#production_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();

            var body = $('.production_print_template').html();
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
                url: "{{ url('reports/product/purchases/search/product') }}" + "/" + product_name,
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
