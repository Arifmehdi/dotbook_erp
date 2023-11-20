<div class="row g-1 locations tab_contant d-none">
    @can('asset_locations_create')
        <div class="col-md-4">
            <div class="card" id="add_form_location">
                <div class="card-header">
                    <h6>Add Location </h6>
                </div>

                <div class="card-body">
                    <div class="form-area px-2 pb-2">
                        <form id="add_location_form" action="{{ route('assets.locations.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label><b>@lang('menu.location') </b> <span class="text-danger">*</span></label>
                                <input required type="text" name="name" class="form-control" id="name" placeholder="@lang('menu.location')" />
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

            <div class="card display-none" id="edit_location_form">
                <div class="card-header">
                    <h6>Edit Location </h6>
                </div>
                <div class="card-body">
                    <div class="form-area px-2 pb-2" id="edit_location_form_body"></div>
                </div>
            </div>
        </div>
    @endcan

    @can('asset_locations_view')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6>Asset Locations</h6>
                </div>
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table assetLocationTable">
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
