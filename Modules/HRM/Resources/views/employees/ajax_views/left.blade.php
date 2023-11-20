<!-- Edit Modal -->
<form id="left_form" action="{{route('hrm.employee.manage', $employee->id)}}"  method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{$employee->id}}" />
    <input type="hidden" name="manageType" value="left" />
    <label><strong> {{ __('Selected Employee') }} </strong></label>
    <div class="col-xl-6 col-md-6">
         <p>ID: {{$employee->employee_id}}</p>
         <p>@lang('menu.name') {{$employee->name}}</p>
         <br>
    </div>
    <div class="col-xl-6 col-md-6">
        <label><strong> {{ __('Left Date') }}</strong> @if(!$employee->left_date) @endif <span class="text-danger">*</span></label>
        <input required type="date" name="left_date" class="form-control" data-name="left_date" id="left_date" placeholder="{{ __('Left Date') }}" @if(!$employee->left_date) @endif/>
        <span class="error error_left_date"></span>
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