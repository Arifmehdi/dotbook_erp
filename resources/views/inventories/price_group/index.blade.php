@extends('layout.master')
@push('css')
@endpush
@section('title', 'Selling Price Groups - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.selling_price_group')</h6>
                </div>
                <div class="d-flex gap-2">

                    <x-table-stat :card-id="'info_item'" :items="[
                        [
                            'id' => 'total_selling_price',
                            'name' => __('Total Selling Price Group'),
                            'value' => $total['selling_price'],
                        ],
                        ['id' => 'active', 'name' => __('Active Selling Price'), 'value' => $total['active']],
                        ['id' => 'inactive', 'name' => __('In-active Selling Price'), 'value' => $total['inactive']],
                    ]" />

                    <x-all-buttons>
                        <x-slot name="before">
                            <x-add-button :href="route('product.selling.price.groups.create')" id="addBtn" :can="'selling_price_group'" :text="__('menu.add_price_group')" />
                        </x-slot>

                        <x-slot name="after">
                            <x-help-button />
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-0">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.serial')</th>
                                        <th>@lang('menu.name')</th>
                                        <th>@lang('menu.description')</th>
                                        <th>@lang('menu.actions')</th>
                                        <th>@lang('menu.status')</th>
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

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Alt + P', 'value' => __('menu.add_price_group')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <!-- Add Modal -->
    <div class="modal fade" id="priceGroupAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script>
        var price_group_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            processing: true,
            serverSide: true,
            aaSorting: [
                [0, 'desc']
            ],
            ajax: "{{ route('product.selling.price.groups.index') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'description',
                name: 'description'
            }, {
                data: 'action',
                name: 'action'
            }, {
                data: 'status',
                name: 'status'
            }, ]
        });

        price_group_table.buttons().container().appendTo('#exportButtonsContainer');

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#addBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#priceGroupAddOrEditModal').html(data);
                    $('#priceGroupAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#price_group_name').focus();
                    }, 500);
                },
                error: function(err) {

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
                url: url,
                type: 'get',
                success: function(data) {

                    $('#priceGroupAddOrEditModal').empty();
                    $('#priceGroupAddOrEditModal').html(data);
                    $('#priceGroupAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#price_group_name').focus().select();
                    }, 500);
                },
                error: function(err) {

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
                async: false,
                data: request,
                success: function(data) {

                    toastr.error(data);
                    price_group_table.ajax.reload(null, false);
                    $('#deleted_form')[0].reset();
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        function refresh() {

            $.get("{{ route('groups.change.status') }}", function(data) {

                $('#total_selling_price').text(data.selling_price);
                $('#active').text(data.active);
                $('#inactive').text(data.inactive);
            });
        }
        refresh();

        $(document).on('click', '.change_status', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'get',
                                success: function(data) {
                                    toastr.success(data);
                                    price_group_table.ajax.reload();
                                    refresh();
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-primary',
                        'action': function() {

                        }
                    }
                }
            });
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.altKey && e.which == 80) {

                $('#addBtn').click();
                return false;
            }
        }
    </script>
@endpush
