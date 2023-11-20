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
        td.group_summary_area {border-left: 1px solid #000;}
        .net_total_balance_footer tr {border-top: 1px solid!important; border-bottom: 1px solid!important;line-height: 16px;padding: 5px;}
        .net_total_balance_footer tr td {padding: 5px!important;font-size: 14px!Important;}
        td.group_summary_area {line-height: 17px;}
        .header_text {letter-spacing: 3px;border-bottom: 1px solid; background-color: #fff!important; color: #000!important}
        tr.group_tr td {border-bottom: 1px solid lightgray;}
        tr.account_tr td {border-bottom: 1px solid lightgray;}

    </style>
@endpush
@section('title', $accountGroup->name.' - Group Summary - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.group_summary') - <span>{{ $accountGroup->name }}({{ $accountGroup->parent_group_name }})</span></h6>
                </div>
                <div class="d-flex">
                    {{-- <button class="btn text-white btn-sm px-2"><i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')</button>
                    <button class="btn text-white btn-sm px-2"><i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')</button>
                    <button id="print_report_btn" class="btn text-white btn-sm px-2" id="print_btn"><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</button>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a> --}}
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                    </a>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_group_summary">
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
                                <div class="group_summary_area">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.opening_balance')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.debit')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.credit')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.closing_balance')</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="group_tr">
                                                    <td class="fw-bold account_group">Account Receivable</td>
                                                    <td class="text-end fw-bold">10,000.00 Dr.</td>
                                                    <td class="text-end fw-bold">11,000.00</td>
                                                    <td class="text-end fw-bold">12,000.00</td>
                                                    <td class="text-end fw-bold">12,000.00 Cr.</td>
                                                </tr>
                                            </tbody>

                                            <tfoot class="net_total_balance_footer">
                                                <tr>
                                                    <td class="text-start fw-bold">@lang('menu.grand_total') :</td>
                                                    <td class="text-end fw-bold net_opening_total">10,000.00 Dr.</td>
                                                    <td class="text-end fw-bold net_debit_total">11,000.00</td>
                                                    <td class="text-end fw-bold net_credit_total">12,000.00</td>
                                                    <td class="text-end fw-bold net_closing_total">12,000.00 Cr.</td>
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

    function getGroupSummary() {

        $('.data_preloader').show();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            url:"{{ route('reports.group.summary.view', [$accountGroup->id]) }}",
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
    getGroupSummary();

    $(document).on('submit', '#filter_group_summary', function (e) {

        e.preventDefault();
        getGroupSummary();
    });

    // Print single payment details
    // $(document).on('click', '#print_report_btn', function (e) {
    //     e.preventDefault();

    //     var url = "";

    //     var from_date = $('#from_date').val();
    //     var to_date = $('#to_date').val();

    //     $.ajax({
    //         url:url,
    //         type:'get',
    //         data: { from_date, to_date },
    //         success:function(data){

    //             if (!$.isEmptyObject(data.errorMsg)) {

    //                 toastr.error(data.errorMsg);
    //                 return;
    //             }

    //             $(data).printThis({
    //                 debug: false,
    //                 importCSS: true,
    //                 importStyle: true,
    //                 loadCSS: "{{asset('css/print/sale.print.css')}}",
    //                 removeInline: false,
    //                 printDelay: 1000,
    //             });
    //         }
    //     });
    // });
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
