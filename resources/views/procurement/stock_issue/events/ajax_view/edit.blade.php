<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Event') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_stock_issue_event_form" action="{{ route('stock.issue.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><strong>{{ __('Name') }}</strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="event_name" value="{{ $event->name }}" data-next="event_description" placeholder="{{ __('Event Name') }}">
                        <span class="error error_event_name"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong>{{ __('Description') }}</strong></label>
                        <input name="description" class="form-control" id="event_description" value="{{ $event->description }}"  data-next="stock_issue_event_save_changes" placeholder="{{ __('Description') }}">
                    </div>

                    <div class="mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button stock_issue_event_loading_btn display-none float-end"><i class="fas fa-spinner"></i></button>
                                <button type="submit" id="stock_issue_event_save_changes" class="btn btn-sm btn-success float-start stock_issue_event_submit_button float-end" id="update_btn">{{ __("Save Changes") }}</button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-start float-end me-2">{{ __("Close") }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.stock_issue_event_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.stock_issue_event_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_stock_issue_event_form').on('submit', function(e) {

        e.preventDefault();
        $('.stock_issue_event_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.stock_issue_event_loading_btn').hide();
                $('#stockIssueEventAddOrEditModal').modal('hide');
                toastr.success(data);
                stockIssueEventTable.ajax.reload(false, null);
            },
            error: function(err) {

                $('.stock_issue_event_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support.');
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_event_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });
</script>
