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
                <div class="col-xl-3 col-md-6">
                    <label><strong>Asset Code </strong></label>
                    <input type="text" name="asset_code" class="form-control edit_input" data-name="Asset Code"
                        id="asset_code" placeholder="Asset Code" value="{{ $asset->asset_code }}" disabled />
                    <span class="error error_e_asset_code"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Asset Name </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="asset_name" class="form-control edit_input" data-name="Asset Name"
                        id="asset_name" placeholder="Asset Name" value="{{ $asset->asset_name }}" />
                    <span class="error error_e_asset_name"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.quantity') </strong> <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control edit_input" data-name="Quantity"
                        id="quantity" placeholder="@lang('menu.quantity')" value="{{ $asset->quantity }}" />
                    <span class="error error_e_quantity"></span>
                </div>

                <div class="col-xl-3 col-md-6">
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
                <div class="col-xl-3 col-md-6">
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

                <div class="col-xl-3 col-md-6">
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

                <div class="col-xl-3 col-md-6">
                    <label><strong>Model </strong> </label>
                    <input type="text" name="model" class="form-control edit_input" data-name="Model"
                        id="model" placeholder="Model Number" value="{{ $asset->model }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.serial') </strong></label>
                    <input type="text" name="serial_number" class="form-control edit_input" data-name="serial_number"
                        id="serial_number" placeholder="@lang('menu.serial')" value="{{ $asset->serial }}" />
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.unit') </strong> <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price" class="form-control edit_input" data-name="unit_price"
                        id="unit_price" placeholder="@lang('menu.unit')" value="{{ $asset->unit_price }}" />
                    <span class="error error_e_unit_price"></span>
                </div>

                <div class="col-xl-3 col-md-6">
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

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.purchase_date') </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="purchase_date" class="form-control edit_input"
                        data-name="purchase_date" id="e_purchase_date" placeholder="@lang('menu.purchase_date')"
                        autocomplete="off" value="{{ $asset->purchase_date }}" />
                    <span class="error error_e_purchase_date"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.expire_date') </strong></label>
                    <input type="text" name="expire_date" class="form-control edit_input" data-name="expire_date"
                        id="e_expire_date" placeholder="@lang('menu.expire_date')" autocomplete="off"
                        value="{{ $asset->expire_date }}" />
                </div>
            </div>

            <div class="form-group row mt-1">

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.supplier') </strong> <span class="text-danger">*</span></label>
                    <select required name="supplier_id" class="form-control submit_able form-select"
                        id="supplier_id">
                        <option value="" class="selected">-- @lang('menu.select_supplier') --</option>
                        @foreach ($supplier as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ $asset->asset_supplier_id == $supplier->id ? 'SELECTED' : '' }}>
                                {{ $supplier->name }} ({{ $supplier->supplier_code }})</option>
                        @endforeach

                    </select>
                    <span class="error error_e_supplier_id"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Components </strong></label>
                    <select name="components_id[]" class="form-control select2 form-select" multiple="multiple">
                        <option disabled>Open this select menu</option>
                        <?php
                            $component_ids = json_decode($asset->components, true);
                            if(isset($component_ids)){
                        ?>
                        @foreach ($components as $component)
                            <option value="{{ $component->id }}"
                                {{ in_array($component->id, $component_ids) ? 'SELECTED' : '' }}>
                                {{ $component->name }}
                            </option>
                        @endforeach
                        <?php
                            }else{
                         ?>
                        @foreach ($components as $component)
                            <option value="{{ $component->id }}">{{ $component->name }}</option>
                        @endforeach
                        <?php
                            }
                         ?>
                    </select>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="is_allocatable" id="is_allocatable"
                                @if ($asset->is_allocatable == 1) checked @endif>
                            &nbsp;
                            <b>Allocatable</b>
                        </p>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="is_visible" id="is_visible"
                                @if ($asset->is_visible == 1) checked @endif>
                            &nbsp;
                            <b>Visible</b>
                        </p>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.image') </strong></label>
                    <input type="hidden" name="old_photo" value="{{ $asset->image }}">
                    <br>
                    @if (isset($asset->image) && !empty($asset->image))
                        <span>
                            <img src="{{ asset('uploads/asset') }}/{{ $asset->image }}" alt=""
                                style="max-width: 120px;">
                            <a href="#" class="assetImageFileDelete"
                                data-url="{{ route('assets.image.file.delete', $asset->id) }}"><i
                                    class='far fa-trash-alt text-primary'></i></a>
                        </span>
                    @endif
                    <input type="file" name="photo" class="form-control edit_input" data-name="photo"
                        id="photo" placeholder="Photo" />
                    <span class="error error_e_photo"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Additional Files </strong></label>
                    <input type="file" name="additional_files[]" class="form-control add_input"
                        data-name="additional_files" id="additional_files" placeholder="Additional Files" multiple />

                    @if (isset($asset->additional_files) && !empty($asset->additional_files))
                        @php
                            $additionalFiles = json_decode($asset->additional_files, true);
                        @endphp
                        @if (isset($additionalFiles) && count($additionalFiles) > 0)
                            @foreach ($additionalFiles as $key => $file)
                                <span>
                                    <a href='{{ asset('uploads/asset/additional_files/' . $file) }}'
                                        target='_blank'>{{ $file }}</a>
                                    @if (isset($asset->id) && isset($file) && !empty($file))
                                        <a href="#" class="deleteAdditionalFile"
                                            data-url="{{ route('assets.additional.files.delete', [$asset->id, $file]) }}">
                                            <i class='far fa-trash-alt text-primary'></i>
                                        </a>
                                    @endif
                                    <br>
                                </span>
                            @endforeach
                        @endif
                    @endif
                    <span class="error error_additional_files"></span>
                </div>

            </div>

            <div class="form-group row mt-1">
                <label><strong>@lang('menu.description')</strong></label>
                <div class="col-mt-12">
                    <textarea name="description" rows="4" class="w-100" id="description"
                        value={{ $asset->description }}placeholder="Asset Description">{{ $asset->description }}</textarea>
                </div>
            </div>

            <hr>
            <div class="d-flex align-items-center justify-content-between mb-1">
                <h4>Depreciation</h4>
            </div>
            <div class="form-group row mt-1">

                <div class="col-md-4">
                    <label><strong>Depreciation Method </strong> <span class="text-danger">*</span></label>
                    <input type="hidden" name="depreciation_method_id" value="{{ $depreciation->id }}">
                    <select required name="depreciation_method" class="form-control submit_able"
                        id="depreciation_method">
                        <option class="selected" value="">-- Select Depreciation Method --</option>
                        <option value="1" {{ 1 == $depreciation->dep_method ? 'SELECTED' : '' }}>Straight-Line
                        </option>
                        {{-- <option value="2" {{ 2 == $depreciation->dep_method ? 'SELECTED' : '' }}>Declining
                            Balance Method</option>
                        <option value="3" {{ 3 == $depreciation->dep_method ? 'SELECTED' : '' }}>
                            Sum-of-the-Years' Digits Method</option>
                        <option value="4" {{ 4 == $depreciation->dep_method ? 'SELECTED' : '' }}>Units of
                            Production Method</option> --}}
                    </select>
                    <span class="error error_e_depreciation_method"></span>
                </div>

                <div class="col-md-4">
                    <label><strong>Salvage Value </strong> <span class="text-danger">*</span></label>
                    <input type="number" required name="salvage_value" class="form-control add_input"
                        data-name="salvage_value" id="salvage_value" placeholder="Salvage Value"
                        value="{{ $depreciation->salvage_value }}" />
                    <span class="error error_e_salvage_value"></span>
                </div>

                <div class="col-md-4">
                    <label><strong>Depreciation Year </strong> <span class="text-danger">*</span></label>
                    <input type="number" required name="depreciation_year" class="form-control add_input"
                        data-name="depreciation_year" id="depreciation_year" placeholder="Depreciation Year"
                        value="{{ $depreciation->dep_year }}" />
                    <span class="error error_e_depreciation_year"></span>
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
                        <input type="hidden" name="request_warranty_ids[]" value="{{ $warranty->id }}">

                        <div class="">
                            <input type="date" name="w_start_dates[]" class="form-control add_input"
                                data-name="w_start_dates" id="w_start_dates" placeholder="Warranty Start Date"
                                value="{{ $warranty->start_date }}" required />
                            <span class="error error_e_w_start_dates"></span>
                        </div>

                        <div class="">
                            <input type="number" name="warranty_months[]" class="form-control add_input"
                                data-name="Warranty Month" id="warranty_month" placeholder="Warranty Month"
                                value="{{ $warranty->warranty_month }}" required />
                        </div>

                        <div class="">
                            <input type="number" name="additional_costs[]" class="form-control add_input"
                                data-name="additional_cost" id="additional_cost" placeholder="Additional Cost"
                                value="{{ $warranty->additional_cost }}" required />

                        </div>

                        <div class="">
                            <input name="additional_descriptions[]" id="additional_description"
                                placeholder="Additional Description" class="form-control" style="min-height: auto" value="{{ $warranty->additional_description }}" />
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
    $('.select2').select2({
        placeholder: "Select a access business location",
        allowClear: true
    });

    // =================================================================================================
    var editWarrantyChild = '';
    editWarrantyChild += '<div class="d-flex justify-content-strech align-items-center gap-2 area-part mb-2">';
    editWarrantyChild +=
        '<div class=""><input type="hidden" name="request_warranty_ids[]" value="-1"> <input type="date" required name="w_start_dates[]" class="form-control add_input" data-name="w_start_dates" id="w_start_dates" placeholder="Warranty Start Date"/>';
    editWarrantyChild += '<span class="error error_e_w_start_dates"></span>';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="">';
    editWarrantyChild +=
        '<input type="number" required name="warranty_months[]" class="form-control add_input" data-name="Warranty Month" id="warranty_month" placeholder="Warranty Month" />';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="">';
    editWarrantyChild +=
        '<input type="number" required name="additional_costs[]" class="form-control add_input" data-name="additional_cost" id="additional_cost" placeholder="Additional Cost" />';
    editWarrantyChild += '</div>';
    editWarrantyChild += '<div class="" style="width: 50%">';
    editWarrantyChild +=
        '<input name="additional_descriptions[]" style="min-height: auto" id="additional_description" placeholder="Additional Description" class="form-control" />';
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

                $('.loading_button').hide();

                toastr.success(data);

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

    $('.deleteAdditionalFile').click(function() {
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                toastr.error(data.message);
            },
            error: function(data) {

                toastr.error(data.message);
                return;
            }
        });

        $(this).parent().hide();
    });

    $('.assetImageFileDelete').click(function() {
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                toastr.error(data.message);
            },
            error: function(data) {
                toastr.error(data.message);
                return;
            }
        });

        $(this).parent().hide();
    });
</script>
