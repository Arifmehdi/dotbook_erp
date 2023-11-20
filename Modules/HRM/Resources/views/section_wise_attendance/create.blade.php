@extends('layout.master')
@section('title', 'Section wise attendance - ')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



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
                <h6>{{ __('Section Wise Attendance') }}</h6>
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
                                            <div class="col-xl-3 col-md-3">
                                                <label><strong>{{ __('Section') }} </strong></label>
                                                <select name="section_id" class="form-control submitable form-select"
                                                    id="section_id">
                                                    <option value="" selected disabled>{{ __('Select Section') }}
                                                    </option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" disabled style="color:blue">
                                                            <strong> {{ $department->name }}</strong>
                                                        </option>
                                                        @foreach ($department->sections as $section)
                                                            <option value="{{ $section->id }}">&nbsp;&nbsp;&nbsp; --
                                                                {{ $section->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-2 col-md-2">
                                                <label><strong>{{ __('Date') }}</strong></label>
                                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}"
                                                    id="today">
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
                                <form action="{{ route('hrm.section-wise.store') }}" method="post"
                                    enctype="multipart/form-data" id="add_form">
                                    @csrf



                                    <table class="display data_tbl data__table award_table table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S/L') }}</th>
                                                <input type="hidden" name="today" value="{{ date('d-m-Y') }}"
                                                    id="date">
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
                                            <button class="btn btn-sm btn-success float-end px-3 py-1 mt-2" type="submit"
                                                target="_blank">Save</button>
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

    {{-- <!-- Delete Form -->
<form id="delete_form" action="" method="post">
    @method('DELETE')
    @csrf
</form> --}}

@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        // $('#employee_id').select2();
        $('#section_id').select2();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            var sectionWiseTable = $("#sectionWiseTable").DataTable({

            });

            $.ajax({
                    "data": function(data) {
                        // data.employee_id = $('#employee_id').val();
                        // data.hrm_department_id = $('#hrm_department_id').val();
                        // data.shift_id = $('#shift_id').val();
                        // data.designation_id = $('#designation_id').val();
                        // data.date_range = $('.submitable_input').val();
                    }
                }),
                // table.buttons().container().appendTo('#exportButtonsContainer');
                // $.ajax({
                //     url: "{{ route('hrm.persons.create') }}",
                //     type: 'get',
                //     dataType: 'json',
                //     success: function(data) {
                //         $.each(data.data, function(key, val) {
                //             $('#employee_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                //         });
                //     }
                // });
                // $.ajax({
                //     url: "{{ route('hrm.v1.departments.index') }}"
                //     , type: 'get'
                //     , dataType: 'json'
                //     , success: function(data) {
                //         $.each(data.data, function(key, val) {
                //             $('#hrm_department_id').append('<option value="' + val.id + '">' + val.name +
                //                 '</option>');
                //         });
                //     }
                // });

                // $.ajax({
                //     url: "{{ route('hrm.v1.designations.index') }}"
                //     , type: 'get'
                //     , dataType: 'json'
                //     , success: function(data) {
                //         $.each(data.data, function(key, val) {
                //             $('#designation_id').append('<option value="' + val.id + '">' + val.name +
                //                 '</option>');
                //         });
                //     }
                // });

                // $.ajax({
                //     url: "{{ route('hrm.v1.shifts.index') }}"
                //     , type: 'get'
                //     , dataType: 'json'
                //     , success: function(data) {
                //         $.each(data.data, function(key, val) {
                //             $('#shift_id').append('<option value="' + val.id + '">' + val.name +
                //                 '</option>');
                //         });
                //     }
                // });
                $(document).on('click', '.btn_remove', function() {
                    $(this).closest('tr').remove();
                });
            // Employee filter
            // $(document).on('change', '#employee_id', function() {
            //     var employee_id = $(this).val();
            //     var count = 0;

            //     $('.create_attendance_table table').find('tr').each(function() {
            //         if ($(this).data('employee_id') == employee_id) {
            //             count++;
            //         }
            //     });

            //     if (employee_id && count == 0) {
            //         $('.data_preloader').show();
            //         $.ajax({
            //             url: "{{ url('hrm/attendances/person/wise/attendance') }}" + "/" + employee_id
            //             , type: 'get'
            //             , success: function(data) {

            //                 $('#attendance_row').append(data);
            //                 $('.data_preloader').hide();
            //             }
            //         });
            //     }
            // });
            // $(document).on('change', '#shift_id', function() {
            //     var shift_id = $(this).val();
            //     var count = 0;

            //     $('.create_attendance_table table').find('tr').each(function() {
            //         if ($(this).data('employee_id') == shift_id) {
            //             count++;
            //         }
            //     });

            //     if (shift_id && count == 0) {
            //         $('.data_preloader').show();
            //         $.ajax({
            //             url: "{{ url('hrm/attendances/shift/wise/attendance') }}" + "/" + shift_id
            //             , type: 'get'
            //             , success: function(data) {

            //                 $('#attendance_row').html(data);
            //                 $('.data_preloader').hide();
            //             }
            //         });
            //     }
            // });
            // $(document).on('change', '#hrm_department_id', function() {
            //     var hrm_department_id = $(this).val();
            //     var count = 0;

            //     $('.create_attendance_table table').find('tr').each(function() {
            //         if ($(this).data('employee_id') == hrm_department_id) {
            //             count++;
            //         }
            //     });

            //     if (hrm_department_id && count == 0) {
            //         $('.data_preloader').show();
            //         $.ajax({
            //             url: "{{ url('hrm/attendances/department/wise/attendance') }}" + "/" + hrm_department_id
            //             , type: 'get'
            //             , success: function(data) {
            //                 $('#attendance_row').html(data);
            //                 $('.data_preloader').hide();
            //             }
            //         });
            //     }
            // });
            //Submit filter form by select input changing
            // $(document).on('change', '.submitable', function() {
            // });
            //Add new data

            $('#add_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.error').html('');

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data.message);
                        $('#add_form')[0].reset();
                        $('.loading_button').hide();
                        sectionWiseTable.ajax.reload();
                        $('#addModal').modal('hide');
                        const a = document.createElement("a");
                        a.setAttribute("href", data.next_url);
                        a.dispatchEvent(new MouseEvent("click", {
                            ctrlKey: true
                        }));
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
        });

        // $(document.body).on('click', '.check1', function(event) {

        //     var allItem = $('.check1');

        //     var array = $.map(allItem, function(el, index) {
        //         return [el]
        //     })

        //     var allChecked = array.every(isSameAnswer);

        //     function isSameAnswer(el, index, arr) {
        //         if (index === 0) {
        //             return true;
        //         } else {
        //             return (el.checked === arr[index - 1].checked);
        //         }
        //     }

        //     if (allChecked && array[0].checked) {
        //         $('#is_check_all').prop('checked', true);
        //     } else {
        //         $('#is_check_all').prop('checked', false);
        //     }
        // });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('change', '#section_id, #today', function() {
            var section_id = $('#section_id').val();
            var date = $('#today').val();
            var name = $(this).data('name');
            $('#date').val(date);
            if (section_id) {
                $('.data_preloader').show();
                var url = "{{ route('hrm.section-wise.create_row') }}";
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        date: date,
                        section_id: section_id
                    },
                    success: function(data) {
                        $('#attendance_row').empty();
                        $('#attendance_row').append(data);
                        $('.data_preloader').hide();
                    }
                });
            }
        });

        $(document).on('click', '.btn_remove', function() {
            $(this).closest('tr').remove();
        });

        // Add attendance by ajax
        $('#add_attendance_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');

            // var request = new FormData(document.getElementById('add_attendance_form'));
            var request = $(this).serialize();
            // request.append('date', document.getElementById('today').value);

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                    } else {
                        toastr.success(data.message);
                        $('.loading_button').hide();
                        window.open(response.data.next_url, '_blank');
                        // window.location = response.redirectUrl;
                    }
                }
            });
        });
    </script>
@endpush
