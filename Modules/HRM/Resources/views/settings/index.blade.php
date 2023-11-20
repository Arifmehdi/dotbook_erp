@extends('layout.master')
@push('css')

@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>{{ __("HRM Poilicy (Settings)") }}</h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
            </div>
            <div class="p-15">
                <div class="form_element mt-0">
                    <div class="element-body">
                        <div class="settings_form_area">
                            <div class="row g-2">
                                <div class="col-xl-2 col-md-3">
                                    <div class="settings_side_menu">
                                        <ul class="menus_unorder_list">
                                            <li class="menu_list">
                                                <a class="menu_btn menu_active" data-form="update_card_form"
                                                    href="#">{{ __("ID Card Settings") }}</a>
                                            </li>

                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="general_payroll_form" href="#">{{ __("Payroll Settings") }}</a>
                                            </li>

                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="dashboard_settings_form" href="#">{{ __("HRM Policy") }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xl-10 col-md-9">
                                    <form id="update_card_form" class="setting_form"
                                        action="{{ route('hrm.store.card.settings') }}" method="post"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="setting_form_heading">
                                                <h6 class="text-primary">{{ __("ID Card Settings") }}</h6>
                                            </div>
                                        </div>
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Footer Left Text') }}</strong></label>
                                                <input type="text" name="id_card_settings__footer_left_text" id="id_card_settings__footer_left_text" class="form-control bs_input"
                                                    autocomplete="off" value="{{ $settings['id_card_settings__footer_left_text'] }}">
                                                    <span class="error error_id_card_settings__footer_left_text"></span>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Footer Right Text') }} </strong></label>
                                                <input type="text" name="id_card_settings__footer_right_text" id="id_card_settings__footer_right_text" class="form-control bs_input"
                                                    autocomplete="off" placeholder="Business address"
                                                    value="{{ $settings['id_card_settings__footer_right_text'] }}">
                                                    <span class="error error_id_card_settings__footer_right_text"></span>
                                            </div>


                                            <div class="col-xl-3 col-md-4">
                                                <label><strong>{{ __('Footer Right Image (Auth Sign.)') }} </strong>
                                                <small class="red-label-notice">40 X 110px;</small></label>
                                                <input type="file" name="id_card_settings__footer_right_signature_image" class="form-control id_card_settings__footer_right_signature_image bs_input" placeholder="Business phone number">
                                                <small>Previous logo (if exists) will be replaced</small><br>
                                                    <span class="error error_id_card_settings__footer_right_signature_image"></span>
                                            </div>

                                            <div class="col-xl-1 col-md-2">
                                                @if(isset($settings['id_card_settings__footer_right_signature_image']) && $settings['id_card_settings__footer_right_signature_image'] != null)

                                                    <img src="{{ asset('uploads/hrm/settings/'.$settings['id_card_settings__footer_right_signature_image']) }}"  alt="no image" style="height:70px; width:70px; margin-top: 13px;" class="signature_image">
                                                    @endif
                                            </div>
                                                    <input type="hidden" name="old_signature" value="{{ $settings['id_card_settings__footer_right_signature_image'] }}">


                                                    <div class="col-xl-4 col-md-6">
                                                        <label><strong>{{ __('Backside line one text') }}</strong></label>
                                                            <input type="text" name="id_card_settings__back_line_one" id="id_card_settings__back_line_one" class="form-control" autocomplete="off" value="{{ $settings['id_card_settings__back_line_one'] }}">
                                                            <span class="error error_id_card_settings__back_line_one"></span>
                                                    </div>


                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Backside company short text') }}</strong></label>
                                                <input type="text" name="id_card_settings__back_company_short_text" id="id_card_settings__back_company_short_text" class="form-control bs_input" placeholder="Business email address" value="{{ $settings['id_card_settings__back_company_short_text'] }}">
                                                <span class="error error_id_card_settings__back_company_short_text"></span>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Backside block 1 header') }}</strong></label>
                                                <input type="text" name="id_card_settings__block1_header" id="id_card_settings__block1_header" class="form-control bs_input"
                                                    autocomplete="off" value="{{ $settings['id_card_settings__block1_header'] }}">
                                                <span class="error error_id_card_settings__block1_header"></span>
                                            </div>



                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Backside block 1 description') }} </strong></label>
                                                <input type="text" class="form-control" name="id_card_settings__block1_description" id="id_card_settings__block1_description" value="{{ $settings['id_card_settings__block1_description'] }}">
                                                <span class="error error_id_card_settings__block1_description"></span>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Backside block 2 header') }}</strong></label>
                                                <input type="text" name="id_card_settings__block2_header" id="id_card_settings__block2_header" class="form-control bs_input"
                                                autocomplete="off"
                                                value="{{ $settings['id_card_settings__block2_header'] }}">
                                                <span class="error error_id_card_settings__block2_header"></span>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Backside block 2 description') }}</strong></label>
                                                <input type="text" name="id_card_settings__block2_description" id="id_card_settings__block2_description" class="form-control bs_input" autocomplete="off"
                                                value="{{ $settings['id_card_settings__block2_description'] }}">
                                                <span class="error error_id_card_settings__block2_description"></span>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="loading-btn-box">
                                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                                    <button class="btn btn-success submit_button">@lang('menu.save_change')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <form id="general_payroll_form" class="setting_form display-none"
                                        action="{{ route('hrm.store.payroll.settings') }}" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="setting_form_heading">
                                                <h6 class="text-primary">{{ __("Payroll Settings") }}</h6>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Prepared By (Text)') }}</strong><span class="text-danger">*</span></label>
                                                <input type="text" name="payroll_settings__prepared_by_text" id="payroll_settings__prepared_by_text" class="form-control payroll_bs_input" autocomplete="off"
                                                    placeholder="{{ __('Prepared By (Text)') }}"
                                                    value="{{ ($settings['payroll_settings__prepared_by_text'] ?? null) }}">
                                                    <span class="error error_payroll_settings__prepared_by_text"></span>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Prepared By Person') }} </strong></label>
                                                <input type="text" name="payroll_settings__prepared_by_person" id="payroll_settings__prepared_by_person" class="form-control payroll_bs_input" autocomplete="off"
                                                value="{{ ($settings['payroll_settings__prepared_by_person'] ?? null) }}">
                                                <span class="error error_payroll_settings__prepared_by_person"></span>
                                            </div>

                                            <div class="col-xl-3 col-md-4">
                                                <label><strong>{{ __('Prepared by signature (Image)') }} </strong><span class="text-danger">*</span></label>
                                                <input type="file" name="payroll_settings__prepared_by_signature"  class="form-control payroll_settings__prepared_by_signature payroll_bs_input" autocomplete="off" >
                                                <span class="error error_payroll_settings__prepared_by_signature"></span>
                                            </div>

                                        <div class="col-xl-1 col-md-2">
                                            @if(isset($settings['payroll_settings__prepared_by_signature']) && $settings['payroll_settings__prepared_by_signature'] != null)

                                            <img src="{{ asset('uploads/hrm/settings/'.$settings['payroll_settings__prepared_by_signature']) }}"  alt="no image" style="height:70px; width:70px; margin-top: 13px;" class="old_prepared_image payroll_bs_input">
                                            @endif
                                        </div>

                                        <input type="hidden" name="old_prepared_by_signature" value="{{ $settings['payroll_settings__prepared_by_signature'] }}">

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Checked By (Text)') }} </strong><span class="text-danger">*</span></label>
                                                <input type="text" name="payroll_settings__checked_by_text" id="payroll_settings__checked_by_text" class="form-control payroll_bs_input" autocomplete="off"
                                                value="{{ ($settings['payroll_settings__checked_by_text'] ?? null) }}">
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Checked By Person') }} </strong><span class="text-danger">*</span></label>
                                                <input type="text" name="payroll_settings__checked_by_person" id="payroll_settings__checked_by_person" class="form-control payroll_bs_input" autocomplete="off" value="{{ ($settings['payroll_settings__checked_by_person'] ?? null) }}">
                                            </div>


                                            <div class="col-xl-3 col-md-4">
                                                <label><strong>{{ __('Checked by signature (Image)') }} </strong><span class="text-danger">*</span></label>
                                                <input type="file" name="payroll_settings__checked_by_signature" class="form-control payroll_settings__checked_by_signature payroll_bs_input" autocomplete="off" >
                                            </div>
                                            <div class="col-xl-1 col-md-2">
                                                @if(isset($settings['payroll_settings__checked_by_signature']) && $settings['payroll_settings__checked_by_signature'] != null)

                                                <img src="{{ asset('uploads/hrm/settings/'.$settings['payroll_settings__checked_by_signature']) }}"  alt="no image" style="height:70px; width:70px; margin-top: 13px;" class="old_checked_image payroll_bs_input">
                                                @endif
                                            </div>

                                            <input type="hidden" name="old_checked_by_signature" value="{{ $settings['payroll_settings__checked_by_signature'] }}">



                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Approved By (Text)') }} </strong><span class="text-danger">*</span></label>
                                                <input type="text" name="payroll_settings__approved_by_text" id="payroll_settings__approved_by_text" class="form-control payroll_bs_input" autocomplete="off"
                                                value="{{ ($settings['payroll_settings__approved_by_text'] ?? null) }}">
                                                <span class="error error_payroll_settings__approved_by_text"></span>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <label><strong>{{ __('Approved By Person') }} </strong><span class="text-danger">*</span></label>
                                                <input type="text" name="payroll_settings__approved_by_person" id="payroll_settings__approved_by_person" class="form-control payroll_bs_input" autocomplete="off"
                                                value="{{ ($settings['payroll_settings__approved_by_person'] ?? null) }}">
                                                <span class="error payroll_settings__approved_by_person"></span>
                                            </div>


                                            <div class="col-xl-3 col-md-4">
                                                <label><strong>{{ __('Approved by signature (Image)') }} </strong><span class="text-danger">*</span></label>
                                                <input type="file" name="payroll_settings__approved_by_signature" class="form-control payroll_settings__approved_by_signature payroll_bs_input" autocomplete="off"
                                                value="{{ ($settings['payroll_settings__approved_by_signature'] ?? null) }}">
                                            </div>
                                            <div class="col-xl-1 col-md-2">
                                                @if(isset($settings['payroll_settings__approved_by_signature']) && $settings['payroll_settings__approved_by_signature'] != null)

                                                <img src="{{ asset('uploads/hrm/settings/'.$settings['payroll_settings__approved_by_signature']) }}"  alt="no image" style="height:70px; width:70px; margin-top: 13px;" class="old_approved_by_signature payroll_bs_input">
                                                @endif
                                            </div>

                                            <input type="hidden" name="old_approved_by_signature" value="{{ $settings['payroll_settings__approved_by_signature'] }}">
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="loading-btn-box">
                                                    <button type="button" class="btn loading_button display-none"><i class="fas fa-spinner"></i></button>
                                                    <button class="btn btn-success submit_button">@lang('menu.save_change')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <form id="dashboard_settings_form" class="setting_form display-none"
                                        action="#" method="post">
                                        <div class="form-group">
                                            <div class="setting_form_heading">
                                                <h6 class="text-primary">{{ __("HRM Policy") }}</h6>
                                            </div>
                                        </div>
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label><strong>@lang('menu.view_stock_expiry_alert_for') </strong> <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="view_stock_expiry_alert_for"
                                                        class="form-control dbs_input" id="dbs_view_stock_expiry_alert_for"
                                                        data-name="Day amount" autocomplete="off"
                                                        value="31" disabled>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text input-group-text-sm"
                                                            id="basic-addon1">@lang('menu.days')</span>
                                                    </div>
                                                </div>
                                                <span class="error error_dbs_view_stock_expiry_alert_for"></span>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="loading-btn-box">
                                                    <button type="button" class="btn loading_button display-none"><i class="fas fa-spinner"></i></button>
                                                    <button class="btn btn-success submit_button" disabled>@lang('menu.save_change')</button>
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
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            //$('.setting_form').hide();
            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide(500);
                $('#' + form_name).show(500);
                $('.menu_btn').removeClass('menu_active');
                $(this).addClass('menu_active');
            });
        });

        $(".id_card_settings__footer_right_signature_image").change(function(){
                var file = $(".id_card_settings__footer_right_signature_image").get(0).files[0];
                if(file){
                    var reader = new FileReader();
                    reader.onload = function(){
                        $(".signature_image").attr("src", reader.result);
                        $(".signature_image").removeClass("d-none");
                        $(".signature_image").addClass("d-block");
                    }
                    reader.readAsDataURL(file);
                }
            });

            $(".payroll_settings__prepared_by_signature").change(function(){
                var file = $(".payroll_settings__prepared_by_signature").get(0).files[0];
                if(file){
                    var reader = new FileReader();
                    reader.onload = function(){
                        $(".old_prepared_image").attr("src", reader.result);
                        $(".old_prepared_image").removeClass("d-none");
                        $(".old_prepared_image").addClass("d-block");
                    }
                    reader.readAsDataURL(file);
                }
            });

            $(".payroll_settings__checked_by_signature").change(function(){
                var file = $(".payroll_settings__checked_by_signature").get(0).files[0];
                if(file){
                    var reader = new FileReader();
                    reader.onload = function(){
                        $(".old_checked_image").attr("src", reader.result);
                        $(".old_checked_image").removeClass("d-none");
                        $(".old_checked_image").addClass("d-block");
                    }
                    reader.readAsDataURL(file);
                }
            });



            $(".payroll_settings__approved_by_signature").change(function(){
                var file = $(".payroll_settings__approved_by_signature").get(0).files[0];
                if(file){
                    var reader = new FileReader();
                    reader.onload = function(){
                        $(".old_approved_by_signature").attr("src", reader.result);
                        $(".old_approved_by_signature").removeClass("d-none");
                        $(".old_approved_by_signature").addClass("d-block");
                    }
                    reader.readAsDataURL(file);
                }
            });

        $('#update_card_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.bs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {

                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {

                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {

                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#general_payroll_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.payroll_bs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                // alert(inputId);
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#dashboard_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.dbs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

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
