<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_campaign') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_customer_basic_form" action="{{ route('website.campaign.update', $campaign->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="title"><b>Title </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Title" value="{{ $campaign->title }}" required>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="description"><b>Description</b></label>
                            <div class="col-sm-9">
                                <textarea type="text" id="description" name="description" class="form-control ckEditor" placeholder="Description">{{ $campaign->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="image"><b>Thumbnail</b></label>
                            <div class="col-sm-9">
                                <input type="file" id="image" class="form-control" name="thumbnail"
                                    onchange="thumbnailreadURL(this);" value="{{ $campaign->thumbnail }}">
                                <img src="{{ asset($campaign->thumbnail) }}" id="thumbnail"
                                    class="preview-thumbnail @if ($campaign->thumbnail == null) d-none @endif"
                                    style="height: 45px; width:100px">
                            </div>
                        </div>
                        @foreach (json_decode($campaign->image) as $image)
                            <div class="form-group row p-1 remove-image">
                                <label class="col-sm-3" for="image"><b>Image</b></label>
                                <div class="col-sm-9 d-flex">
                                    <input type="file" id="image" name="images[]" class="form-control"
                                        value="{{ $image }}">
                                    <a href="javascript:void()"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    <img loading="lazy" class="rounded" style="height:30px; width:30px;"
                                        src="{{ asset($image) }}">
                                </div>
                                <input type="hidden" id="image" name="images[]" class="form-control"
                                    value="{{ $image }}">
                            </div>
                        @endforeach
                        <div class="more-image"></div>
                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <a type="submit" class="btn btn-sm btn-success me-0 float-end add-more-image">+</a>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="status"><b>Status </b></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="status">
                                    <option>Select Your Status</option>
                                    <option @if ($campaign->status == 1) selected @endif value="1">Active
                                    </option>
                                    <option @if ($campaign->status == 0) selected @endif value="0">In-Active
                                    </option>
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

    $(".add-more-image").on("click", function() {
        $html = '<div class="form-group row p-1 remove-image">' +
            '<label class="col-sm-3" for="image"><b>Image</b></label>' +
            '<div class="col-sm-9 d-flex">' +
            '<input type="file" id="image" name="images[]" class="form-control">' +
            '<a href="javascript:void()"><i class="fa fa-times" aria-hidden="true"></i>' +
            '</div>' +

            '</div>';
        $(".more-image").append($html);
    });

    $("#submit_customer_basic_form").on("click", ".fa-times", function() {
        $(this).closest(".remove-image").remove();
    });

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
