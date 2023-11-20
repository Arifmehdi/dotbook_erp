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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title', 'All Todo - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <h6>@lang('menu.project_manage')</h6>
                <x-all-buttons>
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
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row mb-1">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.priority') </strong></label>
                                        <select name="priority" class="form-control submit_able form-select" id="priority"
                                            autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="Low">@lang('menu.low')</option>
                                            <option value="Medium">@lang('menu.medium')</option>
                                            <option value="High">@lang('menu.high')</option>
                                            <option value="Urgent">@lang('menu.urgent')</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.status') </strong></label>
                                        <select name="status" class="form-control submit_able form-select" id="status"
                                            autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="New">New</option>
                                            <option value="In-Progress">@lang('menu.in_progress')</option>
                                            <option value="On-Hold">@lang('menu.on_hold')</option>
                                            <option value="Complated">@lang('menu.completed')</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="datepicker"
                                                class="form-control from_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="datepicker2"
                                                class="form-control to_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-1 ">
                <div class="col-xl-4 col-lg-4">
                    <div class="card" id="add_form">
                        <div class="card-header">
                            <h6>Add Todo </h6>
                        </div>

                        <div class="card-body">
                            <div class="form-area px-2 pb-2">
                                <form id="add_todo_form" action="{{ route('todo.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.task') <span class="text-danger">*</span></b></label>
                                            <input required type="text" name="task" class="form-control"
                                                placeholder="@lang('menu.task')">
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.assigned_to') <span class="text-danger">*</span></b></label>
                                            <select required name="user_ids[]" class="form-control select2"
                                                multiple="multiple">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-6">
                                            <label><b>@lang('menu.priority') <span class="text-danger">*</span> </b></label>
                                            <select required name="priority" class="form-control form-select">
                                                <option value="">@lang('menu.select_priority')</option>
                                                <option value="Low">@lang('menu.low')</option>
                                                <option value="Medium">@lang('menu.medium')</option>
                                                <option value="High">@lang('menu.high')</option>
                                                <option value="Urgent">@lang('menu.urgent')</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label><strong>@lang('menu.status') <span class="text-danger">*</span>
                                                </strong></label>
                                            <select required name="status" class="form-control form-select">
                                                <option value="">@lang('menu.select_status')</option>
                                                <option value="New">New</option>
                                                <option value="In-Progress">@lang('menu.in_progress')</option>
                                                <option value="On-Hold">@lang('menu.on_hold')</option>
                                                <option value="Complated">@lang('menu.completed')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.due_date') <span class="text-danger">*</span> </b></label>
                                            <input required type="text" name="due_date" class="form-control"
                                                id="due_date" placeholder="DD-MM-YYYY" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.description') </b></label>
                                            <textarea name="description" class="form-control ckEditor" id="description" cols="10" rows="3"
                                                placeholder="Workspace Description."></textarea>
                                        </div>
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
                            <h6>Edit Todo</h6>
                        </div>

                        <div class="card-body">
                            <div class="form-area px-2 pb-2" id="edit_form_body">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6>All Todo </h6>
                        </div>

                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th>Todo ID</th>
                                            <th>@lang('menu.task')</th>
                                            <th>@lang('menu.priority')</th>
                                            <th>@lang('menu.status')</th>
                                            <th>@lang('menu.due_date')</th>
                                            <th>@lang('menu.assigned_to')</th>
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
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.change_status')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="change_status_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">View Task</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="show_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var table = $('.data_tbl').DataTable({
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
            aaSorting: [
                [0, 'desc']
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "",
                "data": function(d) {
                    d.priority = $('#priority').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'todo_id',
                    name: 'todo_id'
                },
                {
                    data: 'task',
                    name: 'task'
                },
                {
                    data: 'priority',
                    name: 'priority'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'due_date',
                    name: 'due_date'
                },
                {
                    data: 'assigned_by',
                    name: 'users.name'
                },
                {
                    data: 'action'
                },
            ],
            fnDrawCallback: function() {
                $('.data_preloader').hide();
            }
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        //Add Todo request by ajax
        $(document).on('submit', '#add_todo_form', function(e) {
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
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                    } else {
                        $('#add_todo_form')[0].reset();
                        $(".select2").select2().val('').trigger('change');
                        toastr.success(data);
                        table.ajax.reload();
                    }
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_form_body').html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                }
            });
        });

        //Edit Todo request by ajax
        $(document).on('submit', '#edit_todo_form', function(e) {
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
                    toastr.success(data);
                    table.ajax.reload();
                    $('#add_form').show();
                    $('#edit_form').hide();
                }
            });
        });

        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#change_status_modal_body').html(data);
                    $('#changeStatusModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('click', '#show', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#show_modal_body').html(data);
                    $('#showModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        //Edit Todo request by ajax
        $(document).on('submit', '#changes_status_form', function(e) {
            e.preventDefault();
            $('.loading_button2').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button2').hide();
                    toastr.success(data);
                    $('#changeStatusModal').modal('hide');
                    table.ajax.reload();
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
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker2'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>

    <script type="text/javascript">
        $('.select2').select2();
        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('due_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });
    </script>
@endpush
