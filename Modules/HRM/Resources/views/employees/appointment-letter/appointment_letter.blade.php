@extends('layout.master')
@section('title', 'Appointment Letter - ')
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
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="designation-header">
                <h6>{{ __('Appointment Letter Genereate') }}</h6>
            </div>
            <x-all-buttons />
        </div>
        <div class="row g-0">
            <div class="col-md-12 p-15 pb-0">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-xl-6 col-md-6">

                                        <label><strong>Select Employee :</strong></label>
                                        <select name="employee_id" class="form-control form-select" id="employee_id">
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

        <div class="p-15">
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>

                            <div class="create_attendance_table">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner"></i>Processing...</h6>
                                </div>
                                <div class="table-responsive">
                                    <form id="add_form" action="{{ route('hrm.employee.selected.letter.print') }}"
                                        method="post" target="_blank">
                                        @csrf
                                        @method('get')
                                        <table class="table employee-datatable">
                                            <thead class="">
                                                <tr>
                                                    <th>EmployeeID</th>
                                                    <th>Photo</th>
                                                    <th>Employee Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Joining Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="attendance_row">

                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-sm btn-success"
                                            style="float: right">Submit</button>
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

    <script>
        $(document).ready(function() {
            $('#employee_id').select2();
            //appoinment letter select  employee
            $('#employee_id').on('change', function() {

                var user_id = $(this).val();
                var name = $(this).data('name');
                var count = 0;
                $('.create_attendance_table table').find('tr').each(function() {
                    if ($(this).data('user_id') == user_id) {
                        count++;
                        alert('Your selected employee entered already');
                    }
                });
                if (user_id && count == 0) {
                    $('.data_preloader').show();
                    $.ajax({
                        url: "{{ url('hrm/employee/create/person/wise/row/') }}" + "/" + user_id,
                        type: 'get',
                        success: function(data) {
                            $('#attendance_row').append(data);
                            $('.data_preloader').hide();
                        }
                    });
                }
            });

            $.ajax({
                url: "{{ route('hrm.v1.departments.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#hrm_department_id').append('<option value="' + val.id + '">' + val
                            .name +
                            '</option>');
                    });
                }
            });

            //Add new data
            // $('#add_form').on('submit', function(e) {
            //     e.preventDefault();
            //     $('.loading_button').show();
            //     var url = $(this).attr('action');
            //     var request = $(this).serialize();
            //     $('.error').html('');

            //     $.ajax({
            //         url: url
            //         , type: 'post'
            //         , data: request
            //         , success: function(data) {
            //             toastr.success(data);
            //             $('#add_form')[0].reset();
            //             $('.loading_button').hide();
            //             table.ajax.reload();
            //             $('#addModal').modal('hide');
            //         },
            //         error: function(error) {
            //             $('.loading_button').hide();
            //             toastr.error(error.responseJSON.message);
            //         }
            //     });
            // });
        });

        $('body').on('click', '#print_one_id_get', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            $("#user_id").val($(this).data('id')); //set userid on uerid field
        });
    </script>

    {{-- appointment letter start --}}
    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
    {{-- All Ajax and javascript code start from here --}}
    <script type="text/javascript">
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.btn_remove', function() {
            $(this).closest('tr').remove();
        });
    </script>
@endpush
