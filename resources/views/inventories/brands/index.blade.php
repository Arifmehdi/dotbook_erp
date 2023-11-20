@extends('layout.master')
@push('css')@endpush
@section('title', 'All Brands - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <h6>@lang('menu.brands')</h6>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :href="route('product.brands.create')" id="addBtn" :can="'brand'" :text="__('menu.add_brand')" />
                </x-slot>
                {{-- <x-add-button id="addBtn" :can="'brand'" :text="'Add Brand'"/> --}}
                <x-slot name="after">
                    <x-help-button />
                </x-slot>
            </x-all-buttons>
        </div>
    </div>

    <div class="p-15">
        <div class="card">
            <div class="card-header">
                <h6>@lang('menu.all_brands')</h6>
            </div>

            <div class="card-body pb-1">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>

                <div class="table-responsive h-350" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr>
                                <th>@lang('menu.serial')</th>
                                <th>@lang('menu.photo')</th>
                                <th>@lang('menu.name')</th>
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

<x-shortcut-key-bar.shortcut-key-bar :items="[
        ['key' => 'Alt + B', 'value' => __('menu.add_brand')],
    ]">
</x-shortcut-key-bar.shortcut-key-bar>

<div class="modal fade" id="brandAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script>
    // Get all brands by ajax
    var brand_table = $('.data_tbl').DataTable({
        processing: true
        , serverSide: true
        , searchable: true
        , dom: "lBfrtip"
        , buttons: [{
                extend: 'pdf'
                , text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang("menu.pdf")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: [0, 2]
                }
            }
            , {
                extend: 'excel'
                , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang("menu.excel")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: [0, 2]
                }
            }
            , {
                extend: 'print'
                , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: [0, 2]
                }
            }
        , ]
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('product.brands.index') }}"
        , columnDefs: [{
            "targets": [0, 1, 3]
            , "orderable": false
            , "searchable": false
        }]
        , columns: [{
                data: 'DT_RowIndex'
                , name: 'DT_RowIndex'
            }
            , {
                data: 'photo'
                , name: 'photo'
            }
            , {
                data: 'name'
                , name: 'name'
            }
            , {
                data: 'action'
                , name: 'action'
            }
        , ]
    });

    brand_table.buttons().container().appendTo('#exportButtonsContainer');

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

                    $('#brandAddOrEditModal').html(data);
                    $('#brandAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#brand_name').focus();
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

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url
                , type: 'get'
                , success: function(data) {

                    $('#brandAddOrEditModal').empty();
                    $('#brandAddOrEditModal').html(data);
                    $('#brandAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#brand_name').focus().select();
                    }, 500);
                }
                , error: function(err) {

                    $('.data_preloader').hide();
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
                'title': '@lang("brand.delete_alert")'
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

                    brand_table.ajax.reload(null, false);
                    toastr.error(data);
                }
            });
        });
    });

    document.onkeyup = function() {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.altKey && e.which == 66) {

            $('#addBtn').click();
            return false;
        }
    }

</script>
@endpush
