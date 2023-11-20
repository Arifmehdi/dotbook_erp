<div class="row g-1 categories tab_contant">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="section-header">
                    <h6>@lang('menu.all_category')</h6>
                </div>
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>
                <div class="table-responsive h-350" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th>@lang('menu.serial')</th>
                                <th>@lang('menu.photo')</th>
                                <th>@lang('menu.name')</th>
                                <th>@lang('menu.code')</th>
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

<div class="modal fade" id="categoryAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
