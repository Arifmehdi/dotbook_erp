@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * { box-sizing: border-box;}
        .row { margin-left:-5px; margin-right:-5px;}
        .column {float: left; width: 100%; padding: 0px;}
        /* Clearfix (clear floats) */
        .row::after {content: "";clear: both;display: table;}
        table { border-collapse: collapse;border-spacing: 0; width: 100%;border: 1px solid #ddd;}
        th, td { text-align: left; vertical-align: baseline; }
        .group_tr {line-height: 17px;}
        .account_tr {line-height: 17px;}
        table {border: none!important;}
        td.group_cash_flow_area {border-left: 1px solid #000;}
        .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_credit_total {border-left: 1px solid #000;}
        td.inflow_area {line-height: 17px;}
        td.group_cash_flow_area {line-height: 17px;}
        /* font-family: sans-serif; */
        .header_text {letter-spacing: 3px;border-bottom: 1px solid; background-color: #fff!important; color: #000!important}
    </style>
@endpush
@section('title', $accountGroup->name.' - Group Cash Flow - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.group_cash_flow') - <span>{{ $accountGroup->name }}</span></h6>
                </div>
                <x-all-buttons>
                    <button  class="btn text-white btn-sm px-2" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></button>
                    <x-help-button />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_group_cash_flow">
                                <div class="form-group row align-items-end">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}" autocomplete="off">
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

            <div class="row g-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="px-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="group_cash_flow_area">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.inflow')</th>
                                                    <th class="header_text ps-1 text-end" style="border-left: 1px solid black;">@lang('menu.outflow')</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="group_tr">
                                                    <td class="fw-bold account_group"></td>
                                                    <td class="text-end fw-bold" style="border-right: 1px solid black;"></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>

                                            <tfoot class="net_total_balance_footer">
                                                <tr>
                                                    <td class="text-start fw-bold">@lang('menu.grand_total') :</td>
                                                    <td class="text-end fw-bold net_debit_total"></td>
                                                    <td class="text-end fw-bold net_credit_total"></td>
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
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    function getGroupCashflow() {

        $('.data_preloader').show();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            url:"{{ route('reports.group.cash.flow.view', [$accountGroup->id, $cashFlowSide]) }}",
            type: 'GET',
            data : { from_date, to_date },
            success:function(data) {

                $('.data_preloader').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#data-list').html(data);
            }
        });
    }
    getGroupCashflow();

    $(document).on('submit', '#filter_group_cash_flow', function (e) {

        e.preventDefault();
        getGroupCashflow();
    });

    // Print single payment details
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();

        var url = "{{ route('reports.group.cash.flow.print', [$accountGroup->id, $cashFlowSide]) }}";

        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            url:url,
            type:'get',
            data: { from_date, from_date },
            success:function(data){

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 1000,
                });
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
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
