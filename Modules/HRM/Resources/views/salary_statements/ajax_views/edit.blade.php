<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.leave-applications.update', $leaveApplication->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $leaveApplication->id }}" />
    @csrf


    <div class="row">
        <div class="form-group col-xl-6 col-md-6">

            <label><b> {{ __('Employee Name') }}</b> <span class="text-danger">*</span></label>
            <select name="employee_id" required class="form-control submit_able form-select" id="employee_id2"
                autofocus="">
                <option value="">Select</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}"
                        {{ $leaveApplication->employee_id == $employee->id ? 'selected' : null }}>{{ $employee->name }}
                    </option>
                @endforeach
            </select>
            <span class="error error_employee_id"></span>
        </div>
        <div class="col-xl-6 col-md-6">

            <label> {{ __('Leave Type') }} <span class="text-danger">*</span></label>
            <select name="leave_type_id" required class="form-control submit_able form-select" id="leave_type_id"
                autofocus="">
                <option value="">Select</option>
                @foreach ($leaveTypes as $leaveType)
                    <option value="{{ $leaveType->id }}"
                        {{ $leaveApplication->leave_type_id == $leaveType->id ? 'selected' : null }}>
                        {{ $leaveType->name }}</option>
                @endforeach
            </select>

            <span class="error error_leave_type_id"></span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="form-group col-xl-6 col-md-6">
                <label><b> {{ __('Start From') }}</b> <span class="text-danger">*</span></label>
                <input type="date" name="from_date" class="form-control form-control-sm add_input startdate"
                    data-name="{{ __('Start From') }}" id="startdate1" placeholder="{{ __('Start From') }}"
                    value="{{ $leaveApplication->from_date }}" required />
                <span class="error error_from"></span>
            </div>
            <div class="form-group col-xl-6 col-md-6">
                <label><b> {{ __('End To') }}</b> <span class="text-danger">*</span></label>
                <input type="date" name="to_date" class="form-control form-control-sm add_input enddate"
                    data-name="{{ __('End To') }}" id="enddate1" placeholder="{{ __('End To') }}"
                    value="{{ $leaveApplication->to_date }}" required />
                <span class="error error_to"></span>
            </div>
        </div>
        <div class="form-group col-xl-12 col-md-12">
            <label><b> {{ __('Reason') }}</b> <span class="text-danger">*</span></label>
            <textarea name="reason" id="reason" cols="30" rows="3"
                class="form-control form-control-sm add_input  ckEditor" placeholder="{{ __('Enter leave Reason') }}">{{ $leaveApplication->reason }}</textarea>
            <span class="error error_reason"></span>
        </div>

        <div class="row">
            <div class="form-group col-xl-6 col-md-6">

                <label><b> {{ __('Status') }}</b> <span class="text-danger">*</span></label>
                <select class="form-control form-control-sm form-select" name="status">
                    <option value="1" @if ($leaveApplication->status == 1) selected @endif>Allowed</option>
                    <option value="0" @if ($leaveApplication->status == 0) selected @endif>Not-Allowed</option>
                </select>
                <span class="error error_status"></span>

            </div>


            <div class="form-group col-xl-6 col-md-6">

                <label><b> {{ __('Num of Days') }}</b> <span class="text-danger">*</span></label>
                <input type="text" name="approve_day" class="form-control form-control-sm add_input num_of_days"
                    data-name="{{ __('Num of Days') }}" id="num_of_days1" placeholder="{{ __('Num of Days') }}"
                    required readonly />
                <span class="error error_approve_day"></span>

            </div>
        </div>
    </div>

    <span class="error error_for_month"></span>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit"
                    class="btn btn-sm btn-success float-end submit_button">{{ __('Update') }}</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
        function diffInDays(date1, date2, inclusive = true) {
            var date1 = moment(date1);
            var date2 = moment(date2);
            var diff = moment.duration(date2.diff(date1));
            // var diffInDays = Math.abs(diff.asDays());
            var diffInDays = diff.asDays();
            if (inclusive) {
                diffInDays += 1;
            }
            return diffInDays;
        }

        function caculateDiffAndRender() {
            var fromDate = $('#startdate1').val();
            var toDate = $('#enddate1').val();
            var d = diffInDays(fromDate, toDate);
            $('#num_of_days1').val(d);
        }

        $(document).on('change', '#startdate1', function(e) {
            e.preventDefault();
            caculateDiffAndRender();
        });

        $(document).on('change', '#enddate1', function(e) {
            e.preventDefault();
            caculateDiffAndRender();
        });

        caculateDiffAndRender();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#update_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.error').html('');

        $.ajax({
            url: url,
            type: 'PATCH',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#update_form')[0].reset();
                $('.loading_button').hide();
                $('.leave_application-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
