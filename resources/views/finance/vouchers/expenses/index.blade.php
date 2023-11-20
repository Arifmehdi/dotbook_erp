@extends('layout.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.js')}}" />
@endpush
@section('title', 'Expense List - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.expenses')</h6>
            </div>
            <x-all-buttons>
                <x-add-button :href="route('vouchers.expenses.create', 1)" :can="'add_expense'" :text="'New Expense'" :is_modal="false" />
                <x-slot name="after">
                    <button class="btn text-white btn-sm" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></button>
                    <x-help-button />
                </x-slot>
            </x-all-buttons>
        </div>
    </div>

    <div class="p-15">
        <div class="row">
            <div class="col-md-12">
                <div class="form_element rounded mt-0 mb-1">
                    <div class="element-body">
                        <form id="filter_form">
                            <div class="form-group row align-items-end g-2">
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
                                        <th class="text-start">@lang('menu.actions')</th>
                                        <th class="text-start">@lang('menu.date')</th>
                                        <th class="text-start">@lang('menu.voucher_no')</th>
                                        <th class="text-start">@lang('menu.reference')</th>
                                        <th class="text-start">@lang('menu.remarks')</th>
                                        <th class="text-start">@lang('menu.description')</th>
                                        <th class="text-start">@lang('menu.debit')</th>
                                        <th class="text-start">@lang('menu.credit')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="6" class="text-white">@lang('menu.total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <th id="debit_total" class="text-white"></th>
                                        <th id="credit_total" class="text-white"></th>
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

    @if(Session::has('errorMsg'))
    toastr.error("{{ session('errorMsg') }}");
    @endif

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
        , "ajax": {
            "url": "{{ route('vouchers.expenses.index') }}"
            , "data": function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },

        columns: [{
                data: 'action'
            }
            , {
                data: 'date'
                , name: 'expanses.date'
            }
            , {
                data: 'voucher_no'
                , name: 'expanses.voucher_no'
                , className: 'fw-bold'
            }
            , {
                data: 'reference'
                , name: 'purchases.invoice_id'
                , className: 'fw-bold'
            }
            , {
                data: 'note'
                , name: 'expanses.note'
            }
            , {
                data: 'descriptions'
            }
            , {
                data: 'debit_total'
                , name: 'expanses.debit_total'
                , className: 'fw-bold'
            }
            , {
                data: 'credit_total'
                , name: 'expanses.credit_total'
                , className: 'fw-bold'
            }
        , ]
        , fnDrawCallback: function() {

            var debit_total = sum_table_col($('.data_tbl'), 'debit_total');
            $('#debit_total').text(bdFormat(debit_total));
            var credit_total = sum_table_col($('.data_tbl'), 'credit_total');
            $('#credit_total').text(bdFormat(credit_total));
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

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });

    // Show details modal with data
    $(document).on('click', '#details_btn', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.get(url, function(data) {

            $('#details').html(data);
            $('.data_preloader').hide();
            $('#detailsModal').modal('show');
        });
    });

    // Make print
    $(document).on('click', '#print_modal_details_btn', function(e) {
        e.preventDefault();

        var body = $('.print_details').html();

        $(body).printThis({
            debug: false
            , importCSS: true
            , importStyle: true
            , loadCSS: "{{ asset('css/print/sale.print.css') }}"
            , removeInline: false
            , printDelay: 700
        , });
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
            , async: false
            , data: request
            , success: function(data) {
                if ($.isEmptyObject(data.errorMsg)) {
                    table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                } else {
                    toastr.error(data.errorMsg);
                }
            }
        });
    });

</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true
        , element: document.getElementById('from_date')
        , dropdowns: {
            minYear: new Date().getFullYear() - 50
            , maxYear: new Date().getFullYear() + 100
            , months: true
            , years: true
        }
        , tooltipText: {
            one: 'night'
            , other: 'nights'
        }
        , tooltipNumber: (totalDays) => {
            return totalDays - 1;
        }
        , format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true
        , element: document.getElementById('to_date')
        , dropdowns: {
            minYear: new Date().getFullYear() - 50
            , maxYear: new Date().getFullYear() + 100
            , months: true
            , years: true
        }
        , tooltipText: {
            one: 'night'
            , other: 'nights'
        }
        , tooltipNumber: (totalDays) => {
            return totalDays - 1;
        }
        , format: 'DD-MM-YYYY'
    , });

</script>
@endpush
