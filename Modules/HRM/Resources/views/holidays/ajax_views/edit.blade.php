<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.holidays.update', $holiday->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $holiday->id }}" />
    @csrf
    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Holiday Name') }}</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm add_input"
                data-name="{{ __('Holiday Name') }}" value="{{ $holiday->name }}" id="name"
                placeholder="{{ __('Holiday Name') }}" required />
            <span class="error error_name"></span>
        </div>


        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Holiday Type') }}</b> <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control form-control-sm add_input form-select">
                <option value="">Select</option>
                <option value="Holiday" {{ $holiday->type == 'Holiday' ? 'selected' : null }}>Holiday</option>
                <option value="Offday" {{ $holiday->type == 'Offday' ? 'selected' : null }}>Offday</option>
            </select>
            <span class="error error_type"></span>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('Start From)') }}</b> <span class="text-danger">*</span></label>
            <input type="date" name="from" class="form-control form-control-sm add_input"
                data-name="{{ __('Start From') }}" value="{{ $holiday->from }}" id="from"
                placeholder="{{ __('Start From') }}" required />
            <span class="error error_from"></span>
        </div>

        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('End To') }}</b> <span class="text-danger">*</span></label>
            <input type="date" name="to" class="form-control form-control-sm add_input"
                data-name="{{ __('End To') }}" value="{{ $holiday->to }}" id="to"
                placeholder="{{ __('End To') }}" required />
            <span class="error error_to"></span>
        </div>
    </div>

    <div class="row"></div>
    <div class="form-group col-xl-6 col-md-6">
        <label><b> {{ __('Num Of Days)') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="num_of_days" class="form-control form-control-sm add_input"
            data-name="{{ __('Num Of Days') }}" value="{{ $holiday->num_of_days }}" id="num_of_days1"
            placeholder="{{ __('Num Of Days') }}" required readonly />
        <span class="error error_num_of_days"></span>
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
                $('.holiday-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
