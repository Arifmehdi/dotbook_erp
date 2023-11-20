@if(config('app.is_buyer_mode'))

<div class="print_area" style="margin-top: -2px; margin-bottom: -15px">
    <div class="heading_area">
        <div class="row">
            <div class="col-md-12">
                <div class="company_name text-center">
                    <h2 style="font-size: 16px; line-height: 100%; font-weight: 600; color:black; margin-bottom: 15px">{{ json_decode($settings->business)?->shop_name ?? 'Company Name' }}</h2>
                    <h4 style="font-size: 14px; font-weight: 600; color:black; margin-bottom: 8px">{{ json_decode($settings->business)?->address ?? 'Company Address' }}</h4>
                    <h3 style="font-size: 15px; font-weight: 500; color:black; font-weight: bolder; margin-bottom: 2px">Job Card Report : {{ $month }}, {{ $year }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div style="border:1px solid #414141;">
        <table class="w-100 table-border" border="1px" style="border-left:0; border-right:0">
            <span style="color: black; font-size: 11px; margin-top: 5px; line-height: 100%; display: block; margin-left:  8px;">
                <strong>Employee ID:</strong>
                {{ $employee->employee_id }}
            </span>

            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Name:</strong> {{ $employee->employee_name }}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Designation:</strong> {{ $employee?->designation_name }}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Section:</strong> {{ $employee?->section_name}}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Joining Date:</strong> {{ date(config('hrm.date_format'), strtotime($employee->joining_date)) }}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px; mmargin-bottom:3px !important"><strong>Status:</strong> {{ $employee_type }} {{ $employee_type_date }}</span>

            {{-- <h3 style="color: black; font-size: 14px; font-weight: 600; float: right; margin-top: -15px; margin-right: 8px;">Job Card Report</h3> --}}
            <thead>
                <tr>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Date 2</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Shift</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">In Time</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Out Time</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Late</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">E.Exit</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">O.T</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Status</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5;" class="text-center">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                <tr>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ date(config('hrm.date_format'), strtotime($result['date'])) }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['shift'] }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['clock_in'] }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['clock_out'] }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['late'] == "00:00" ? '...' : $result['late'] }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['early_exit'] == "00:00" ? '...' : $result['early_exit'] }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">
                        {{  $result['overtime'] == "00:00" ? '...' : $result['overtime'] }}
                    </td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['status'] }}</td>
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['break_remark'] ?? '' }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <strong style="color: black; font-size: 11px;  margin-top: 15px;line-height: 100%; display: block;  margin-left: 8px;"> Present : {{  $total_present ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Leave : {{ $total_leave ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Absent : {{ $total_absent ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Late : {{ $total_late }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Off Days (W&H) : {{ $total_weekend ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px; margin-bottom: 5px">Total O.T: {{ $employee->overtime_allowed ? $total_overtime : 'N/A' }}</strong>
    </div>
    <br>
    <table class="table">
        <tr>
            <th style="font-weight: bold; font-size: 13px; text-align:center !important; border:0; color:black;"> Employee Signature </th>
            <th></th>
            <th style="font-weight: bold; font-size: 13px; text-align:center !important; border:0; color:black;">Authorized Signature </th>
        </tr>
    </table>
</div>

@else


<div class="print_area" style="margin-top: -2px">
    <div class="heading_area">
        <div class="row">
            <div class="col-md-12">
                <div class="company_name text-center">
                    <h2 style="font-size: 16px; line-height: 100%; font-weight: 600; color:black; margin-bottom: 15px">
                        {{ json_decode($settings->business)?->shop_name ?? 'Company Name' }}
                    </h2>
                    <h4 style="font-size: 14px; font-weight: 600; color:black; margin-bottom: 8px">
                        {{ json_decode($settings->business)?->address ?? 'Company Address' }}
                    </h4>
                    <h3 style="font-size: 15px; font-weight: 500; color:black; font-weight: bolder; margin-bottom: 2px">
                        Job Card Report: {{ $month }}, {{ $year }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div style="border:1px solid #414141;">
        <table class="w-100 table-border" border="1px" style="border-left:0; border-right:0">
            <span style="color: black; font-size: 11px; margin-top: 5px; line-height: 100%; display: block; margin-left:  8px;">
                <strong>Employee ID:</strong>
                {{ $employee->employee_id }}
            </span>

            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Name:</strong> {{ $employee->employee_name }}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Designation:</strong> {{ $employee?->designation_name }}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Section:</strong> {{ $employee?->section_name}}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"><strong>Joining Date:</strong> {{ date(config('hrm.date_format'), strtotime($employee->joining_date)) }}</span>
            <span style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px; margin-bottom:7px !important"><strong>Status:</strong> {{ $employee_type }} {{ $employee_type_date }}</span>

            {{-- <h3 style="color: black; font-size: 14px; font-weight: 600; float: right; margin-top: -15px; margin-right: 8px;">Job Card Report</h3> --}}
            <thead>
                <tr>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Date</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Shift</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">In Time</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Out Time</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Late</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">E.Exit</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">O.T</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5; border-right: 1px solid #414141" class="text-center">Status</th>
                    <th style="color: black; font-size: 12px !important; line-height: 1.5;" class="text-center">Remarks</th>
                </tr>
            </thead>

        <tbody>
            @foreach($results as $result)
            <tr>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ date(config('hrm.date_format'), strtotime($result['date'])) }}</td>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['shift'] }}</td>
                {{-- <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">
                    {{ date(config('hrm.time_format'),strtotime($result['clock_in'])) }}</td> --}}
                    <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">
                        {{ $result['clock_in'] }}</td>
                {{-- <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ date(config('hrm.time_format'),strtotime($result['clock_out'])) }}</td> --}}
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['clock_out'] }}</td>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['late'] == "00:00" ? '...' : $result['late'] }}</td>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['early_exit'] == "00:00" ? '...' : $result['early_exit'] }}</td>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">
                    {{  $result['overtime'] == "00:00" ? '...' : $result['overtime'] }}
                </td>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; border-right: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['status'] }}</td>
                <td style="color: black; font-size: 12px !important; font-weight:400 !important; line-height:1.5; border-top: 1px solid #414141; font-weight: bold;" class="text-center">{{ $result['break_remark'] ?? '' }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
        <strong style="color: black; font-size: 11px;  margin-top: 15px;line-height: 100%; display: block;  margin-left: 8px;"> Present : {{  $total_present ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Leave : {{ $total_leave ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Absent : {{ $total_absent ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Late : {{ $total_late }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px;"> Off Days (W&H) : {{ $total_weekend ?? '-' }} Day(s) </strong>
        <strong style="color: black; font-size: 11px;  margin-top: 5px;line-height: 100%; display: block;  margin-left: 8px; margin-bottom: 5px">Total O.T: {{ $employee->overtime_allowed ? $total_overtime : 'N/A' }}</strong>
    </div>
    <br>
    <table class="table">
        <tr>
            <th style="font-weight: bold; font-size: 13px; text-align:center !important; border:0; color:black;"> Employee Signature </th>
            <th></th>
            <th style="font-weight: bold; font-size: 13px; text-align:center !important; border:0; color:black;">Authorized Signature </th>
        </tr>
    </table>
</div>
@endif
