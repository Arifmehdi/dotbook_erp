<!-- Edit Modal -->
<form id="shfit_update_form" action="{{ route('hrm.shifts.update', $shift->id) }}"  method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $shift->id }}" />

    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
                <label><strong> {{ __('Shift Name') }}</strong>  <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{$shift->name}}" data-name="{{ __('Shift Name') }}" id="name" placeholder="{{ __('Shift Name') }}" required/>
                <span class="error error_name"></span>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="form-group">
                <label><strong>{{ __('Start Time') }}</strong> : <span class="text-danger">*</span></label>
                <input type="time" name="start_time" class="form-control add_input" value="{{$shift->start_time}}" data-name="Start Time" id="start_time" placeholder="Start Time"/>
                <span class="error error_start_time"></span>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="form-group">
                <label><strong>{{ __('Late Count') }}</strong> :</label>
                <input type="time" name="late_count" class="form-control add_input" value="{{$shift->late_count}}" data-name="Late Count" id="late_count" placeholder="Late Count"/>
                <span class="error error_late_count"></span>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="form-group">
                <label><strong>{{ __('End Time') }}</strong> : <span class="text-danger">*</span></label>
                <input type="time" name="end_time" class="form-control add_input" value="{{$shift->end_time}}" data-name="End Time" id="end_time" placeholder="End Time"/>
                <span class="error error_end_time"></span>
            </div>
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
            <div class="row mt-4">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" name="is_allowed_overtime" id="is_allowed" @if ( $shift->is_allowed_overtime ) checked @endif > &nbsp;
                    <label id="overtime-checkbox" for="is_allowed"><strong>{{ __('Allow Overtime') }}</strong> :</label>
                </p>
            </div>
        </div>
    <div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('#shfit_update_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.error').html('');
            // var formData = new FormData(this);
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'PATCH',
                data: request,
                // data: formData,
                    success: function(data) {
                        toastr.success(data);
                        $('#shfit_update_form')[0].reset();
                        $('.loading_button').hide();
                        $('.department-table').DataTable().draw(false);
                        $('#editModal').modal('hide');
                },
                error: function(error) {
                    $('.loading_button').hide();
                    toastr.error(error.responseJSON.message);
                }
            });
        });
        if($('#is_allowed').is(':checked')){
            $('#is_allowed').val(1);
        }else{
            $('#is_allowed').val(0);
        }

        $(document).on('change','#is_allowed',function(){
            if($(this).is(':checked')){
                $('#is_allowed').val(1);
            }else{
                $('#is_allowed').val(0);
            }
        });
</script>
