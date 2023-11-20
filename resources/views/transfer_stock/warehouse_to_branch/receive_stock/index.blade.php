@extends('layout.master')
@push('css') @endpush
@section('title', 'Receive Stocks(From Branch) - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.stocks_receive') <small>(From Branch)</small> </h6>
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
                                            <th class="text-startx">@lang('menu.date')</th>
                                            <th class="text-startx">@lang('menu.reference_id')</th>
                                            <th class="text-startx">@lang('menu.b_location')(From)</th>
                                            <th class="text-startx">@lang('menu.warehouse')(To)</th>
                                            <th class="text-startx">@lang('menu.total_item')</th>
                                            <th class="text-startx">@lang('menu.total_qty')</th>
                                            <th class="text-startx">@lang('menu.status')</th>
                                            <th class="text-center">@lang('menu.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
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

    <div id="transfer_details">

    </div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            ajax: "{{ route('transfer.stocks.to.branch.receive.stock.index') }}",
            columnDefs: [{"targets": [2, 3, 4, 7],"orderable": false,"searchable": false}],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'from'},
                {data: 'to',name: 'to'},
                {data: 'total_item',name: 'total_item'},
                {data: 'total_send_qty',name: 'total_send_qty'},
                {data: 'status',name: 'status'},
                {data: 'action'},
            ],
        });
        table.buttons().container().appendTo('#exportButtonsContainer');
        function transferDetails(url) {
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#transfer_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        }

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).closest('tr').data('href');
            transferDetails(url);
        });

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:last-child)', function(e) {
            e.preventDefault();
            var url = $(this).parent().data('href');
            transferDetails(url);
        });

        // Make print
        $(document).on('click', '.print_btn',function (e) {
           e.preventDefault();
            var body = $('.transfer_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 1000,
                header: null,
            });
        });
    </script>
@endpush
