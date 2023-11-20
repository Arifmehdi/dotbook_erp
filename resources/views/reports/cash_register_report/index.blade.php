@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Cash Register Reports - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.cash_register_reports')</h6>
                </div>
                <div class="d-flex">
                    <div id="exportButtonsContainer">
                    </div>
                    <div class="custom-print-left">
                        <button id="print_report" class="btn text-white btn-sm"><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</button>
                    </div>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i>
                        <br>@lang('menu.back')
                    </a>
                </div>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <form id="filter_form" action="" method="get">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.user') </strong></label>
                                            <select name="user_id" class="form-control submit_able form-select" id="user_id" autofocus>
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <option value="">@lang('menu.all')</option>
                                                @else
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($branchUsers as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.status') </strong></label>
                                            <select name="status" class="form-control submit_able form-select" id="status">
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">@lang('menu.open')</option>
                                                <option value="2">@lang('menu.closed')</option>
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

                                                <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
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

                                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <button type="submit" id="filter_button" class="btn btn-sm btn-info">
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
                    <div class="col-md-12">
                        <div class="report_data_area">
                            <div class="data_preloader">
                                <h6>
                                    <i class="fas fa-spinner text-primary"></i> @lang('menu.processing')
                                </h6>
                            </div>

                            <div class="card">
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-startx">@lang('menu.open_time')</< /th>
                                                <th class="text-startx">@lang('menu.closed_time')</th>
                                                <th class="text-startx">@lang('menu.user')</th>
                                                <th class="text-startx">@lang('menu.closing_note')</th>
                                                <th class="text-startx">@lang('menu.status')</th>
                                                <th class="text-startx">@lang('menu.closed_time')</th>
                                                <th class="text-startx">@lang('menu.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="6" class="text-end text-white">@lang('menu.total') :
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                </th>
                                                <th id="closed_amount" class="text-end text-white"></th>
                                                <th></th>
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

    <div class="modal fade" id="cashRegisterDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="cash_register_details_content"></div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var cr_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                // {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.cash.registers.index') }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [1, 5, 7],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'created_at',
                    name: 'created_at'
                }, {
                    data: 'closed_time',
                    name: 'closed_time'
                }, {
                    data: 'user',
                    name: 'users.name'
                }, {
                    data: 'closing_note',
                    name: 'closing_note'
                }, {
                    data: 'status',
                    name: 'status',
                    className: 'text-end'
                }, {
                    data: 'closed_amount',
                    name: 'closed_amount',
                    className: 'text-end'
                }, {
                    data: 'action'
                },

            ],
            fnDrawCallback: function() {
                $('.data_preloader').hide();
            }
        });
        cr_table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            cr_table.ajax.reload();
            $('.data_preloader').show();
        });

        $(document).on('click', '#register_details_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#cash_register_details_content').html(data);
                    $('#cashRegisterDetailsModal').modal('show');
                }
            });
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.get.cash.register.report.print') }}";
            var user_id = $('#user_id').val();
            var status = $('#status').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    status,
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
                        // footer: 'Footer Text',
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
