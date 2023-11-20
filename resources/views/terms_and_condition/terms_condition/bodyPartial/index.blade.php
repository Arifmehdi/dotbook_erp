<div class="row g-1 locations tab_contant d-none">
    <div class="col-md-12">
        <div class="form_element rounded m-0">
            <div class="element-body">
                <form id="filter_tc_form">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label><strong>@lang('menu.categories') </strong></label>
                            <select name="category_id" class="form-control submit_able form-select" id="f_category_id"
                                autofocus>
                                <option value="">@lang('menu.all')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label><strong></strong></label>
                            <div class="input-group">
                                <button type="submit" class="btn text-white btn-sm btn-info"><i
                                        class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="form_element rounded m-0">
            <div class="element-body">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>
                <div class="table-responsive h-350" id="data-list">
                    <table class="display data_tbl data__table TermsConditionTable">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th class="text-black">@lang('menu.action')</th>
                                <th class="text-black">@lang('menu.title')</th>
                                <th class="text-black">@lang('menu.category')</th>
                                <th class="text-black">@lang('menu.description')</th>
                                <th class="text-black">@lang('menu.created_by')</th>
                                <th class="text-black">@lang('menu.updated_by')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_terms_and_condition')</h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_terms_condition" action="{{ route('terms.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mt-1">
                        <div class="col-md-9">
                            <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control add_input" data-name="Title"
                                id="title" placeholder="@lang('menu.title')" />
                            <span class="error error_title"></span>
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('menu.category') </strong> <span class="text-danger">*</span></label>
                            <select name="categories_id" class="form-control submit_able form-select" id="categories_id"
                                autofocus>
                                <option value="">@lang('menu.select_category')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                            <span class="error error_categories_id"></span>
                        </div>


                    </div>
                    <div class="form-group row mt-1">
                        <div class="col-md-9">
                            <label><strong>@lang('menu.description')</strong></label>
                            <br>
                            <textarea name="description" rows="3" cols="143" id="description" placeholder="Description"></textarea>
                            <span class="error error_description"></span>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                        class="fas fa-spinner"></i></button>
                                <button type="submit"
                                    class="btn btn-sm btn-success me-2 submit_button">@lang('menu.save')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document" id="edit-content">

    </div>
</div>
