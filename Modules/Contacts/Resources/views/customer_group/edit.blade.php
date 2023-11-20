<form id="edit_group_form" action="{{ route('customers.groups.update', $customer_group->id) }}">
    <input type="hidden" name="id" id="id">
    <div class="form-group mt-2">
        <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control edit_input" data-name="Group name" value="{{ $customer_group->group_name }}" id="e_name" placeholder="Group name" />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-2">
        <label><strong>@lang('menu.calculation_percent') (%) </strong></label>
        <input type="number" step="any" name="calculation_percent" class="form-control" value="{{ $customer_group->calc_percentage }}" id="e_calculation_percent" placeholder="@lang('menu.calculation_percent')" />
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end change_button">@lang('menu.save_change')</button>
                <button type="button" id="close_form" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).on('submit', '#edit_group_form', function(e) {
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
                toastr.success(data);
                $('#edit_group_form')[0].reset();
                $('#add_customer_group_form_div').show();
                $('#edit_customer_group_form').hide();
                $('.loading_button').hide();
                $('.customerTable').DataTable().ajax.reload();
                $('.error').html('');
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

        $(document).on('click', '#close_form', function() {
            $('#add_customer_group_form_div').show();
            $('#edit_customer_group_form').hide();
            $('.error').html('');
        });
</script>
