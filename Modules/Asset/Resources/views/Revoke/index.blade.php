@extends('layout.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Assets - ')
@section('content')

<div class="body-wraper">
    <div class="sec-name">
        <div class="section-header">
            <div class="col-md-10">
                <h6>Revokes</h6>
            </div>
        </div>
        <x-all-buttons>
            <x-slot name="after">
                <x-help-button />
            </x-slot>
        </x-all-buttons>
    </div>
    <div class="p-15">
        <div class="row">
            <div class="col-12">
                @can('asset_revokes_view')
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table revokeTable">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('menu.action')</th>
                                        <th class="text-start">Revoked Code</th>
                                        <th class="text-start">Allocated To</th>
                                        <th class="text-start">Asset Name</th>
                                        <th class="text-start">@lang('menu.quantity')</th>
                                        <th class="text-start">Revoked Date</th>
                                        <th class="text-start">Revoked By</th>
                                        <th class="text-start">@lang('menu.reason')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
</div>
<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <!--begin::Form-->
                <form id="" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong>Allocated From </strong> <span class="text-danger">*</span></label>
                            <input required type="text" name="allocated_from" class="form-control add_input" data-name="allocated_from" id="allocated_from" placeholder="Date" autocomplete="off" />
                            <span class="error error_purchase_date"></span>
                        </div>
                        <div class="col-md-3">
                            <label><strong>Allocated Upto </strong> <span class="text-danger">*</span></label>
                            <input required type="text" name="allocated_upto" class="form-control add_input" data-name="allocated_upto" id="allocated_upto" placeholder="Date" autocomplete="off" />
                            <span class="error error_purchase_date"></span>
                        </div>
                        <div class="col-md-3">
                            <label><strong>@lang('menu.description')</strong></label>
                            <textarea name="description" rows="3" cols="68" id="description" placeholder="Asset Description"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Setup ajax for csrf token end.

    // code for data table show
    var allocation_table = $('.revokeTable').DataTable({
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
        ],

        ajax: "{{ route('assets.revoke.index') }}",

        columns: [{
                data: 'action'
            }
            , {
                data: 'revoke_code'
                , name: 'revoke_code'
            }
            , {
                data: 'allocated_to_user'
                , name: 'allocated_to_user'
            }
            , {
                data: 'asset_name'
                , name: 'asset_name'
            }
            , {
                data: 'quantity'
                , name: 'quantity'
            }
            , {
                data: 'revoke_date'
                , name: 'revoke_date'
            }
            , {
                data: 'revokedBy'
                , name: 'revokedBy'
            }
            , {
                data: 'reason'
                , name: 'reason'
            }
        , ]
        , fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });
    allocation_table.buttons().container().appendTo('#exportButtonsContainer');

    // Date picker
    new Litepicker({
        singleMode: true
        , element: document.getElementById('allocated_upto')
        , dropdowns: {
            minYear: new Date().getFullYear() - 50
            , maxYear: new Date().getFullYear() + 100
            , months: true
            , years: true
        }
        , tooltipText: {
            one: 'night'
            , other: 'nights'
        }
        , tooltipNumber: (totalDays) => {
            return totalDays - 1;
        }
        , format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true
        , element: document.getElementById('allocated_from')
        , dropdowns: {
            minYear: new Date().getFullYear() - 50
            , maxYear: new Date().getFullYear() + 100
            , months: true
            , years: true
        }
        , tooltipText: {
            one: 'night'
            , other: 'nights'
        }
        , tooltipNumber: (totalDays) => {
            return totalDays - 1;
        }
        , format: 'DD-MM-YYYY'
    });

    // Delete Part Start
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
                    , 'action': function() {}
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

                allocation_table.ajax.reload();
                toastr.error(data);
            }
        });
    });
    // Delete Part End

    // edit Part Start
    // // Pass Editable Data
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
    // edit Part end

</script>
@endpush
