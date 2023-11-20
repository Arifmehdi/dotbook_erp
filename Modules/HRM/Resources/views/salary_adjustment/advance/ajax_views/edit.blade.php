<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.salary-advances.update', $salaryAdvance->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $salaryAdvance->id }}" />

    <div class="col-xl-12 col-md-12">
        <label><b> {{ __('Employee') }}</b> <span class="text-danger">*</span></label>
        <select name="employee_id" class="form-control submit_able employee2 form-select" id="employee_id"
            autofocus="">
            <option value="" selected disabled>{{ __('-- Choose Employee --') }}</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @if ($salaryAdvance->employee_id == $employee->id) {{ 'selected' }} @endif>
                    {{ $employee->name }} {{ '(' }}{{ $employee->employee_id }} {{ ')' }}</option>
            @endforeach
        </select>
        <span class="error error_employee_id"></span>
    </div>
    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label>{{ __('Amount') }} <span class="text-danger">*</span></label>
            <input type="number" name="amount" value="{{ $salaryAdvance->amount }}"
                class="form-control form-control-sm add_input" data-name="{{ __('Designation Name') }}" id="amount"
                placeholder="{{ __('Amount') }}" />
            <span class="error error_amount"></span>
        </div>

        <div class="col-xl-6 col-md-6">
            <label> {{ __('Approve Date') }} </label>
            <input type="date" name="date" class="form-control form-control-sm add_input" id="amount"
                placeholder="{{ __('Date') }}" value="{{ $salaryAdvance->date }}">
            <span class="error error_date"></span>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label>{{ __('Deduction Month') }} <span class="text-danger">*</span></label>
            @php
                $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
            @endphp
            <select name="month" class="form-control form-control-sm add_input" id="month"
                placeholder="{{ __('Deduction Month') }}">
                <option value="" selected disabled>Choose Month</option>
                @foreach ($months as $month => $monthName)
                    <option value="{{ $month }}"
                        @php $todayMonth = carbon\Carbon::now(); $todayMonth->month; @endphp
                        @if ($month == $salaryAdvance->month) {{ 'selected' }} @endif> {{ $monthName }}</option>
                @endforeach
            </select>

        </div>

        <div class="col-xl-6 col-md-6">
            <label> {{ __('Deduction Year') }} </label>
            <select name="year" class="form-control form-control-sm add_input" id="year"
                placeholder="{{ __('Deduction Year') }}" required>
                <option value="" selected disabled>Choose Year</option>
                @for ($year = 2020; $year <= date('Y') + 5; $year++)
                    <option value="{{ $year }}" @php $today = carbon\Carbon::now(); $today->year; @endphp
                        @if ($salaryAdvance->year == $year) {{ 'selected' }} @endif>{{ $year }}</option>
                @endfor
            </select>
            <span class="error error_year"></span>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xl-12 col-md-12">
            <label>{{ __('Description') }} <span class="text-danger">*</span></label>
            <textarea cols="30" rows="3" name="description" class="form-control form-control-sm add_input ckEditor"
                id="description" placeholder="{{ __('Description') }}"> {{ $salaryAdvance->detail }}</textarea>
            <span class="error error_name"></span>
        </div>

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
                $('.employee-table').DataTable().draw(false);
                $('#editModal').modal('hide');
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
