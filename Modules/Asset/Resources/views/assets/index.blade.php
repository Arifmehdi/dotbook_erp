@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title', 'Assets - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.assets')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'asset_create'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

        </div>

        <div class="p-15">
            @can('asset_view')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <form id="filter_form" class="px-2">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.supplier') </strong></label>
                                            <select name="supplier_id" class="form-control submit_able" id="f_supplier_id"
                                                autofocus>
                                                <option value="">All Supplier</option>
                                                @foreach ($supplier as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                        ({{ $item->supplier_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.location') </strong></label>
                                            <select name="location_id" class="form-control submit_able" id="f_location_id"
                                                autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($locations as $location)
                                                    <option value="{{ $location->id }}">
                                                        {{ $location->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Units </strong></label>
                                            <select name="unit_id" class="form-control submit_able form-select" id="f_unit_id"
                                                autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.categories') </strong></label>
                                            <select name="category_id" class="form-control submit_able" id="f_category_id"
                                                autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($asset_categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="datepicker1"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="datepicker2"
                                                    class="form-control to_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('asset_view')
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table assetTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">Asset Code</th>
                                                <th class="text-start">Asset Name</th>
                                                <th class="text-start">@lang('menu.supplier')</th>
                                                <th class="text-start">Asset Qty</th>
                                                <th class="text-start">Allocated Qty</th>
                                                <th class="text-start">Revoked Qty</th>
                                                <th class="text-start">Current Allocated</th>
                                                <th class="text-start">Unused Assets</th>
                                                <th class="text-start">Model</th>
                                                <th class="text-start">@lang('menu.unit')</th>
                                                <th class="text-start">Asset Category</th>
                                                <th class="text-start">@lang('menu.created_by')</th>
                                                <th class="text-start">@lang('menu.unit')</th>
                                                <th class="text-start">@lang('menu.purchase_date')</th>
                                                <th class="text-start">Allocation Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Asset</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_asset_form" action="{{ route('assets.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Asset Name </strong> <span class="text-danger">*</span></label>
                                <input type="text" required name="asset_name" class="form-control add_input"
                                    data-name="Asset Name" id="Asset_name" placeholder="Asset Name" />
                                <span class="error error_asset_name"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.quantity') </strong> <span class="text-danger">*</span></label>
                                <input type="number" required name="quantity" class="form-control add_input"
                                    data-name="Quantity" id="quantity" placeholder="@lang('menu.quantity')" />
                                <span class="error error_quantity"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.unit') </strong> <span class="text-danger">*</span></label>
                                <select name="units_id" required class="form-control submit_able" id="units_id"
                                    autofocus>
                                    <option value="">@lang('menu.select_unit')</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_units_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.category') </strong> <span class="text-danger">*</span></label>
                                <select required name="categories_id" class="form-control submit_able" id="categories_id"
                                    autofocus>
                                    <option value="">@lang('menu.select_category')</option>
                                    @foreach ($asset_categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_categories_id"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.location') </strong> <span class="text-danger">*</span></label>
                                <select required name="location_id" class="form-control submit_able" id="location_id"
                                    autofocus>
                                    <option value="">Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_location_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Model </strong> </label>
                                <input type="text" name="model" class="form-control add_input" data-name="Model"
                                    id="model" placeholder="Model Number" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.serial') </strong></label>
                                <input type="text" name="serial_number" class="form-control add_input"
                                    data-name="serial_number" id="serial_number" placeholder="@lang('menu.serial')" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.unit') </strong> <span class="text-danger">*</span></label>
                                <input required type="number" name="unit_price" class="form-control add_input"
                                    data-name="unit_price" id="unit_price" placeholder="@lang('menu.unit')" />
                                <span class="error error_unit_price"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.purchase_type') </strong> <span class="text-danger">*</span></label>
                                <select required name="purchase_type" class="form-control submit_able"
                                    id="purchase_type">
                                    <option value="" class="selected">-- @lang('menu.select_purchase_type') --</option>
                                    <option value="Rented">Rented</option>
                                    <option value="Leased">Leased</option>
                                    <option value="Owned">Owned</option>
                                </select>
                                <span class="error error_purchase_type"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.purchase_date') </strong> <span class="text-danger">*</span></label>
                                <input required type="text" name="purchase_date" class="form-control add_input"
                                    data-name="purchase_date" id="purchase_date" placeholder="@lang('menu.purchase_date')"
                                    autocomplete="off" />
                                <span class="error error_purchase_date"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.expire_date') </strong></label>
                                <input type="text" name="expire_date" class="form-control add_input"
                                    data-name="expire_date" id="expire_date" autocomplete="off"
                                    placeholder="@lang('menu.expire_date')" />
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.image') </strong></label>
                                <input type="file" name="photo" class="form-control add_input" data-name="photo"
                                    id="photo" placeholder="Photo" multiple />
                                <span class="error error_photo"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Additional Files </strong></label>
                                <input type="file" name="additional_files[]" class="form-control add_input"
                                    data-name="additional_files" id="additional_files" placeholder="Additional Files"
                                    multiple />
                                <span class="error error_additional_files"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Components </strong></label>
                                <select name="components_id[]" class="form-control select2 form-select"
                                    multiple="multiple">
                                    <option disabled>Open this select menu</option>
                                    @foreach ($components as $component)
                                        <option value="{{ $component->id }}">{{ $component->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="row mt-4">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="is_allocatable" id="is_allocatable"> &nbsp;
                                        <b>Allocatable</b>
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="row mt-4">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="is_visible" id="is_visible"> &nbsp;
                                        <b>Visible</b>
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.supplier') </strong> <span class="text-danger">*</span></label>
                                <select required name="supplier_id" class="form-control submit_able form-select"
                                    id="supplier_id">
                                    <option value="" class="selected">-- @lang('menu.select_supplier') --</option>
                                    @foreach ($supplier as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                            ({{ $supplier->supplier_code }})
                                        </option>
                                    @endforeach

                                </select>
                                <span class="error error_supplier_id"></span>
                            </div>
                        </div>

                        <div clas1s="form-group row mt-1">
                            <label><strong>@lang('menu.description')</strong></label>
                            <div class="col-md-12">
                                <textarea name="description" rows="3" class="w-100 ckEditor" id="description"
                                    placeholder="Asset Description"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h4>Depreciation</h4>
                        </div>
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-4">
                                <label><strong>Depreciation Method </strong> <span class="text-danger">*</span></label>
                                <select required name="depreciation_method" class="form-control submit_able"
                                    id="depreciation_method">
                                    <option class="selected" value="">-- Select Depreciation Method --</option>
                                    <option value="1">Straight-Line</option>
                                    {{-- <option value="2">Declining Balance Method</option>
                                    <option value="3">Sum-of-the-Years' Digits Method</option>
                                    <option value="4">Units of Production Method</option> --}}
                                </select>
                                <span class="error error_depreciation_method"></span>
                            </div>

                            <div class="col-xl-3 col-md-4">
                                <label><strong>Salvage Value </strong> <span class="text-danger">*</span></label>
                                <input required type="number" name="salvage_value" class="form-control add_input"
                                    data-name="salvage_value" id="salvage_value" placeholder="Salvage Value" />
                                <span class="error error_salvage_value"></span>
                            </div>

                            <div class="col-xl-3 col-md-4">
                                <label><strong>Depreciation Year </strong> <span class="text-danger">*</span></label>
                                <input required type="number" name="depreciation_year" class="form-control add_input"
                                    data-name="depreciation_year" id="depreciation_year"
                                    placeholder="Depreciation Year" />
                                <span class="error error_depreciation_year"></span>
                            </div>

                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h4>@lang('menu.warranty')<span class="text-danger">*</span></h4>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm mb-2 p-1" id="addMoreButton"><i
                                        class="fas fa-plus"></i> @lang('menu.add_more')</button>
                            </div>
                        </div>

                        <div id="warrrenty_area" class="">
                            <div class="d-flex justify-content-strech align-items-center gap-2 area-part mb-2">
                                <div class="" style="width: 15%">
                                    <input required type="date" name="w_start_dates[]" class="form-control add_input"
                                        data-name="w_start_dates" placeholder="Warranty Start Date" />
                                    <span class="error error_w_start_dates"></span>
                                </div>

                                <div class="" style="width: 15%">
                                    <input required type="number" name="warranty_months[]"
                                        class="form-control add_input" data-name="Warranty Month" id="warranty_months"
                                        placeholder="Warranty Month" />
                                    <span class="error error_warranty_months"></span>
                                </div>

                                <div class="" style="width: 15%">
                                    <input required type="number" step=0.01 name="additional_costs[]"
                                        class="form-control add_input" data-name="additional_costs" id="additional_costs"
                                        placeholder="Additional Cost" />
                                </div>

                                <div class="" style="width: 50%">
                                    <input name="additional_descriptions[]" id="additional_descriptions"
                                        placeholder="Additional Description" class="form-control" style="min-height: auto" />
                                </div>

                                <div class="">
                                    <button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px"
                                        type="button" onclick="this.parentElement.parentElement.remove()">X</button>
                                </div>
                            </div>
                        </div>
                        {{-- warrenty Field --}}
                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2({
            placeholder: "Select a access business location",
            allowClear: true
        });

        var child = '';
        child += '<div class="d-flex justify-content-strech align-items-center gap-2 area-part mb-2">';
        child +=
            '<div class="" style="width: 15%"> <input required type="date" name="w_start_dates[]" class="form-control add_input" data-name="w_start_dates"  placeholder="Warranty Start Date"/>';
        child += '<span class="error error_warrenty_start"></span>';
        child += '</div>';
        child += '<div class="" style="width: 15%">';
        child +=
            '<input required type="number" name="warranty_months[]" class="form-control add_input" data-name="Warranty Month" id="warranty_months" placeholder="Warranty Month" />';
        child += '</div>';
        child += '<div class="" style="width: 15%">';
        child +=
            '<input required type="number" name="additional_costs[]" class="form-control add_input" data-name="additional_costs" id="additional_costs" placeholder="Additional Cost" />';
        child += '</div>';
        child += '<div class="" style="width: 50%">';
        child +=
            '<input name="additional_descriptions[]" style="min-height: auto" id="additional_descriptions" placeholder="Additional Description" class="form-control" />';
        child += '</div>';
        child += '<div class="">';
        child +=
            '<button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px" type="button" onclick="this.parentElement.parentElement.remove()">X</button>';
        child += '</div>';
        child += '</div>';

        var addMoreButton = document.getElementById('addMoreButton');

        var warrantyContainer = document.getElementById('warrrenty_area');

        $('#addMoreButton').on('click', function(e) {
            e.preventDefault();
            $('#warrrenty_area').append(child);
        })

        var asset_table = $('.data_tbl').DataTable({
            "processing": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('assets.index') }}",
                "data": function(d) {
                    d.supplier_id = $('#f_supplier_id').val();
                    d.location_id = $('#f_location_id').val();
                    d.unit_id = $('#f_unit_id').val();
                    d.category_id = $('#f_category_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columns: [{
                    data: 'action',
                    name: 'Asset Code'
                },
                {
                    data: 'asset_code',
                    name: 'Asset Code'
                },
                {
                    data: 'asset_name',
                    name: 'Asset Name'
                },
                {
                    data: 'supplier_name',
                    name: 'Supplier Name'
                },
                {
                    data: 'quantity',
                    name: 'Quantity'
                },
                {
                    data: 'allocated_quantity',
                    name: 'allocated_quantity'
                },
                {
                    data: 'revoked_quantity',
                    name: 'revoked_quantity'
                },
                {
                    data: 'current_allocated',
                    name: 'current_allocated'
                },
                {
                    data: 'unused_assets',
                    name: 'unused_assets'
                },
                {
                    data: 'model',
                    name: 'Models'
                },
                {
                    data: 'unit_name',
                    name: 'Unit'
                },
                {
                    data: 'my_category_name',
                    name: 'Category'
                },
                {
                    data: 'creator',
                    name: 'Creator'
                },
                {
                    data: 'unit_price',
                    name: 'Unit Prices'
                },
                {
                    data: 'purchase_date',
                    name: 'Purchase Date'
                },
                {
                    data: 'is_allocated',
                    name: 'is_allocated'
                }
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        asset_table.buttons().container().appendTo('#exportButtonsContainer');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('submit', '#add_asset_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.success(data);
                    $('#add_asset_form')[0].reset();
                    $('#addModal').modal('hide');
                    $('.assetTable').DataTable().ajax.reload();
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');

                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }
                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            asset_table.ajax.reload();
        });

        // delete part
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {

                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    asset_table.ajax.reload();
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        return;
                    }
                    toastr.error(data.responseJSON);
                },
                error: function(data) {
                    toastr.error(data.responseJSON)
                    asset_table.ajax.reload();
                }
            });
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('purchase_date'),
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
            element: document.getElementById('expire_date'),
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
            element: document.getElementById('datepicker1'),
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
            element: document.getElementById('datepicker2'),
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

        // Pass Editable Data Moon
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',

                success: function(data) {

                    $('.data_preloader').hide();


                    $('#edit-content').empty();
                    $('#edit-content').html(data);
                    $('#editModal').modal('show');

                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else if (err.status == 500) {

                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });
    </script>
@endpush
