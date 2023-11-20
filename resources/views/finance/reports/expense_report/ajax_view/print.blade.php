<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important;}
</style>
@php
    $totalExpense = 0;
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
    <div class="col-4">
        @if (json_decode($generalSettings->business, true)['business_logo'] != null)

            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
        @else

            <p style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
        @endif
    </div>

    <div class="col-8 text-end">
        <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
        <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
        <p>
            <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
            <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
        </p>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.expense_report') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to')</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-6">
        <small><strong>@lang('menu.group_name') :</strong> {{ $expenseGroupName ? $expenseGroupName : 'All' }}</small>
    </div>

    <div class="col-6">
        <small><strong>@lang('menu.ledger_or_account_name') :</strong> {{ $expenseAccountName }} </small>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.voucher_type')</th>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.amount')</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $previousAccountId = '';
                    $totalAmount = 0;
                    $dateTotalAmount = 0;
                    $isSameGroup = true;
                    $lastAccountId = null;
                    $lastDateTotalAmount = 0;
                @endphp
                @foreach ($expenses as $ex)
                    @php
                        $totalAmount += $ex->amount;
                        $date = date($__date_format, strtotime($ex->date));
                        $isSameGroup = (null != $lastAccountId && $lastAccountId == $ex->account_id) ? true : false;
                        $lastAccountId = $ex->account_id;
                    @endphp

                    @if ($isSameGroup == true)

                        @php
                            $dateTotalAmount += $ex->amount;
                        @endphp
                    @else
                        @if ($dateTotalAmount != 0)
                            <tr>
                                <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($dateTotalAmount) }}</td>
                            </tr>
                        @endif

                        @php $dateTotalAmount = 0; @endphp
                    @endif

                    @if ($previousAccountId != $ex->account_id)
                        @php
                            $previousAccountId = $ex->account_id;
                            $dateTotalAmount += $ex->amount;
                        @endphp

                        <tr>
                            <td class="text-start text-uppercase fw-bold" colspan="4">{{ $ex->account_name.' - ('.$ex->group_name.')' }} </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $date }}</td>
                        <td class="text-start fw-bold">
                            @php
                                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                                $type = $accountLedgerUtil->voucherType($ex->voucher_type);
                            @endphp
                            {!! $ex->voucher_type != 0 ?  $type['name'] : '' !!}
                        </td>

                        <td class="text-start fw-bold">{!! $ex->{$type['voucher_no']} !!}</td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->amount) }}</td>
                    </tr>

                    @php
                        $__veryLastAccountId = $veryLastAccountId;
                        $currentAccountId = $ex->account_id;
                        if ($currentAccountId == $__veryLastAccountId) {

                            $lastDateTotalAmount += $ex->amount;
                        }
                    @endphp

                    @if($loop->index == $lastRow)

                        <tr>
                            <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastDateTotalAmount) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-end">@lang('menu.total_expense') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalAmount) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
