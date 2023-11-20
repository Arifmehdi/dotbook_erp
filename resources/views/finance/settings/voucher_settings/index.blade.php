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
    </style>
@endpush
@section('title', 'Voucher Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.voucher_settings')</h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="accounting_voucher_settings_form" class="setting_form p-3"
                            action="{{ route('finance.voucher.settings.update') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.add_transaction_details_default') </strong></label>
                                    <select name="add_transaction_details" class="form-control form-select"
                                        id="add_transaction_details" data-next="maintain_cost_centre" autofocus>
                                        @php
                                            $addTransactionDetails = '1';
                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['add_transaction_details']) && json_decode($generalSettings->accounting_vouchers, true)['add_transaction_details'] == '0') {
                                                $addTransactionDetails = '0';
                                            }
                                        @endphp
                                        <option {{ $addTransactionDetails == '1' ? 'SELECTED' : '' }} value="1">Yes
                                        </option>
                                        <option {{ $addTransactionDetails == '0' ? 'SELECTED' : '' }} value="0">No
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.maintain_cost_centre_default') </strong></label>
                                    <select name="maintain_cost_centre" class="form-control form-select"
                                        id="maintain_cost_centre" data-next="show_cost_centre_list">
                                        @php
                                            $maintainCostCentre = '1';
                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['maintain_cost_centre']) && json_decode($generalSettings->accounting_vouchers, true)['maintain_cost_centre'] == '0') {
                                                $maintainCostCentre = '0';
                                            }
                                        @endphp
                                        <option {{ $maintainCostCentre == '1' ? 'SELECTED' : '' }} value="1">Yes
                                        </option>
                                        <option {{ $maintainCostCentre == '0' ? 'SELECTED' : '' }} value="0">No
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.show_the_list_after_assigning_the_cost_centres') </strong></label>
                                    <select name="show_cost_centre_list" class="form-control form-select"
                                        id="show_cost_centre_list" data-next="all_voucher_maintain_by_approval">
                                        @php
                                            $showCostCentreList = '1';
                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['show_cost_centre_list']) && json_decode($generalSettings->accounting_vouchers, true)['show_cost_centre_list'] == '0') {
                                                $showCostCentreList = '0';
                                            }
                                        @endphp
                                        <option {{ $showCostCentreList == '0' ? 'SELECTED' : '' }} value="0">No
                                        </option>
                                        <option {{ $showCostCentreList == '1' ? 'SELECTED' : '' }} value="1">Yes
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.all_accounting_voucher_maintain_by_approval') </strong></label>
                                    <select name="all_voucher_maintain_by_approval" class="form-control form-select"
                                        id="all_voucher_maintain_by_approval" data-next="save_btn">
                                        @php
                                            $allVoucherMaintainByApproval = '0';
                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['all_voucher_maintain_by_approval']) && json_decode($generalSettings->accounting_vouchers, true)['all_voucher_maintain_by_approval'] == '1') {
                                                $allVoucherMaintainByApproval = '1';
                                            }
                                        @endphp
                                        <option {{ $allVoucherMaintainByApproval == '1' ? 'SELECTED' : '' }} value="1">
                                            Yes</option>
                                        <option {{ $allVoucherMaintainByApproval == '0' ? 'SELECTED' : '' }} value="0">
                                            No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                                class="fas fa-spinner"></i></button>
                                        <button id="save_btn"
                                            class="btn btn-success submit_button">@lang('menu.save_change')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            }
        });

        $('#accounting_voucher_settings_form').on('submit', function(e) {
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
                },
                error: function(err) {

                    $('.loading_button').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    } else if (err.status == 403) {

                        toastr.error('Access Denied');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });
    </script>
@endpush
