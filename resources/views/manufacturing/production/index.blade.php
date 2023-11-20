@extends('layout.master')
@push('css')
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'All Productions - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div>
                    <h6>@lang('menu.production_manage')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('manufacturing.productions.create')" :can="'production_add'" :is_modal="false" />
                    @if (auth()->user()->can('process_view'))
                        <div>
                            <a href="{{ route('manufacturing.process.index') }}"
                                class="text-white btn text-white btn-sm"><span><i
                                        class="fa-thin  fa-dumpster-fire  fa-2x"></i><br> @lang('menu.process')</span></a>
                        </div>
                    @endif
                    @if (auth()->user()->can('production_view'))
                        <div>
                            <a href="{{ route('manufacturing.productions.index') }}"
                                class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-shapes fa-2x"></i><br>
                                    @lang('menu.productions')</span></a>
                        </div>
                    @endif
                    @if (auth()->user()->can('manuf_settings'))
                        <div>
                            <a href="{{ route('manufacturing.settings.index') }}"
                                class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-sliders fa-2x"></i><br>
                                    @lang('menu.manufacturing_setting')</span></a>
                        </div>
                    @endif
                    @if (auth()->user()->can('manuf_report'))
                        <div>
                            <a href="{{ route('manufacturing.report.index') }}"
                                class="text-white btn text-white btn-sm"><span><i
                                        class="fa-thin fa-file-lines fa-2x"></i><br> @lang('menu.manufacturing_report')</span></a>
                        </div>
                    @endif
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form id="filter_form" class="px-2">
                                <div class="form-group row">
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
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-sm btn-info"><i
                                                    class="fa-solid fa-filter-list"></i> @lang('menu.filter')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">
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
                                                <th class="text-black">@lang('menu.actions')</th>
                                                <th class="text-black">@lang('menu.date')</th>
                                                <th class="text-black">@lang('menu.voucher_no')</th>
                                                <th class="text-black">@lang('menu.product')</th>
                                                <th class="text-black">@lang('menu.status')</th>
                                                <th class="text-black">@lang('menu.per_unit_cost')(Inc.Tax)</th>
                                                <th class="text-black">@lang('menu.selling_price')(@lang('menu.exc_tax'))</th>
                                                <th class="text-black">@lang('menu.final_qty')</th>
                                                <th class="text-black">@lang('menu.total_ingredient_cost')</th>
                                                <th class="text-black">@lang('menu.production_cost')</th>
                                                <th class="text-black">@lang('menu.total_cost')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="8" class="text-white text-end">@lang('menu.total') :
                                                    ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
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

                        @if (auth()->user()->can('production_delete'))
                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        @endif
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
    <script>
        var production_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
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
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('manufacturing.productions.index') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0, 7],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                data: 'action'
            }, {
                data: 'date',
                name: 'date'
            }, {
                data: 'reference_no',
                name: 'reference_no'
            }, {
                data: 'product',
                name: 'products.name'
            }, {
                data: 'status',
                name: 'productions.is_final'
            }, {
                data: 'unit_cost_inc_tax',
                name: 'unit_cost_inc_tax',
                className: 'text-end'
            }, {
                data: 'price_exc_tax',
                name: 'price_exc_tax',
                className: 'text-end'
            }, {
                data: 'total_final_quantity',
                name: 'total_final_quantity',
                className: 'text-end'
            }, {
                data: 'total_ingredient_cost',
                name: 'total_ingredient_cost',
                className: 'text-end'
            }, {
                data: 'production_cost',
                name: 'production_cost',
                className: 'text-end'
            }, {
                data: 'total_cost',
                name: 'total_cost',
                className: 'text-end'
            }, ],
            fnDrawCallback: function() {

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
        $(document).on('submit', '#filter_form', function(e) {
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

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
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
                    production_table.ajax.reload();
                    toastr.error(data);
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
