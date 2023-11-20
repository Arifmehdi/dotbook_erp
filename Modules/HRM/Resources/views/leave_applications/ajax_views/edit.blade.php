<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.leave-applications.update', $leaveApplication->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $leaveApplication->id }}" />

    <div class="row">
        <div class="form-group col-xl-12 col-md-12">

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

        <div class="row">
            <div class="col-xl-6 col-md-6">

                <label> {{ __('Leave Type') }} <span class="text-danger">*</span></label>
                <select name="leave_type_id" required class="form-control submit_able" id="leave_type_id"
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
            <div class="form-group col-xl-6 col-md-6">
                <label><b> {{ __('Paid Type') }}</b> <span class="text-danger">*</span></label>
                <select name="is_paid" class="form-control submit_able form-select" id="is_paid" autofocus="">
                    <option value="1" @selected($leaveApplication->is_paid == 1)>{{ __('Paid') }}</option>
                    <option value="0" @selected($leaveApplication->is_paid == 0)>{{ __('Unpaid') }}</option>
                </select>
                <span class="error error_is_paid"></span>
            </div>



            <div class="form-group col-xl-6 col-md-6">

                <label><b> {{ __('Status') }}</b> <span class="text-danger">*</span></label>
                <select class="form-control form-control-sm form-select" name="status">
                    <option value="1" @selected($leaveApplication->status == 1)>Allowed</option>
                    <option value="0" @selected($leaveApplication->status == 0)>Not-Allowed</option>
                </select>
                <span class="error error_status"></span>
            </div>
        </div>


        <div class="form-group col-xl-12 col-md-12">
            <label><b> {{ __('Reason') }}</b> <span class="text-danger">*</span></label>
            <textarea name="reason" id="reason" cols="30" rows="3"
                class="form-control form-control-sm add_input  ckEditor" placeholder="{{ __('Enter leave Reason') }}">{{ $leaveApplication->reason }}</textarea>
            <span class="error error_reason"></span>
        </div>


        <input type="hidden" name="old_photo" value="{{ $leaveApplication->attachment }}">
        <div class="row">
            <div class="form-group col-xl-5 col-md-5">

                <label><b> {{ __('Num of Days') }}</b> <span class="text-danger">*</span></label>
                <input type="text" name="approve_day" class="form-control form-control-sm add_input num_of_days"
                    data-name="{{ __('Num of Days') }}" id="num_of_days1" placeholder="{{ __('Num of Days') }}"
                    required readonly />
                <span class="error error_approve_day"></span>

            </div>

            <div class="form-group col-xl-5 col-md-5">
                <label><strong>@lang('menu.add_file') </strong></label>
                <input type="file" name="attachment" class="form-control" id="edit_attachment">
                <span class="error error_attachment"></span>
            </div>
            <div class="form-group col-xl-2 col-md-2">
                @php
                    $image_extention = pathinfo($leaveApplication->attachment, PATHINFO_EXTENSION);
                @endphp
                <img src="@if ($image_extention == 'pdf') {{ asset('uploads/application/pdf.jpg') }}
                @else {{ asset('/uploads/application/' . $leaveApplication->attachment) }} @endif"
                    style="height:70px; width:60px; margin-top: 13px; margin-right:10px;" id="edit_p_avatar"
                    alt="No image" class="@if ($leaveApplication->attachment != null) d-block @else d-none @endif">
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
                    class="btn btn-sm btn-danger float-start float-end me-2 close-modal">@lang('menu.close')</button>
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
            type: 'POST',
            // data: request,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                toastr.success(data);
                $('#update_form')[0].reset();
                $('.loading_button').hide();
                $('.leave_application_table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });





    //     // Edit category by ajax
    //     $(document).on('submit', '#update_form', function(e) {
    //     e.preventDefault();
    //     $('.loading_button').show();
    //     var url = $(this).attr('action');
    //     $.ajax({
    //         url: url,
    //         type: 'post',
    //         data: new FormData(this),
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function(data) {
    //             toastr.success(data);
    //             $('#update_form')[0].reset();
    //             $('#editModal .close-modal').click();
    //             $('.leave_application-table').DataTable().draw(false);
    //             $('.loading_button').hide();
    //             table.ajax.reload();

    //             // $('#edit_notice_form')[0].reset();
    //             // $('#editModal .close-modal').click();
    //             // $('.notice_table').DataTable().draw(false);
    //             // $('.loading_button').hide();
    //         },
    //         error: function(err) {
    //             $('.loading_button').hide();
    //             if (err.status == 0) {
    //                 toastr.error('Net Connetion Error. Reload This Page.');
    //                 return;
    //             }
    //             $.each(err.responseJSON.errors, function(key, error) {
    //                 $('.error_' + key + '').html(error[0]);
    //             });
    //         }
    //     });
    // });
    // coder start here

    //show image on add from with jquery
    $("#edit_attachment").change(function() {
        var file = $("#edit_attachment").get(0).files[0];
        // if(file){
        //     var reader = new FileReader();
        //     reader.onload = function(){
        //         var extension = file.name.split(".").pop();
        //             $("#edit_p_avatar").attr("src", reader.result);
        //             $("#edit_p_avatar").attr("alt", extension);
        //     }
        //     reader.readAsDataURL(file);
        // }

        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                var extension = file.name.split(".").pop();
                if (extension.toLowerCase() == 'pdf') {
                    $("#edit_p_avatar").attr("src", "{{ asset('uploads/application/pdf.jpg') }}");
                    $("#edit_p_avatar").attr("alt", extension);
                    $("#edit_p_avatar").removeClass("d-none");
                    $("#edit_p_avatar").addClass("d-block");
                } else if (extension.toLowerCase() == 'jpg' || 'jpeg' || 'png' || 'gif') {
                    $("#edit_p_avatar").attr("src", reader.result);
                    $("#edit_p_avatar").attr("alt", extension);
                    $("#edit_p_avatar").removeClass("d-none");
                    $("#edit_p_avatar").addClass("d-block");
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
