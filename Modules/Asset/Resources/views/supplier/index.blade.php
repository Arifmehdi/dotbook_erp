@extends('layout.master')
@section('title', 'Assets - ')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush


@section('content')

<div class="body-wraper">
    <div class="sec-name">
        <div class="section-header">
            <h6>Suppliers</h6>
        </div>
        <x-all-buttons>
            <x-add-button :can="'asset_allocation_create'" :text="'New Suppliers'" />
            <x-slot name="after">
                <x-help-button />
            </x-slot>
        </x-all-buttons>
    </div>

    <div class="p-15">
        @can('asset_allocation_view')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table supplierTable">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('menu.action')</th>
                                        <th class="text-start">Supplier Code</th>
                                        <th class="text-start">@lang('menu.name')</th>
                                        <th class="text-start">@lang('menu.phone')</th>
                                        <th class="text-start">Alternative Phone</th>
                                        <th class="text-start">@lang('menu.email')</th>
                                        <th class="text-start">@lang('menu.address')</th>
                                        <th class="text-start">@lang('menu.status')</th>
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
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_supplier')</h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_supplier_form" action="{{ route('assets.supplier.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mt-1">
                        <div class="col-xl-3 col-md-6">
                            <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                            <input required type="text" name="name" class="form-control " data-name="Name" id="name" placeholder="@lang('menu.name')" />
                            <span class="error error_name"></span>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label><strong>@lang('menu.phone') </strong> <span class="text-danger">*</span></label>
                            <input type="text" name="phone" required class="form-control " data-name="Phone" id="phone" placeholder="@lang('menu.phone')" />
                            <span class="error error_phone"></span>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label><strong>Alternative Phone </strong> </label>
                            <input type="text" name="alternative_phone" class="form-control" data-name="Alternative Phone" id="alternative_phone" placeholder="Alternative Phone" />

                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label><strong>@lang('menu.email') </strong> <span class="text-danger">*</span></label>
                            <input type="text" name="email" required class="form-control " data-name="Email" id="email" placeholder="Email" />
                            <span class="error error_email"></span>
                        </div>


                    </div>
                    <div class="form-group row mt-1">

                        <div class="col-xl-3 col-md-6">
                            <label><strong>@lang('menu.address') </strong> <span class="text-danger">*</span></label>
                            <input type="text" name="address" required class="form-control " data-name="Address" id="address" placeholder="Address" />
                            <span class="error error_address"></span>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="row mt-4">
                                <p class="checkbox_input_wrap">
                                    <input type="checkbox" name="status" id="status"> &nbsp;
                                    <b>@lang('menu.status')</b>
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
</div>
<!-- Edit Modal -->
<form id="deleted_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var supplier_table = $('.supplierTable').DataTable({
        "processing": true
        , dom: "lBfrtip"
        , buttons: [{
                extend: 'pdf'
                , text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang("menu.pdf")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }
            , {
                extend: 'excel'
                , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang("menu.excel")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }
            , {
                extend: 'print'
                , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }
        , ]
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('assets.supplier.index') }}"
        , columns: [{
                data: 'action'
                , name: 'action'
            }
            , {
                data: 'supplier_code'
                , name: 'supplier_code'
            }
            , {
                data: 'name'
                , name: 'name'
            }
            , {
                data: 'phone'
                , name: 'phone'
            }
            , {
                data: 'alternative_phone'
                , name: 'alternative_phone'
            }
            , {
                data: 'email'
                , name: 'email'
            }
            , {
                data: 'address'
                , name: 'address'
            }
            , {
                data: 'status'
                , name: 'status'
            }
        ]
        , fnDrawCallback: function() {
            $('.data_preloader').hide();
        }
    });
    supplier_table.buttons().container().appendTo('#exportButtonsContainer');

    $(document).on('submit', '#add_supplier_form', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url
            , type: 'post'
            , data: new FormData(this)
            , contentType: false
            , cache: false
            , processData: false
            , success: function(data) {

                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                $('#addModal').modal('hide');
                $('.supplierTable').DataTable().ajax.reload();
                $('#add_supplier_form')[0].reset();
                $('.add_input').addClass('bdr-red');
            }
            , error: function(err) {

                $('.submit_button').prop('type', 'submit');
                alert('ERROR')
                return;
            }
        });
    });



    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation'
            , 'content': 'Are you sure?'
            , 'buttons': {
                'Yes': {
                    'class': 'yes btn-primary'
                    , 'action': function() {
                        $('#deleted_form').submit();
                    }
                }
                , 'No': {
                    'class': 'no btn-danger'
                    , 'action': function() {

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
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                supplier_table.ajax.reload();
                if (!$.isEmptyObject(data.errorMsg)) {
                    toastr.error(data.errorMsg);
                    return;
                }
                toastr.success(data.responseJSON);
            }
            , error: function(err) {
                toastr.error(err.responseJSON)
                asset_table.ajax.reload();
            }
        });
    });

    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url: url
            , type: 'get'
            , success: function(data) {
                $('.data_preloader').hide();
                $('#edit-content').html(data);
                $('#editModal').modal('show');
            }
            , error: function(err) {
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
