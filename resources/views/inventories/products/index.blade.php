@extends('layout.master')
@push('css')
@endpush
@section('title', 'Item List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.items')</h6>
                </div>


                <div class="d-flex gap-2">

                    <x-table-stat :card-id="'info_item'" :items="[
                        ['id' => 'totalitem', 'name' => __('Total Item'), 'value' => $total['item']],
                        ['id' => 'activeStat', 'name' => __('Active Item'), 'value' => $total['active']],
                        ['id' => 'inactiveStat', 'name' => __('In-active Item'), 'value' => $total['inactive']],
                    ]" />

                    <x-all-buttons :can="'product_add'">
                        <x-slot name="before">
                            @can('product_add')
                                <a href="{{ route('products.add.view') }}" id="add_btn" class="btn btn-sm"><span><i
                                            class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.new_item') </span></a>
                            @endcan
                        </x-slot>
                        <x-slot name="after">
                            <a href="#" class="btn btn-sm d-lg-block d-none"><span><span
                                        class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>

            <div class="p-15">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body pt-0">
                                <form action="" method="get">
                                    <div class="form-group row">
                                        <div class="col-xl-2 col-md-4">
                                            <label><b>@lang('menu.type') </b></label>
                                            <select name="product_type" id="product_type" class="form-control submit_able"
                                                autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">@lang('menu.single')</option>
                                                <option value="2">@lang('menu.variant')</option>
                                                <option value="3">@lang('menu.combo')</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><b>@lang('menu.category') </b></label>
                                            <select id="category_id" name="category_id"
                                                class="form-control submit_able form-select">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><b>@lang('menu.subcategory') </b></label>
                                            <select id="subcategory_id" name="subcategory_id"
                                                class="form-control submit_able">
                                                <option value="">@lang('menu.select_category_first')</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><b>@lang('menu.brand') </b></label>
                                            <select id="brand_id" name="brand_id"
                                                class="form-control submit_able form-select">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><b>@lang('menu.unit') </b></label>
                                            <select id="unit_id" name="unit_id"
                                                class="form-control submit_able form-select">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">
                                                        {{ $unit->name . ' (' . $unit->code_name . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><b>@lang('menu.status') </b></label>
                                            <select name="status" id="status"
                                                class="form-control submit_able form-select">
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">@lang('menu.active')</option>
                                                <option value="0">@lang('menu.inactive')</option>
                                            </select>
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
                                <!--begin: Datatable-->
                                <form id="multiple_action_form" action="{{ route('products.multiple.delete') }}"
                                    method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="action" id="action">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                                    </div>
                                    <div class="table-responsive" id="data_list">
                                        <table class="display table-hover data_tbl data__table">
                                            <thead>
                                                <tr class="bg-navey-blue">
                                                    <th data-bSortable="false">
                                                        <input class="all" type="checkbox" name="all_checked" />
                                                    </th>
                                                    <th>@lang('menu.image')</th>
                                                    <th>@lang('menu.actions')</th>
                                                    <th>@lang('menu.item')</th>
                                                    <th>@lang('menu.item_code')</th>
                                                    <th>@lang('menu.unit')</th>
                                                    <th>@lang('menu.unit_cost_inc_tax')</th>
                                                    <th>@lang('menu.unit_price_exc_tax')</th>
                                                    <th>@lang('menu.current_stock')</th>
                                                    <th>@lang('menu.item_type')</th>
                                                    <th>@lang('menu.category')</th>
                                                    <th>@lang('menu.brand')</th>
                                                    <th>@lang('menu.tax')</th>
                                                    <th>@lang('menu.status')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="14">
                                                        @if (auth()->user()->can('product_delete'))
                                                            <a href="#"
                                                                class="btn btn-sm btn-danger multipla_delete_btn">@lang('menu.delete_selected')</a>
                                                        @endif
                                                        <a href="#"
                                                            class="btn btn-sm btn-warning multipla_deactive_btn text-dark">@lang('menu.deactivate_selected')</a>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </form>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
    <!-- Details Modal End-->

    <!-- Opening stock Modal -->
    <div class="modal fade" id="openingStockModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    </div>
    <!-- Opening stock Modal-->
@endsection
@push('scripts')
    <!--Data table js active link-->
    <script>
        $('.loading_button').hide();
        // Filter toggle
        $('.filter_btn').on('click', function(e) {
            e.preventDefault();

            $('.filter_body').toggle(500);
        });

        var product_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }, ],
            // <a href="#" id="excel" class="excel btn text-white btn-sm px-1"><i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')</a>
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [0, 'asc']
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('products.all.product') }}",
                "data": function(d) {
                    d.type = $('#product_type').val();
                    d.category_id = $('#category_id').val();
                    d.subcategory_id = $('#subcategory_id').val();
                    d.brand_id = $('#brand_id').val();
                    d.unit_id = $('#unit_id').val();
                    d.status = $('#status').val();
                    d.is_for_sale = $('#is_for_sale').val();
                }
            },
            columnDefs: [{
                "targets": [0, 1, 2, 12],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                data: 'multiple_delete',
            }, {
                data: 'photo',
                name: 'photo'
            }, {
                data: 'action',
                name: 'action'
            }, {
                data: 'name',
                name: 'products.name'
            }, {
                data: 'product_code',
                name: 'products.product_code'
            }, {
                data: 'unit_name',
                name: 'units.name'
            }, {
                data: 'unit_cost_inc_tax',
                name: 'products.product_cost_with_tax',
                className: 'fw-bold'
            }, {
                data: 'unit_price_exc_tax',
                name: 'products.product_price',
                className: 'fw-bold'
            }, {
                data: 'current_stock',
                name: 'products.quantity',
                className: 'fw-bold'
            }, {
                data: 'type',
                name: 'products.type'
            }, {
                data: 'cate_name',
                name: 'categories.name'
            }, {
                data: 'brand_name',
                name: 'brands.name'
            }, {
                data: 'tax_name',
                name: 'taxes.tax_name'
            }, {
                data: 'status',
                name: 'products.status'
            }, ],
        });

        product_table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).ready(function() {

            $(document).on('change', '.submit_able', function() {

                product_table.ajax.reload();
                refresh();
            });
        });

        // set sub category in form field
        $('#category_id').on('change', function() {

            var category_id = $(this).val();

            $.get("{{ url('common/ajax/call/category/subcategories/') }}" + "/" + category_id, function(
                subCategories) {

                $('#subcategory_id').empty();
                $('#subcategory_id').append('<option value="">All</option>');

                $.each(subCategories, function(key, val) {

                    $('#subcategory_id').append('<option data-sub_cate_name="' + val.name +
                        '" value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

        $(document).on('ifChanged', '#is_for_sale', function() {

            product_table.ajax.reload();
            refresh();
        });

        $(document).on('change', '.all', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.data_id').prop('checked', true);
            } else {

                $('.data_id').prop('checked', false);
            }
        });

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.get(url, function(data) {

                $('#detailsModal').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        //Check purchase and generate burcode
        $(document).on('click', '#check_pur_and_gan_bar_button', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        window.location = data;
                    }
                }
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

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    product_table.ajax.reload();
                    refresh();
                    toastr.error(data);
                }
            });
        });

        function refresh() {
            $.get("{{ route('product.status.change') }}", function(data) {
                $('#totalitem').text(data.item);
                $('#activeStat').text(data.active);
                $('#inactiveStat').text(data.inactive);
            });
        }
        refresh();

        // Show sweet alert for delete
        $(document).on('click', '.change_status', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'get',
                                success: function(data) {
                                    toastr.success(data);
                                    product_table.ajax.reload();
                                    refresh();
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-primary',
                        'action': function() {

                        }
                    }
                }
            });
        });

        $(document).on('click', '.multipla_delete_btn', function(e) {
            e.preventDefault();

            $('#action').val('multiple_delete');

            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#multiple_action_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
                    }
                }
            });
        });

        $(document).on('click', '.multipla_deactive_btn', function(e) {
            e.preventDefault();

            $('#action').val('multipla_deactive');

            $.confirm({
                'title': 'Deactive Confirmation',
                'content': 'Are you sure to deactive selected all?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#multiple_action_form').submit();
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
        $(document).on('submit', '#multiple_action_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'Attention');
                    } else {

                        product_table.ajax.reload();
                        toastr.success(data, 'Attention');
                    }
                }
            });
        });

        // Show opening stock modal with data
        $(document).on('click', '#openingStockBtn', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#openingStockModal').html(data);
                    $('#openingStockModal').modal('show');
                    $('.data_preloader').hide();
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

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();
            var body = $('.modal-body').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: true,
                printDelay: 800,
                header: null,
            });
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                // $('#add_btn').click();
                window.location = $('#add_btn').attr('href');
                return false;
            }
        }
    </script>
@endpush
