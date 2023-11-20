<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.do_approval')</h6>
    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <form id="do_approval_form" action="{{ route('sales.order.do.approval.update', $sale->id) }}" method="post">
        @csrf
        <div class="form-group">
            <label>@lang('menu.do_approval') </label>
            <select name="do_approval" class="form-control form-select" id="purchase_status">
                <option {{ $sale->do_approval == 0 ? 'SELECTED' : '' }} value="0">@lang('menu.pending')</< /option>
                <option {{ $sale->do_approval == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.approved')</option>
            </select>
        </div>

        <div class="form-group row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="loading-btn-box">
                    <button type="button" class="btn btn-sm loading_button display-none"><i
                            class="fas fa-spinner"></i></button>
                    <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                    <button type="reset" data-bs-dismiss="modal"
                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#do_approval_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();

        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.success(data);
                orders_table.ajax.reload(null, false);
                $('#doApprovalModal').modal('hide');
            },
            error: function(err) {

                $('.loading_button').hide();

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });
</script>
