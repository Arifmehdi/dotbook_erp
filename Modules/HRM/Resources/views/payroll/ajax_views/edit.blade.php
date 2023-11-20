<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.awards.update', $award->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $award->id }}" />
    @csrf
    <div class="row">
        <div class="form-group col-xl-12 col-md-12">
            <label><b> {{ __('Employee') }}</b> <span class="text-danger">*</span></label><br>
            <select name="employee_id" id="employee_id" class="form-control select2 form-select">
                <option value="">--Choose Employee--</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @if ($award->employee_id == $employee->id) {{ 'selected' }} @endif>
                        {{ $employee->name }} - {{ $employee->employee_id }}</option>
                @endforeach
            </select>
            <span class="error error_employee_id"></span>
        </div>


        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Award Name') }}</b> <span class="text-danger">*</span></label>
            <input type="text" name="award_name" class="form-control form-control-sm add_input" id="award_name"
                data-name="{{ __('Start From') }}" value="{{ $award->award_name }}" id="from"
                placeholder="{{ __('Award Name') }}" />
            <span class="error error_award_name"></span>
        </div>

        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Award By)') }}</b> <span class="text-danger">*</span></label>
            <input type="text" name="award_by" id="award_by" class="form-control form-control-sm add_input"
                data-name="{{ __('Start From') }}" value="{{ $award->award_by }}"
                placeholder="{{ __('Award By') }}" />
            <span class="error error_award_by"></span>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Gift Name)') }}</b> <span class="text-danger">*</span></label>
            <input type="text" name="gift_item" class="form-control form-control-sm add_input"
                data-name="{{ __('Start From') }}" value="{{ $award->gift_item }}" id="gift_item"
                placeholder="{{ __('Gift Name') }}" />
            <span class="error error_gift_item"></span>
        </div>

        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Award Date') }}</b> <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control form-control-sm add_input"
                data-name="{{ __('Award Date') }}" value="{{ $award->date }}" id="date"
                placeholder="{{ __('Award Date') }}" />
            <span class="error error_date"></span>
        </div>
    </div>

    <div class="row"></div>
    <div class="col-xl-12 col-md-12">
        <label> {{ __('Award Details') }} <span class="text-danger"></span></label>
        <textarea name="award_description" id="award_description" cols="30" rows="3"
            class="form-control form-control-sm add_input ckEditor" placeholder="Award Description">{{ $award->award_description }}</textarea>
        <span class="error error_award_description"></span>
    </div>
    </div>

    <input type="hidden" name="status" value="1">

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
        function diffInDay(info1, info2, inclusive = true) {
            var info1 = moment(info1);
            var info2 = moment(info2);
            var diff = moment.duration(info2.diff(info1));
            // var diffInDays = Math.abs(diff.asDays());
            var diffInDays = diff.asDays();
            if (inclusive) {
                diffInDays += 1;
            }

            return diffInDays;
        }


        function calculateDiffAndRender() {
            var formDate = $('#from').val();
            var toDate = $('#to').val();

            var d = diffInDay(formDate, toDate, true);
            $('#num_of_days1').val(d);
        }

        $(document).on('change', '#from', function(e) {
            e.preventDefault();
            calculateDiffAndRender();

        })

        $(document).on('change', '#to', function(e) {
            e.preventDefault();
            calculateDiffAndRender();

        })

        calculateDiffAndRender();

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
                $('.award-table').DataTable().draw(false);
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
