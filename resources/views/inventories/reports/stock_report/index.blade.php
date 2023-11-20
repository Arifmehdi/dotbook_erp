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
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    
@endpush
@section('title', 'Stock Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.stock_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button class="btn text-white btn-sm" id="w_print_report"><span><i
                                    class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></button>
                        <button class="btn text-white btn-sm" id="w_print_stock_value"><span><i
                                    class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print_stock_value')</span></button>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                @if ($addons->branches == 1)
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-1">
                                <div class="tab_list_area">
                                    <ul class="list-unstyled mb-2">
                                        <li>
                                            <a id="tab_btn" data-show="branch_stock" class="tab_btn tab_active"
                                                href="#"><i class="fas fa-info-circle"></i> @lang('menu.business_location_stock')</a>
                                        </li>

                                        <li>
                                            <a id="tab_btn" data-show="warehouse_stock" class="tab_btn" href="#">
                                                <i class="fas fa-scroll"></i> @lang('menu.warehouse_stock')</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($addons->branches == 1)
                    <div class="tab_contant branch_stock">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <div class="form_element rounded">
                                    <div class="element-body">
                                        <form id="branch_stock_filter_form">
                                            @csrf
                                            <div class="form-group row align-items-end g-2">
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.category') </strong></label>
                                                    <select id="category_id" name="category_id"
                                                        class="form-control form-select">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($categories as $c)
                                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.brand') </strong></label>
                                                    <select id="brand_id" name="brand_id" class="form-control form-select">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($brands as $b)
                                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.unit') </strong></label>
                                                    <select id="unit_id" name="unit_id" class="form-control form-select">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($units as $u)
                                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <button type="submit" id="filter_button"
                                                        class="btn text-white btn-sm btn-info float-start"><i
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
                                        <div class="table-responsive" id="data_list1">
                                            <table class="display data_tbl data__table b_data_tbl">
                                                <thead>
                                                    <tr class="text-start">
                                                        <th>@lang('menu.p_code')</th>
                                                        <th>@lang('menu.item')</th>
                                                        <th>@lang('menu.business_location')</th>
                                                        <th>@lang('menu.unit_price')</th>
                                                        <th>@lang('menu.current_stock')</th>
                                                        <th>@lang('menu.stock_value') <b><small>(@lang('menu.by_nit_cost'))</small></b>
                                                        </th>
                                                        <th>@lang('menu.total_sold')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th class="text-white text-end" colspan="3">@lang('menu.total') :
                                                        </th>
                                                        <th class="text-white text-end">---</th>
                                                        <th class="text-white text-end" id="stock"></th>
                                                        <th class="text-white text-end" id="stock_value"></th>
                                                        <th class="text-white text-end" id="total_sale"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="tab_contant warehouse_stock {{ $addons->branches == 1 ? 'd-none' : '' }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_element rounded">
                                <div class="element-body">
                                    <form id="warehouse_stock_filter_form">
                                        @csrf
                                        <div class="form-group row align-items-end g-2">

                                            <div class="col-xl-2 col-md-4">
                                                @php
                                                    $wh = DB::table('warehouses')
                                                        ->select('warehouses.id', 'warehouses.warehouse_name as name', 'warehouses.warehouse_code as code')
                                                        ->get();
                                                @endphp

                                                <label><strong>@lang('menu.warehouse') :</strong></label>
                                                <select name="warehouse_id"
                                                    class="form-control submit_able select2 form-select" id="warehouse_id"
                                                    autofocus>
                                                    <option data-warehouse_name="All" value="">@lang('menu.all')
                                                    </option>
                                                    @foreach ($wh as $row)
                                                        <option data-warehouse_name="{{ $row->name . '/' . $row->code }}"
                                                            value="{{ $row->id }}">{{ $row->name . '/' . $row->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.category') :</strong></label>
                                                <select name="category_id" id="w_category_id"
                                                    class="form-control common_submitable select2 form-select">
                                                    <option data-category_name="All" value="">@lang('menu.all')
                                                    </option>
                                                    @foreach ($categories as $c)
                                                        <option data-category_name="{{ $c->name }}"
                                                            value="{{ $c->id }}">
                                                            {{ $c->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.subcategory') :</strong></label>
                                                <select name="subcategory_id" id="w_subcategory_id"
                                                    class="form-control submit_able select2 form-select">
                                                    <option data-subcategory_name="All" value="">@lang('menu.select_category_first')
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.brand') :</strong></label>
                                                <select name="brand_id" id="w_brand_id"
                                                    class="form-control common_submitable select2 form-select">
                                                    <option data-brand_name="All" value="">@lang('menu.all')
                                                    </option>
                                                    @foreach ($brands as $b)
                                                        <option data-brand_name="{{ $b->name }}"
                                                            value="{{ $b->id }}">{{ $b->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.unit') :</strong></label>
                                                <select name="unit_id" id="w_unit_id"
                                                    class="form-control common_submitable select2 form-select">
                                                    <option data-unit_name="All" value="">@lang('menu.all')
                                                    </option>
                                                    @foreach ($units as $u)
                                                        <option data-unit_name="{{ $u->name }}"
                                                            value="{{ $u->id }}">{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <button type="submit" id="filter_button"
                                                    class="btn text-white btn-sm btn-info"><i
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
                                    <div class="data_preloader" id="w_data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                    </div>
                                    <div class="table-responsive" id="data_list">
                                        <table class="display data_tbl data__table w_data_tbl">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.item_code')</th>
                                                    <th>@lang('menu.item')</th>
                                                    <th>@lang('menu.warehouse')</th>
                                                    <th>@lang('menu.current_stock')</th>
                                                    <th>@lang('menu.per_unit_cost') <small
                                                            class="text-white">(@lang('menu.by_wt_avg'))</small></th>
                                                    <th class="text-start">@lang('menu.current_stock_value') <small
                                                            class="text-white">(@lang('menu.by_wt_avg'))</small></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th class="text-white text-end" colspan="3">@lang('menu.total') :
                                                    </th>
                                                    <th class="text-white text-end">---</th>
                                                    <th class="text-white text-end" id="w_stock"></th>
                                                    <th class="text-white text-end" id="w_stock_value"></th>
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
@endsection
@push('scripts')
    <script>
        $('.select2').select2();

        @if ($addons->branches == 1)

            var branch_stock_table = $('.b_data_tbl').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                "ajax": {
                    "url": "{{ route('reports.stock.index') }}",
                    "data": function(d) {
                        d.category_id = $('#category_id').val();
                        d.brand_id = $('#brand_id').val();
                        d.unit_id = $('#unit_id').val();
                        d.tax_ac_id = $('#tax_ac_id').val();
                    }
                },
                columnDefs: [{
                    "targets": [4, 5, 6],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'product_code',
                        name: 'products.product_code'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'price',
                        name: 'products.product_price',
                        className: 'text-end'
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                        className: 'text-end'
                    },
                    {
                        data: 'stock_value',
                        name: 'stock_value',
                        className: 'text-end'
                    },
                    {
                        data: 'total_sale',
                        name: 'total_sale',
                        className: 'text-end'
                    },

                ],
                fnDrawCallback: function() {
                    var stock = sum_table_col($('.b_data_tbl'), 'stock');
                    $('#stock').text(bdFormat(stock));
                    var stock_value = sum_table_col($('.b_data_tbl'), 'stock_value');
                    $('#stock_value').text(bdFormat(stock_value));
                    var total_sale = sum_table_col($('.b_data_tbl'), 'total_sale');
                    $('#total_sale').text(bdFormat(total_sale));
                    $('.data_preloader').hide();
                },
            });


            $(document).on('submit', '#branch_stock_filter_form', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                branch_stock_table.ajax.reload();
            });
        @endif

        @if ($addons->branches == 1)

            $(document).on('click', '.tab_btn', function(e) {
                e.preventDefault();
                $('.tab_btn').removeClass('tab_active');
                $('.tab_contant').addClass('d-none');
                var show_content = $(this).data('show');
                $('.' + show_content).removeClass('d-none');
                $(this).addClass('tab_active');
            });

            //Print purchase Payment report
            $(document).on('click', '#branch_stock_print_report', function(e) {
                e.preventDefault();
                var url = "{{ route('reports.stock.print.branch.stock') }}";
                var category_id = $('#category_id').val();
                var brand_id = $('#brand_id').val();
                var unit_id = $('#unit_id').val();
                var tax_ac_id = $('#tax_ac_id').val();
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        category_id,
                        brand_id,
                        unit_id,
                        tax_ac_id
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
                            // footer: 'Footer Text',
                            formValues: false,
                            canvas: false,
                            beforePrint: null,
                            afterPrint: null
                        });
                    }
                });
            });
        @endif
    </script>

    <script>
        var warehouse_stock_table = $('.w_data_tbl').DataTable({
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
            "searching": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.stock.warehouse.stock') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.category_id = $('#w_category_id').val();
                    d.subcategory_id = $('#w_subcategory_id').val();
                    d.brand_id = $('#w_brand_id').val();
                    d.unit_id = $('#w_unit_id').val();
                    d.tax_ac_id = $('#w_tax_ac_id').val();
                }
            },
            columns: [{
                    data: 'product_code',
                    name: 'products.product_code'
                },
                {
                    data: 'name',
                    name: 'products.name'
                },
                {
                    data: 'warehouse',
                    name: 'warehouses.warehouse_name'
                },
                {
                    data: 'stock',
                    name: 'products.name',
                    className: 'fw-bold'
                },
                {
                    data: 'per_unit_cost',
                    name: 'products.name',
                    className: 'fw-bold'
                },
                {
                    data: 'stock_value',
                    name: 'product_variants.variant_name',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var stock = sum_table_col($('.w_data_tbl'), 'stock');
                $('#w_stock').text(bdFormat(stock));
                var stock_value = sum_table_col($('.w_data_tbl'), 'stock_value');
                $('#w_stock_value').text(bdFormat(stock_value));
                $('#w_data_preloader').hide();
            },
        });

        warehouse_stock_table.buttons().container().appendTo('#exportButtonsContainer');

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

        $(document).on('submit', '#warehouse_stock_filter_form', function(e) {
            e.preventDefault();

            $('#w_data_preloader').show();
            warehouse_stock_table.ajax.reload();
        });

        // set sub category in form field
        $('#w_category_id').on('change', function() {

            var category_id = $(this).val();
            $('#w_subcategory_id').empty();
            $('#w_subcategory_id').append('<option data-subcategory_name="All" value="">All</option>');

            $.get("{{ url('common/ajax/call/category/subcategories/') }}" + "/" + category_id, function(
                subCategories) {

                $('#w_subcategory_id').empty();
                $('#w_subcategory_id').append('<option data-subcategory_name="All" value="">All</option>');

                $.each(subCategories, function(key, val) {

                    $('#w_subcategory_id').append('<option data-subcategory_name="' + val.name +
                        '" value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

        $(document).on('click', '#w_print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.stock.print.warehouse.stock') }}";
            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('warehouse_name');
            var category_id = $('#w_category_id').val();
            var category_name = $('#w_category_id').find('option:selected').data('category_name');
            var subcategory_id = $('#w_subcategory_id').val();
            var subcategory_name = $('#w_subcategory_id').find('option:selected').data('subcategory_name');
            var brand_id = $('#w_brand_id').val();
            var brand_name = $('#w_brand_id').find('option:selected').data('brand_name');
            var unit_id = $('#w_unit_id').val();
            var unit_name = $('#w_unit_id').find('option:selected').data('unit_name');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    warehouse_id,
                    warehouse_name,
                    category_id,
                    category_name,
                    subcategory_id,
                    subcategory_name,
                    brand_id,
                    brand_name,
                    unit_id,
                    unit_name,
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                    });
                }
            });
        });

        $(document).on('click', '#w_print_stock_value', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.stock.print.warehouse.stock.value') }}";

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('warehouse_name');
            var category_id = $('#w_category_id').val();
            var category_name = $('#w_category_id').find('option:selected').data('category_name');
            var subcategory_id = $('#w_subcategory_id').val();
            var subcategory_name = $('#w_subcategory_id').find('option:selected').data('subcategory_name');
            var brand_id = $('#w_brand_id').val();
            var brand_name = $('#w_brand_id').find('option:selected').data('brand_name');
            var unit_id = $('#w_unit_id').val();
            var unit_name = $('#w_unit_id').find('option:selected').data('unit_name');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    warehouse_id,
                    warehouse_name,
                    category_id,
                    category_name,
                    subcategory_id,
                    subcategory_name,
                    brand_id,
                    brand_name,
                    unit_id,
                    unit_name,
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                    });
                }
            });
        });
    </script>
@endpush
