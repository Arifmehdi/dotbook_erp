<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_cost_centre_category')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="add_category_form" action="{{ route('cost.centres.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label><strong>@lang('menu.name')</strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="name" data-next="parent_category_id" placeholder="@lang('menu.name')" autocomplete="off" />
                    <span class="error error_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><strong>@lang('menu.under_category')</label>

                    <select name="parent_category_id" class="form-control select2 form-select" id="parent_category_id" data-next="use_in_expense_items">
                        <option value="">@lang('menu.select') @lang('menu.category')</option>
                        @php
                            function Child($subCategories)
                            {
                                foreach ($subCategories as $subCategory) {
                                    echo '<option value="' . $subCategory->id . '">' . $subCategory->name . ($subCategory->parentCategory ? ' - (' . $subCategory->parentCategory->name . ')' : '') . '</option>';

                                    if (count($subCategory->subCategories) > 0) {
                                        Child($subCategory->subCategories);
                                    }
                                }
                            }
                        @endphp

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}{{ $category->parentCategory ? ' - (' . $category->parentCategory->name . ')' : '' }}
                            </option>
                            @php
                                if (count($category->subCategories) > 0) {
                                    Child($category->subCategories);
                                }
                            @endphp
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-1">
                    <label><strong> @lang('menu.use_in_expense_items') </strong></label>
                    <div class="input-group">
                        <select name="use_in_expense_items" class="form-control form-select" id="use_in_expense_items" data-next="use_in_income_items">
                            <option value="0">{{ __("No") }}</option>
                            <option selected value="1">{{ __("Yes") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label><strong> @lang('menu.use_in_income_items') </strong></label>
                    <div class="input-group">
                        <select name="use_in_income_items" class="form-control form-select" id="use_in_income_items" data-next="category_submit_btn">
                            <option value="0">{{ __("No") }}</option>
                            <option selected value="1">{{ __("Yes") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="category_submit_btn" class="btn btn-sm btn-success submit_button float-end">@lang('menu.save')</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
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

    $('#add_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    $('#add_category_form')[0].reset();

                    if (typeof getAjaxList() != undefined) {

                        getAjaxList();
                    }

                    $('#parent_category_id').append('<option value="' + data.id + '">' + data.name +
                        '</option>');
                    toastr.success('Cost Centre Category Added successfully');
                    $('#name').focus();
                    $("#parent_category_id").select2("destroy");
                    $("#parent_category_id").select2();

                    var category_id = $('#category_id').val();
                    if (category_id != undefined) {

                        $('#category_id').append('<option value="' + data.id + '">' + data.name +
                            '</option>');
                        $('#category_id').val(data.id);
                        $('#addOrEditCategoryModal').modal('hide');
                    }

                    var e_category_id = $('#e_category_id').val();
                    if (e_category_id != undefined) {

                        $('#e_category_id').append('<option value="' + data.id + '">' + data.name +
                            '</option>');
                        $('#e_category_id').val(data.id);
                        $('#addOrEditCategoryModal').modal('hide');
                    }
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
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

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
