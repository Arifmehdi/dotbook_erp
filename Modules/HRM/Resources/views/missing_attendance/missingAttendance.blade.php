@extends('layout.master')
@push('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .sorting_disabled {
            background: none;
        }



        .designation-table img {
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


        .select2-search--dropdown .select2-search__field {
            padding: 6px;
        }
    </style>
    <!-- Page header -->
    <style>
        .data_preloader {
            position: absolute;
            left: 0%;
            right: 0%;
            top: 0;
            bottom: 0;
            z-index: 1;
            background: white;
            padding-top: 2px;
            border-radius: 3px;
            opacity: 0.7;
            color: black;
            width: 100%;
            height: 100%;
            display: none;
        }

        .data_preloader {
            text-align: center;
            font-size: 17px;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="designation-header">
                <h6>{{ __('Missing Attendence') }}</h6>
            </div>
            <x-all-buttons />
        </div>

        <div class="p-15">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-xl-3 col-md-6">

                                            <label><strong>Select Department :</strong></label>
                                            <select name="hrm_department_id" class="form-control form-select"
                                                id="hrm_department_id">
                                                <option selected disabled>Select Division</option>
                                                @forelse($departments as $department)
                                                    <option value="" disabled class="text-primary">
                                                        -----{{ $department->name }}-----</option>
                                                    @foreach ($department->sections as $section)
                                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                    @endforeach
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-md-6">

                                            <label><strong>Department wise Employee :</strong></label>
                                            <select name="employee_id" class="form-control form-select" id="employee_id">
                                                <option value="">Select Department First</option>
                                            </select>

                                        </div>
                                        <div class="col-xl-4 col-md-6">

                                            <label><strong>Or Select Employee :</strong></label>
                                            <select name="employee_id" class="form-control single form-select"
                                                id="single_employee_id">
                                                <option selected readonly>Select</option>
                                                @forelse($employees as $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->employee_id }}-{{ $employee->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <form id="add_attendance_form" action="{{ route('hrm.missing.store') }}" method="POST">
                            @csrf
                            <div class="create_attendance_table">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner"></i>Processing...</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table employee-datatable">
                                        <thead class="">
                                            <tr>
                                                <th>Employee</th>
                                                <th>Start Date</th>
                                                <th>Clock-In</th>
                                                <th>Clock-Out</th>
                                                <th>Clock In Note</th>
                                                <th>Clock Out Note</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendance_row">
                                            <td>---</td>
                                            <td>--:--</td>
                                            <td>--:--</td>
                                            <td>--:--</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>

                                        </tbody>
                                    </table>

                                    {{-- <button type="submit" class="btn btn-sm btn-success" style="float: right">Submit</button>
                                </form> --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-sm btn-success" style="float: right">Save</button>
                                            <button type="btn" class="btn loading_button float-right d-none"><i
                                                    class="fas fa-spinner"></i><b>Loading...</b></button>
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
    </div>


@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
    {{-- All Ajax and javascript code start from here --}}
    <script type="text/javascript">
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#employee_id').select2();
            $('#single_employee_id').select2();
            $('#hrm_department_id').select2();
        });

        $(document).on('change', '#hrm_department_id', function() {
            var departmentId = $(this).val();
            var count = 0;
            $('.create_attendance_table table').find('tr').each(function() {
                count++;
            });

            $.ajax({
                url: "{{ url('hrm/attendances/division/wise/employee') }}" + "/" + departmentId,
                type: 'GET',
                data: {
                    departmentID: departmentId,
                    dataType: 'department',
                },
                success: function(data) {
                    $('#employee_id').empty();
                    $('#employee_id').append('<option value="">Select Employee</option>');
                    $.each(data, function(key, emp) {
                        $('#employee_id').append('<option value="' + emp.id + '">' + emp
                            .employee_id + '- ' + emp.name + '</option>');
                    });
                }
            });
        });

        $(document).on('change', '#employee_id', function() {
            var employeeId = $(this).val();
            $.ajax({
                url: "{{ url('hrm/attendances/division/wise/employee') }}" + "/" + employeeId,
                type: 'GET',
                data: {
                    departmentID: employeeId,
                    dataType: 'section-wise',
                },
                success: function(data) {
                    $('#attendance_row').append(data);
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('change', '#single_employee_id', function() {
            var employeeId = $(this).val();
            $.ajax({
                url: "{{ url('hrm/attendances/division/wise/employee') }}" + "/" + employeeId,
                type: 'GET',
                data: {
                    departmentID: employeeId,
                    dataType: 'section-wise',
                },
                success: function(data) {
                    $('#attendance_row').append(data);
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('click', '.btn_remove', function() {
            $(this).closest('tr').remove();
        });

        // Add idcard by ajax
        $('#add_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'get',
                data: request,
                success: function(data) {
                    $('.loading_button').hide();
                    if (data.errorMsg) {
                        toastr.error(data.errorMsg);
                    } else {
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/idcard.css') }}",
                            removeInline: false,
                            printDelay: 500,
                            header: null,
                            footer: null,
                        });
                    }

                }
            });
        });
        // Add attendance by ajax
        $('#add_attendance_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                    } else {
                        toastr.success(data);
                        $('.loading_button').hide();
                        location.reload(true);
                    }
                }
            });
        });
    </script>
@endpush
