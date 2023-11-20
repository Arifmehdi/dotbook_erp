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
                            {{ $attendances['date'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <table class="display data_tbl data__table department-table dataTable no-footer table-striped pt-5"
                id="ad_table1">
                <thead>
                    <tr>
                        <th style="color: black; font-size: 16px;" class="text-left">Employee</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Date</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Shift</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Clock In</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Clock Out</th>
                        <th style="color: black; font-size: 16px;" class="text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances['attendances'] as $key => $row)
                        <tr>
                            {{-- dynamic onchange changing value --}}
                            <td style="color: black; font-size: 16px; font-weight: bold;" class="text-center">
                                {{ $row->employeeId }} - {{ $row->name }} </td>
                            <td style="color: black; font-size: 16px; font-weight: bold;" class="text-center">
                                {{ $row->at_date->format(config('hrm.date_format')) }}
                            </td>

                            <td style="color: black; font-size: 16px; font-weight: bold;" class="text-center">
                                @if ($row->shift == null or $row->status == 'Leave')
                                @else
                                    <select class="shift_change form-select" name="shift" id="{{ $row->id }}"
                                        style="background-color: #DAF7A6;">
                                        @foreach ($attendances['fallback_shift'] as $shft)
                                            <option @if ($shft->name == $row->shift) selected @endif>{{ $shft->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>

                            <td style="color: black; font-size: 16px; font-weight: bold;" class="text-center">
                                @if ($row->clock_in == null)
                                    <input type="time" class="clock_in" name="clock_in" value=""
                                        id="{{ $row->id }}" style="background-color:red;">
                                @else
                                    @if ($row->status == 'Present' || $row->status == 'Late')
                                        <input type="time" class="clock_in" name="clock_in"
                                            value="{{ date('H:i', strtotime($row->clock_in)) }}" id="{{ $row->id }}"
                                            style="background-color:#DAF7A6;">
                                        <small> <a href="#" id="{{ $row->id }}" class="empty_clockin"> Empty
                                            </a> </small>
                                    @else
                                    @endif
                                @endif
                            </td>


                            <td style="color: black; font-size: 16px; font-weight: bold;" class="text-center">
                                @if ($row->clock_out == null)
                                    <input type="time" class="clock_out" name="clock_out" value=""
                                        id="{{ $row->id }}" style="background-color:red;">
                                @else
                                    @if ($row->status == 'Present' || $row->status == 'Late')
                                        <input type="time" class="clock_out" name="clock_out"
                                            value="{{ date('H:i', strtotime($row->clock_out)) }}" id="{{ $row->id }}"
                                            style="background-color:#DAF7A6;">
                                        <small> <a href="#" id="{{ $row->id }}" class="empty_clockout">Empty</a>
                                        </small>
                                    @else
                                        .....
                                    @endif
                                @endif

                                @if ($row->clock_out == null)
                                    <input type="datetime-local" class="clock_out" name="clock_out" value=""
                                        id="{{ $row->id }}" style="background-color:red;">
                                @else
                                    @if ($row->status == 'Present' || $row->status == 'Late')
                                        @php
                                            $makingDate = date('Y-m-d', strtotime($row->clock_out_ts)) . ' ' . $row->clock_out;
                                        @endphp

                                        <input type="datetime-local" class="clock_out_ts" name="clock_out_ts"
                                            value="{{ date('Y-m-d\TH:i', strtotime($makingDate)) }}"
                                            style="background-color:#DAF7A6;" id="{{ $row->id }}">

                                        <small> <a href="#" id="{{ $row->id }}" class="empty_clockout">Empty</a>
                                        </small>
                                    @else
                                        .....
                                    @endif
                                @endif

                            </td>

                            <td>
                                <a href="#" id="{{ $row->id }}"
                                    class="btn btn-sm btn-danger ml-2 delete_adjustment">Delete</a>
                            </td>
                        </tr>
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
