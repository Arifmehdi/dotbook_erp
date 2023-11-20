<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table tr td{font-size: 10px!important;}
    .print_table tr th{font-size: 10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
    .report-table th {
        line-height: 1.2;
        vertical-align: top;
    }
    .report-table td {
        line-height: 1.2;
        vertical-align: middle;
    }
</style>
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

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.payroll') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        {{--@if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to') :</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif--}}
    </div>
</div>

<div class="row">
    <div class="col-4">
        {{--<small><strong>@lang('menu.customer') :</strong> 
             {{ $customerName }} 
        </small> --}}
    </div>

    <div class="col-4">
        {{--<small><strong>@lang('menu.sr') :</strong> 
             {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }} 
        </small>--}}
    </div>

    <div class="col-4">
        {{--<small><strong>@lang('menu.sales_ledger_ac') :</strong> 
             {{ $saleAccountName }}  
        </small>--}}
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $totalQty = 0;
    $totalWeight = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalShipmentCharge = 0;
    $TotalSaleAmount = 0;
@endphp

<div class="row mt-1">
    <div class="col-12">
       <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    {{--\\--<th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-start">@lang('menu.do_id')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.sr')</th>
                    <th class="text-end">@lang('menu.total_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-end">@lang('menu.net_weight')</th>
                    <th class="text-end">@lang('short.net_total_amt').</th>
                    <th class="text-end">@lang('menu.sale_discount')</th>
                    <th class="text-end">@lang('menu.sale_tax')</th>
                    <th class="text-end">@lang('menu.shipment_charge')</th>
                    <th class="text-end">@lang('menu.total_amount')</th>--//--}}




                    <th class="text-black" style="font-size: 12px;">SI</th>
                    <th class="text-black" style="font-size: 12px;">ID. Name</th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Joining Date</span></th>
                    <th style="font-size: 12px;" class="text-black">Designation. Grade</th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Gross Salary</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Basic</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">House Rent</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Medical</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Food</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Transport</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Month's Day</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Working Day</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Present</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Absent</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Leave</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Offday</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Attendance Bonus</span></th>


                    {{-- BUYER MODE --}}

                    @if(! $isBuyerMode)
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Tiffin(d)</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Tiffin Bill</span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Night(d) </span></th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Night Bill</span></th>
                    @endif


                    <th class="text-center text-black" style="font-size: 12px;" colspan="3">Overtime</th>
                    <th style="font-size: 12px;" class="text-black"><span class="vertical">Other Earn</span></th>
                    <th style="font-size:12px;" class="text-black"><span class="vertical">Gross. pay</span></th>
                    <th class="text-center text-black" style="font-size: 12px;" colspan="3">Deduction</th>
                    <th class="text-black" style="font-size: 12px;" >Total Deduc.</th>
                    <th class="text-black" style="font-size: 12px;" >Net Payable salary</th>
                    <th class="text-center text-black" style="font-size: 12px;">Signature</th>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    @if(! $isBuyerMode)
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @else
                    {{-- Ignore --}}
                    @endif


                    <td class="" style="color: black;">OT/H</td>
                    <td class="" style="color: black;">Rate</td>
                    <td class="" style="color: black;">Amount</td>
                    <td></td>
                    <td></td>

                    <td class="" style="color: black;">Abs/Adv</td>
                    <td class="" style="color: black;">Tax</td>
                    <td class="" style="color: black;">Stmp</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <div>
                    @foreach($employees as $row_key => $employee)
                    <tr>
                        <td style="color: black; font-size: 12px; font-weight: bold;"><span class="vertical"> {{ $loop->index + 1 }} </span></td>
                        <td class="id_name_column" style="color: black; font-size: 12px;"> {{ $employee->employee_id }} <br>{{ $employee->employee_name }} </td>
                        <td style="color: black; font-size: 12px;"><span class="vertical"> {{ $employee->joining_date }}</span></td>
                        <td style="color: black; font-size: 12px;"><span class="vertical"> {{ $employee->designation_name }} <br> {{ $employee->grade_name }} </span></td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->salary }} </td>
                        <td class="text-center" style="color: black; font-size: 12px; ">{{ $employee->basic }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->house_rent }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->medical }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->food }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->transport }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $days_in_month }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->working_days }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->present }}</td>
                        <td class="text-center" style="color: black; font-size: 12px;">{{ $employee->absent }}</td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->leaves }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->off_days }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->attendance_bonus ?? '0'  }} </td>

                        {{-- BUYER MODE --}}
                        @if(!$isBuyerMode)

                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->tiffin_days ?? '0'  }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->tiffin_bill ?? '0' }} </td>

                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->night_bill_days ?? '0' }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->night_bill ?? '0'  }} </td>

                        @endif


                        <td class="text-center" style="color: black; font-size: 12px; "> {{ $employee->over_time ?? '0'  }} </td>
                        <td class="text-center" style="color: black; font-size: 12px; "> {{ $employee->over_time_rate ?? '0'  }} </td>
                        <td class="text-center" style="color: black; font-size: 12px; "> {{ $employee->over_time_amount ?? '0'  }} </td>
                        <td class="text-center" style="color: black; font-size: 12px; "> {{ $employee->other_earning ?? '0'  }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;">{{ $employee->gross_pay ?? '0' }}</td>
                        <td class="text-center" style="color: black; font-size: 12px;">
                            {{ $employee->absent_amount ?? '0' }}
                            <hr style="border-top: 1px solid black; opacity: 1;margin: 15px 0">
                            {{ $employee->advance ?? '0' }}
                        </td>
                        <td class="text-center" style="color: black; font-size: 12px;"> {{ $employee->tax ?? '0' }} </td>
                        <td class="text-center" style="color: black; font-size: 12px;">{{ $employee->stamp ?? '0' }}</td>
                        <td class="text-center" style="color: black; font-size: 12px;">
                            {{ $employee->total_deductions ?? '0' }}
                        </td>
                        <td class="text-center" style="color: black; font-size: 12px; ">
                            {{ $employee->payable_salary ?? '0' }}
                        </td>
                        @if(! $isBuyerMode)
                        <td class="text-center" style="padding-top: 70px; font-size: 12px;">{{ $employee->rocket ?? '0' }}</td>
                        @else
                        <td></td>
                        @endif

                    </tr>
                    @endforeach
                </div>

            </tbody>



            @if($isBuyerMode)
            <tr style="padding-top: 30px;padding-bottom:30px;">
                <th colspan="4" class="text-center" style="color: black; font-size: 12px;">Total Employees: {{ count($employees) }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-center" style="color: black; font-size: 12px;"> {{ $total_attendance_bonus ?? '0' }} </th>
                <th colspan="3" class="text-center" style="color: black; font-size: 12px;"> {{ $total_over_time_amount }} </th>
                <th></th>
                <th class="text-center" style="color: black; font-size: 12px;"> {{ $total_gross_pay }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-center" style="color: black; font-size: 12px;">{{ $total_payable_salary ?? '0' }} </th>
                <th></th>
            </tr>
    
            @else
    
            <tr style="padding-top: 30px;padding-bottom:30px;">
                <th colspan="4" class="text-center" style="color: black; font-size: 12px;">Total Employees: {{ count($employees) }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-center" style="color: black; font-size: 12px;"> {{ $total_attendance_bonus ?? '0' }} </th>
                <th colspan="2" class="text-center" style="color: black; font-size: 12px;">{{ $total_tiffin_bill ?? '0' }} </th>
                <th colspan="2" class="text-center" style="color: black; font-size: 12px;">{{ $total_night_bill ?? '0' }} </th>
                <th colspan="3" class="text-center" style="color: black; font-size: 12px;"> {{ $total_over_time_amount }} </th>
                <th></th>
                <th class="text-center" style="color: black; font-size: 12px;"> {{ $total_gross_pay }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-center" style="color: black; font-size: 12px;">{{ $total_payable_salary ?? '0' }} </th>
                <th></th>
            </tr>
    
    
            @endif




            <br>
    @php
    $hrm = array_reduce($hrm_settings, function($key, $value){ return $value; });
    @endphp
    <table class="table mt-2">
        {{--<tr>{{ json_decode($settings->bussiness)?->shop_name ?? 'Company Name' }}
             <th style="font-weight: bold; font-size: 14px; float: left;">
                <img src="{{ asset('images/application_image/' . json_decode($settings->payroll_setting)?->prepared_by_signature ?? 'Image') }}" style="height: 40px; margin-left:10px;"><br>
                {{ $settings?->payroll_setting['prepared_by_text'] }} <br>
                {{ $settings?->payroll_setting['prepared_by_person'] }}
            </th>
            <th style="font-weight: bold; font-size: 16px;"></th>
            <th style="font-weight: bold; font-size: 16px;"></th>
            <th style="font-weight: bold; font-size: 14px;">
                <img src="{{ asset('images/application_image/' . $settings?->payroll_setting['checked_by_signature']) }}" style="height: 40px; margin-left:10px;"><br>
                {{ $settings?->payroll_setting['checked_by_text'] }} <br>
                {{ $settings?->payroll_setting['checked_by_person'] }}
            </th>
            <th style="font-weight: bold; font-size: 16px;"></th>
            <th style="font-weight: bold; font-size: 16px;"></th>
            <th style="font-weight: bold; font-size: 14px;">
                <img src="{{ asset('images/application_image/' . $settings?->payroll_setting['approved_by_signature']) }}" style="height: 40px; margin-left:10px;"><br>
                {{ $settings?->payroll_setting['approved_by_text'] }} <br>
                {{ $settings?->payroll_setting['approved_by_person'] }}
            </th> 
        </tr>--}}
    </table>
             {{--<tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($sales as $sale)

                    @if ($previousDate != $sale->date)

                        @php $previousDate = $sale->date; @endphp

                        <tr>
                            <th colspan="11" style="font-size: 11px!important; font-weight:600;">{{ date($__date_format, strtotime($sale->date)) }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $sale->invoice_id }}</td>
                        <td class="text-start">{{ $sale->do_id }}</td>
                        <td class="text-start">{{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}</td>

                        <td class="text-start">{{ $sale->sr_prefix . ' ' . $sale->sr_name . ' ' . $sale->sr_last_name }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_sold_qty) }}
                            @php
                                $totalQty += $sale->total_sold_qty;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            @if($sale->first_weight)
                                @php

                                    $netWeight = $sale->second_weight - $sale->first_weight;
                                    $totalWeight += $netWeight;
                                @endphp

                                {{ App\Utils\Converter::format_in_bdt($netWeight) }}
                            @endif
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                            @php
                                $TotalNetTotal += $sale->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                            @php
                                $TotalOrderDiscount += $sale->order_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount). '(' . $sale->order_tax_percent . '%)' }}
                            @php
                                $TotalOrderTax += $sale->order_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                            @php
                                $TotalShipmentCharge += $sale->shipment_charge;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                            @php
                                $TotalSaleAmount += $sale->total_payable_amount;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>--}}
        </table>
    </div>
</div>

{{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        {{--<table class="table report-table table-sm table-bordered print_table">
            <thead>

                <tr>
                    <th class="text-end">@lang('menu.total_sold_qty') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_weight') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalWeight) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_amount') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total') @lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalShipmentCharge) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sold_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.average_unit_price') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        @php
                            $averageUnitPrice = $TotalSaleAmount / $totalQty;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($averageUnitPrice) }}
                    </td>
                </tr>
            </thead>
        </table>--}}
    </div>
</div>

<div id="footer">
    <div class="row">
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
