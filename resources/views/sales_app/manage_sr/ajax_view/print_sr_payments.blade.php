<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 5px;margin-right: 5px;}

</style>
<div class="row">
    <div class="col-12 text-center">

        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') :</strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <p style="margin-top: 10px;"><strong>@lang('menu.sr_receipts_or_payments_report') </strong></p>

        @if ($fromDate && $toDate)
            <p style="margin-top: 10px;"><strong>@lang('menu.from') :</strong> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
        @endif
    </div>
</div>

<div class="sr_details_area">
    <div class="row">
        <div class="col-12">
            <ul class="list-unstyled">
                <li><strong>{{ __("Sr.") }} : </strong> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </li>
                <li><strong>@lang('menu.phone') : </strong> {{ $user->phone }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="row mt-2">
    <p><strong>@lang('menu.filtered_by')</strong></p>
    <div class="col-12">
        <p><strong>@lang('menu.customer') :</strong> {{ $customerName }} </p>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.reference')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.against_invoice')</th>
                    <th class="text-start">@lang('menu.payment_status')</th>
                    <th class="text-start">@lang('menu.payment_type')</th>
                    <th class="text-start">@lang('menu.account')</th>
                    <th class="text-end">@lang('menu.less_amount')</th>
                    <th class="text-end">@lang('menu.paid_amount')</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($payments as $row)
                    <tr>
                        <td class="text-start">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp

                            {{ date($__date_format, strtotime($row->report_date)) }}
                        </td>

                        <td class="text-start">
                            {{ $row->customer_payment_voucher . $row->sale_payment_voucher }}
                        </td>

                        <td class="text-start">{{ $row->reference }}</td>
                        <td class="text-start">{{ $row->c_name }}</td>

                        <td class="text-start">
                            @if ($row->sale_inv || $row->return_inv)

                                @if ($row->sale_inv)

                                    {{ 'Sale : ' . $row->sale_inv}}
                                @else

                                    {{ 'Sale Return : ' . $row->return_inv }}
                                @endif
                            @endif
                        </td>

                        <td class="text-start">
                            @if ($row->voucher_type == 3 || $row->voucher_type == 5)

                                {{ 'Receipt' }}
                            @else

                                {{ 'Return' }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{  $row->cp_pay_mode . $row->cp_payment_method . $row->sp_pay_mode . $row->sp_payment_method }}
                        </td>

                        <td class="text-start">
                            @if ($row->cp_account)

                                {{ $row->cp_account . ($row->cp_account_number ? '(A/c:' . $row->cp_account_number . ')' : '') }}
                            @else

                                {{ $row->sp_account . ($row->sp_account_number ? '(A/c:' . $row->sp_account_number . ')' : '') }}
                            @endif
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->less_amount) }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->amount) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (config('company.print_on_others'))
    <div class="row">
        <div class="col-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
    <small style="font-size: 5px;float:right;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
