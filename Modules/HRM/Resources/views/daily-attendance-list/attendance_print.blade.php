@php

use Carbon\Carbon;

@endphp
<style type="text/css">
    .dtable tr {
        border: 1px solid black;
    }

    .dtable td {
        border: 1px solid black;
        margin: 0;
        padding: 0;
    }
</style>
<div class="print_area">
    <div class="heading_area">
        <div class="row">
            @php
            $generalSettings = json_decode($generalSettings->business);
            @endphp
            <div class="col-md-12">
                <div class="company_name text-center">
                    <h2 style="color:black;"><b>{{ $generalSettings->shop_name}}</b></h2>
                    <h5 style="color:black;"><b>{{ $generalSettings->address }}</b></h5>
                    <h6 style="color:black; font-weight: bolder;">All Attendance Of {{ Carbon::now()->format('F j, Y')}}</h6>
                </div>
            </div>
        </div>
    </div>
    <table class="w-100 table-border dtable" border="1px" style="border-collapse: collapse;">
        <thead>
            <tr>
                <td style="color: black; font-weight: bold; font-size: 17px;">Emp. ID</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Emp. Name</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Joining Date</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Shift</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Section</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Designation</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Clock In</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Clock Out</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Status</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">Late </td>
                <td style="color: black; font-weight: bold; font-size: 17px;">E.Exit</td>
                <td style="color: black; font-weight: bold; font-size: 17px;">O.T</td>
            </tr>
        </thead>
        <tbody>
            @php
            $totalemployee = 0;
            @endphp

            @foreach ($attendances as $key=>$row)
                @php
                    $shift_details = DB::connection('hrm')
                        ->table('shift_adjustments')
                        ->where('shift_id', $row->shift_id)
                        ->where('applied_date_from', '<=', date('Y-m-d', strtotime($row->at_date)))
                        ->where('applied_date_to', '>=', date('Y-m-d', strtotime($row->at_date)))
                        ->first();
                @endphp

                <tr class="border">
                    <td style="color: black; font-size: 11px; font-weight: bold;">{{ $row->employee_id }}</td>
                    <td style="color: black; font-size: 11px; font-weight: bold;">{{ $row->name}}</td>
                    <td style="color: black; font-size: 11px; font-weight: bold;">{{ $row->joining_date }}</td>
                    <td style="color: black; font-size: 11px; font-weight: bold;" class="text-center">{{ $row->shift }}</td>
                    <td style="color: black; font-size: 11px; font-weight: bold; border: 1px solid black;">{{ $row->division_name }}</td>
                    <td style="color: black; font-size: 11px; font-weight: bold; border: 1px solid black;">{{ $row->position_name }}</td>
                    <td style="color: black; font-size: 11px; font-weight: bold;" class="text-center">@if($row->clock_in !=NULL) {{ date('h:ia',strtotime($row->clock_in)) }} @else ..... @endif</td>
                    <td style="color: black; font-size: 11px; font-weight: bold;" class="text-center">@if($row->clock_out !=NULL) {{ date('h:ia',strtotime($row->clock_out)) }} @else ..... @endif</td>
                    <td style="color: black; font-size: 11px; font-weight: bold;" class="text-center">
                        @if ($row->status)
                        @if ($row->status == 'Present')
                        Present
                        @elseif($row->status == 'Late')
                        Late
                        @elseif($row->status=='Leave')
                        Leave
                        @endif
                        @else
                        @endif
                    </td>
                    <td style="color: black; font-size: 11px; font-weight: bold;">
                        @if ($row->status == 'Late')
                        @php
                        $officeStartTime = Carbon::parse($shift_details->start_time);
                        $endTime = Carbon::parse($row->clock_in);
                        $late = $endTime->diff($officeStartTime)->format("%H:%I");
                        @endphp
                        {{ $late }}
                        @else
                        00:00
                        @endif
                    </td>


                    <td style="color: black; font-size: 11px; font-weight: bold;" class="text-center">
                        @if ($row->clock_out_ts)
                        @php
                        $startTime = Carbon::parse($row->clock_in);
                        $endTime = Carbon::parse($row->clock_out);
                        //get shift
                        //$shift_details=DB::connection('hrm')->table('shifts')->where('name',$row->shift)->first();
                        //early exit time get
                        $early_exit = $endTime->diff($shift_details->end_time)->format("%H:%I");
                        @endphp
                        @if(strtotime($row->clock_out)<strtotime($shift_details->end_time))
                            {{ $early_exit }}
                            @else
                            00:00
                            @endif
                            @else
                            00:00
                            @endif
                    </td>

                    <td style="color: black; font-size: 11px; font-weight: bold;">
                        @if ($row->clock_out_ts)
                        @php
                        $officeStartTime = Carbon::parse($shift_details->start_time);
                        $officeEndTime = Carbon::parse($shift_details->end_time);

                        $startTime = Carbon::parse($row->clock_in);
                        $endTime = Carbon::parse($row->clock_out);
                        $mainOfficeTime = $officeEndTime->diff($officeStartTime)->format("%H:%I");
                        $totalDuration = $endTime->diff($startTime)->format("%H:%I");

                        $_mainOfficeTime = Carbon::createFromFormat("H:i",$mainOfficeTime);
                        $_totalDuration = Carbon::createFromFormat("H:i",$totalDuration);

                        // If main duration time > office time then show over-time
                        $__mainOfficeTime = $officeEndTime->diffInSeconds($officeStartTime);
                        $__totalDuration = $endTime->diffInSeconds($startTime);

                        if($__totalDuration > $__mainOfficeTime) {
                        $overTime = $_totalDuration->diff($_mainOfficeTime)->format("%H:%I");
                        } else {
                        $overTime = '00:00';
                        }
                        @endphp
                        {{ $overTime }}
                        @else
                        00:00
                        @endif
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table><br>

    <br><br>
    <table class="table">
        <tr>
            <td style="font-weight: bold; font-size: 16px; float: left; color:black;">PREPARED BY ............</td>
            <td style="font-weight: bold; font-size: 16px;"></td>
            <td style="font-weight: bold; font-size: 16px;"></td>
            <td style="font-weight: bold; font-size: 16px;"></td>
            <td style="font-weight: bold; font-size: 16px;">CHECKED BY ............</td>
        </tr>
    </table>
</div>
