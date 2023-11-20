<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_page') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_customer_basic_form" action="{{ route('website.pages.store') }}" method="POST">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="title"><b>Title </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Title" required>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label for="basic-url" class="col-sm-3">Slug</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3">{{ env('WEB_URL') }}</span>
                                    <input type="text" class="form-control" name="slug" id="basic-url"
                                        aria-describedby="basic-addon3">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="description"><b>Description</b></label>
                            <div class="col-sm-9">
                                <textarea type="text" id="description" name="description" class="form-control ckEditor" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="position"><b>Position</b></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="position">
                                    <option disabled>Select Your Position</option>
                                    <option value="top_header">Top Header</option>
                                    <option value="menu_header">Menu Header</option>
                                    <option value="footer">Footer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="status"><b>Status</b></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="status">
                                    <option disabled>Select Your Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">In-Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function thumbnailreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#thumbnail')
                    .attr('src', e.target.result)
                    .width(80)
                    .height(80);
            };
            $('.preview-thumbnail').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#submit_customer_basic_form').on('submit', function(e) {
        e.preventDefault();
        $('.c_loading_button').show();
        var url = $(this).attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.error == 1) {
                    toastr.error("Something went wrong");
                    $('.c_loading_button').hide();
                } else {
                    $('.error').html('');
                    toastr.success(data);
                    $('.c_loading_button').hide();
                    $('#add_customer_basic_modal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                    location.reload();
                }
            },
            error: function(err) {
                $('.c_loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {
                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
