@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
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
            z-index: 9999;
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
    </style>
@endpush
@section('title', 'Purchase List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.purchased_item_list')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :href="route('purchases.create')" :can="'purchase_add'" :is_modal="false" />
                    </x-slot>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body overflow-visible">
                                <div class="col-md-12">
                                    <form action="" method="get">
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
                                                <select name="supplier_account_id" class="form-control submit_able select2" id="supplier_account_id">
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($supplierAccounts as $supplierAccount)
                                                        <option value="{{ $supplierAccount->id }}">
                                                            {{ $supplierAccount->name . '/' . $supplierAccount->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-3 col-md-4">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label><strong>@lang('menu.category') </strong></label>
                                                        <select name="category_id" class="form-control submit_able select2" id="category_id">
                                                            <option value="">@lang('menu.all')</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label><strong>@lang('menu.sub_category') </strong></label>
                                                        <select name="sub_category_id" class="form-control submit_able select2 form-select" id="sub_category_id">
                                                            <option value="">@lang('menu.all')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input type="text" name="from_date" id="datepicker" class="form-control from_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-1 col-md-4">
                                                <button type="button" id="filter_button" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.item')</th>
                                            <th>@lang('menu.p_code')</th>
                                            <th>@lang('menu.supplier')</th>
                                            <th>@lang('short.p_invoice_id')</th>
                                            <th>@lang('menu.quantity')</th>
                                            <th>@lang('menu.unit_cost')({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </th>
                                            <th>@lang('menu.subtotal')({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="5" class="text-end text-white">@lang('menu.total')
                                                {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                            <th class="text-startx text-white">(<span id="total_qty"></span>)</th>
                                            <th class="text-startx text-white">---</th>
                                            <th class="text-startx text-white"><span id="total_subtotal"></span></th>
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

    <div id="purchase_details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>

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
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
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
                "url": "{{ route('purchases.product.list') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.category_id = $('#category_id').val();
                    d.sub_category_id = $('#sub_category_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'purchases.date'
                },
                {
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'product_code',
                    name: 'products.name'
                },
                {
                    data: 'supplier_name',
                    name: 'suppliers.name as supplier_name'
                },
                {
                    data: 'invoice_id',
                    name: 'purchases.invoice_id'
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    className: 'text-end'
                },
                {
                    data: 'net_unit_cost',
                    name: 'net_unit_cost',
                    className: 'text-end'
                },
                {
                    data: 'subtotal',
                    name: 'subtotal',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {
                var total_qty = sum_table_col($('.data_tbl'), 'qty');
                $('#total_qty').text(bdFormat(total_qty));
                var total_subtotal = sum_table_col($('.data_tbl'), 'subtotal');
                $('#total_subtotal').text(bdFormat(total_subtotal));
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
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#purchase_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
                $('.action_hideable').hide();
            })
        });

        $('#category_id').on('change', function() {

            var category_id = $(this).val();

            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">ALL</option>');

            $.get("{{ url('common/ajax/call/category/subcategories/') }}" + "/" + category_id, function(
                subCategories) {

                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">ALL</option>');
                $.each(subCategories, function(key, val) {
                    $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            });
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
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        //Submit filter form by date-range field blur
        $(document).on('click', '#search_product', function() {
            $(this).val('');
            $('#product_id').val('');
            $('#variant_id').val('');
        });

        //Submit filter form by select input changing
        $(document).on('click', '#filter_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
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
@endpush
