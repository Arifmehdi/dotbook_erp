<form id="add_form" action="{{ route('hrm.overtimeAdjustments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col-xl-12 col-md-12">
        <label> {{ __('Employee Name') }} <span class="text-danger">*</span></label>
        <select name="employee_id" class="form-control submit_able employee2 form-select" id="employee_id" autofocus="">
            <option value="" selected disabled>Choose Employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </select>
        <span class="error error_employee_id"></span>
    </div>
    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label>{{ __('OT Minutes ') }} <span class="text-danger">*</span></label>
            <input type="text" name="ot_minutes" class="form-control form-control-sm add_input" id="ot_minutes"
                placeholder="{{ __('Over Time example 3.15') }}" />
            <span class="error error_ot_minutes"></span>
        </div>

        <div class="col-xl-6 col-md-6">
            <label> {{ __('Adjustment Type') }} </label>
            <select name="type" class="form-control submit_able form-select" id="type">
                <option value="" selected>Select</option>
                <option value="1">Addition</option>
                <option value="2">Deduction</option>

            </select>
            <span class="error error_type"></span>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label>{{ __('Month') }} <span class="text-danger">*</span></label>
            {{-- <input type="month" name="month" class="form-control form-control-sm add_input"  id="month" placeholder="{{ __('Month') }}" required/> --}}

            @php
                $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
            @endphp
            <select name="month" class="form-control form-control-sm add_input" id="month"
                placeholder="{{ __('Month') }}">
                <option value="" selected disabled>Choose Month</option>
                @foreach ($months as $month => $monthName)
                    <option value="{{ $month }}"
                        @php $todayMonth = carbon\Carbon::now(); $todayMonth->month; @endphp
                        @if ($todayMonth->month == $month) {{ 'selected' }} @endif> {{ $monthName }}</option>
                @endforeach
            </select>

            <span class="error error_month"></span>
        </div>

        <div class="col-xl-6 col-md-6">
            <label> {{ __('Year') }} </label>
            {{-- <input type="month" name="year" class="form-control form-control-sm add_input"  id="year" placeholder="{{ __('Year') }}" required/> --}}
            <select name="year" class="form-control form-control-sm add_input" id="year"
                placeholder="{{ __('Year') }}" required>
                <option value="" selected disabled>Choose Year</option>
                @for ($year = 2020; $year <= date('Y') + 5; $year++)
                    <option value="{{ $year }}" @php $today = carbon\Carbon::now(); $today->year; @endphp
                        @if ($today->year == $year) {{ 'selected' }} @endif>{{ $year }}</option>
                @endfor
            </select>
            <span class="error error_year"></span>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xl-12 col-md-12">
            <label>{{ __('Description') }} <span class="text-danger">*</span></label>
            <textarea cols="30" rows="3" name="description" class="form-control form-control-sm add_input ckEditor"
                id="description" placeholder="{{ __('Description') }}"></textarea>
            <span class="error error_name"></span>
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
                toastr.success(data);
                $('#add_form')[0].reset();
                $('.loading_button').hide();
                $('.employee-table').DataTable().draw(false);
                $('#addModal').modal('hide');
            },

            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
                ///field error.
                $.each(error.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0].replace(' id ', ' '));
                });
            }
        });
    });
</script>
