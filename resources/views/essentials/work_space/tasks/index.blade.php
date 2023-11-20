@extends('layout.master')
@push('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}plugins/pace-master/themes/red/pace-theme-fill-left.css"/> --}}
    <style>
        b {
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
        }

        th.task-name {
            width: 75%;
        }

        th.task-assign-to {
            width: 15%;
        }

        th.task-status {
            width: 10%;
        }

        .custom-modify {
            padding: 3px 5px !important;
            line-height: 31px !important;
        }
    </style>
@endpush
@section('title', 'Manage Tasks - ')
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>{{ __('Manage Task') }}</h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <div class="main__content">
                <div class="row g-1">
                    <div class="col-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <div class="col-md-12">
                                    <i class="fas fa-paperclip ms-2"></i> <b class="text-danger">({{ $ws->ws_id }})</b>
                                    {{ $ws->name }}
                                    <div class="px-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <ul class="list-unstyled ws_description">
                                                    <li><b>@lang('menu.start_date'):</b>
                                                        {{ date('d-m-Y', strtotime($ws->start_date)) }}</li>
                                                    <li><b>@lang('menu.end_date') :</b>
                                                        {{ date('d-m-Y', strtotime($ws->end_date)) }}</li>
                                                    <li><b>@lang('menu.estimated_hours') :</b> {{ $ws->estimated_hours }}</li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4">
                                                <ul class="list-unstyled ws_description">
                                                    <li><b>@lang('menu.assigned_by') :</b>
                                                        {{ $ws->admin->prefix . ' ' . $ws->admin->name . ' ' . $ws->admin->last_name }}
                                                    </li>
                                                    <li>
                                                        <b>@lang('menu.assigned_to') :</b>
                                                        @foreach ($ws->ws_users as $ws_user)
                                                            {{ $ws_user->user->prefix . ' ' . $ws_user->user->name . ' ' . $ws_user->user->last_name }},
                                                        @endforeach
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4">
                                                <ul class="list-unstyled ws_description">
                                                    <li><b>@lang('menu.priority') :</b> {{ $ws->priority }}</li>
                                                    <li><b>@lang('menu.status') :</b> {{ $ws->status }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <form id="add_task_form" action="{{ route('workspace.task.store') }}">
                                        @csrf
                                        <input type="hidden" name="ws_id" id="ws_id" value="{{ $ws->id }}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input required type="text" name="task_name" id="task_name"
                                                    class="form-control form-control-sm"
                                                    placeholder="Wright task and press enter">
                                            </div>

                                            <div class="col-md-2">
                                                <select name="task_status" id="task_status"
                                                    class="form-control form-control-sm">
                                                    <option value="In-Progress">@lang('menu.in_progress')</option>
                                                    <option value="Pending">@lang('menu.pending')</option>
                                                    <option value="Completed">@lang('menu.completed')</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mb-2">
                                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.add')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>

                                <table class="table">
                                    {{-- <thead class="d-none">
                                        <tr class="bg-secondary">
                                            <th class="task-name text-white text-start">@lang('menu.task')</th>
                                            <th class="task-assign-to text-white text-start">@lang('menu.assigned_to')</th>
                                            <th class="task-status text-white text-start">@lang('menu.status')</th>
                                        </tr>
                                    </thead> --}}

                                    <tbody id="task_list">

                                    </tbody>
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

@endsection
@push('scripts')
    {{-- <script src="{{ asset('') }}plugins/pace-master/pace.min.js"></script> --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Get all customer by ajax
        function task_list() {
            $('.data_preloader').show();
            //Pace.start();
            $.ajax({
                url: "{{ route('workspace.task.list', $ws->id) }}",
                type: 'get',
                success: function(data) {
                    $('#task_list').html(data);
                    $('.data_preloader').hide();
                    //Pace.stop();
                }
            });
        }
        task_list();

        $(document).on('submit', '#add_task_form', function(e) {
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
                        $('#add_task_form')[0].reset();
                        $('.loading_button').hide();
                        toastr.success(data);
                        task_list();
                    }
                }
            });
        });

        var previous_value = "";
        $(document).on('click', '#edit_task_btn', function(e) {
            e.preventDefault();
            $('.task_area').show();
            $('.edit_task_name').hide();
            $(this).closest('tr').find('.edit_task_name').show();
            previous_value = $(this).closest('tr').find('#edit_task_name').val();
            $(this).closest('tr').find('.task_area').hide();
            $(this).closest('tr').find('.edit_task_name').focus();
        });

        $(document).on('keyup', '#edit_task_name', function(e) {
            // if (e.key == "Enter") $('.save').click();
            // if (e.key == "Escape") $('.cancel').click();
            if (e.key == "Escape") {
                $('.task_area').show();
                $('.edit_task_name').hide();
                $(this).closest('tr').find('#edit_task_name').val(previous_value);
            } else if (e.key == "Enter") {
                $(this).closest('tr').find('.update_task_button').click();
            }
        });

        $(document).on('click', '.update_task_button', function() {
            var value = $(this).closest('tr').find('#edit_task_name').val();
            $(this).closest('tr').find('#task_name').html(value);
            var id = $(this).closest('tr').find('.task_area').data('id');
            $('.task_area').show();
            $('.edit_task_name').hide();
            $.ajax({
                url: "{{ url('essentials/workspaces/tasks/update') }}",
                type: 'post',
                data: {
                    id,
                    value
                },
                success: function(data) {

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
                    task_list();
                    toastr.error(data);
                }
            });
        });

        $(document).on('click', '#assign_user', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var user_id = $(this).data('user_id');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id
                },
                success: function(data) {

                    task_list();
                }
            });
        });

        $(document).on('click', '#assign_user', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var user_id = $(this).data('user_id');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id
                },
                success: function(data) {

                    task_list();
                }
            });
        });

        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var status = $(this).data('status');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    status
                },
                success: function(data) {

                    task_list();
                }
            });
        });

        $(document).on('click', '#change_priority', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var priority = $(this).data('priority');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    priority
                },
                success: function(data) {
                    task_list();
                }
            });
        });
    </script>
@endpush
