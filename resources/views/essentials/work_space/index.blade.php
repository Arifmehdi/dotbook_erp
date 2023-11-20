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
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.min.css') }}" />
    
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}plugins/image-previewer/jquery.magnify.min.css" />
@endpush
<x-lightpicker />
@section('title', 'All Workspaces - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <h6>@lang('menu.work_space_manage')</h6>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :text="'New Work Space'" />
                        @can('assign_todo')
                            <div>
                                <a href="{{ route('todo.index') }}" class="text-white btn text-white btn-sm">
                                    <span><i class="fa-thin fa-clipboard-list fa-2x"></i><br> @lang('menu.todo')</span></a>
                            </div>
                        @endcan
                        @can('work_space')
                            <div>
                                <a href="{{ route('workspace.index') }}"
                                    class="d-md-block d-none text-white btn text-white btn-sm">
                                    <span><i class="fa-thin fa-briefcase fa-2x"></i><br> @lang('menu.work_space')</span></a>
                            </div>
                        @endcan
                        @can('memo')
                            <div>
                                <a href="{{ route('memos.index') }}" class="text-white btn text-white btn-sm"><span>
                                        <i class="fa-thin fa-memo-circle-check fa-2x"></i><br> @lang('menu.memo')</span></a>
                            </div>
                        @endcan
                        @can('msg')
                            <div>
                                <a href="{{ route('messages.index') }}" class="text-white btn text-white btn-sm"><span>
                                        <i class="fa-thin fa-message fa-2x"></i><br> @lang('menu.message')</span></a>
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
            <div class="row mb-1">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form action="" id="filter_form" method="get">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-3">
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

                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.status') </strong></label>
                                        <select name="status" class="form-control submit_able form-select" id="status"
                                            autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="New">@lang('menu.new')</option>
                                            <option value="In-Progress">@lang('menu.in_progress')</option>
                                            <option value="On-Hold">@lang('menu.on_hold')</option>
                                            <option value="Complated">@lang('menu.completed')</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.date') @lang('menu.range') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input readonly type="text" name="date_range" id="date_range"
                                                class="form-control daterange submit_able_input" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-3">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                                            <th>@lang('menu.entry_date')</th>
                                            <th>@lang('menu.name')</th>
                                            <th>@lang('menu.workspace_id')</th>
                                            <th>@lang('menu.priority')</th>
                                            <th>@lang('menu.status')</th>
                                            <th>@lang('menu.start_date')</th>
                                            <th>@lang('menu.end_date')</th>
                                            <th>@lang('menu.estimated_hours')</th>
                                            <th>@lang('menu.assigned_by')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_work_space')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_work_space_form" action="{{ route('workspace.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>@lang('menu.name') <span class="text-danger">*</span></b></label>
                                <input required type="text" name="name" class="form-control"
                                    placeholder="Workspace Name">
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('menu.assigned_to') <span class="text-danger">*</span></b></label>
                                <select required name="user_ids[]" class="form-control select2 form-select"
                                    multiple="multiple">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</option>
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
                                <label><strong>@lang('menu.status') <span class="text-danger">*</span> </strong></label>
                                <select required name="status" class="form-control form-select">
                                    <option value="">@lang('menu.select_status')</option>
                                    <option value="New">@lang('menu.new')</option>
                                    <option value="In-Progress">@lang('menu.in_progress')</option>
                                    <option value="On-Hold">@lang('menu.on_hold')</option>
                                    <option value="Complated">@lang('menu.completed')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('menu.start_date') <span class="text-danger">*</span> </b></label>
                                <input required type="text" id="start_date" name="start_date"
                                    class="form-control date-input"
                                    value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}"
                                    autocomplete="off">
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('menu.end_date') <span class="text-danger">*</span> </b></label>
                                <input required type="text" name="end_date" id="end_date"
                                    class="form-control date-input"
                                    placeholder="{{ json_decode($generalSettings->business, true)['date_format'] }}"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('menu.description') </b></label>
                                <textarea name="description" class="form-control ckEditor" id="description" cols="10" rows="3"
                                    placeholder="Workspace Description."></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('menu.documents') </b></label>
                                <input type="file" name="documents[]" class="form-control" multiple id="documents" />
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('menu.estimated_hours') </b></label>
                                <input type="text" name="estimated_hours" class="form-control"
                                    placeholder="@lang('menu.estimated_hours')">
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_work_space')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="docsModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.uploaded') @lang('menu.documents')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="document-list-modal"></div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/image-previewer/jquery.magnify.min.js') }}"></script>


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
            aaSorting: [
                [0, 'desc']
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('workspace.index') }}",
                "data": function(d) {
                    d.priority = $('#priority').val();
                    d.status = $('#status').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                data: 'date',
                name: 'date'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'ws_id',
                name: 'ws_id'
            }, {
                data: 'priority',
                name: 'priority'
            }, {
                data: 'status',
                name: 'status'
            }, {
                data: 'start_date',
                name: 'start_date'
            }, {
                data: 'end_date',
                name: 'end_date'
            }, {
                data: 'estimated_hours',
                name: 'estimated_hours'
            }, {
                data: 'assigned_by',
                name: 'users.name'
            }, {
                data: 'action'
            }, ],
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function() {
            table.ajax.reload();
        });

        //Submit filter form by date-range field blur
        $(document).on('blur', '.submit_able_input', function() {
            setTimeout(function() {
                table.ajax.reload();
            }, 800);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function() {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 1000);
        });

        // //Show payment view modal with data
        $(document).on('click', '#view', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            // $.ajax({
            //     url:url,
            //     type:'get',
            //      success:function(date){
            //     }
            // });
        });

        $(document).on('click', '#docs', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('.data_preloader').hide();
                    $('#document-list-modal').html(data);
                    $('#docsModal').modal('show');
                }
            });
        });


        // Show add payment modal with date
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_modal_body').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        //Add workspace request by ajax
        $(document).on('submit', '#add_work_space_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    } else {
                        $('#add_work_space_form')[0].reset();
                        $(".select2").select2().val('').trigger('change');
                        $('.loading_button').hide();
                        $('.modal').modal('hide');
                        toastr.success(data);
                        table.ajax.reload();
                    }
                }
            });
        });

        //Edit workspace request by ajax
        $(document).on('submit', '#edit_work_space_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    } else {
                        $('.loading_button').hide();
                        $('.modal').modal('hide');
                        toastr.success(data);
                        table.ajax.reload();
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

        $(document).on('click', '#delete_doc', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var tr = $(this).closest('tr');
            $('#deleted_doc_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes bg-primary',
                        'action': function() {
                            $('#deleted_doc_form').submit();
                            tr.remove();
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
        $(document).on('submit', '#deleted_doc_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.error(data);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function() {
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            $('.daterange').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                startDate: start,
                endDate: end,
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                        .subtract(1, 'year')
                    ],
                }
            });
            $('.daterange').val('');
        });

        $(document).on('click', '.cancelBtn ', function() {
            $('.daterange').val('');
        });

        $('.select2').select2();
        $('[data-magnify=gallery]').magnify();

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";

        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

        new Litepicker({
            element: document.getElementById('start_date'),
            format: _expectedDateFormat
        });

        new Litepicker({
            element: document.getElementById('end_date'),
            format: _expectedDateFormat
        });
    </script>
@endpush
