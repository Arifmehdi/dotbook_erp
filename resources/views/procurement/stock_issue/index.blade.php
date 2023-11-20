@extends('layout.master')
@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
@endpush
@section('title', 'Stock Issue List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.stock_issues')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :href="route('stock.issue.create')" :can="'stock_issue_create'" :is_modal="false" />
                    </x-slot>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
            <div class="p-15">
                @if (auth()->user()->can('stock_issue_view'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_element mt-0 mb-1 rounded">
                                <div class="element-body ">
                                    <form id="filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            <div class="col-xl-2 col-md-6">
                                                <label><strong>@lang('menu.receiver_department') </strong></label>
                                                <select name="department_id" id="department_id" class="form-control submit_able select2 form-select">
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><strong>@lang('menu.issue_event') </strong></label>
                                                <select name="stock_event_id" id="stock_event_id" class="form-control submit_able select2 form-select">
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($events as $event)
                                                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input type="text" name="from_date" id="datepicker" class="form-control from_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (auth()->user()->can('stock_issue_view'))
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                    </div>
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.actions')</th>
                                                    <th>@lang('menu.date')</th>
                                                    <th>@lang('menu.voucher_no')</th>
                                                    <th>@lang('menu.receiver_dep')</th>
                                                    <th>@lang('menu.issue_note')</th>
                                                    <th>@lang('menu.created_by')</th>
                                                    <th>@lang('menu.total_item')</th>
                                                    <th>@lang('menu.total_qty')</th>
                                                    <th>@lang('menu.net_total_value')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white text-end">@lang('menu.total')
                                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                    </th>
                                                    <th class="text-white text-end" id="total_item"></th>
                                                    <th class="text-white text-end" id="total_qty"></th>
                                                    <th class="text-white text-end" id="net_total_value"></th>
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
                @endif
            </div>
        </div>
    </div>

    <div id="details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        $('.select2').select2();

        purchase_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
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
                "url": "{{ route('stock.issue.index') }}",
                "data": function(d) {
                    d.department_id = $('#department_id').val();
                    d.stock_event_id = $('#stock_event_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'dep_name',
                    name: 'departments.name'
                },
                {
                    data: 'note',
                    name: 'stock_issues.note'
                },
                {
                    data: 'created_by',
                    name: 'created_by.name'
                },
                {
                    data: 'total_item',
                    name: 'total_item',
                    className: 'fw-bold'
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'net_total_value',
                    name: 'net_total_value',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var net_total_value = sum_table_col($('.data_tbl'), 'net_total_value');
                $('#net_total_value').text(bdFormat(net_total_value));

                $('.data_preloader').hide();
            }
        });
        purchase_table.buttons().container().appendTo('#exportButtonsContainer');

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

        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
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

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'vi Confirmation',
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
                    purchase_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            purchase_table.ajax.reload();
        });

        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();

            var body = $('.print_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 700,
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
    </script>
@endpush
