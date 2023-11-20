@extends('layout.master')
@push('css')
    
@endpush
@section('title', 'Purchase Order List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.purchase_orders')</h6>
                </div>

                <x-all-buttons>
                    <x-slot name="before">
                        @can('create_po')
                            <a href="{{ route('purchases.order.create') }}" class="btn text-white btn-sm"><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.new_order')</span></a>
                        @endcan
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.supplier') </strong></label>
                                                <select name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" autofocus>
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($supplierAccounts as $supplierAccount)
                                                        <option value="{{ $supplierAccount->id }}">
                                                            {{ $supplierAccount->name . '/' . $supplierAccount->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>
                                                    <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>
                                                    <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1 g-0">
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
                                            <th>@lang('menu.po_id')</th>
                                            <th>@lang('menu.requisition_no')</th>
                                            <th>@lang('menu.supplier')</th>
                                            <th>@lang('menu.created_by')</th>
                                            <th>@lang('menu.receiving_status')</th>
                                            {{-- <th>@lang('menu.payment_status')</th> --}}
                                            <th>@lang('menu.total_ordered_amount')</th>
                                            {{-- <th>@lang('menu.paid')</th>
                                            <th>@lang('menu.payment_due')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="7" class="text-white text-end">@lang('menu.total')
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th class="text-white text-end" id="total_purchase_amount"></th>
                                            {{-- <th class="text-white text-end" id="paid"></th>
                                            <th class="text-white text-end" id="due"></th> --}}
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
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('
                                successMsg ') }}');
        @endif

        purchase_order_table = $('.data_tbl').DataTable({
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
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('purchases.order.index') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.receive_status = $('#receive_status').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'action',
                    name: 'purchases.invoice_id'
                }, {
                    data: 'date',
                    name: 'purchases.date'
                }, {
                    data: 'invoice_id',
                    name: 'purchases.invoice_id',
                    className: 'fw-bold'
                }, {
                    data: 'requisition_no',
                    name: 'purchase_requisitions.requisition_no',
                    className: 'fw-bold'
                }, {
                    data: 'supplier_name',
                    name: 'suppliers.name'
                }, {
                    data: 'created_by',
                    name: 'created_by.name'
                }, {
                    data: 'status',
                    name: 'purchases.po_receiving_status',
                    className: 'fw-bold'
                },
                // {data: 'payment_status', name: 'purchase_requisitions.requisition_no', className: 'text-end'},
                {
                    data: 'total_purchase_amount',
                    name: 'total_purchase_amount',
                    className: 'text-end',
                    className: 'fw-bold'
                },
                // {data: 'paid', name: 'purchases.paid', className: 'text-end'},
                // {data: 'due', name: 'purchases.due', className: 'text-end'},
            ],
            fnDrawCallback: function() {
                var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                $('#total_purchase_amount').text(bdFormat(total_purchase_amount));
                // var paid = sum_table_col($('.data_tbl'), 'paid');
                // $('#paid').text(bdFormat(paid));
                // var due = sum_table_col($('.data_tbl'), 'due');
                // $('#due').text(bdFormat(due));
                $('.data_preloader').hide();
            }
        });

        purchase_order_table.buttons().container().appendTo('#exportButtonsContainer');

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
            $.get(url, function(data) {
                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            })
        });

        // Print Packing slip
        $(document).on('click', '#print_supplier_copy', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
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
                    purchase_order_table.ajax.reload(null, false);
                    toastr.error(data);
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            purchase_order_table.ajax.reload();
        });

        // Make print
        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();
            var body = $('.print_details').html();
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
