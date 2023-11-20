@include('layout.mpdf.header')
<style>
    table {
        width: 100%;
    }

    .text-center {
        text-align: center;
    }

    table,
    tr,
    th,
    td {
        border: 1px solid;
        font-size: 12px;
    }

    .employee p {
        font-size: 12px;
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

    tr td:nth-child(3) {
        /* background-color: rgb(243, 167, 167); */
        padding: 2px 10px;
        width: 80px;
        text-align: right;
    }

    tr th:nth-child(3) {
        /* background-color: rgb(243, 167, 167); */
        padding: 2px 10px;
        width: 80px;
        text-align: right;
    }

    tr td:nth-child(2) {
        padding: 2px 10px;
    }

    tr td:nth-child(1) {
        padding: 0;
        margin: 0;
        width: 120px;
        padding: 2px 10px;
    }

</style>

<h4 class="text-center">Leave Statement of {{ $year }}</h4>
<div class="employee">
    <p>Employee ID &nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->employee_id }}</p>
    <p>Name &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->employee_nme }}</p>
    <p>Designation &nbsp;&nbsp;&nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->designation_name }}</p>
    <p>Joining Date &nbsp;&nbsp; :-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ date('Y-m-d', strtotime($employee->joining_date)) }}</p>
</div>
<table class="b1">
    <thead>
        <tr>
            <th>Leave Type</th>
            <th>Description</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        {{-- CL --}}
        <tr class="b1">
            <td rowspan="{{ count($leaves['cl'])+1 }}">CL</td>
            <td>
                <span style="float: left;">Current Year CL Leave Opening</span>
            </td>
            <td> {{ $opening_balance['total_cl'] }} </td>
        </tr>

        @php
        $balance_cl = $opening_balance['total_cl'];
        @endphp
        @foreach($leaves['cl'] as $leave)
        <tr>

            @php
            $balance_cl = $balance_cl - \App\Utils\DateTimeUtils::diffInDaysInclusive($leave->approve_start_date, $leave->approve_end_date);
            @endphp

            <td>
                {{ date('d-m-Y', strtotime($leave->approve_start_date)) }}, To: {{ date('d-m-Y', strtotime($leave->approve_end_date)) }} = {{ $leave->approve_day }} day(s)
            </td>
            <td> {{ $balance_cl }} </td>
        </tr>
        @endforeach

        <tr colspan="4"></tr>
        {{-- CL Ends--}}

        {{-- SL --}}
        <tr class="b1">
            <td rowspan="{{ count($leaves['sl'])+1 }}">SL</td>
            <td>
                <span style="float: left;">Current Year SL Leave Opening</span>
            </td>
            <td> {{ $opening_balance['total_sl'] }} </td>
        </tr>

        @php
        $balance_sl = $opening_balance['total_sl'];
        @endphp
        @foreach($leaves['sl'] as $leave)
        <tr>
            @php
            $balance_sl = $balance_sl - \App\Utils\DateTimeUtils::diffInDaysInclusive($leave->approve_start_date, $leave->approve_end_date);
            @endphp

            <td>{{ date('d-m-Y', strtotime($leave->approve_start_date)) }}, To: {{ date('d-m-Y', strtotime($leave->approve_end_date)) }} = {{ $leave->approve_day }} day(s)</td>
            <td> {{ $balance_sl }} </td>
        </tr>
        @endforeach

        <tr colspan="4"></tr>
        {{-- SL Ends--}}

        {{-- EL --}}
        <tr class="b1">
            <td rowspan="{{ count($leaves['el'])+2 }}">EL</td>
            <td>
                <span style="float: left;">Current Year EL Leave Opening</span>
            </td>
            <td> {{ $opening_balance['total_el'] }} </td>
        </tr>
        <tr>
            <td>Employee is total {{ $leaves['total_el'] }} day(s) "Present" in {{ $year }}. Hence, Earned Leaves Equals:- </td>
            <td>{{  ceil($leaves['total_el'] / 18) }}</td>
        </tr>

        @php
        $balance_el = ceil($leaves['total_el'] / 18);
        @endphp
        @foreach($leaves['el'] as $leave)
        <tr>
            @php
            $balance_el = $balance_el - \App\Utils\DateTimeUtils::diffInDaysInclusive($leave->approve_start_date, $leave->approve_end_date);
            @endphp

            <td>{{ date('d-m-Y', strtotime($leave->approve_start_date)) }}, To: {{ date('d-m-Y', strtotime($leave->approve_end_date)) }} = {{ $leave->approve_day }} day(s)</td>
            <td> {{ $balance_el }} </td>
        </tr>
        @endforeach

        {{-- EL Ends --}}

    </tbody>
</table>
