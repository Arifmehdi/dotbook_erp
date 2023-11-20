@extends('layout.master')
@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.js')}}" />
@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.units')</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :href="route('products.units.create', 1)" id="addBtn" :can="'units'" :text="'Add Unit'" />
                </x-slot>
                <x-slot name="after">
                    <x-help-button />
                </x-slot>
            </x-all-buttons>
        </div>
    </div>

    <div class="p-15">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table unitTable">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.sl')</th>
                                        <th>@lang('menu.name')</th>
                                        <th>@lang('menu.short_name')</th>
                                        <th>@lang('menu.base_unit')</th>
                                        <th>@lang('menu.multiplier_details')</th>
                                        <th>@lang('menu.actions')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <form id="deleted_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<x-shortcut-key-bar.shortcut-key-bar :items="[
        ['key' => 'Alt + U', 'value' => __('menu.add_unit')],
    ]">
</x-shortcut-key-bar.shortcut-key-bar>

<div class="modal fade" id="unitAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')

<script>
    var unitTable = $('.data_tbl').DataTable({
        processing: true
        , dom: "lBfrtip"
        , buttons: [{
                extend: 'pdf'
                , text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang("menu.pdf")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
            , {
                extend: 'excel'
                , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang("menu.excel")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
            , {
                extend: 'print'
                , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
        , ],

        serverSide: true
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('products.units.index') }}"
        , columns: [{
                data: 'DT_RowIndex'
                , name: 'baseUnit.code_name'
            , }
            , {
                data: 'name'
                , name: 'units.name'
            }
            , {
                data: 'code_name'
                , name: 'units.code_name'
            }
            , {
                data: 'base_unit_name'
                , name: 'baseUnit.name'
            }
            , {
                data: 'multiplierUnitDetails'
                , name: 'baseUnit.code_name'
                , className: 'fw-bold'
            }
            , {
                data: 'action'
            }
        , ]
    , });

    unitTable.buttons().container().appendTo('#exportButtonsContainer');

    // insert branch by ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#addBtn', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url
            , type: 'get'
            , success: function(data) {

                $('#unitAddOrEditModal').html(data);
                $('#unitAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#unit_name').focus();
                }, 500);
            }
            , error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $(document).on('click', '#edit', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url
            , type: 'get'
            , success: function(data) {

                $('#unitAddOrEditModal').empty();
                $('#unitAddOrEditModal').html(data);
                $('#unitAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#unit_name').focus().select();
                }, 500);
            }
            , error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
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
                    'class': 'yes btn-danger'
                    , 'action': function() {
                        $('#deleted_form').submit();
                    }
                }
                , 'No': {
                    'class': 'no btn-primary'
                    , 'action': function() {}
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
            url: url
            , type: 'delete'
            , data: request
            , success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    toastr.error(data);
                    $('.unitTable').DataTable().ajax.reload(null, false);
                } else {

                    toastr.error(data.errorMsg, 'Error');
                }
            }
        });
    });

    document.onkeyup = function() {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.altKey && e.which == 85) {

            $('#addBtn').click();
            return false;
        }
    }

</script>
@endpush
