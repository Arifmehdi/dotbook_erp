<!-- Edit Modal -->
<form id="employee_resign_form" action="{{route('hrm.employee.manage', $employee->id)}}"  method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{$employee->id}}" />
    <input type="hidden" name="manageType" value="resign" />
    <label><strong> {{ __('Selected Employee') }} </strong></label>
    <div class="col-xl-6 col-md-6">
         <p>ID: {{$employee->employee_id}}</p>
         <p>@lang('menu.name') {{$employee->name}}</p>
         <br>
    </div>
    <div class="col-xl-6 col-md-6">
        <label><strong> {{ __('Resign Date') }}</strong> @if(!$employee->resign_date) @endif<span class="text-danger">*</span></label>
        <input required type="date" name="resign_date" class="form-control" data-name="resign_date" id="resign_date" placeholder="{{ __('Resign Date') }}" @if(!$employee->resign_date) @endif />
        <span class="error error_resign_date"></span>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">{{ __('Update') }}</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('#employee_resign_form').on('submit', function(e) {
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
                $('#employee_resign_form')[0].reset();
                $('.loading_button').hide();
                $('.employee-table').DataTable().draw(false);
                $('#resignModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
    // new Litepicker({
    //         singleMode: true,
    //         element: document.getElementById('resign_date'),
    //         dropdowns: {
    //             minYear: new Date().getFullYear() - 50,
    //             maxYear: new Date().getFullYear() + 100,
    //             months: true,
    //             years: true
    //         },
    //         tooltipText: {
    //             one: 'night',
    //             other: 'nights'
    //         },
    //         tooltipNumber: (totalDays) => {
    //             return totalDays - 1;
    //         },
    //         format: 'DD-MM-YYYY'
    //     });
</script>