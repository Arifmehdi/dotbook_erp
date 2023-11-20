<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.schedule_update', ['id' => $interviewSchedule->id]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $interviewSchedule->id }}" />
    <div class="form-group row mt-1">
        <div class="col-xl-6 col-md-12">
            <label><strong> {{ __('Interview Title') }}</strong> <span class="text-danger">*</span></label>
            <select name="interview_id" required class="form-control submit_able form-select" id="interview_id"
                autofocus="">
                <option disabled value="">{{ __('Select One') }}</option>
                @foreach ($interviewTitles as $interviewTitle)
                    <option value="{{ $interviewTitle->id }}"
                        {{ $interviewTitle->id == $interviewSchedule->interview_id ? 'selected' : null }}>
                        {{ $interviewTitle->title }}</option>
                @endforeach
            </select>
            <span class="error error_interview_id"></span>
        </div>
        <div class="col-xl-6 col-md-12">
            <label><strong> {{ __('Interviewers') }}</strong> <span class="text-danger">*</span></label>
            <input type="text" name="interviewers" value="{{ $interviewSchedule->interviewers }}"
                class="form-control" data-name="{{ __('Interviewers Name') }}" id="interviewers"
                placeholder="{{ __('Interviewers') }}" required />
            <span class="error error_interviewers"></span>
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="col-xl-6 col-md-12">
            <label><strong> {{ __('Interviewees') }}</strong> <span class="text-danger">*</span></label>
            <select name="applicant_id" required class="form-control submit_able form-select" id="applicant_id"
                autofocus="">
                <option disabled value="">{{ __('Select interviewee') }}</option>
                @foreach ($selectedForInterviees as $selectedForInterview)
                    {{-- @php
                    $fullName = $selectedForInterview->first_name . ' ' . $selectedForInterview->last_name;
                @endphp --}}
                    {{-- <option value="{{ $selectedForInterview->id }}">{{ $selectedForInterview->fullName }}</option> --}}
                    <option value="{{ $selectedForInterview->id }}"
                        {{ $selectedForInterview->id == $interviewSchedule->applicant_id ? 'selected' : null }}>
                        {{ $selectedForInterview->fullName }}
                    </option>
                @endforeach
            </select>
            <span class="error error_interviewees"></span>
        </div>
        <div class="col-xl-6 col-md-12">
            <label><strong> {{ __('Date Time') }}</strong> <span class="text-danger">*</span></label>
            <input type="datetime-local" value="{{ $interviewSchedule->date_time }}" name="date_time"
                class="form-control" data-name="{{ __('Interviewees Name') }}" id="date_time"
                placeholder="{{ __('Date time') }}" required />
            <span class="error error_date_time"></span>
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="col-xl-12 col-md-12">
            <label><strong> {{ __('Description') }}</strong> <span class="text-danger">*</span></label>
            <textarea class="form-control ckEditor" name="descriptions" id="descriptions" rows="7">
                {{ $interviewSchedule->descriptions }}
                </textarea>
            <span class="error error_descriptions"></span>
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('#update_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.error').html('');

        $.ajax({
            url: url,
            type: 'POST',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#update_form')[0].reset();
                $('.loading_button').hide();
                $('.leave-type-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
