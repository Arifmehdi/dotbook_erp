<div class="row g-1 categories tab_contant">
    <div class="col-md-4">
        <div class="card" id="add_category_form_div">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('menu.add_category')</h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_category_form" action="{{ route('terms.category.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label><b>@lang('menu.category') </b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="@lang('menu.category')" />
                        <span class="error error_name"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                           <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                <button type="reset" class="btn btn-sm btn-danger float-end me-2">@lang('menu.reset')</button>
                           </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-none" id="edit_category_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('menu.edit_category')</h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_category_form_body"></div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="section-header">
                    <h6>@lang('menu.terms_condition_categories')</h6>
                </div>
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>
                <div class="table-responsive h-350" id="data-list">
                    <table class="display data_tbl data__table categoryTable">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th class="text-black">@lang('menu.name')</th>
                                <th class="text-black">@lang('menu.created_by')</th>
                                <th class="text-black">@lang('menu.actions')</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
