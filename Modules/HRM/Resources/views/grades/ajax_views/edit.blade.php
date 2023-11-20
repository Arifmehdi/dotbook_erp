<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.grades.update', $grade->id) }}"  method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $grade->id }}" />
    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
                <label><strong> {{ __('Grade Name') }}</strong>  <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" data-name="{{ __('Grade Name') }}" id="name" value="{{ $grade->name }}" placeholder="{{ __('Grade Name') }}" required/>
                <span class="error error_name"></span>
        </div>
        <div class="col-xl-3 col-md-6">
            <label><strong> {{ __('Basic Salary') }}</strong>  <span class="text-danger">*</span></label>
            <input type="number" name="basic" class="form-control" data-name="{{ __('Basic Salary') }}" id="name" value="{{ $grade->basic }}" placeholder="{{ __('Basic Salary') }}" required/>
            <span class="error error_basic"></span>
        </div>

        <div class="col-xl-3 col-md-6">
            <label><strong> {{ __('House Rent') }}</strong>  <span class="text-danger">*</span></label>
            <input type="number" name="house_rent" class="form-control" data-name="{{ __('House Rent') }}" id="name" value="{{ $grade->house_rent }}" placeholder="{{ __('House Rent') }}" required/>
            <span class="error error_house_rent"></span>
        </div>

        <div class="col-xl-3 col-md-6">
            <label><strong> {{ __('Medical') }}</strong>  <span class="text-danger">*</span></label>
            <input type="number" name="medical" class="form-control" data-name="{{ __('Medical') }}" id="name" value="{{ $grade->medical }}" placeholder="{{ __('Medical') }}" required/>
            <span class="error error_medical"></span>
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
            <label><strong> {{ __('Food') }}</strong>  <span class="text-danger">*</span></label>
            <input type="number" name="food" class="form-control" data-name="{{ __('Food') }}" id="name" value="{{ $grade->food }}" placeholder="{{ __('Food') }}" required/>
            <span class="error error_food"></span>
        </div>

        <div class="col-xl-3 col-md-6">
            <label><strong> {{ __('Transport') }}</strong>  <span class="text-danger">*</span></label>
            <input type="number" name="transport" class="form-control" data-name="{{ __('Transport') }}" id="name" value="{{ $grade->transport }}" placeholder="{{ __('Transport') }}" required/>
            <span class="error error_transport"></span>
        </div>

        <div class="col-xl-3 col-md-6">
            <label><strong> {{ __('Other') }}</strong>  <span class="text-danger">*</span></label>
            <input type="number" name="other" class="form-control" data-name="{{ __('Other') }}" id="name" value="{{ $grade->other }}" placeholder="{{ __('Other') }}" required/>
            <span class="error error_other"></span>
        </div>
        <div class="form-group row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="loading-btn-box">
                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                    <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                </div>
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
                url: url
                , type: 'PATCH'
                , data: request
                , success: function(data) {
                    toastr.success(data);
                    $('#update_form')[0].reset();
                    $('.loading_button').hide();
                    $('.grade-table').DataTable().draw(false);
                    $('#editModal').modal('hide');
                },
                error: function(error) {
                    $('.loading_button').hide();
                    toastr.error(error.responseJSON.message);
                }
            });
        });
</script>