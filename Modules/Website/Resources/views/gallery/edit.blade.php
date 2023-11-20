<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_gallery') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_customer_basic_form" action="{{ route('website.gallery.update', $gallery->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row mt-1">
                    <div class="form-group row p-1">
                        <label class="col-sm-3" for="image"><b>Image</b></label>
                        <div class="col-sm-9">
                            <input type="file" id="image" class="form-control" name="image"
                                onchange="readURL(this);">
                            <img src="{{ asset($gallery->image) }}" id="one"
                                class="preview-image @if ($gallery->image == null) d-none @endif"
                                style="height: 45px; width:100px">
                        </div>
                    </div>
                    <div class="form-group row p-1">
                        <label class="col-sm-3" for="category"><b>Category </b> <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control form-select" id="category" name="category_id" required>
                                <option value="">Select Your Status</option>
                                @foreach ($gallery_categories as $gallery_categorie)
                                    <option @if ($gallery->gallery_category_id == $gallery_categorie->id) selected @endif
                                        value="{{ $gallery_categorie->id }}">{{ $gallery_categorie->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row p-1">
                        <label class="col-sm-3" for="status"><b>Status </b></label>
                        <div class="col-sm-9">
                            <select class="form-control form-select" name="status">
                                <option disabled>Select Your Status</option>
                                <option @if ($gallery->status == 1) selected @endif value="1">Active</option>
                                <option @if ($gallery->status == 0) selected @endif value="0">In-Active
                                </option>
                            </select>
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#one')
                    .attr('src', e.target.result)
                    .width(80)
                    .height(80);
            };
            $('.preview-image').removeClass('d-none');
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
