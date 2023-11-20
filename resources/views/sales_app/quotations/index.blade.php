@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Sale Quotations - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.manage_quotation')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        @if (auth()->user()->can('add_quotation'))
                            <a href="{{ route('sales.quotations.create') }}" class="btn text-white btn-sm "><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.add_quotation')</span></a>
                        @endif
                    </x-slot>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="row g-0">
                <div class="col-md-12 p-15 pb-0">

                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            <div class="col-xl-2 col-md-6">
                                                <label><strong>@lang('menu.customer') </strong></label>
                                                <select name="customer_account_id" class="form-control select2 form-select" id="customer_account_id" autofocus>
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($customerAccounts as $customer)
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->name . ' (' . $customer->phone . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @if (!auth()->user()->can('view_own_sale'))
                                                <div class="col-xl-2 col-md-6">
                                                    <label><strong>{{ __("Sr.") }}</strong></label>
                                                    <select name="user_id" class="form-control select2 form-select" id="user_id" autofocus>
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <button type="submit" id="filter_button" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i>
                                                    @lang('menu.filter')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row margin_row">
            <div class="col-12 p-15">
                <div class="card">

                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.actions')</th>
                                        <th>@lang('menu.date')</th>
                                        <th>@lang('menu.expire_date')</th>
                                        <th>@lang('menu.quotation_id')</th>
                                        <th>@lang('menu.current_status')</th>
                                        <th>@lang('menu.customer')</th>
                                        <th>@lang('menu.sr')</th>
                                        <th>@lang('menu.created_by')</th>
                                        <th>@lang('menu.total_amount')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

    <div id="details"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var qutotation_table = $('.data_tbl').DataTable({
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
            // aaSorting: [[3, 'asc']],
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('sales.quotations') }}",
                "data": function(d) {
                    d.customer_account_id = $('#customer_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'action'
                },
                {
                    data: 'quotation_date',
                    name: 'quotation_date'
                },
                {
                    data: 'expire_date',
                    name: 'user.last_name'
                },
                {
                    data: 'quotation_id',
                    name: 'quotation_id',
                    className: 'fw-bold'
                },
                {
                    data: 'current_status',
                    name: 'quotation_id'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'sr',
                    name: 'sr.name'
                },
                {
                    data: 'user',
                    name: 'users.name'
                },
                {
                    data: 'total_payable_amount',
                    name: 'sr.last_name',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {
                $('.data_preloader').hide();
            },
        });
        qutotation_table.buttons().container().appendTo('#exportButtonsContainer');

        // Pass sale details in the details modal
        function quotationDetails(url) {
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }

        // Pass quotation details in the details modal
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            quotationDetails(url);
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            qutotation_table.ajax.reload();
            $('.data_preloader').show();
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();
            var body = $('.print_details').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 800,
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
                    qutotation_table.ajax.reload();
                    toastr.error(data);
                    countSalesOrdersQuotationDo();
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
            format: 'DD-MM-YYYY'
        });
    </script>
@endpush
