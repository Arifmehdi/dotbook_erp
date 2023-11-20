<style>
    .company_name h3 {
        font-size: 20px;
        line-height: 100%;
        font-weight: 600 !important;
        margin-bottom: 20px;
    }
    .company_name h6 {
        font-size: 14px;
        line-height: 100%;
        font-weight: 300 !important;
        margin-bottom: 15px;
    }
    .company_name p {
        margin-bottom: 10px;
    }
    .table-wrapper {
        text-align: center;
    }
    table {
        border-width: 0 !important;
    }
    th {
        font-weight: 500;
        font-size: 11px;
        line-height: 1.3;
        vertical-align: middle;
        text-align: center !important;
        padding: 15px 3px;
        border-top: 1px solid #8d8d8d;
        border-left: 1px solid #8d8d8d;
        border-bottom: 1px solid #8d8d8d;
    }
    th:last-child {
        border-right: 1px solid #8d8d8d;
    }

    td {
        font-size: 11px;
        line-height: 1.4 ;
        padding: 6px 0 !important;
        text-align: center !important;
        border-left: 1px solid #8d8d8d;
        border-bottom: 1px solid #8d8d8d;
        vertical-align: middle;
    }
    td:last-child {
        border-right: 1px solid #8d8d8d;
    }
    td b {
        font-weight: 600;
    }
    .bold {
        font-weight: 700;
    }

    .vertical {
        transform: rotate(-90deg);
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        padding: 0;
        margin: 0;
        color: black;
    }
</style>
<style type="text/css">
    @media all {
        .page-break {
            display: none;
        }

        .page-break-before {
            display: none;
        }

    }

    @media print {
        .page-break {
            display: block;
            page-break-before: always;
        }

        .page-break-before {
            display: block;
            page-break-before: always;
        }
    }

</style>
<style type="text/css" media="print">
    @media print {
        @page {
            size: A4 landscape;
            max-width: 100%;
            max-height: 100%
        }

        .vertical {
            width: 100%;
            -webkit-transform: rotate() scale(.80, .68);
        }
    }

</style>


<div class="page-break">
    <div class="heading_area">
        <div class="row">
            <div class="col-md-12">
                <div class="company_name text-center">
                    <h3 style="color:black;">{{ json_decode($settings->business)?->shop_name ?? 'Company Name' }} </h3>
                    <h6 style="color:black;">{{ json_decode($settings->business)?->address ?? 'Company Address' }}</h6>
                    <div>
                        <p style="float: left;">
                            <span class="float-right"><b>Jobcard Summary: {{ $month }}, {{ $year }}</b></span>
                        </p>
                        <p style="float: right;">
                            {{ $section_name }} <span style="float: right;"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <table class="w-100">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th style="color: black;" class="text-center">ID</th>
                    <th style="color: black;" class="text-center"  colspan="8">Name<br><i>Joined</i></th>
                    {{-- <th style="color: black;" class="text-center">DOJ</th> --}}
                    @php $day_arr = array(); @endphp
                    @foreach($attendances_dates as $index => $date)
                    <th style="color: black; font-weight:bold;" class="text-center">{{ date('d', strtotime( $date )) }}</th>
                    @php $day_arr[$index] = 0; @endphp
                    @endforeach
                    <th style="color: black;" class="text-center">P/OT<br>WH+LV</th>
                </tr>
            </thead>
            <tbody>

                @foreach($employees as $employee)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $employee->employee_id }}</td>
                    <td colspan="8"><b>{{ $employee->name }}</b><br>Joined: {{ date(config('hrm.date_format'), strtotime($employee->joining_date)) }}</td>

                    @foreach($employee->results as $result_index => $result)
                    <td>
                        {{ $result['status'] }}
                        <br>
                        {{ $result['overtime'] }}
                        @php
                            $arr = explode(':', trim($result['overtime']));
                            $mins = floatval($arr[0]) * 60 + floatval($arr[1] ?? 0);
                            $day_arr[$result_index] += $mins;
                        @endphp
                        {{-- {{ $mins }} *{{ $result_index }} --}}
                    </td>
                    @endforeach
                    <td>
                    {{ $employee->total_present }} / {{ $employee->total_overtime }}
                    <br>
                    {{ $employee->total_weekend }}{{ ($employee->total_leave > 0) ? '+' . $employee->total_leave : '' }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="10"></td>
                    @foreach($day_arr as $row)
                    <td>{{ intval($row/60) . ':'. intval($row%60) }}</td>
                    @endforeach
                    <td>{{ $overtime_sum }}</td>
                </tr>
            </tbody>
            <tfoot>
                {{-- <tr>Total OT = </tr> --}}
            </tfoot>
        </table>
    </div>
</div>
