@extends('layout.master')
@section('title', 'Attendance Rapid Update - ')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style type="text/css">
        tr,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 5px 7px;
        }
    </style>
@endpush

@section('content')
    @php use Carbon\Carbon; @endphp
    <div class="print_area ml-2 mr-2 card-body">
        @php
            $generalSettingsBusiness = json_decode($generalSettings->business);
        @endphp
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12">
                    <div class="company_name text-center">
                        <h2 style="color:black;"><b>{{ $generalSettingsBusiness->shop_name }}</b></h2>
                        <h6 style="color:black;"><b>{{ $generalSettingsBusiness->address }}</b></h6>
                        <h6 class="pt-2" style="color:black; font-weight: bolder;">Attendance Report Of
                            {{ $attendances['months'] }} , {{ $attendances['year'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive" id="data-list">
            <table class="display data_tbl data__table designation-table">
                {{-- <table class="display data_tbl data__table leave_application-table dataTable no-footer table-striped" id="ad_table1"> --}}
                <strong style="color: black; font-size: 12px; margin-left: 8px;">Employee ID:
                    {{ $attendances['employee']['employee_id'] ?? null }}</strong><br>
                <strong style="color: black; font-size: 12px; margin-left: 8px;">Name:
                    {{ $attendances['employee']['name'] ?? null }}</strong><br>
                <strong style="color: black; font-size: 12px; margin-left: 8px;">Designation:
                    {{ $attendances['employee']->designation->name ?? null }}</strong><br>
                <strong style="color: black; font-size: 12px; margin-left: 8px;">Section/Line:
                    {{ $attendances['employee']->section->name ?? null }}</strong><br>
                <strong style="color: black; font-size: 12px; margin-left: 8px;">Joining date:
                    {{ $attendances['employee']['joining_date'] ?? null }}</strong>

                <thead>
                    <tr class="mt-5">
                        <th style="color: black; font-size: 16px;" class="text-left">Date</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Shift</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Clock In</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Clock Out</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Status</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <input type="hidden" name="employee_id" value="{{ $attendances['employee']['id'] }}" id="employee_id">
                    @foreach ($attendances['attendance_dates'] as $key => $atDate)
                        @php
                            $att = DB::connection('hrm')
                                ->table('attendances')
                                ->where('employee_id', $attendances['employee']['id'])
                                ->where('at_date', $atDate)
                                ->first();

                            $holiday = DB::connection('hrm')
                                ->table('attendances')
                                ->where('at_date', $atDate)
                                ->where('status', 'Offday')
                                ->first();

                        @endphp
                        @if (strtotime($attendances['employee']['joining_date']) <= strtotime($atDate))
                            <tr>
                                {{-- dynamic onchange changing value --}}
                                <td style="color: black; font-size: 16px; font-weight: bold;" class="text-left">
                                    {{ $atDate ?? '' }} </td>

                                <td style="color: black; font-size: 16px; font-weight: bold;" class="text-left">
                                    @if ($att)
                                        @if ($att->shift == null or $att->status == 'Leave')
                                        @else
                                            <select class="shift_change form-select py-0" name="shift"
                                                id="{{ $att->id }}" style="background-color: #DAF7A6;">
                                                @foreach ($attendances['fallback_shifts'] as $row)
                                                    <option @if ($row->name == $att->shift) selected @endif>
                                                        {{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @else
                                        <select class="missing_shift_change form-select py-0" name="shift"
                                            id="{{ $atDate }}" style="background-color: #DAF7A6;">
                                            <option disabled="" selected>shift</option>
                                            @foreach ($attendances['fallback_shifts'] as $row)
                                                <option value="{{ $row->name }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>

                                <td style="color: black; font-size: 16px; font-weight: bold;" class="text-left">
                                    @if ($att)
                                        @if ($att->clock_in == null)
                                            <input type="time" class="clock_in" name="clock_in" value=""
                                                id="{{ $att->id }}" style="background-color:red;">
                                        @else
                                            @if ($att->status == 'Present' || $att->status == 'Late')
                                                <input type="time" class="clock_in" name="clock_in"
                                                    value="{{ date('H:i', strtotime($att->clock_in)) }}"
                                                    id="{{ $att->id }}" style="background-color:#DAF7A6;">
                                                <small> <a href="#" id="{{ $att->id }}" class="empty_clockin">
                                                        Empty </a> </small>
                                            @else
                                                .....
                                            @endif
                                        @endif
                                    @else
                                        .....
                                    @endif
                                </td>

                                <td style="color: black; font-size: 16px; font-weight: bold;" class="text-left">
                                    @if ($att)
                                        @if ($att->clock_out == null)
                                            <input type="datetime-local" class="clock_out" name="clock_out" value=""
                                                id="{{ $att->id }}" style="background-color:red;">
                                        @else
                                            @if ($att->status == 'Present' || $att->status == 'Late' || $att->status == null)
                                                @php
                                                    $makingDate = date('Y-m-d', strtotime($att->clock_out_ts)) . ' ' . $att->clock_out;
                                                @endphp

                                                <input type="datetime-local" class="clock_out_ts" name="clock_out_ts"
                                                    value="{{ date('Y-m-d\TH:i', strtotime($makingDate)) }}"
                                                    style="background-color:#DAF7A6;" id="{{ $att->id }}">

                                                <small> <a href="#" id="{{ $att->id }}"
                                                        class="empty_clockout">Empty</a> </small>
                                            @else
                                                .....
                                            @endif
                                        @endif
                                    @else
                                        .....
                                    @endif
                                </td>

                                <td @if (!isset($att->status)) style="background-color: #CCC;" @endif>
                                    <span id="{{ date('d-m-Y', strtotime($atDate)) }}-status">
                                        {{ isset($att) ? $att->status : null }}
                                    </span>
                                </td>
                                <td>
                                    @if ($att)
                                        <a href="#" id="{{ $att->id }}"
                                            class="btn btn-sm btn-danger ml-2 delete_adjustment">Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endsection

    @push('js')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script type="text/javascript">
            //shift change
            $(document).on('change', '.shift_change', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var shift = $(this).val();
                var url =
                    "{{ route('hrm.attendance.employeeShiftChange', [
                        'id' => ':a',
                        'shift' => ':b',
                    ]) }}";
                url = url.replace(':a', id);
                url = url.replace(':b', shift);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);
                        location.reload();
                    },
                    error: function(error) {
                        toastr.error(error);
                    }
                });
            });

            //clock in update
            $(document).on('blur', '.clock_in', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var clock_in = $(this).val();
                if (id == null || clock_in == null) {
                    toastr.error("Insert clock in data first.");
                    return;
                }
                var url =
                    "{{ route('hrm.clock_in_adjustment', [
                        'id' => ':a',
                        'clock_in' => ':b',
                    ]) }}";
                url = url.replace(':a', id);
                url = url.replace(':b', clock_in);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data.message);
                        var resId = '#' + data.date + '-status';
                        $(resId).text(data.status);
                    }
                });
            });

            //clock in empty
            $(document).on('click', '.empty_clockin', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var url = "{{ route('hrm.clock_in_empty', [
                    'id' => ':a',
                ]) }}";
                url = url.replace(':a', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);
                        location.reload();
                    }
                });
            });

            //clockout
            $(document).on('blur', '.clock_out', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var clock_out = $(this).val();
                var url =
                    "{{ route('hrm.clock_out_adjustment', [
                        'id' => ':a',
                        'clock_out' => ':b',
                    ]) }}";
                url = url.replace(':a', id);
                url = url.replace(':b', clock_out);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data.message);
                        // location.reload(true);
                    }
                });
            });
            //clockout ts
            $(document).on('blur', '.clock_out_ts', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var clock_out_ts = $(this).val();
                var url =
                    "{{ route('hrm.clock_out_ts_adjustment', [
                        'id' => ':a',
                        'clock_out_ts' => ':b',
                    ]) }}";
                url = url.replace(':a', id);
                url = url.replace(':b', clock_out_ts);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data.message);
                        var resId = '#' + data.date + '-status';
                        $(resId).text(data.status);
                        // location.reload(true);
                    }
                });
            });

            //clock out empty
            $(document).on('click', '.empty_clockout', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var url = "{{ route('hrm.clock_out_empty', [
                    'id' => ':a',
                ]) }}";
                url = url.replace(':a', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);
                        location.reload();
                    }
                });
            });
            //misiing shift change
            $(document).on('change', '.missing_shift_change', function(e) {
                e.preventDefault();
                var id = $("#employee_id").val();
                var at_date = $(this).attr('id');
                var shift = $(this).val();
                var url =
                    "{{ route('hrm.missing_attendance_shift_change', [
                        'id' => ':a',
                        'at_date' => ':b',
                        'shift' => ':c',
                    ]) }}";
                url = url.replace(':a', id);
                url = url.replace(':b', at_date);
                url = url.replace(':c', shift);

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);
                        location.reload(true);
                    }
                });
            });

            //delete adjustment
            $(document).on('click', '.delete_adjustment', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var url = "{{ route('hrm.adjustment_att_delete', [
                    'id' => ':a',
                ]) }}";
                url = url.replace(':a', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);
                        location.reload();
                    }
                });
            });
        </script>
    @endpush
