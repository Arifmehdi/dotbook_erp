<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.el-payments.update', $elPayment->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $elPayment->id }}" />
    @csrf


    <div class="row">
        <div class="form-group col-xl-6 col-md-6">

            <label><b> {{ __('Employee Name') }}</b> <span class="text-danger">*</span></label>
            <select name="employee_id" required class="form-control submit_able form-select" id="employee_id"
                autofocus="">
                <option value="">Select</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}"
                        {{ $elPayment->employee_id == $employee->id ? 'selected' : null }}>{{ $employee->name }}
                    </option>
                @endforeach
            </select>
            <span class="error error_employee_id"></span>
        </div>
        <div class="col-xl-6 col-md-6">

            <label> {{ __('Year') }} <span class="text-danger">*</span></label>
            <select name="year" required class="form-control submit_able form-select" id="year" autofocus="">
                <option value="">Select</option>
                @php $years_array = Modules\Core\Utils\DateTimeUtils::years_array(); @endphp

                @foreach ($years_array as $selectable_year)
                    <option value="{{ $selectable_year }}" @if (date('Y') == $selectable_year) selected @endif>
                        {{ $selectable_year }}</option>
                @endforeach
            </select>

            <span class="error error_year"></span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="form-group col-xl-6 col-md-6">
                <label><b> {{ __('EL Days') }}</b> <span class="text-danger">*</span></label>
                <input type="number" name="el_days" class="form-control form-control-sm add_input"
                    data-name="{{ __('Start From') }}" id="el_days" placeholder="{{ __('EL Days') }}"
                    value="{{ $elPayment->el_days }}" required />
                <span class="error error_el_days"></span>
            </div>
            <div class="form-group col-xl-6 col-md-6">
                <label><b> {{ __('Payment Date') }}</b> <span class="text-danger">*</span></label>
                <input type="date" name="payment_date" class="form-control form-control-sm add_input"
                    data-name="{{ __('Payment Date') }}" id="payment_date" placeholder="{{ __('End To') }}"
                    value="{{ $elPayment->payment_date }}" required />
                <span class="error error_payment_date"></span>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="form-group col-xl-6 col-md-6">
                    <label><b> {{ __('Payment Amount') }}</b> <span class="text-danger">*</span></label>
                    <input type="number" name="payment_amount" class="form-control form-control-sm add_input"
                        data-name="{{ __('Start From') }}" id="payment_amount"
                        placeholder="{{ __('Payment Amount') }}" value="{{ $elPayment->payment_amount }}" required />
                    <span class="error error_payment_amount"></span>
                </div>
                <div class="form-group col-xl-6 col-md-6">
                    <label><b> {{ __('Payment Type') }}</b> <span class="text-danger">*</span></label>
                    <select name="payment_type_id" id="payment_type_id"
                        class="form-control form-control-sm add_input form-select">
                        <option value="">Select</option>
                        @foreach ($paymentTypes as $paymentType)
                            <option value="{{ $paymentType->id }}"
                                {{ $elPayment->payment_type_id == $paymentType->id ? 'selected' : null }}>
                                {{ $paymentType->name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_payment_type"></span>
                </div>
            </div>
            <div class="form-group col-xl-12 col-md-12">
                <label><b> {{ __('Remarks') }}</b> <span class="text-danger">*</span></label>
                <textarea name="remarks" id="remarks" cols="30" rows="3"
                    class="form-control form-control-sm add_input  ckEditor" placeholder="{{ __('Enter leave remarks') }}">{{ $elPayment->remarks }}</textarea>
                <span class="error error_remarks"></span>
            </div>

            <div class="row">
                <div class="form-group col-xl-6 col-md-6">

                    <label><b> {{ __('Status') }}</b> <span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm form-select" name="status">
                        <option value="1" @if ($elPayment->status == 1) selected @endif>Allowed</option>
                        <option value="0" @if ($elPayment->status == 0) selected @endif>Not-Allowed</option>
                    </select>
                    <span class="error error_status"></span>

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
                $('.el-payments-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
