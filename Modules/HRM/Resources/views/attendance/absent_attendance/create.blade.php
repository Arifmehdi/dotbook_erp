@extends('layout.master')
@section('title', 'Division wise attendance - ')
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
                <h6>{{ __('Division Wise Attendance') }}</h6>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button">
                    <i class="fa-thin fa-left-to-line fa-2x"></i>
                    <br>
                    @lang('menu.back')
                </a>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-md-12 p-15 pb-0">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter_form">
                                    <div class="form-group row d-flex justify-content-between">
                                        <div class="col-xl-3 col-md-3">
                                            <label><strong>{{ __('Division') }} </strong></label>
                                            <select name="section_id" class="form-control submitable form-select"
                                                id="section_id">
                                                <option value="" selected disabled>{{ __('Select Division') }}
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
                                            <label><strong>{{ __('Today') }}</strong></label>
                                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive create_attendance_table" id="data-list">
                                <form action="{{ route('hrm.sections.store') }}" method="post"
                                    enctype="multipart/form-data" id="add_form">
                                    @csrf
                                    <table class="display data_tbl data__table table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>{{ __('S/L') }}</th>
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
        // $('#employee_id').select2();
        $('#section_id').select2();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $.ajax({
                    "data": function(data) {
                        data.employee_id = $('#employee_id').val();
                        data.hrm_department_id = $('#hrm_department_id').val();
                        data.shift_id = $('#shift_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('.submitable_input').val();
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



                //Submit filter form by select input changing
                // $(document).on('change', '.submitable', function() {
                // });
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

        // $(document.body).on('click', '.check1', function(event) {

        // var allItem = $('.check1');

        // var array = $.map(allItem, function(el, index) {
        //     return [el]
        // })

        // var allChecked = array.every(isSameAnswer);

        // function isSameAnswer(el, index, arr) {
        //     if (index === 0) {
        //         return true;
        //     } else {
        //         return (el.checked === arr[index - 1].checked);
        //     }
        // }

        // if (allChecked && array[0].checked) {
        //     $('#is_check_all').prop('checked', true);
        // } else {
        //     $('#is_check_all').prop('checked', false);
        // }
        // });




        {{-- All Ajax and javascript code start from here --}}
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('change', '#section_id', function() {
            var section_id = $(this).val();
            var name = $(this).data('name');
            if (section_id) {
                $('.data_preloader').show();
                // return;
                $.ajax({
                    url: "{{ url('hrm/attendances/create/sections/wise/row/') }}" + "/" + section_id,
                    type: 'get',
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
                        window.location = "{{ route('hrm.attendance_log.index') }}";
                    }
                }
            });
        });
    </script>
@endpush
