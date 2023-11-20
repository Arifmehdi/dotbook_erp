@extends('layout.master')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .daterangepicker .calendar-table tr th {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            background-color: #e7e7e7 !important;
            color: black !important;
            border-radius: unset;
            line-height: unset;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="employee-header">
                <h6>{{ __('Person Wise Attendance') }}</h6>
            </div>
            <x-back-button />
        </div>
        <div class="p-15">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row d-flex justify-content-between">
                                            <div class="col-xl-4 col-md-4">
                                                <label><strong>{{ __('Employee') }} </strong></label>
                                                <select name="employee_id" class="form-control submitable form-select"
                                                    id="employee_id">
                                                    <option value="">{{ __('Select Employee') }}</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->employee_id }}
                                                            -{{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-2 col-md-2">
                                                <label><strong>{{ __('Date') }}</strong></label>
                                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}"
                                                    name="date" id="date">
                                            </div>
                                        </div>
                                    </form>
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
                            <div class="table-responsive create_attendance_table" id="data-list">
                                <form action="{{ route('hrm.persons.store') }}" method="post" enctype="multipart/form-data"
                                    id="add_form">
                                    @csrf
                                    <table class="display data_tbl data__table table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>{{ __('S/L') }}</th>
                                                <input type="hidden" name="today" value="{{ date('Y-m-d') }}"
                                                    id="today">
                                                <th>{{ __('Employee') }}</th>
                                                <th>{{ __('Clock In') }}</th>
                                                <th>{{ __('Clock Out') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendance_row"></tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-sm btn-success float-end px-3 py-1 mt-2"
                                                type="submit">Save</button>
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

    <!-- Delete Form -->
    <form id="delete_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('#employee_id').select2();
        $('#hrm_department_id').select2();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $.ajax({
                    "data": function(data) {}
                }),
                // table.buttons().container().appendTo('#exportButtonsContainer');
                $.ajax({
                    url: "{{ route('hrm.persons.create') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data.data, function(key, val) {
                            $('#employee_id').append('<option value="' + val.id + '">' + val.name +
                                '</option>');
                        });
                    }
                });
            $(document).on('click', '.btn_remove', function() {
                $(this).closest('tr').remove();
            });
            // Employee filter
            $(document).on('change', '#employee_id', function() {
                var employee_id = $(this).val();
                var date = $('#date').val();
                var count = 0;
                $('#today').val(date);
                $('.create_attendance_table table').find('tr').each(function() {
                    if ($(this).data('employee_id') == employee_id) {
                        count++;
                    }
                });

                if (employee_id && count == 0) {
                    $('.data_preloader').show();
                    var url = "{{ route('hrm.person_wise_attendance') }}";
                    $.ajax({
                        url: url,
                        type: 'get',
                        data: {
                            date: date,
                            employee_id: employee_id,
                            currentRow: $('#data-list tr').length,
                        },
                        success: function(data) {
                            $('#attendance_row').append(data);
                            $('.data_preloader').hide();
                        }
                    });
                }
            });
            //Date wise Filter
            $(document).on('change', '#date', function() {
                $('#attendance_row').empty();
                var employee_id = $('#employee_id').val();
                var date = $(this).val();
                $('#today').val(date);
                if (employee_id) {
                    $('.data_preloader').show();
                    var url = "{{ route('hrm.person_wise_attendance') }}";
                    $.ajax({
                        url: url,
                        type: 'get',
                        data: {
                            date: date,
                            employee_id: employee_id
                        },
                        success: function(data) {
                            $('#attendance_row').append(data);
                            $('.data_preloader').hide();
                        }
                    });
                }
            });
            // Submit filter form by select input changing
            //Add new data
            $('#add_form').on('submit', function(e) {
                e.preventDefault();
                alert('submitting...');
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.error').html('');

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush
