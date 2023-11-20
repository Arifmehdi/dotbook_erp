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
@section('title', __('menu.received_stocks_report'))
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.received_stocks_report')</h6>
                </div>
                <x-all-buttons>
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
                        <div class="element-body overflow-visible">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4 search_area">
                                        <label><strong>@lang('menu.search_item') </strong></label>
                                        <input type="text" name="search_product" id="search_product" class="form-control" placeholder="Search Item By name" autofocus autocomplete="off">
                                        <input type="hidden" name="product_id" id="product_id" value="">
                                        <input type="hidden" name="variant_id" id="variant_id" value="">
                                        <div class="search_result display-none">
                                            <ul id="list" class="list-unstyled">
                                                <li><a id="select_product" class="" data-p_id="" data-v_id="" href="#">@lang('menu.samsung_a')</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.supplier') </strong></label>
                                        <select name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" autofocus>
                                            <option data-supplier_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($supplierAccounts as $supplierAccount)
                                                <option data-supplier_name="{{ $supplierAccount->name . '/' . $supplierAccount->phone }}" value="{{ $supplierAccount->id }}">
                                                    {{ $supplierAccount->name . '/' . $supplierAccount->phone }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.created_by') </strong></label>
                                        <select name="user_id" class="form-control select2 form-select" id="user_id" autofocus>
                                            <option data-user_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($users as $user)
                                                <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                    {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
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
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
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
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <button type="button" id="filter_button" class="btn btn-sm btn-info">
                                            <i class="fa-solid fa-filter-list"></i> @lang('menu.filter')
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

                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.item_name')</th>
                                            <th>@lang('menu.stored_location')</th>
                                            <th>@lang('menu.rs_voucher_no')</th>
                                            <th>@lang('menu.requisition_no')</th>
                                            <th>@lang('menu.department')</th>
                                            <th>@lang('menu.supplier')</th>
                                            {{-- <th>@lang('menu.created_by')</th> --}}
                                            <th>@lang('menu.received_qty')</th>
                                            <th>@lang('menu.lot_number')</th>
                                            <th>@lang('menu.short_description')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
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
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.receive.stocks.index') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.warehouse_id = $('#warehouse_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'receive_stocks.date'
                },
                {
                    data: 'product_name',
                    name: 'products.name'
                },
                {
                    data: 'stored_location',
                    name: 'warehouses.warehouse_name'
                },
                {
                    data: 'voucher_no',
                    name: 'receive_stocks.voucher_no'
                },
                {
                    data: 'requisition_no',
                    name: 'purchase_requisitions.requisition_no'
                },
                {
                    data: 'department_name',
                    name: 'departments.name'
                },
                {
                    data: 'supplier_name',
                    name: 'suppliers.name'
                },
                // {data: 'createdBy', name: 'users.name'},
                {
                    data: 'quantity',
                    name: 'receive_stock_products.quantity',
                    className: 'fw-bold'
                },
                {
                    data: 'lot_number',
                    name: 'receive_stock_products.lot_number'
                },
                {
                    data: 'short_description',
                    name: 'receive_stock_products.short_description'
                },
            ],
            fnDrawCallback: function() {

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
        $(document).on('click', '#filter_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.receive.stocks.print') }}";

            var user_id = $('#user_id').val();
            var search_product = $('#search_product').val();
            var product_id = $('#product_id').val();
            var variant_id = $('#variant_id').val();
            var supplier_account_id = $('#supplier_account_id').val();
            var supplier_name = $('#supplier_account_id').find('option:selected').data('supplier_name');
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    search_product,
                    user_name,
                    supplier_account_id,
                    supplier_name,
                    product_id,
                    variant_id,
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
            } else if (e.which == 27) {

                $('.search_result').hide();
                $('#list').empty();
                return false;
            }
        });

        $(document).on('click', function(e) {

            if ($(e.target).closest(".search_result").length === 0) {

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

        $("#exportButtonsContainer .dt-buttons").append(
            '<button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>'
        );
    </script>
@endpush
