@extends('layout.master')
@push('css') @endpush
@section('title', 'All Transfer(B.Location To Warehouse) - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.transfer_list')</h6>
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
                            <table class="display data_tbl data__table w-100">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.date')</th>
                                        <th>@lang('menu.reference_id')</th>
                                        <th>@lang('menu.b_location')(From)</th>
                                        <th>@lang('menu.warehouse')(To)</th>
                                        <th>@lang('menu.shipping_charge')</th>
                                        <th>@lang('menu.total_amount')</th>
                                        <th>@lang('menu.status')</th>
                                        <th>@lang('menu.actions')</th>
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
</div>

<div id="transfer_details"></div>
@endsection
@push('scripts')
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip"
        , buttons: [{
                extend: 'pdf'
                , text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang("menu.pdf")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
            , {
                extend: 'excel'
                , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang("menu.excel")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
            , {
                extend: 'print'
                , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
        , ]
        , "processing": true
        , "serverSide": true
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('transfer.stock.to.warehouse.index') }}"
        , columnDefs: [{
            "targets": [2, 3, 4, 7]
            , "orderable": false
            , "searchable": false
        }]
        , columns: [{
                data: 'date'
                , name: 'date'
            }
            , {
                data: 'invoice_id'
                , name: 'invoice_id'
            }
            , {
                data: 'from'
                , name: 'from'
            }
            , {
                data: 'to_name'
                , name: 'to_name'
            }
            , {
                data: 'shipping_charge'
                , name: 'shipping_charge'
                , className: 'text-end'
            }
            , {
                data: 'net_total_amount'
                , name: 'net_total_amount'
                , className: 'text-end'
            }
            , {
                data: 'status'
                , name: 'status'
            }
            , {
                data: 'action'
            }
        , ]
    , });
    table.buttons().container().appendTo('#exportButtonsContainer');

    $(document).on('click', '.details_button', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#transfer_details').html(data);
            $('.data_preloader').hide();
            $('#detailsModal').modal('show');
        });
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation'
            , 'message': 'Are you sure?'
            , 'buttons': {
                'Yes': {
                    'class': 'yes btn-danger'
                    , 'action': function() {
                        $('#deleted_form').submit();
                    }
                }
                , 'No': {
                    'class': 'no btn-primary'
                    , 'action': function() {}
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
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                table.ajax.reload();
                toastr.error(data);
            }
        });
    });

    // Make print
    $(document).on('click', '.print_btn', function(e) {
        e.preventDefault();
        var body = $('.transfer_print_template').html();
        var header = $('.heading_area').html();
        $(body).printThis({
            debug: false
            , importCSS: true
            , importStyle: true
            , loadCSS: "{{asset('css/print/sale.print.css')}}"
            , removeInline: false
            , printDelay: 1000
            , header: null
        , });
    });

</script>
@endpush
