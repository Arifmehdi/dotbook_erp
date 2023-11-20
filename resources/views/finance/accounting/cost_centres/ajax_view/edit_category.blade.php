<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_cost_centre_category')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_category_form" action="{{ route('cost.centres.categories.update', $costCentreCategory->id) }}">
                @csrf
                <div class="form-group">
                    <label><strong>@lang('menu.name')</strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="e_name" value="{{ $costCentreCategory->name }}" data-next="e_parent_category_id" placeholder="@lang('menu.name')" autocomplete="off" />
                    <span class="error error_e_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><strong>@lang('menu.under_category')</label>
                    <select name="parent_category_id" class="form-control select2 form-select" id="e_parent_category_id" data-next="e_use_in_expense_items">
                        <option value="">@lang('menu.select') @lang('menu.category')</option>
                        @php
                            function Child($subCategories, $parentCategoryId)
                            {
                                foreach ($subCategories as $subCategory) {
                                    echo '<option ' . ($subCategory->id == $parentCategoryId ? 'SELECTED' : '') . ' value="' . $subCategory->id . '">' . $subCategory->name . ($subCategory->parentCategory ? ' - (' . $subCategory->parentCategory->name . ')' : '') . '</option>';

                                    if (count($subCategory->subCategories) > 0) {
                                        Child($subCategory->subCategories, $parentCategoryId);
                                    }
                                }
                            }
                        @endphp

                        @foreach ($categories as $category)
                            <option {{ $category->id == $costCentreCategory->parent_category_id ? 'SELECTED' : '' }} value="{{ $category->id }}">
                                {{ $category->name }}{{ $category->parentCategory ? ' - (' . $category->parentCategory->name . ')' : '' }}
                            </option>
                            @php
                                if (count($category->subCategories) > 0) {
                                    Child($category->subCategories, $costCentreCategory->parent_category_id);
                                }
                            @endphp
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-1">
                    <label><strong> @lang('menu.use_in_expense_items')</strong></label>
                    <div class="input-group">
                        <select name="use_in_expense_items" class="form-control form-select" id="e_use_in_expense_items" data-next="e_use_in_income_items">
                            <option value="0">No</option>
                            <option {{ $costCentreCategory->use_in_expense_items == 1 ? 'SELECTED' : '' }} value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label><strong> @lang('menu.use_in_income_items') </strong></label>
                    <div class="input-group">
                        <select name="use_in_income_items" class="form-control form-select" id="e_use_in_income_items" data-next="edit_category_submit_btn">
                            <option value="0">No</option>
                            <option {{ $costCentreCategory->use_in_income_items == 1 ? 'SELECTED' : '' }} value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="form-group pt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="edit_category_submit_btn" class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_changes')</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();
            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:select', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    $('.loading_button').hide();
                } else {

                    if (typeof getAjaxList() != undefined) {

                        getAjaxList();
                    }

                    $('.loading_button').hide();
                    toastr.success(data);
                    $('.modal').modal('hide');
                }
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
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
