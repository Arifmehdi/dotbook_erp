<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_terms_condition')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_terms_condition_form" action="{{ route('terms.update', $terms_condition->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">

                <div class="col-md-9">
                    <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control edit_input" data-name="Title"
                        id="title" placeholder="@lang('menu.title')" value="{{ $terms_condition->title }}" />
                    <span class="error error_e_title"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.category') </strong> <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-control submit_able form-select" id="category_id" autofocus>
                        <option value="">@lang('menu.select_unit')</option>
                        @foreach ($categories as $key => $category)
                            <option {{ $category->id == $terms_condition->category_id ? 'SELECTED' : '' }}
                                value="{{ $category->id }}">
                                {{ $category->category }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_category_id"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-md-9">
                    <label><strong>@lang('menu.description')</strong></label>
                    <textarea name="description" rows="3" class="w-100" id="description" value="" placeholder="Description">{{ $terms_condition->description }}</textarea>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit"
                            class="btn btn-sm btn-success float-end submit_button">@lang('menu.save_change')</button>
                        <button type="reset" data-bs-dismiss="modal"
                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $('#edit_terms_condition_form').on('submit', function(e) { // clisk Edit Button
        e.preventDefault(); // collect old values
        $('.loading_button').show(); // show loading button which id is loading_button
        var url = $(this).attr('action'); // get the url with id. Just alert the url and you will know.

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                toastr.success(data);
                $('.loading_button').hide();
                $('#editModal').modal('hide');
                $('.TermsConditionTable').DataTable().ajax.reload();
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
</script>
