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

    .message_area {
        height: 61vh;
        overflow-y: scroll;
    }

    .message_area .user {
        font-size: 12px;
        font-weight: 600;
        color: #1669af;
    }

    .message-box {
        margin-bottom: 9px;
        border-bottom: 1px solid lightgray;
        padding: 3px 0px;
    }

    .message-box .message-time {
        font-size: 10px;
        margin: 0px !important;
    }

    .message-text p {
        font-size: 12px;
        color: black;
        margin-top: 3px;
    }

    .delete_message {
        color: red;
        font-weight: 700;
    }

    .message_area:last-child {
        border-bottom: 0px solid black;
    }

</style>
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.js')}}" />
@endpush
@section('title', 'User Messages - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <h6>@lang('menu.message_manage')</h6>
            <x-all-buttons>
                @can('assign_todo')
                <div>
                    <a href="{{ route('todo.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-clipboard-list fa-2x"></i><br> @lang('menu.todo')</span></a>
                </div>
                @endcan
                @can('work_space')
                <div>
                    <a href="{{ route('workspace.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-briefcase fa-2x"></i><br> @lang('menu.work_space')</span></a>
                </div>
                @endcan
                @can('memo')
                <div>
                    <a href="{{ route('memos.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-memo-circle-check fa-2x"></i><br> @lang('menu.memo')</span></a>
                </div>
                @endcan
                @can('msg')
                <div>
                    <a href="{{ route('messages.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-message fa-2x"></i><br> @lang('menu.message')</span></a>
                </div>
                @endcan
                <x-help-button />
            </x-all-buttons>
        </div>
    </div>

    <div class="p-15">
        <div class="row g-0">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6>@lang('menu.message')</h6>
                    </div>

                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="row g-0">
                            <div class="message_area" id="chat-box">

                            </div>
                        </div>

                        <div class="row form-header">
                            <div class="col-md-12">
                                <form id="add_message_form" action="{{ route('messages.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            {{-- <input required type="text" name="task_name" id="task_name" class="form-control" placeholder="Wright task and press enter">  --}}
                                            <div class="d-flex">
                                                <div class="attach-document-group">
                                                    <label for="attachedFile"><i class="fa-regular fa-plus"></i></label>
                                                    <input type="file" name="attachment" id="attachedFile">
                                                </div>
                                                <input required type="text" name="description" id="description" class="form-control message-input" placeholder="Type Message" autofocus>
                                                <button type="submit" class="submit_button"><i class="fas ts_preloader d-none" id="ts_preloader"></i><i class="fa-duotone fa-paper-plane"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
@endsection
@push('scripts')

<script>
    // Get all messages by ajax
    function message_list() {
        //$('.data_preloader').show();
        $.ajax({
            url: "{{ route('messages.all') }}"
            , type: 'get'
            , success: function(data) {
                $('.message_area').html(data);
                scroll_down_chat_div();
                //$('.data_preloader').hide();
            }
        });
    }
    message_list();

    //Add message request by ajax
    $(document).on('submit', '#add_message_form', function(e) {
        e.preventDefault();
        $('#ts_preloader').removeClass('d-none');
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                message_list();
                $('#add_message_form')[0].reset();
                toastr.success(data);
                $('#ts_preloader').addClass('d-none');
                scroll_down_chat_div();
            }
        });
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation'
            , 'message': 'Are you sure?'
            , 'buttons': {
                'Yes': {
                    'class': 'yes bg-primary'
                    , 'action': function() {
                        $('#deleted_form').submit();
                    }
                }
                , 'No': {
                    'class': 'no bg-danger'
                    , 'action': function() {
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
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                message_list();
                toastr.error(data);
            }
        });
    });

    function scroll_down_chat_div() {
        var msg_box = $('#chat-box');
        var height = msg_box[0].scrollHeight;
        msg_box.scrollTop(height);
    }

</script>

@endpush
