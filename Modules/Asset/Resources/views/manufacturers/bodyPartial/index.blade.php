<div class="row g-1 manufacturers tab_contant d-none">
    @can('asset_manufacturer_create')
        <div class="col-md-4">
            <div class="card" id="add_form_manufacturer">
                <div class="card-header">
                    <h6>Add Manufacturers </h6>
                </div>

                <div class="card-body">
                    <div class="form-area px-2 pb-2">
                        <form id="add_manufacturer_form" action="{{ route('assets.manufacturers.submit') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label><b>Manufacturer </b> <span class="text-danger">*</span></label>
                                <input required type="text" name="name" class="form-control" id="name" placeholder="Manufacturer name" />
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
            </div>

            <div class="card display-none" id="edit_manufacturer_form_body">
                <div class="card-header">
                    <h6>Edit Manufacturers </h6>
                </div>
                <div class="card-body">

                    <div class="form-area px-2 pb-2" id="edit_manufacturer_form">
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('asset_manufacturer_view')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6>Asset Manufacturers</h6>
                </div>

                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table manufacturerTable">
                            <thead>
                                <tr class="bg-navey-blue">
                                    <th>@lang('menu.sl')</th>
                                    <th>@lang('menu.name')</th>
                                    <th>@lang('menu.actions')</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan

</div>
