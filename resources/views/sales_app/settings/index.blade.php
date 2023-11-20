@extends('layout.master')
@push('css')
@endpush
@section('title', 'Sales App Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.sales_app_settings')</h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i
                        class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
            </div>
            <div class="p-15">
                <div class="form_element mt-0">
                    <div class="element-body">
                        <div class="settings_form_area">
                            <div class="row g-2">
                                <div class="col-xl-2 col-md-3">
                                    <div class="settings_side_menu">
                                        <ul class="menus_unorder_list">
                                            @if (auth()->user()->can('sale_settings'))
                                                <li class="menu_list">
                                                    <a class="menu_btn menu_active" data-form="sale_settings_form"
                                                        data-first_focue_field="default_sale_discount_type"
                                                        href="#">@lang('menu.sale_settings')</a>
                                                </li>
                                            @endif

                                            @if (auth()->user()->can('pos_sale_settings'))
                                                <li class="menu_list">
                                                    <a class="menu_btn" data-form="pos_settings_form"
                                                        data-first_focue_field="is_enabled_multiple_pay"
                                                        href="#">@lang('menu.pos_settings')</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xl-10 col-md-9">
                                    @if (auth()->user()->can('sale_settings'))
                                        <form id="sale_settings_form" class="setting_form"
                                            action="{{ route('sales.app.settings.sale.settings.update') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <div class="setting_form_heading">
                                                    <h6 class="text-primary">@lang('menu.sale') @lang('menu.settings')</h6>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.default_sale_discount')</strong></label>
                                                    <div class="input-group">
                                                        <select name="default_sale_discount_type"
                                                            class="form-control w-40 form-select"
                                                            id="default_sale_discount_type"
                                                            data-next="default_sale_discount" autofocus>
                                                            <option value="1">@lang('menu.fixed')(0.00)</option>
                                                            <option value="2">@lang('menu.percentage')(%)</option>
                                                        </select>

                                                        <input type="text" name="default_sale_discount"
                                                            class="form-control w-60" id="default_sale_discount"
                                                            value="{{ json_decode($generalSettings->sale, true)['default_sale_discount'] }}"
                                                            data-next="default_tax_id" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.default_sale_tax') </strong></label>
                                                    <select name="default_tax_id" class="form-control form-select"
                                                        id="default_tax_id" data-next="default_price_group_id">
                                                        <option value="null">@lang('menu.none')</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option
                                                                {{ json_decode($generalSettings->sale, true)['default_tax_id'] == $taxAccount->id ? 'SELECTED' : '' }}
                                                                value="{{ $taxAccount->id }}">{{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.default') @lang('menu.selling_price_group') </strong></label>
                                                    <select name="default_price_group_id" class="form-control form-select"
                                                        id="default_price_group_id"
                                                        data-next="sale_settings_save_changes_btn">
                                                        <option value="null">@lang('menu.none')</option>
                                                        @foreach ($priceGroups as $pg)
                                                            <option
                                                                {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }}
                                                                value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <div class="loading-btn-box">
                                                        <button type="button"
                                                            class="btn loading_button item_settings_loading_btn display-none"><i
                                                                class="fas fa-spinner text-white"></i></button>
                                                        <button id="sale_settings_save_changes_btn"
                                                            class="btn btn-success submit_button">@lang('menu.save_changes')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    @if (auth()->user()->can('pos_sale_settings'))
                                        <form id="pos_settings_form" class="setting_form display-none"
                                            action="{{ route('sales.app.settings.pos.settings.update') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <div class="setting_form_heading">
                                                    <h6 class="text-primary">@lang('menu.pos_settings')</h6>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_multiple_pay') </strong></label>
                                                    <select name="is_enabled_multiple_pay" class="form-control form-select"
                                                        id="is_enabled_multiple_pay" data-next="is_enabled_draft">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_multiple_pay'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_draft') </strong></label>
                                                    <select name="is_enabled_draft" class="form-control form-select"
                                                        id="is_enabled_draft" data-next="is_enabled_quotation">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_draft'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_quotation') </strong></label>
                                                    <select name="is_enabled_quotation" class="form-control form-select"
                                                        id="is_enabled_quotation" data-next="is_enabled_suspend">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_quotation'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-1">
                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_suspend') </strong></label>
                                                    <select name="is_enabled_suspend" class="form-control form-select"
                                                        id="is_enabled_suspend" data-next="is_enabled_discount">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_suspend'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_order_discount') </strong></label>
                                                    <select name="is_enabled_discount" class="form-control form-select"
                                                        id="is_enabled_discount" data-next="is_enabled_order_tax">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_discount'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_order_tax') </strong></label>
                                                    <select name="is_enabled_order_tax" class="form-control form-select"
                                                        id="is_enabled_order_tax" data-next="is_show_recent_transactions">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_order_tax'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-1">
                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.show_recent_transactions') </strong></label>
                                                    <select name="is_show_recent_transactions"
                                                        class="form-control form-select" id="is_show_recent_transactions"
                                                        data-next="is_enabled_credit_full_sale">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_show_recent_transactions'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_full_credit_sale') </strong></label>
                                                    <select name="is_enabled_credit_full_sale"
                                                        class="form-control form-select" id="is_enabled_credit_full_sale"
                                                        data-next="is_enabled_hold_invoice">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_credit_full_sale'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.enable_hold_invoice') </strong></label>
                                                    <select name="is_enabled_hold_invoice"
                                                        class="form-control form-select" id="is_enabled_hold_invoice"
                                                        data-next="pos_settings_save_changes_btn">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">
                                                            @lang('menu.no')
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <div class="loading-btn-box">
                                                        <button type="button"
                                                            class="btn loading_button item_settings_loading_btn display-none"><i
                                                                class="fas fa-spinner text-white"></i></button>
                                                        <button id="pos_settings_save_changes_btn"
                                                            class="btn btn-success submit_button">@lang('menu.save_changes')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
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

            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide();
                $('#' + form_name).show();
                $('.menu_btn').removeClass('menu_active');
                $(this).addClass('menu_active');
                var firstFocusField = $(this).data('first_focue_field');
                $('#' + firstFocusField).focus().select();
            });
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        $(document).on('click', '.submit_button', function() {

            $(this).prop('type', 'submit');
        });

        $('#sale_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.item_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    toastr.success(data);
                    $('.item_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.item_settings_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    } else if (err.status == 403) {

                        toastr.error('Access Denied');
                        return;
                    }
                }
            });
        });

        $('#pos_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.item_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    toastr.success(data);
                    $('.item_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.item_settings_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    } else if (err.status == 403) {

                        toastr.error('Access Denied');
                        return;
                    }
                }
            });
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                e.preventDefault();

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('click change keypress', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });
    </script>
@endpush
