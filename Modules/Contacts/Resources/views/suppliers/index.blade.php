@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .common-btn {
            color: #e7e8f7 !important;
            border: 1px solid #06f526;
            border-radius: 10px;
        }

        .common-btn:hover,
        .common-btn.active {
            background: #ffffff !important;
            color: #0f0f0f !important;
            border: 1px solid #0c0c0c;
            border-radius: 15px;
        }

        .form-title {
            background: transparent;
            color: #0c0c0c;
            text-shadow: 0 0;
            height: 50px;
            line-height: 50px;
            margin: 0px;
        }

        #winbox-1 {
            z-index: 1;
        }

        #winbox-1 .wb-min {
            /* display: none !important; */
        }

        .card-body ul {
            padding-left: 15px;
        }
    </style>
@endpush
@section('title', 'Supplier List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.supplier_list')</h6>
                </div>
                <div class="d-flex gap-2">
                    <x-table-stat :items="[
                        ['id' => 'supplier', 'name' => __('Total Supplier'), 'value' => $total['supplier']],
                        [
                            'id' => 'active_supplier',
                            'name' => __('Active Supplier'),
                            'value' => $total['active_supplier'],
                        ],
                        [
                            'id' => 'inactive_supplier',
                            'name' => __('Inactive Supplier'),
                            'value' => $total['inactive_supplier'],
                        ],
                        ['id' => 'total_debit', 'name' => __('Total Debit'), 'value' => '---'],
                        ['id' => 'total_credit', 'name' => __('Total Credit'), 'value' => '---'],
                        ['id' => 'logged_in_today', 'name' => __('Logged In Today'), 'value' => '---'],
                    ]" />
                    <x-all-buttons :can="'supplier_add'">
                        <x-slot name="before">
                            <a href="#" class="btn text-white btn-sm" id="add_supplier"><span><i
                                        class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.add_suppliers')</span></a>
                            <a href="{{ route('contacts.suppliers.import.create') }}"
                                class="btn text-white btn-sm"><span><i
                                        class="fa-thin fa-file-arrow-down fa-2x"></i><br>@lang('menu.import_suppliers')</span></a>
                        </x-slot>
                        <x-slot name="after">
                            <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span
                                        class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>
        </div>
        <div class="p-15">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6>
                                    <i class="fas fa-spinner"></i> @lang('menu.processing')
                                </h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr class="text-start">
                                            <th>@lang('menu.actions')</th>
                                            <th>@lang('menu.supplier_id')</th>
                                            <th>@lang('menu.prefix')</th>
                                            <th>@lang('menu.name')</th>
                                            <th>@lang('menu.business')</th>
                                            <th>@lang('menu.phone')</th>
                                            <th>@lang('menu.opening_balance')</th>
                                            <th>@lang('menu.debit')</th>
                                            <th>@lang('menu.credit')</th>
                                            <th>@lang('menu.closing_balance')</th>
                                            <th>@lang('menu.status')</th>
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

        {{-- Add mini modal start --}}
        <div class="modal fade" id="add_supplier_basic_modal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="add_supplier_detailed_modal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        {{-- Add mini modal end --}}

        <!-- Edit Supplier Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true"></div>
        <!-- Edit Supplier Modal End-->
    @endsection
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
            integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            var table = $('.data_tbl').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, ],
                "processing": true,
                "serverSide": true,
                aaSorting: [
                    [1, 'asc']
                ],
                ajax: "{{ route('contacts.supplier.index') }}",
                "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                columns: [{
                    data: 'action',
                    name: 'action'
                }, {
                    data: 'contact_id',
                    name: 'contact_id'
                }, {
                    data: 'prefix',
                    name: 'prefix'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'business_name',
                    name: 'business_name'
                }, {
                    data: 'phone',
                    name: 'phone'
                }, {
                    data: 'opening_balance',
                    name: 'opening_balance'
                }, {
                    data: 'debit',
                    name: 'contact_id'
                }, {
                    data: 'credit',
                    name: 'contact_id'
                }, {
                    data: 'closing_balance',
                    name: 'contact_id'
                }, {
                    data: 'status',
                    name: 'status'
                }, ]
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            function sum_table_col(table, class_name) {
                var sum = 0;

                table.find('tbody').find('tr').each(function() {

                    if (parseFloat($(this).find('.' + class_name).data('value'))) {

                        sum += parseFloat(
                            $(this).find('.' + class_name).data('value')
                        );
                    }
                });

                return sum;
            }

            // Setup ajax for csrf token.
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();

                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.get(url, function(data) {

                    $('#editModal').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
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

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg, 'Attention');
                            return;
                        }

                        table.ajax.reload(null, false);
                        refresh();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });

            // Show sweet alert for delete
            function refresh() {

                $.get("{{ route('supplier.statistics') }}", function(data) {

                    $('#supplier').text(data.supplier);
                    $('#active_supplier').text(data.active_supplier);
                    $('#inactive_supplier').text(data.inactive_supplier);
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
                                        table.ajax.reload(null, false);
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

                if (e.ctrlKey && e.which == 13) {

                    $('#addModal').modal('show');
                    setTimeout(function() {

                        $('#name').focus();
                    }, 500);
                    //return false;
                }
            }

            $('#add_supplier').on('click', function(e) {
                e.preventDefault();
                $.get("{{ route('contacts.supplier.create.basic.modal') }}", function(data) {

                    $('#add_supplier_basic_modal').html(data);

                    $('#add_supplier_basic_modal').modal('show');


                    $('#editModal').empty();
                });
            });
        </script>
    @endpush
