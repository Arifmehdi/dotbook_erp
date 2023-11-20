<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Lisence</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_lisence_from" action="{{ route('assets.licenses.update', $licenses->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>Asset Names And Codes </strong> <span class="text-danger">*</span></label>
                    <select required name="asset_name" class="form-control submit_able form-select" id="asset_name"
                        autofocus>
                        <option value="">Select Assets</option>
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}"
                                {{ $asset->id == $licenses->asset_id ? 'SELECTED' : '' }}>{{ $asset->asset_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_asset_name"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.category') </strong> <span class="text-danger">*</span></label>
                    <select required name="categories_id" class="form-control submit_able form-select"
                        id="categories_id" autofocus>
                        <option value="">@lang('menu.select_category')</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $licenses->category_id ? 'SELECTED' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_categories_id"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Seats </strong></label>
                    <input type="number" name="seats" class="form-control add_input" data-name="Seats" id="seats"
                        placeholder="Seats" value="{{ $licenses->seats }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Manufacturer </strong> <span class="text-danger">*</span></label>
                    <select required name="manufacturer" class="form-control submit_able form-select" id="manufacturer"
                        autofocus>
                        <option value="">Select Manufacturer</option>
                        @foreach ($manufacturer as $manufacturer)
                            <option value="{{ $manufacturer->id }}"
                                {{ $licenses->manufacturer_id == $manufacturer->id ? 'SELECTED' : '' }}>
                                {{ $manufacturer->name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_e_manufacturer"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>Licensed to name </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="licensed_to_name" class="form-control add_input"
                        data-name="licensed_to_name" id="licensed_to_name" placeholder="Licensed To Name"
                        value="{{ $licenses->licensed_to_name }}" />
                    <span class="error error_e_licensed_to_name"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Licensed to email </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="licensed_to_email" class="form-control add_input"
                        data-name="licensed_to_email" id="licensed_to_email" placeholder="Licensed To Email"
                        value="{{ $licenses->licensed_to_email }}" />
                    <span class="error error_e_licensed_to_email"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.supplier') : </strong><span class="text-danger">*</span></label>
                    <select required name="supplier" class="form-control submit_able form-select" id="supplier"
                        autofocus>
                        <option value="">@lang('menu.select_supplier')</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ $licenses->supplier_id == $supplier->id ? 'SELECTED' : '' }}>{{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error error_e_supplier"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Order Number </strong> </label>
                    <input type="number" name="order_number" class="form-control add_input" data-name="order_number"
                        id="order_number" placeholder="Order Number" value="{{ $licenses->order_number }}" />

                </div>
            </div>

            <div class="form-group row mt-1">

                <div class="col-xl-3 col-md-6">
                    <label><strong>Purchase order number </strong> </label>
                    <input type="text" name="purchase_order_number" class="form-control add_input"
                        data-name="purchase_order_number" id="purchase_order_number" placeholder="Purchase order number"
                        value="{{ $licenses->purchase_order_number }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.purchase_cost') </strong> <span class="text-danger">*</span></label>
                    <input required type="number" name="purchase_cost" class="form-control add_input"
                        data-name="purchase_cost" id="purchase_cost" placeholder="@lang('menu.purchase_cost')"
                        value="{{ $licenses->purchase_cost }}" />
                    <span class="error error_e_purchase_cost"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.purchase_date') </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="purchase_date" class="form-control add_input"
                        data-name="purchase_date" id="edit_purchase_date" placeholder="Date" autocomplete="off"
                        value="{{ $licenses->purchase_date }}" />
                    <span class="error error_e_purchase_date"></span>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.expire_date') </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="expire_date" class="form-control add_input"
                        data-name="expire_date" id="edit_expire_date" autocomplete="off"
                        placeholder="@lang('menu.expire_date')" value="{{ $licenses->expire_date }}" />
                    <span class="error error_e_expire_date"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>Termination Date </strong></label>
                    <input type="text" name="termination_date" class="form-control add_input"
                        data-name="termination_date" id="edit_termination_date" autocomplete="off"
                        placeholder="Termination Date" value="{{ $licenses->termination_date }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Depreciation </strong> <span class="text-danger">*</span></label>
                    <select required name="depreciation" class="form-control submit_able" id="e_depreciation"
                        autofocus>
                        <option value="">Select Depreciation</option>
                        <option {{ $licenses->depreciation_id == 1 ? 'SELECTED' : '' }} value="1">Straight-Line
                        </option>
                        {{-- <option {{ $licenses->depreciation_id == 2 ? 'SELECTED' : '' }} value="2">Declining Balance Method</option>
                        <option {{ $licenses->depreciation_id == 3 ? 'SELECTED' : '' }} value="3">Sum-of-the-Years' Digits Method</option>
                        <option {{ $licenses->depreciation_id == 4 ? 'SELECTED' : '' }} value="4">Units of Production Method</option> --}}
                    </select>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Product Key </strong> </label>
                    <input type="text" name="product_key" class="form-control add_input" data-name="product_key"
                        id="product_key" placeholder="Product Key" value="{{ $licenses->product_key }}" />
                </div>

                <div class="col-xl-1 col-md-3">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="re_assignable" id="re_assignable"
                                @if ($licenses->re_assignable == 1) checked @endif>
                            &nbsp;
                            <b>Reassignable</b>
                        </p>
                    </div>
                </div>

                <div class="col-xl-2 col-md-3">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="maintained" id="maintained"
                                @if ($licenses->maintained == 1) checked @endif>
                            &nbsp;
                            <b>Maintained</b>
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-group row mt-1">
                <label><strong>@lang('menu.description')</strong></label>
                <div class="col-md-12">
                    <textarea name="description" rows="3" class="form-control ckEditor" id="description"
                        placeholder="Description">{{ $licenses->description }}</textarea>
                </div>

            </div>

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
    new Litepicker({
        singleMode: true,
        element: document.getElementById('edit_termination_date'),
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
        element: document.getElementById('edit_expire_date'),
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
        element: document.getElementById('edit_purchase_date'),
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

    // submit edited form

    $('#edit_lisence_from').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');

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
</script>
