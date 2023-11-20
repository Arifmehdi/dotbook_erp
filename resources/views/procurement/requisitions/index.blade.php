@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Requisition List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.purchase_requisition')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        @can('create_requisition')
                            <a href="{{ route('purchases.requisition.create') }}" class="btn text-white btn-sm"><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.add_requisition')</span></a>
                        @endcan
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
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.approval_status') </strong></label>
                                            <select name="is_approved" id="is_approved" class="form-control form-select" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option value="0">@lang('menu.pending')</option>
                                                <option value="1">@lang('menu.approved')</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>@lang('menu.requested_by') </strong></label>
                                            <select name="requester_id" class="form-control add_input select2 form-select" data-name="requestedBy" id="requester_id">
                                                <option value="">@lang('menu.requested_by')</option>
                                                @foreach ($requesters as $requester)
                                                    @php
                                                        $phone = $requester->phone_number ? '/' . $requester->phone_number : '';
                                                    @endphp
                                                    <option value="{{ $requester->id }}">{{ $requester->name . $phone }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>@lang('menu.created_by') </strong></label>
                                            <select name="created_by_id" id="created_by_id" class="form-control submit_able select2">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</option>
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
                                            <button type="submit" class="btn btn-sm btn-info">
                                                <i class="fa-solid fa-filter-list"></i> @lang('menu.filter')
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
                                            <th>@lang('menu.actions')</th>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.requisition_no')</th>
                                            <th>@lang('menu.departments')</th>
                                            <th>@lang('menu.note')</th>
                                            <th>@lang('menu.created_by')</th>
                                            <th>@lang('menu.approval_status')</th>
                                            <th>@lang('menu.total_orders')</th>
                                            <th>@lang('menu.total_purchase')</th>
                                            <th>@lang('menu.total_item')</th>
                                            <th>@lang('menu.total_quantity')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="7" class="text-end text-white">@lang('menu.total') </th>
                                            <th id="total_purchase_order" class="text-white"></th>
                                            <th id="total_purchase" class="text-white"></th>
                                            <th id="total_item" class="text-white"></th>
                                            <th id="total_qty" class="text-white"></th>
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

    <div id="details"></div>

    <!-- Do approval modal -->
    <div class="modal fade" id="requisitionApprovalModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="requisition_approval_modal_content">

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
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
                "url": "{{ route('purchases.requisition.index') }}",
                "data": function(d) {

                    d.requester_id = $('#requester_id').val();
                    d.created_by_id = $('#created_by_id').val();
                    d.is_approved = $('#is_approved').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'requisition_no',
                    name: 'requisition_no',
                    className: 'fw-bold'
                },
                {
                    data: 'department',
                    name: 'departments.name'
                },
                {
                    data: 'note',
                    name: 'note'
                },
                {
                    data: 'created_by',
                    name: 'created_by.name'
                },
                {
                    data: 'is_approved',
                    name: 'is_approved'
                },
                {
                    data: 'total_purchase_order',
                    name: 'total_purchase_order',
                    className: 'fw-bold'
                },
                {
                    data: 'total_purchase',
                    name: 'total_purchase',
                    className: 'fw-bold'
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
            ],
            fnDrawCallback: function() {

                var total_purchase_order = sum_table_col($('.data_tbl'), 'total_purchase_order');
                $('#total_purchase_order').text(bdFormat(total_purchase_order));

                var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
                $('#total_purchase').text(bdFormat(total_purchase));

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

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

        // Show details modal with data
        $(document).on('click', '#requisition_approval', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#requisition_approval_modal_content').html(data);
                $('.data_preloader').hide();
                $('#requisitionApprovalModal').modal('show');
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

                    table.ajax.reload(null, false);
                    toastr.error(data);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else if (err.status == 500) {

                    } {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            table.ajax.reload();
        });

        // Make print
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
                header: null,
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

        function getSupplier() {}
    </script>
@endpush
