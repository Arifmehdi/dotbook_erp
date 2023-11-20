@extends('layout.master')
@push('css')
@endpush
@section('title', 'Warrantites - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.warranties')/@lang('menu.guaranties')</h6>
            </div>
            <x-all-buttons>
                <x-add-button :href="route('product.warranties.create')" id="addBtn" :can="'units'" />
                <x-slot name="after">
                    <x-help-button />
                </x-slot>
            </x-all-buttons>
        </div>
    </div>

    <div class="p-15">
        <div class="row g-1">
            <div class="card">
                <div class="card-header">
                    <h6>@lang('menu.warranty')/@lang('menu.guaranty_list')</h6>
                </div>
                <div class="card-body pb-1">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>

                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table w-100">
                            <thead>
                                <tr class="text-center">
                                    <th>@lang('menu.sl')</th>
                                    <th>@lang('menu.name')</th>
                                    <th>@lang('menu.duration')</th>
                                    <th>@lang('menu.description')</th>
                                    <th>@lang('menu.action')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
        ['key' => 'Alt + W', 'value' => __('menu.add_warranty')],
    ]">
</x-shortcut-key-bar.shortcut-key-bar>

<div class="modal fade" id="warrantyAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script>
    var warranties_table = $('.data_tbl').DataTable({
        processing: true
        , serverSide: true
        , dom: "lBfrtip"
        , buttons: [{
                extend: 'print'
                , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
            , {
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
        , ]
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('product.warranties.index') }}"
        , columns: [{
                data: 'DT_RowIndex'
                , name: 'DT_RowIndex'
            }
            , {
                data: 'name'
                , name: 'name'
            }
            , {
                data: 'duration'
                , name: 'duration'
            }
            , {
                data: 'description'
                , name: 'description'
            }
            , {
                data: 'action'
                , name: 'action'
            }
        , ]
    , });

    warranties_table.buttons().container().appendTo('#exportButtonsContainer');

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {

        $(document).on('click', '#addBtn', function(e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url
                , type: 'get'
                , success: function(data) {

                    $('#warrantyAddOrEditModal').html(data);
                    $('#warrantyAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#warranty_name').focus();
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

                    $('#warrantyAddOrEditModal').empty();
                    $('#warrantyAddOrEditModal').html(data);
                    $('#warrantyAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#warranty_name').focus().select();
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
                , 'content': 'Are you sure, you want to delete?'
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

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url
                , type: 'post'
                , async: false
                , data: request
                , success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                    warranties_table.ajax.reload(null, false);
                }
                , error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });
    });

    document.onkeyup = function() {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.altKey && e.which == 87) {

            $('#addBtn').click();
            return false;
        }
    }

</script>
@endpush
