<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Asset</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_asset_form" action="{{ route('assets.update', $asset->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>Asset Code </strong></label>
                    <input type="text" name="asset_code" class="form-control edit_input" data-name="Asset Code"
                        id="asset_code" placeholder="Asset Code" value="{{ $asset->asset_code }}" disabled />
                    <span class="error error_e_asset_code"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>Asset Name </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="asset_name" class="form-control edit_input" data-name="Asset Name"
                        id="asset_name" placeholder="Asset Name" value="{{ $asset->asset_name }}" />
                    <span class="error error_e_asset_name"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.quantity') </strong> <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control edit_input" data-name="Quantity"
                        id="quantity" placeholder="@lang('menu.quantity')" value="{{ $asset->quantity }}" />
                    <span class="error error_e_quantity"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.unit') </strong> <span class="text-danger">*</span></label>
                    <select name="units_id" class="form-control submit_able form-select" id="units_id" autofocus>
                        <option class="selected" value="">@lang('menu.select_unit')</option>
                        @foreach ($units as $unit)
                            <option {{ $unit->id == $asset->asset_unit_id ? 'SELECTED' : '' }}
                                value="{{ $unit->id }}">
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_units_id"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>@lang('menu.category') </strong> <span class="text-danger">*</span></label>
                    <select name="categories_id" class="form-control submit_able form-select" id="categories_id"
                        autofocus>
                        <option value="">@lang('menu.select_category')</option>
                        @foreach ($asset_categories as $category)
                            <option {{ $category->id == $asset->asset_category_id ? 'selected' : '' }}
                                value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_categories_id"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.location') </strong> <span class="text-danger">*</span></label>
                    <select name="location_id" class="form-control submit_able form-select" id="location_id" autofocus>
                        <option value="">Select Location</option>
                        @foreach ($locations as $location)
                            <option {{ $location->id == $asset->asset_location_id ? 'SELECTED' : '' }}
                                value="{{ $location->id }}">
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_location_id"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>Model </strong> </label>
                    <input type="text" name="model" class="form-control edit_input" data-name="Model"
                        id="model" placeholder="Model Number" value="{{ $asset->model }}" />
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.serial') </strong></label>
                    <input type="text" name="serial_number" class="form-control edit_input" data-name="serial_number"
                        id="serial_number" placeholder="@lang('menu.serial')" value="{{ $asset->serial }}" />
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>Depreciation </strong> </label>
                    <input type="number" name="depreciation" class="form-control edit_input" data-name="depreciation"
                        id="depreciation" placeholder="Depreciation" value="{{ $asset->depreciation }}" />
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.unit') </strong> <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price" class="form-control edit_input" data-name="unit_price"
                        id="unit_price" placeholder="@lang('menu.unit')" value="{{ $asset->unit_price }}" />
                    <span class="error error_e_unit_price"></span>
                </div>


                <div class="col-md-3">
                    <label><strong>@lang('menu.purchase_type') </strong> <span class="text-danger">*</span></label>
                    <select name="purchase_type" class="form-control submit_able form-select" id="purchase_type">
                        <option class="@if ($asset->purchase_type == 'Rented') Selected @endif" value="Rented"> Rented
                        </option>
                        <option class="@if ($asset->purchase_type == 'Leased') Selected @endif" value="Leased">Leased
                        </option>
                        <option class="@if ($asset->purchase_type == 'Owned') Selected @endif" value="Owned">Owned
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.purchase_date') </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="purchase_date" class="form-control edit_input"
                        data-name="purchase_date" id="e_purchase_date" placeholder="@lang('menu.purchase_date')"
                        autocomplete="off" value="{{ $asset->purchase_date }}" />
                    <span class="error error_e_purchase_date"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>@lang('menu.expire_date') </strong></label>
                    <input type="text" name="expire_date" class="form-control edit_input" data-name="expire_date"
                        id="e_expire_date" placeholder="@lang('menu.expire_date')" autocomplete="off"
                        value="{{ $asset->expire_date }}" />
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.image') </strong></label>
                    <input type="hidden" name="old_photo" value="{{ $asset->image }}">
                    <br>
                    @isset($asset->image)
                        <img src="{{ asset('uploads/asset') }}/{{ $asset->image }}" alt=""
                            style="max-width: 120px;">
                    @endisset
                    <input type="file" name="photo" class="form-control edit_input" data-name="photo"
                        id="photo" placeholder="Photo" />
                    <span class="error error_e_photo"></span>
                </div>


                <div class="col-md-3">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="is_allocatable" id="is_allocatable"
                                @if ($asset->is_allocatable == 1) checked @endif>
                            &nbsp;
                            <b>Allocatable</b>
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="is_visible" id="is_visible"
                                @if ($asset->is_visible == 1) checked @endif>
                            &nbsp;
                            <b>Visible</b>
                        </p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <label><strong>Additional Files </strong></label>
                    <input type="file" name="additional_files[]" class="form-control add_input"
                        data-name="additional_files" id="additional_files" placeholder="Additional Files" multiple />

                    @isset($asset->additional_files)
                        @foreach (json_decode($asset->additional_files) as $file)
                            <li>
                                <a href="{{ asset('uploads/asset/additional_files/' . $file) }}" target="_blank">
                                    {{ $file }}
                                </a>
                                <span class="pl-4">&times;</span>
                            </li>
                        @endforeach

                    @endisset

                    <span class="error error_additional_files"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <label><strong>@lang('menu.description')</strong></label>
                <div class="col-mt-12">
                    <textarea name="description" rows="4" cols="145" id="description"
                        value={{ $asset->description }}placeholder="Asset Description">{{ $asset->description }}</textarea>
                </div>
            </div>

            {{-- warranty part start --}}
            <hr>
            <div class="d-flex align-items-center justify-content-between mb-1">
                <h4>@lang('menu.warranty')</h4>
                <div>
                    <button type="button" class="btn btn-primary btn-sm mb-2 p-1" id="addMoreButtonForEdit"><i
                            class="fas fa-plus"></i>@lang('menu.add_more')</button>
                </div>
            </div>
            {{-- warrenty Field --}}

            <div id="edit_warrrenty_area" class="">
                @foreach ($warranties as $warranty)
                    <div class="d-flex justify-content-strech align-items-center gap-2 area-part mb-2">
                        <input type="hidden" name="request_warranties_ids[]" value="{{ $warranty->id }}">

                        <div class="" style="width: 15%">
                            <input type="text" name="w_start_dates[]" class="form-control add_input"
                                data-name="w_start_date" id="w_start_date" placeholder="Warranty Start Date"
                                value="{{ $warranty->start_date }}" required />
                            <span class="error error_warrenty_start"></span>
                        </div>

                        <div class="" style="width: 15%">
                            <input type="number" name="warranty_months[]" class="form-control add_input"
                                data-name="Warranty Month" id="warranty_month" placeholder="Warranty Month"
                                value="{{ $warranty->warranty_month }}" required />
                        </div>

                        <div class="" style="width: 15%">
                            <input type="number" name="additional_costs[]" class="form-control add_input"
                                data-name="additional_cost" id="additional_cost" placeholder="Additional Cost"
                                value="{{ $warranty->additional_cost }}" required />

                        </div>

                        <div class="" style="width: 50%">
                            <textarea name="additional_descriptions[]" rows="1" id="additional_description"
                                placeholder="Additional Description" class="form-control" style="min-height: auto">{{ $warranty->additional_description }}</textarea>
                        </div>

                        <div class="">
                            <button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px"
                                type="button" onclick="this.parentElement.parentElement.remove()">X</button>
                        </div>
                    </div>
                @endforeach

            </div>
            {{-- warranty part end --}}
            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit" class="btn btn-sm btn-success float-end submit_button">Save
                            Changes</button>
                        <button type="reset" data-bs-dismiss="modal"
                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // =================================================================================================
    var editWarrantyChild = '';
    editWarrantyChild += '<div class="d-flex justify-content-strech align-items-center gap-2 area-part mb-2">';
    editWarrantyChild +=
        '<div class="" style="width: 15%"><input type="hidden" name="request_warranties_ids[]" value="-1"> <input type="text" name="w_start_dates[]" class="form-control add_input" data-name="w_start_date" id="w_start_date" placeholder="Warranty Start Date"/>';
    editWarrantyChild += '<span class="error error_warrenty_start"></span>';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="" style="width: 15%">';
    editWarrantyChild +=
        '<input type="number" name="warranty_months[]" class="form-control add_input" data-name="Warranty Month" id="warranty_month" placeholder="Warranty Month" />';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="" style="width: 15%">';
    editWarrantyChild +=
        '<input type="number" name="additional_costs[]" class="form-control add_input" data-name="additional_cost" id="additional_cost" placeholder="Additional Cost" />';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="" style="width: 50%">';
    editWarrantyChild +=
        '<textarea name="additional_descriptions[]" style="min-height: auto" rows="1" id="additional_description" placeholder="Additional Description" class="form-control ckEditor"></textarea>';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="">';
    editWarrantyChild +=
        '<button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px" type="button" onclick="this.parentElement.parentElement.remove()">X</button>';
    editWarrantyChild += '</div>';
    editWarrantyChild += '</div>';

    var addMoreButtonForEdit = document.getElementById('addMoreButtonForEdit');

    var editWarrantyContainer = document.getElementById('edit_warrrenty_area');


    $('#addMoreButtonForEdit').on('click', function(e) {
        e.preventDefault();
        $('#edit_warrrenty_area').append(editWarrantyChild);
    })

    // ====================================================================================================

    $('#edit_asset_form').on('submit', function(e) { // clisk Edit Button
        e.preventDefault(); // what
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
                $('.assetTable').DataTable().ajax.reload();
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_purchase_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('w_start_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });


    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_expire_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });
</script>
