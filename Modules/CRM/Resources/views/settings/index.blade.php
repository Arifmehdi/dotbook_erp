@extends('layout.master')
@push('css')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'CRM Settings - ')
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>Settings</h6>
        <x-back-button/>
    </div>
    <div class="p-15">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="settings_submit_form" class="setting_form p-3" action="{{ route('crm.settings.change') }}" method="post">
                            @csrf
                            <div class="form-group row mt-1">
                                <div class="col-md-4 mt-1">
                                    <div class="row mt-4">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="is_active" autocomplete="off" {{ (isset($settings->is_enable) && $settings->is_enable == true) ? 'checked' : '' }}> &nbsp; <b>@lang('menu.is_active')</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('crm.order_request_prefix') </strong></label>
                                    <input type="text" name="prefix" class="form-control es_input" placeholder="@lang('crm.order_request_prefix')" autocomplete="off" value="{{ (isset($settings->order_request_prefix)) ? $settings->order_request_prefix : '' }}">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                        <button class="btn btn-sm btn-success submit_button float-end">@lang('crm.update')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
         $('#settings_submit_form').on('submit', function(e) {
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
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
