<style>
    /* Global CSS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        line-height: 16px !important;
    }

    body {
        font-family: solaimanlipi, sans-serif;
        line-height: 10px !important;
    }

    .small-text {
        font-size: 10px;
    }

    .main {
        box-sizing: border-box;
        width: 100%;
        display: inline-block;
        /* padding: 20px 30px; */
    }

    .wrapper {
        width: 100%;
    }

    .b-font {
        font-size: 10px;
    }

    table,
    th,
    td,
    tr {
        border-collapse: collapse;
    }

    td {
        margin: 0;
        padding: 0;
        vertical-align: top;
    }

    .no-wrap {
        white-space: nowrap;
    }

    .my-1 {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .my-2 {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .my-3 {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .mt-0 {
        margin-top: 0;
    }

    .mr-1 {
        margin-right: 10px;
    }

    .pt-0 {
        padding-top: 0;
    }

    .pr-1 {
        padding-right: 10px;
    }

    .text-center {
        text-align: center;
    }

    .d-flex {
        display: flex;
    }

    .d-block {
        display: block;
        width: 100%;
    }

    .t1 {
        -moz-tab-size: 4;
        tab-size: 4;
    }

    span.tab {
        display: inline-block;
        width: 34px;
    }

    span.relative {
        position: relative;
    }

    span.absolute {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
    }

    /* Header style */
    #header {
        width: 100%;
    }

    .header_wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo img {
        width: 100px;
        margin-top: 5px;
    }

    .company_title {
        /* width: 200px; */
    }

    .text-bold-large {
        font-weight: bolder;
        font-size: 1em;
    }

    h1.company_name {
        white-space: nowrap;
        text-transform: capitalize;
        color: #000;
        display: inline-block;
        font-weight: 600;
        font-size: 18px;
        transform: scale(.9, 1.5);
        margin-top: 5px;
        /* margin-left: -20px;*/
    }

    p.company_desc {
        /* text-align: center; */
        font-size: 10px;
        color: #000;
        /* margin-top: 15px; */
        /* background: #d57706; */
        /* margin-left: -10px; */
    }

    p {
        font-size: 10px;
        color: #000;
    }

    .border_right {
        width: 4px;
        margin: 0 5px;
        background: #737373;
    }

    /* Line One */
    .line_one table tbody,
    .present_address table tbody,
    .permanent_address table tbody,
    .letter_sign table tbody {
        display: block;
        width: 100%;
    }

    .line_one table td {
        font-size: 10px;
        width: 33%;
        display: flex;
        float: left;
    }

    .present_address td,
    .permanent_address td {
        font-size: 10px;
    }

    table.salary td {
        border: 1px solid;
        text-align: center;
        padding: 3px 1px;
    }

    @media print {
        .main {
            margin: 0;
        }

        .page-break {
            page-break-after: auto;
        }

        .page-break-before {
            page-break-before: always;
        }
    }

    .flex-center {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .f-left {
        float: left;
    }

    .f-right{
        float: right;
    }

    .monospace {
        /* font-family: monospace; */
    }

    .small-text {
        font-size: 7px !important;
    }

    .col-6 {
        width: 50%;
        float: left;
    }
    .col-7 {
        width: 60%;
        float: left;
    }
    .col-5 {
        width: 40%;
        float: left;
    }

    .row {
        width: 100%;
        float: left;
    }

    .mt-20 {
        margin-top: 20px;
    }
    table,
    tr,
    th,
    td {
        border: 1px solid;
        font-size: 12px;
        padding: 3px 10px;
    }
    tr td{
        padding: 3px 10px;
        text-align: center;
    }
    .employee {
        margin-bottom: 10px;
    }
    .fw {
        font-weight: bold;
    }
</style>
@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
@endphp
<div class="main page-break" style="margin-bottom: 30px;">
    <div class="wrapper">
        @include('layout.mpdf.header')
        <h4 class="text-center">{{ __('Salary Statement of') }} {{ $employee->name }}</h4>

        <div class="employee">
            <p><strong class="fw">{{ __('Employee ID') }} </strong>&nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>{{ $employee->employee_id }}</strong></p>
            <p><strong class="fw"> {{ __('Name') }} </strong>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>{{ $employee->name }}</strong> </p>
            <p><strong class="fw"> {{ __('Designation') }} </strong>&nbsp;&nbsp;&nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->designation->name }}</p>
            @if(isset($employee->section->name))
            <p><strong class="fw"> {{ __('Section') }} </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->section->name }}</p>
            @endif
            <p><strong class="fw">{{ __('Joining Date') }}</strong> &nbsp;&nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ date('d-m-Y', strtotime($employee->joining_date)) }}</p>
        </div>
        <table class="table table-bordered table-striped table-sm increments_table b1">
            <tbody>
                <tr>
                    <th>{{ __('Settlement Date') }}</th>
                    <th>{{ __('Previous Basic') }}</th>
                    <th>{{ __('Previous Gross') }}</th>
                    <th>{{ __('Change Type') }}</th>
                    <th>{{ __('Change Amount') }}</th>
                    <th>{{ __('Amount Type') }}</th>
                    <th>{{ __('Inc/Dec Amount') }}</th>
                    <th>{{ __('Current Basic') }}</th>
                    <th>{{ __('Extra') }}</th>
                    <th>{{ __('Current Gross') }}</th>
                    <th>{{ __('Remarks') }}</th>
                </tr>
                @foreach($statements as $item)
                <tr>
                    <td>{{ $item->created_at->format($dateFormat) }}</td>
                    {{-- <td>{{ $item->created_at->format('d-m-Y') }}</td> --}}
                    <td>
                        @if($item->salary_type === 1 && $item->amount_type === 1 && $item->amount_type != 3)
                            {{ $item->previous }}
                        @endif

                        @if($item->salary_type === 2 && $item->amount_type === 1 && $item->amount_type != 3)
                            {{ $item->previous }}
                        @endif

                        @if($item->salary_type === 1 && $item->amount_type === 2 && $item->amount_type != 3)
                            {{ $item->previous }}
                        @endif

                        @if($item->salary_type === 2 && $item->amount_type === 2 && $item->amount_type != 3)
                            {{ $item->previous }}
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type === 1 && $item->amount_type === 3 && $item->amount_type != 1 && $item->amount_type != 2 )
                            {{ $item->previous }}
                        @endif
                        @if($item->salary_type === 2 && $item->amount_type === 3 && $item->amount_type != 1 && $item->amount_type != 2 )
                            {{ $item->previous }}
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type == 1 )
                            <span class='text-success fw-bold'>Increment</span>
                        @endif
                        @if($item->salary_type == 2 )
                            <span class='text-danger fw-bold'>Decrement</span>
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type == 1 )
                            @if($item->amount_type == 1 )
                                {{  $item->how_much_amount  }}
                            @else
                                {!! $item->how_much_amount !!}
                            @endif
                        @endif

                        @if($item->salary_type == 2 )
                            @if($item->amount_type == 1 )
                                {{ $item->how_much_amount }}
                            @else
                                {!! $item->how_much_amount !!}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type == 1 )
                            @if($item->amount_type == 1 )
                                {!! 'Fixed' !!}
                            @else
                                {!! 'Percent' !!}
                            @endif
                        @endif

                        @if($item->salary_type == 2 )
                            @if($item->amount_type == 1 )
                                {!! 'Fixed' !!}
                            @else
                                {!! 'Percent' !!}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type === 1 && $item->amount_type === 1 && $item->amount_type != 3 && $item->amount_type != 2)
                            {{ round($item->how_much_amount) }}
                        @endif
                        @if($item->salary_type === 1 && $item->amount_type === 2 && $item->amount_type != 3 && $item->amount_type != 1)
                            {{ round($item->how_much_amount) }}
                        @endif
                        @if($item->salary_type === 2 && $item->amount_type === 1 && $item->amount_type != 3 && $item->amount_type != 2)
                            {!! '(' . round($item->how_much_amount) . ')' !!}
                        @endif
                        @if($item->salary_type === 2 && $item->amount_type === 2 && $item->amount_type != 3 && $item->amount_type != 1)
                            {!! '(' . $item->previous -  $item->after_updated . ')' !!}
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type != 1 && $item->amount_type != 3)
                            @php
                                echo round($item->after_updated);
                            @endphp
                        @endif
                        @if($item->salary_type != 2 && $item->amount_type != 3)
                            @php
                                echo round($item->after_updated);
                            @endphp
                        @endif
                    </td>
                    <td>
                        @if($item->salary_type != 1 && $item->amount_type != 3)
                            @php
                                echo $employee->beneficialSalary;
                            @endphp
                        @elseif ($item->salary_type != 2 && $item->amount_type != 3)
                            @php
                                echo $employee->beneficialSalary;
                            @endphp
                        @endif
                    </td>
                    <td>{{ round($item->after_updated + $employee->beneficialSalary) . '.00' }} </td>
                    <td>{{ $item->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    <div class="row mt-20">
        <div class="col-6">
            <h6><strong>@lang('menu.checked_by')</strong></h6>
        </div>
        <div style="float: right; margin-left: 250px">
            <h6><strong>@lang('menu.approved_by')</strong></h6>
        </div>
    </div>
