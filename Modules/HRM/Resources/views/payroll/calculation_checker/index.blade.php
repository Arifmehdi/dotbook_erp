@extends('layout.master')
@section('title', 'Calculation Checker - ')
@php
    use Modules\Core\Utils\DateTimeUtils;
    $months_array = DateTimeUtils::months_array();
    $years_array = DateTimeUtils::years_array();
@endphp
@push('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        .employee-table img {
            width: 30px;
        }

        .daterangepicker .calendar-table tr th {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            background-color: #e7e7e7 !important;
            color: black !important;
            border-radius: unset;
            line-height: unset;
        }

        .dtable tr {
            border: 1px solid black;
        }

        .dtable td {
            border: 1px solid black;
            line-height: 100%;
            padding: 0;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="employee-header">
                <h6>{{ __('Calculation Checker') }}</h6>
            </div>
            <x-back-button />
        </div>
        <div class="row g-0">
            <div class="col-md-12 p-15 pb-0">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="GET" id="calculationForm" target="_blank">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-3 col-md-3">
                                            <label><strong>{{ __('Employee') }}</strong></label>
                                            <select name="employee_id" id="employee_id"
                                                class="form-control submitable form-select">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->employee_id }}
                                                        - {{ $employee->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-2 col-md-2">
                                            <label><strong>{{ __('Section') }} </strong></label>
                                            <select name="section_id" class="form-control submitable form-select"
                                                id="section_id">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}" style="color:blue">
                                                        <strong>
                                                            {{ $section->name }}</strong>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-xl-2 col-md-2">
                                            <label><strong>{{ __('Sub Section') }}</strong></label>
                                            <select name="sub_section_id" class="form-control submitable"
                                                id="sub_section_id">
                                                <option value="">{{ __('Choose Section') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-2 col-md-2">
                                            <label><strong>@lang('menu.shift') </strong></label>
                                            <select name="shift_id" class="form-control submitable form-select" id="shift_id">
                                                <option value="" selected>@lang('menu.all')</option> --}}
                                        {{-- @foreach ($shifts as $shift)
                                                <option value="{{ $shift->id }}">{{ $shift->name }} --}}
                                        {{-- </option>
                                                @endforeach --}}
                                        {{-- </select>
                                        </div> --}}

                                        <div class="col-xl-2 col-md-2">
                                            <label><strong>{{ __('Month') }}</strong></label>
                                            <select name="month" id="month"
                                                class="form-control submitable form-select">
                                                @foreach ($months_array as $month)
                                                    <option value="{{ $month }}" @selected($month == date('F') ?? null)>
                                                        {{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-1 col-md-1">
                                            <label><strong>{{ __('Year') }}</strong></label>
                                            <select name="year" id="year"
                                                class="form-control submitable form-select">
                                                @foreach ($years_array as $year)
                                                    <option value="{{ $year }}" @selected($year == date('Y') ?? null)>
                                                        {{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-4">
                                            <div class="loading-btn-box">
                                                <a href="#" class="btn btn-sm btn-info" name="summaryBtn"
                                                    id="summaryBtn">{{ 'Job Card Vs Salary' }}</a>
                                                <a href="#" class="btn btn-sm btn-info" name="jobcardBtn"
                                                    id="jobcardBtn">{{ 'Summary Vs Salary' }}</a>
                                                <a href="#" class="btn btn-sm btn-info" name="allCalculationBtn"
                                                    id="allCalculationBtn">{{ __('All Calculation') }}</a>
                                                <button type="btn"
                                                    class="btn summery_button loading_button display-none"><i
                                                        class="fas fa-spinner"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    {{-- need link --}}
    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>

    <script type="text/javascript">
        $('select[name="employee_id"]').select2();
        $('select[name="section_id"]').select2();
        $('select[name="sub_section_id"]').select2();
        $('select[name="shift_id"]').select2();
        $('select[name="month"]').select2();
        $('select[name="year"]').select2();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#section_id').on('change', function() {
                $('select[name="sub_section_id"]').empty();
                var section_id = $(this).val();
                url = "{{ route('hrm.subsection.pluck') }}";
                if (section_id) {
                    $.ajax({
                        url: url,
                        type: "post",
                        data: {
                            sectionId: section_id,
                        },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('select[name="sub_section_id"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .name + '</option>')

                            });

                        }
                    });
                }
            });
            $(document).on('change', '#employee_id, #month , #year', function() {
                var employee_id = $('#employee_id').val();
                var month = $('#month').val();
                var year = $('#year').val();
                if (employee_id !== null) {
                    $.ajax({
                        url: "{{ route('hrm.attendance.job.card.print') }}",
                        type: 'get',
                        data: {
                            employee_id: employee_id,
                            month: month,
                            year: year
                        }
                    });
                } else {
                    alert('Please select employee first.')
                }

            });
            // call jquery method
            $('#jobPrint').on('click', function(e) {
                e.preventDefault();
                var employee_id = $('#employee_id').val();
                if (!employee_id) {
                    alert('Please select employee first.');
                } else {
                    $('.summery_button').show();
                    $.ajax({
                        url: "{{ route('hrm.attendance.job.card.print') }}",
                        type: 'get',
                        data: {
                            employee_id: $('#employee_id').val(),
                            month: $('#month').val(),
                            year: $('#year').val()
                        },
                        success: function(data) {
                            $('.summery_button').hide();
                            //return;
                            $(data).printThis({
                                debug: false,
                                importCSS: true,
                                importStyle: true,
                                removeInline: false,
                                printDelay: 500,
                                header: null,
                                footer: null,
                            });

                        }
                    });

                };
            });
        });

        // call jquery method
        $('#summeryprint').on('click', function(e) {
            e.preventDefault();
            var subsection = $('#sub_section_id').val();
            $('.summery_button').show();
            $.ajax({
                url: "{{ route('hrm.job.card.summery.print') }}",
                type: 'get',
                data: {
                    employee_id: $('#employee_id').val(),
                    section_id: $('#section_id').val(),
                    sub_section_id: $('#sub_section_id').val(),
                    shift_id: $('#shift_id').val(),
                    month: $('#month').val(),
                    year: $('#year').val()
                },
                success: function(data) {
                    $('.summery_button').hide();
                    //return;
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        removeInline: false,
                        printDelay: 500,
                        header: null,
                        footer: null,
                    });
                }
            });
        });

        // code start here

        $(document).ready(function() {
            $('#summaryBtn').on('click', function(e) {
                e.preventDefault();
                $('#calculationForm').attr('action', "{{ route('hrm.calculation.jobCard_and_salary') }}")
                    .submit();
            });
            $('#jobcardBtn').on('click', function(e) {
                e.preventDefault();
                $('#calculationForm').attr('action', "{{ route('hrm.calculation.summary_and_salary') }}")
                    .submit();
                calculationForm.submit();
            });
            $('#allCalculationBtn').on('click', function(e) {
                e.preventDefault();
                $('#calculationForm').attr('action', "{{ route('hrm.calculation.all') }}").submit();
                calculationForm.submit();
            });
        });
    </script>
@endpush
