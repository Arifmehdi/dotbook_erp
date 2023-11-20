<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Source</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_source_form" action="{{ route('crm.source.update', $source->id) }}">
            @csrf
            <div class="form-group">
                <label><b>@lang('menu.source')</b> <span class="text-danger">*</span></label>
                <input required type="text" name="name" class="form-control add_input" data-name="Sources"
                    id="source" value="{{ $source->name }}" placeholder="Source Name" />
                <span class="error error_e_source"></span>
            </div>

            <div class="form-group mt-1">
                <label><b>@lang('menu.description')</b> </label>
                <textarea name="description" class="form-control form-control-sm ckEditor" id="description" cols="10"
                    rows="5" placeholder="@lang('menu.description')">{{ $source->description }}</textarea>
            </div>

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
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#edit_source_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.loading_button').hide();
                    toastr.success(data);
                    $('#editModal').modal('hide');
                    $('.sourceTable').DataTable().ajax.reload();
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            });
        });
    });
</script>
