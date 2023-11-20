@extends('layout.master')
@push('css')

    <style>
        .input-group-text {
            font-size: 12px !important;
            margin-top: 1px;
        }
    </style>
@endpush
@section('title', 'Account List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <h6>@lang('menu.accounts')</h6>
                <x-all-buttons>
                    <x-add-button :href="route('accounting.accounts.create.modal')" id="addAccountBtn" :can="'accounts_add'" :text=" __('Add Account')"/>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form" class="px-2">
                                <div class="form-group row">
                                    <div class="col-xl-2 col-md-4">
                                        <select name="account_type" id="f_account_group_id" class="form-control form-select">
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start py-1 px-2"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.account_group')</th>
                                            <th class="text-start">@lang('menu.account_name')</th>
                                            <th class="text-start">@lang('menu.account_number')</th>
                                            <th class="text-start">@lang('menu.bank')</th>
                                            <th class="text-end">@lang('menu.opening_balance')</th>
                                            <th class="text-end">@lang('menu.debit')</th>
                                            <th class="text-end">@lang('menu.credit')</th>
                                            <th class="text-end">@lang('menu.closing_balance')</th>
                                            <th class="text-end">@lang('menu.action')</th>
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
        ['key' => 'Alt + A', 'value' => __('Add Account')]
    ]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <!--Add/Edit Account modal-->
    <div class="modal fade" id="accountAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account modal End-->

    <!--Add/Edit Account Group modal-->
    <div class="modal fade" id="accountGroupAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account Group modal End-->
@endsection
@push('scripts')
    <script>
        $('#f_account_group_id').select2();

        $('#business_location').select2({
            placeholder: "Select a access business location",
            allowClear: true
        });

        var accounts_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }, ],
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('accounting.accounts.index') }}",
                "data": function(d) {
                    d.account_group_id = $('#f_account_group_id').val();
                }
            },

            columns: [{
                data: 'group_name',
                name: 'account_groups.name',
                className: 'fw-bold'
            }, {
                data: 'name',
                name: 'accounts.name'
            }, {
                data: 'account_number',
                name: 'accounts.account_number',
                className: 'fw-bold'
            }, {
                data: 'b_name',
                name: 'banks.name',
                className: 'fw-bold'
            }, {
                data: 'opening_balance',
                name: 'accounts.opening_balance',
                className: 'text-end fw-bold'
            }, {
                data: 'debit',
                name: 'accounts.account_number',
                className: 'text-end fw-bold'
            }, {
                data: 'credit',
                name: 'accounts.account_number',
                className: 'text-end fw-bold'
            }, {
                data: 'closing_balance',
                name: 'accounts.account_number',
                className: 'text-end fw-bold'
            }, {
                data: 'action'
            }, ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        accounts_table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            accounts_table.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {

            $(document).on('click', '#addAccountBtn', function(e) {
                e.preventDefault();
                var group_id = $(this).data('group_id');
                $('#parent_group_id').val(group_id).trigger('change');
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    cache: false,
                    async: false,
                    success: function(data) {

                        $('#accountAddOrEditModal .modal-dialog').remove();
                        $('#accountAddOrEditModal').html(data);
                        $('#accountAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#account_name').focus();
                        }, 500);

                        $('.data_preloader').hide();

                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {

                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });

            $(document).on('click', '#addAccountGroupBtn', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var group_id = $(this).data('group_id');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#accountGroupAddOrEditModal').html(data);
                        $('#accountGroupAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#account_group_name').focus();
                        }, 500);
                    }
                })
            });

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();

                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#accountAddOrEditModal').empty();
                        $('#accountAddOrEditModal').html(data);
                        $('#accountAddOrEditModal').modal('show');

                        $('.data_preloader').hide();

                        setTimeout(function() {

                            $('#account_name').focus().select();
                        }, 500);
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {

                            toastr.error('Server Error. Please contact to the support team.');
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
                    data: request,
                    success: function(data) {

                        accounts_table.ajax.reload(null, false);
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            console.log(e.which);
            if (e.altKey && e.which == 65) {

                $('#addAccountBtn').click();
                return false;
            }
        }
    </script>
@endpush
