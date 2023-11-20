@php
use Carbon\Carbon;
@endphp

<style type="text/css" media="print">
    @media print {
        @page {
            size: A4 landscape;
            max-width: 100%;
            max-height: 100%;
        }
    }

</style>

<style>
    /* Reset default styles */
    body,
    table {
        margin: 0;
        padding: 0;
    }

    /* General styles */
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .vertical {
        display: block;
        transform: rotate(-90deg);
        padding: 5px 0;
        text-align: center;
        line-height: 1.3;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 15px;
        border: 1px solid black;
        font-size: 12px;
        line-height: 1.8;
        vertical-align: middle;
        text-align: center;
    }

    th {
        vertical-align: middle;
    }
    table th {
        color: black !important;
    }

    /* Header section */
    .heading_area {
        margin-bottom: 20px;
    }

    .company_name h3,
    .company_name h6 {
        margin: 0;
    }

    /* Footer section */
    .footer_area {
        margin-top: 20px;
    }

    .footer_text {
        float: left;
    }

    .prepared_by_signature {
        float: right;
        height: 40px;
        margin-left: 10px;
    }

    /* Other styles */
    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }

    .id_name_column {
        white-space: nowrap;
    }
</style>

<div>
    <div class="verticals">
        <div class="print_area">
            <div class="heading_area">
                <div class="employee">
                    <div class="col-md-12">
                        <div class="company_name text-center">
                            <h3><b>{{ json_decode($settings->bussiness)?->shop_name ?? 'Company Name' }}</b></h3>
                            <h6><b>Salary Sheet for the Month of {{ $month_name }} - {{ $year }}</b></h6>
                        </div>
                    </div>
                </div>
                <span style="float:left;">@isset($division_name){{ $division_name }} @else All Employees @endisset</span>
                <span style="float:right;">Date: {{ date('d-m-Y', strtotime($printDate)) }}</span>
            </div>
            <table class="table-border11">
                <thead>
                    <tr>
                        <th class="text-black" style="font-size: 12px;">SI</th>
                        <th class="" style="font-size: 12px;">ID. Name</th>
                        <th style="font-size: 12px;"><span class="vertical">Joining Date</span></th>
                        <th style="font-size: 12px;">Designation. Grade</th>
                        <th style="font-size: 12px;"><span class="vertical">Gross Salary</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Basic</span></th>
                        <th style="font-size: 12px;"><span class="vertical">House Rent</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Medical</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Food</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Transport</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Month's Day</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Working Day</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Present</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Absent</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Leave</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Offday</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Attendance Bonus</span></th>


                        {{-- BUYER MODE --}}

                        @if(! $isBuyerMode)
                        <th style="font-size: 12px;"><span class="vertical">Tiffin(d)</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Tiffin Bill</span></th>
                        <th style="font-size: 12px;"><span class="vertical">Night(d) </span></th>
                        <th style="font-size: 12px;"><span class="vertical">Night Bill</span></th>
                        @endif


                        <th class="text-center" style="font-size: 12px;" colspan="3">Overtime</th>
                        <th style="font-size: 12px;"><span class="vertical">Other Earn</span></th>
                        <th style="font-size:12px;"><span class="vertical">Gross. pay</span></th>
                        <th class="text-center" style="font-size: 12px;" colspan="3">Deduction</th>
                        <th class="" style="font-size: 12px;">Total Deduc.</th>
                        <th class="" style="font-size: 12px;">Net Payable salary</th>
                        <th class="text-center" style="font-size: 12px;">Signature</th>
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
        </div>

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
        </table>
    </div> <br>
    @php
    $hrm = array_reduce($hrm_settings, function($key, $value){ return $value; });
    @endphp
    <table class="table mt-2">
        <tr>{{ json_decode($settings->bussiness)?->shop_name ?? 'Company Name' }}
            {{-- <th style="font-weight: bold; font-size: 14px; float: left;">
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
            </th> --}}
        </tr>
    </table>

</div>
