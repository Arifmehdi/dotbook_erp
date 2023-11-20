@extends('layout.master')
@push('css')
    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }

        .form-control {
            padding: 4px !important;
        }
    </style>
    
@endpush
@section('title', 'All Memos -')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <h6>@lang('menu.memo_manage')</h6>
                <x-all-buttons>
                    <x-slot name="before">
                        @can('assign_todo')
                            <div>
                                <a href="{{ route('todo.index') }}" class="text-white btn text-white btn-sm"><span><i
                                            class="fa-thin fa-clipboard-list fa-2x"></i><br> @lang('menu.todo')</span></a>
                            </div>
                        @endcan
                        @can('work_space')
                            <div>
                                <a href="{{ route('workspace.index') }}" class="text-white btn text-white btn-sm"><span><i
                                            class="fa-thin fa-briefcase fa-2x"></i><br> @lang('menu.work_space')</span></a>
                            </div>
                        @endcan
                        @can('memo')
                            <div>
                                <a href="{{ route('memos.index') }}" class="text-white btn text-white btn-sm"><span><i
                                            class="fa-thin fa-memo-circle-check fa-2x"></i><br> @lang('menu.memo')</span></a>
                            </div>
                        @endcan
                        @can('msg')
                            <div>
                                <a href="{{ route('messages.index') }}" class="text-white btn text-white btn-sm"><span><i
                                            class="fa-thin fa-message fa-2x"></i><br> @lang('menu.message')</span></a>
                            </div>
                        @endcan
                    </x-slot>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-5">
                    <div class="card" id="add_form">
                        <div class="card-header">
                            <h6>@lang('menu.add_memo') </h6>
                        </div>

                        <div class="card-body">
                            <div class="form-area px-2 pb-2">
                                <form id="add_memo_form" action="{{ route('memos.store') }}">
                                    @csrf
                                    <div class="from-group">
                                        <label><b>@lang('menu.heading') <span class="text-danger">*</span></b></label>
                                        <input required type="text" class="form-control" name="heading"
                                            placeholder="@lang('menu.heading')">
                                    </div>

                                    <div class="from-group mt-1">
                                        <label><b>@lang('menu.description') <span class="text-danger">*</span></b></label>
                                        <textarea name="description" class="form-control ckEditor" cols="10" rows="4" placeholder="Memo Description"></textarea>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="loading-btn-box">
                                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                                        class="fas fa-spinner"></i></button>
                                                <button type="submit"
                                                    class="btn btn-sm btn-success">@lang('menu.save')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card display-none" id="edit_form">
                        <div class="card-header">
                            <h6>{{ __('Edit Memo') }}</h6>
                        </div>

                        <div class="card-body">
                            <div class="form-area px-2 pb-2" id="edit-form-body">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ __('All Memos') }}</h6>
                        </div>

                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.heading')</th>
                                            <th>@lang('menu.description')</th>
                                            <th>@lang('menu.created_date')</th>
                                            <th>@lang('menu.actions')</th>
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
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.share_memo')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_user_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content" id="view_content">

            </div>
        </div>
    </div>
    <!-- Add Modal End-->
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            processing: true,
            serverSide: true,
            aaSorting: [
                [2, 'desc']
            ],
            ajax: "{{ route('memos.index') }}",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            columns: [{
                    data: 'heading',
                    name: 'heading'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        // //Show payment view modal with data
        $(document).on('click', '#view', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#view_content').html(data);
                    $('#showModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        // Show add payment modal with date
        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit-form-body').html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                }
            });
        });

        //Add workspace request by ajax
        $(document).on('submit', '#add_memo_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    } else {
                        $('#add_memo_form')[0].reset();
                        $('.loading_button').hide();
                        toastr.success(data);
                        table.ajax.reload();
                    }
                },
                error: function(error) {
                    // console.log(error.responseJSON.message)
                    toastr.error(error.responseJSON.message)
                    $('.loading_button').hide();
                }
            });
        });

        //Edit workspace request by ajax
        $(document).on('submit', '#edit_memo_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').hide();
                    $('#add_memo_form')[0].reset();
                    $('#add_form').show();
                    $('#edit_form').hide();
                    toastr.success(data);
                    table.ajax.reload();
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        });

        $(document).on('click', '#cancel_edit', function(){
            $('#add_form').show();
            $('#edit_form').hide();
        });

        $(document).on('click', '#add_user_btn', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#addUserModal').modal('show');
                    $('#add_user_modal_body').html(data)
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('submit', '#add_user_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('#addUserModal').modal('hide');
                    $('.loading_button').hide();
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
                        'class': 'yes bg-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no bg-danger',
                        'action': function() {
                            // alert('Deleted canceled.')
                        }
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
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        $('.select2').select2();
    </script>
@endpush
