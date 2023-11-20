@extends('layout.master')
@push('css')
@endpush
@section('title', 'Bank List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name d-flex justify-contents-between align-items-center">
                <div class="name-head">
                    <h6>{{ __('Banks') }}</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :href="route('accounting.banks.create')" id="addBtn" :can="'banks'" :text="__('Add Bank')" />
                    </x-slot>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-startx">@lang('menu.serial')</th>
                                            <th class="text-startx">@lang('menu.bank_name')</th>
                                            <th class="text-startx">@lang('menu.action')</th>
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
        ['key' => 'Alt + B', 'value' => __('Add Bank')]
    ]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <!-- Add Modal -->
    <div class="modal fade" id="bankAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    <script>
        var bankTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [0, 1]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [0, 1]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [0, 1]
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('accounting.banks.index') }}",
            columnDefs: [{
                "targets": [0, 2],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'action',
                name: 'action'
            }, ],
        });

        bankTable.buttons().container().appendTo('#exportButtonsContainer');

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add bank by ajax
            $(document).on('click', '#addBtn', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#bankAddOrEditModal').html(data);
                        $('#bankAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#bank_name').focus();
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

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#bankAddOrEditModal').html(data);
                        $('#bankAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#bank_name').focus().select();
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

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-primary',
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

                        bankTable.ajax.reload(null, false);
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
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
