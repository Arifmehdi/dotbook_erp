<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.shift-adjustments.update', $shiftAdjustment->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $shiftAdjustment->id }}" />
    @csrf
    <div class="form-group row mt-1">
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Shift Name') }}</strong><span class="text-danger">*</span></label>
            <select name="shift_id" class="form-control submit_able form-select" id="section_id" autofocus=""
                required>
                <option value="" selected>Select</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}" @if ($shift->id === $shiftAdjustment->shift->id) selected @endif>
                        {{ $shift->name }}</option>
                @endforeach
            </select>
            <span class="error error_shift_id"></span>
        </div>
    </div>
    <div class="row mt-1 form-group">
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Start Time') }}</strong> <span class="text-danger">*</span></label>
            <input type="time" name="start_time" value="{{ $shiftAdjustment->start_time }}"
                class="form-control add_input" data-name="Start Time" id="start_time" placeholder="Start Time" />
            <span class="error error_start_time"></span>
        </div>

        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Late Count Time') }}</strong> <span class="text-danger">*</span></label>
            <input type="time" name="late_count" value="{{ $shiftAdjustment->late_count }}"
                class="form-control add_input" data-name="Late Count Time" id="start_time"
                placeholder="Late Count Time" />
            <span class="error error_late_count"></span>
        </div>

        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('End Time') }}</strong> <span class="text-danger">*</span></label>
            <input type="time" name="end_time" class="form-control" value="{{ $shiftAdjustment->end_time }}"
                data-name="{{ __('End Time') }}" id="end_time" placeholder="{{ __('End Time') }}" required />
            <span class="error error_end_time"></span>
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Applied From Date (DD/MM/YYYY)') }}</strong> <span
                    class="text-danger">*</span></label>
            <input type="text" name="applied_date_from" value="{{ $shiftAdjustment->applied_date_from }}"
                class="form-control litePicker" data-name="applied_date_from" id="applied_date_from"
                placeholder="{{ __('Applied From') }}" required />
            <span class="error error_applied_date_to"></span>
        </div>
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Applied To Date (DD/MM/YYYY)') }}</strong> <span
                    class="text-danger">*</span></label>
            <input type="text" name="applied_date_to" class="form-control litePicker"
                value="{{ $shiftAdjustment->applied_date_to }}" data-name="applied_date_from" id="applied_date_to"
                placeholder="{{ __('Applied To') }}" required />
            <span class="error error_applied_date_to"></span>
        </div>
    </div>
    <div class="row mt-1">

        <div class="col-xl-4 col-md-6">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" name="with_break" id="with_break_edit"
                        @if ($shiftAdjustment->with_break) value="1" checked @else value="0" @endif> &nbsp;
                    <label for="with_break_edit"><strong>{{ __('Allow OverTime Break (Optional)') }}</strong></label>
                </p>
            </div>
            <span class="error error_with_break"></span>
        </div>

        <div class="col-xl-4 col-md-6 break_start">
            <label><strong> {{ __('Break Start (Optional)') }}</strong></label>
            <input type="time" name="break_start" class="form-control " value="{{ $shiftAdjustment->break_start }}"
                data-name="{{ __('Break Start') }}" placeholder="{{ __('Break Start') }}" />
            <span class="error error_break_start"></span>
        </div>

        <div class="col-xl-4 col-md-6 break_end">
            <label><strong> {{ __('Break End (Optional)') }}</strong></label>
            <input type="time" name="break_end" class="form-control " value="{{ $shiftAdjustment->break_end }}"
                data-name="{{ __('Break End') }}" placeholder="{{ __('Over Time Break End') }}" />
            <span class="error error_break_end"></span>
        </div>
        <div>
            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit"
                            class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
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
            type: 'PATCH',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#update_form')[0].reset();
                $('.loading_button').hide();
                $('.shift-adjustment-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });

    // Check Box Config    
    if ($('#with_break_edit').is(':checked')) {
        $('.break_start').show();
        $('.break_end').show();
    } else {
        $('.break_start').hide();
        $('.break_end').hide();
    }

    $(document.body).on('click', '#with_break_edit', function(event) {
        var checked = event.target.checked;
        if (true == checked) {
            $('#with_break_edit').attr('value', 1);
            $('.break_start').show();
            $('.break_end').show();
        }
        if (false == checked) {
            $('#with_break_edit').attr('value', 0);
            $('.break_start').hide();
            $('.break_end').hide();

        }
    });


    // var form = document.getElementById('update_form');
    // var editDateFrom = form.querySelector('#applied_date_from');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('update_form').querySelector('#applied_date_from'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('update_form').querySelector('#applied_date_to'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });
</script>
