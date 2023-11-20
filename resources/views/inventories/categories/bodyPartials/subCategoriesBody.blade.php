<div class="row g-1 sub-categories tab_contant">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="section-header">
                    <h6>@lang('menu.all_subCategory')</h6>
                </div>
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>
                <div class="table-responsive">
                    <table class="display data_tbl2 data__table w-100">
                        <thead>
                            <tr>
                                <th>@lang('menu.serial')</th>
                                <th>@lang('menu.photo')</th>
                                <th>@lang('menu.subcategory')</th>
                                <th>@lang('menu.parent_category')</th>
                                <th>@lang('menu.description')</th>
                                <th>@lang('menu.actions')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subcategoryAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
