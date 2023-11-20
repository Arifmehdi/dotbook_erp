@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }
    </style>
@endpush
@section('title', 'Assets - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Licences</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'asset_licenses_create'" :text="'New Licenses'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            @can('asset_licenses_view')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.category') </strong></label>
                                            <select name="f_category" class="form-control submit_able" id="f_category"
                                                autofocus>
                                                <option value="">@lang('menu.all_category')</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>License To Email </strong></label>
                                            <select name="f_license_to_email" class="form-control submit_able"
                                                id="f_license_to_email" autofocus>
                                                <option value="">All License To Email</option>
                                                @foreach ($licenses as $license)
                                                    <option value="{{ $license->licensed_to_email }}">
                                                        {{ $license->licensed_to_email }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.supplier') </strong></label>
                                            <select name="f_supplier" class="form-control submit_able" id="f_supplier"
                                                autofocus>
                                                <option value="">All Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.purchase_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="f_purchase_date" id="f_purchase_date"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.expire_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="f_expire_date" id="f_expire_date"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Termination Date </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="f_termination_date" id="f_termination_date"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-1 col-md-4">
                                            <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('asset_licenses_view')
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table licenseTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">@lang('menu.asset')</th>
                                                <th class="text-start">@lang('menu.category')</th>
                                                <th class="text-end">Licensed to name</th>
                                                <th class="text-end">Licensed to email</th>
                                                <th class="text-end">@lang('menu.supplier')</th>
                                                <th class="text-end">Order Number</th>
                                                <th class="text-end">Purchase order number</th>
                                                <th class="text-end">@lang('menu.purchase_cost')</th>
                                                <th class="text-end">@lang('menu.purchase_date')</th>
                                                <th class="text-end">@lang('menu.expire_date')</th>
                                                <th class="text-end">Termination Date</th>
                                                <th class="text-end">Reassignable</th>
                                                <th class="text-end">Maintained</th>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Licenses</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_license_form" action="{{ route('assets.licenses.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Asset Names And Codes </strong> <span class="text-danger">*</span> </label>
                                <select name="asset_name" required data-name="Asset Name"
                                    class="form-control submit_able " id="asset_name" autofocus>
                                    <option value="">Select Assets</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}">{{ $asset->asset_name }} (
                                            {{ $asset->asset_code }} )</option>
                                    @endforeach
                                </select>
                                <span class="error error_asset_name"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.category') </strong> <span class="text-danger">*</span> </label>
                                <select name="categories_id" required data-name="Category"
                                    class="form-control submit_able " id="categories_id" autofocus>
                                    <option value="">@lang('menu.select_category')</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_categories_id"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Seats </strong></label>
                                <input type="number" name="seats" class="form-control" data-name="Seats"
                                    id="seats" placeholder="Seats" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Manufacturer </strong> <span class="text-danger">*</span> </label>
                                <select name="manufacturer" required data-name="Manufacturer"
                                    class="form-control submit_able" id="manufacturer" autofocus>
                                    <option value="">Select Manufacturer</option>
                                    @foreach ($manufacturer as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_manufacturer"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Licensed to name </strong> <span class="text-danger">*</span> </label>
                                <input type="text" required data-name="Licensed to name" name="licensed_to_name"
                                    class="form-control " data-name="licensed_to_name" id="licensed_to_name"
                                    placeholder="Licensed To Name" />
                                <span class="error error_licensed_to_name"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Licensed to email </strong> <span class="text-danger">*</span> </label>
                                <input type="text" required data-name="Licensed to email" name="licensed_to_email"
                                    class="form-control " data-name="licensed_to_email" id="licensed_to_email"
                                    placeholder="Licensed To Email" />
                                <span class="error error_licensed_to_email"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.supplier') </strong><span class="text-danger">*</span></label>
                                <select required name="supplier" required data-name="Supplier"
                                    class="form-control submit_able" id="supplier" autofocus>
                                    <option value="">@lang('menu.select_supplier')</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_supplier"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Order Number </strong> </label>
                                <input type="number" name="order_number" class="form-control" data-name="order_number"
                                    id="order_number" placeholder="Order Number" />

                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Purchase order number </strong><span class="text-danger">*</span></label>
                                <input required type="text" name="purchase_order_number" class="form-control"
                                    data-name="purchase_order_number" id="purchase_order_number"
                                    placeholder="Purchase order number" />

                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.purchase_cost') </strong><span class="text-danger">*</span> </label>
                                <input required type="number" data-name="SuppPurchase Costlier" name="purchase_cost"
                                    class="form-control " data-name="purchase_cost" id="purchase_cost"
                                    placeholder="@lang('menu.purchase_cost')" />
                                <span class="error error_purchase_cost"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.purchase_date') </strong> <span class="text-danger">*</span></label>
                                <input required type="text" data-name="Purchase Date" name="purchase_date"
                                    class="form-control " id="purchase_date_L_P" placeholder="Date"
                                    autocomplete="off" />
                                <span class="error error_purchase_date"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>@lang('menu.expire_date') </strong><span class="text-danger">*</span></label>
                                <input required type="text" data-name="Expire Date" name="expire_date"
                                    class="form-control " id="expire_date_L_P" autocomplete="off"
                                    placeholder="@lang('menu.expire_date')" />
                                <span class="error error_expire_date"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong>Termination Date </strong></label>
                                <input type="text" name="termination_date" class="form-control "
                                    data-name="termination_date" id="termination_date_L_P" autocomplete="off"
                                    placeholder="Termination Date" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Depreciation </strong></label>
                                <select name="depreciation" class="form-control submit_able form-select"
                                    id="depreciation" autofocus>
                                    <option value="">Select Depreciation</option>
                                    <option value="1">Straight-Line</option>
                                    {{-- <option value="2">Declining Balance Method</option>
                                    <option value="3">Sum-of-the-Years' Digits Method</option>
                                    <option value="4">Units of Production Method</option> --}}
                                </select>
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Product Key </strong></label>
                                <input type="text" name="product_key" class="form-control" data-name="product_key"
                                    id="product_key" placeholder="Product Key" />
                            </div>

                            <div class="col-xl-1 col-md-3">
                                <div class="row mt-4">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="re_assignable" id="re_assignable"> &nbsp;
                                        <b>Reassignable</b>
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-3">
                                <div class="row mt-4">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="maintained" id="maintained"> &nbsp;
                                        <b>Maintained</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <label><strong>@lang('menu.description')</strong></label>
                            <div class="col-md-12">
                                <textarea name="description" rows="3" class="form-control ckEditor" id="description"
                                    placeholder="Description"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none float-end"><i
                                            class="fas fa-spinner"></i></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-start submit_button float-end">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
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
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
    </div>
    <!-- Edit Modal -->
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
        var licenses_table = $('.data_tbl').DataTable({
            "processing": true,
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('assets.licenses.index') }}",
                "data": function(d) {
                    d.f_category = $('#f_category').val();
                    d.f_license_to_email = $('#f_license_to_email').val();
                    d.f_supplier = $('#f_supplier').val();
                    d.f_purchase_date = $('#f_purchase_date').val();
                    d.f_expire_date = $('#f_expire_date').val();
                    d.f_termination_date = $('#f_termination_date').val();
                }
            },
            columns: [{
                data: 'action',
                name: 'Asset Code'
            }, {
                data: 'asset',
                name: 'Asset Code'
            }, {
                data: 'category',
                name: 'Category Name'
            }, {
                data: 'licensed_to_name',
                name: 'Licensed to name'
            }, {
                data: 'licensed_to_email',
                name: 'Licensed to email'
            }, {
                data: 'supplier',
                name: 'Supplier'
            }, {
                data: 'order_number',
                name: 'Order Number'
            }, {
                data: 'purchase_order_number',
                name: 'Purchase Order Number'
            }, {
                data: 'purchase_cost',
                name: 'Purchase Cost'
            }, {
                data: 'purchase_date',
                name: 'Purchase Date'
            }, {
                data: 'expire_date',
                name: 'Expire Date'
            }, {
                data: 'termination_date',
                name: 'Termination Date'
            }, {
                data: 're-assignable',
                name: 'Re-assignable'
            }, {
                data: 'maintained',
                name: 'maintained'
            }],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        licenses_table.buttons().container().appendTo('#exportButtonsContainer');


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).on('submit', '#add_license_form', function(e) {
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

                    $('.error').html('');
                    $('.loading_button').hide();


                    toastr.success(data);
                    $('#add_license_form')[0].reset();
                    $('.submit_button').prop('type', 'submit');
                    $('#addModal').modal('hide');
                    $('.licenseTable').DataTable().ajax.reload();
                    $('.add_input').addClass('bdr-red');
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


        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            licenses_table.ajax.reload();
        });

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
                        'action': function() {

                        }
                    }
                }
            });
        });

        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    licenses_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('purchase_date_L_P'),
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
            element: document.getElementById('termination_date_L_P'),
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
            element: document.getElementById('expire_date_L_P'),
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
            element: document.getElementById('f_purchase_date'),
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
            element: document.getElementById('f_expire_date'),
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
            element: document.getElementById('f_termination_date'),
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


        $(document).on('click', '#edit_id', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('.data_preloader').hide();
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
