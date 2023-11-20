@extends('layout.master')
@push('css')
@endpush
@section('title', 'Inventory Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.inventory_settings')</h6>
                <x-back-button />
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
                                                @if (auth()->user()->can('product_settings'))
                                                    <a class="menu_btn menu_active" data-form="item_settings_form"
                                                        data-first_focue_field="product_code_prefix"
                                                        href="#">@lang('menu.item_settings')</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xl-10 col-md-9">
                                    @if (auth()->user()->can('product_settings'))
                                        <form id="item_settings_form" class="setting_form"
                                            action="{{ route('inventories.settings.item.settings.update') }}" method="post"
                                            enctype="multipart/form-data">
                                            <div class="form-group">
                                                <div class="setting_form_heading">
                                                    <h6 class="text-primary">@lang('menu.item') @lang('menu.settings')</h6>
                                                </div>
                                            </div>

                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.item_code_prefix') (SKU) </strong></label>
                                                    <input type="text" name="product_code_prefix" class="form-control"
                                                        id="product_code_prefix"
                                                        value="{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}"
                                                        data-next="default_unit_id" placeholder="@lang('menu.item_code_prefix')"
                                                        autocomplete="off" autofocus>
                                                </div>

                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.default_unit') </strong></label>
                                                    <select name="default_unit_id" class="form-control form-select"
                                                        id="default_unit_id" data-next="is_enable_brands">
                                                        <option value="null">@lang('menu.none')</option>
                                                        @foreach ($units as $unit)
                                                            <option
                                                                {{ json_decode($generalSettings->product, true)['default_unit_id'] == $unit->id ? 'SELECTED' : '' }}
                                                                value="{{ $unit->id }}">{{ $unit->name }}
                                                                ({{ $unit->code_name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-1">
                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.enable_brands') </strong></label>
                                                    <select name="is_enable_brands" class="form-control form-select"
                                                        id="is_enable_brands" data-next="is_enable_categories">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->product, true)['is_enable_brands'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>

                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.enable_categories') </strong></label>
                                                    <select name="is_enable_categories" class="form-control form-select"
                                                        id="is_enable_categories" data-next="is_enable_sub_categories">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->product, true)['is_enable_categories'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>

                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.enable_sub_categories') </strong></label>
                                                    <select name="is_enable_sub_categories" class="form-control form-select"
                                                        id="is_enable_sub_categories" data-next="is_enable_price_tax">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-1">
                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.enable_price_tax_info') </strong></label>
                                                    <select name="is_enable_price_tax" class="form-control form-select"
                                                        id="is_enable_price_tax" data-next="is_enable_warranty">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->product, true)['is_enable_price_tax'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>

                                                <div class="col-xl-4 col-md-4">
                                                    <label><strong>@lang('menu.enable_warranty') </strong></label>
                                                    <select name="is_enable_warranty" class="form-control form-select"
                                                        id="is_enable_warranty" data-next="item_settings_save_changes_btn">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ json_decode($generalSettings->product, true)['is_enable_warranty'] == '0' ? 'SELECTED' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <div class="loading-btn-box">
                                                        <button type="button"
                                                            class="btn loading_button item_settings_loading_btn display-none"><i
                                                                class="fas fa-spinner"></i></button>
                                                        <button id="item_settings_save_changes_btn"
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
            //$('.setting_form').hide();
            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide(500);
                $('#' + form_name).show(500);
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

        $('#item_settings_form').on('submit', function(e) {
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
