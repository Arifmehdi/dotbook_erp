@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .selected_weight {
            background-color: #645f61;
            color: #fff !important;
        }

        .invoice_search_result {
            position: absolute;
            width: 374%;
            ;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 99999;
            padding: 3px;
            margin-top: 1px;
            border: 1px solid black;
        }

        .invoice_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .invoice_search_result ul li a {
            color: #6b6262;
            font-size: 15px;
            display: block;
            padding: 5px 3px;
            line-height: 15px;
        }

        .invoice_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 88.3%;
            z-index: 9999999;
            padding: 0;
            left: 6%;
            display: none;
            border: 1px solid #706a6d;
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 0px 2px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 2px 2px;
            display: block;
            border: 1px solid lightgray;
            margin: 2px 0px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70 !important;
            color: #fff !important;
        }

        .custome-input-group {
            font-size: 11px !important;
            padding-left: 4px !important;
            padding-right: 3px !important;
        }

        .custome_qty {
            font-size: 13px;
            font-weight: 600;
        }

        .element-body {
            overflow: initial !important;
        }

        .item-details-sec {
            overflow: initial !important;
        }

        .weight_input {
            height: 30px!important;
            max-height: 30px!important;
            font-size: 20px;
            font-weight: 500;
        }

        .sale-item-sec {
            height: 298px !important;
        }

        .head_btn {
            color: #fff !important;
        }

        .weight-details-modal-content {
            margin-top: 46px;
        }

        .select2-container--default .select2-selection--single {
            margin-bottom: 1px;
        }

        .select2-container .select2-selection--single {
            overflow: hidden;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: inline-block;
            width: 143px;
        }

        .select2-selection:focus {
            box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
        }

        span.select2-dropdown.select2-dropdown--below {
            border-top: 1px solid gray;
        }

        span.select2-dropdown.select2-dropdown--below {
            width: 283px !important;
        }

        span.select2-results ul li {
            font-size: 15px !important;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: 137px;
        }

        .do_table_area a {
            border: 1px solid gray;
            padding: 1px;
            color: black;
        }

        .do_table_area {
            max-height: 275px;
            min-height: 275px;
            overflow: scroll;
            overflow-x: hidden;
        }

        .do_table_area table {
            border-collapse: separate;
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .do_table_area table tbody tr:hover {
            background: #dbdbdb !important;
        }

        .do_table_area table thead tr th {
            line-height: 17px;
            padding: 0px 10px!important;
        }

        .do_table_area table tbody tr td {
            font-size: 14px !important;
            line-height: 17px;
            padding: 0px 3px!important;
        }

        input#search_vehicle_input {
            width: 69px;
            font-size: 9px;
            padding-left: 2px;
            font-weight: 700;
        }

        span#weight_connect {
            height: 30px;
        }

        .show_weights_input {
            font-size: 30px;
            height: 50px!important;
            max-height: 50px!important;
            font-weight: 900!important;
            color: black;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title', 'Weight Scale - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="weight_scale_from">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="first_weight" id="first_weight">

                <section class="mt-5x">
                    <div class="container-fluid p-0">
                        <div>
                            <div class="main__content">
                                <div class="sec-name">
                                    <div class="name-head">
                                        <h6>@lang('menu.weight_scale')</h6>
                                    </div>
                                    <x-all-buttons>
                                        <a href="#" id="printWeightBtn" class="head_btn print_weight btn text-white btn-sm px-3"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print_weight')</span></a>
                                    </x-all-buttons>
                                </div>
                            </div>
                            <div class="p-15 pb-0">
                                <div class="form_element rounded my-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">

                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.search_wt_id') </b> </label>
                                                    <div class="col-8">
                                                        <div style="position: relative;">
                                                            <div class="input-group">
                                                                <input type="text" id="search_weight_id" class="form-control scanable" placeholder="Search by Weight ID" autocomplete="off" autofocus>
                                                            </div>

                                                            <div class="invoice_search_result display-none">
                                                                <ul id="invoice_list" class="list-unstyled"></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.client_name')</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="select-half">
                                                                <select name="client_id" id="client_id" class="form-control select2 add_input form-select">
                                                                    <option value="">Select Client</option>
                                                                    @foreach ($clients as $client)
                                                                        <option data-client_name="{{ $client->name }}" data-client_phone="{{ $client->phone }}" value="{{ $client->id }}">
                                                                            {{ $client->name . ($client->phone ? '/' . $client->phone : '') }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="style-btn">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" id="addWeightClient"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error error_client_id"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.item_name')</b> </label>
                                                    <div class="col-8">
                                                        <select name="product_id" id="product_id" class="form-control select2 add_input form-select">
                                                            <option value="">@lang('menu.select_item')</option>
                                                            @foreach ($products as $product)
                                                                <option data-product_id="{{ $product->product_id }}" value="{{ $product->product_id }}">
                                                                    {{ $product->product_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.quantity')</b></label>
                                                    <div class="col-8">
                                                        <div style="position: relative;">
                                                            <div class="input-group">
                                                                <input type="text" name="quantity" id="quantity" class="form-control scanable" placeholder="quantity" autocomplete="off" autofocus>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.weight_id')</b></label>
                                                    <div class="col-8">
                                                        <div style="position: relative;">
                                                            <div class="input-group">
                                                                <input readonly type="text" name="weight_id" id="weight_id" class="form-control scanable fw-bold" placeholder="Weight ID" autocomplete="off" autofocus>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.scaling_date')</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="date" class="form-control add_input" id="date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" tabindex="-1">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.challan_no')</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="inputed_challan_number" id="inputed_challan_number" class="form-control" placeholder="challan no." autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.challan_date')</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="challan_date" id="challan_date" class="form-control" placeholder="challan date" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.vehicle_no') </b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="vehicle_number" id="vehicle_number" class="form-control fw-bold" placeholder="@lang('menu.vehicle_number')" autocomplete="off">
                                                        <input type="hidden" name="weight_scale_primary_id" id="weight_scale_primary_id">
                                                        <span class="error error_vehicle_number"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.driver_name')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="driver_name" class="form-control" id="driver_name" placeholder="@lang('menu.driver_name')">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.driver_phone')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="driver_phone" class="form-control" id="driver_phone" placeholder="@lang('menu.driver_phone')">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.serial_no')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="serial_no" id="serial_no" class="form-control scanable" placeholder="Serial No." autocomplete="off" autofocus>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group ">
                                                    <label class="col-4"><b> @lang('menu.weight') </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="weight_connect" style="cursor: pointer;"><i class="fas fa-plug"></i></span>
                                                            </div>

                                                            <input readonly type="number" step="any" name="weight" class="form-control weight_input" id="weight" value="0.00" placeholder="Weight" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.set_weight') </b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select name="weight_type" class="form-control fw-bold form-select" id="weight_type" required>
                                                            <option value="" selected>{{ __("Select Set Weight") }}</option>
                                                            <option class="fw-bold" value="2">@lang('menu.tare_weight')</option>
                                                            <option class="fw-bold" value="1">@lang('menu.gross_weight')</option>
                                                        </select>
                                                        <span class="error error_weight_type"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b> @lang('menu.net_weight') </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input readonly type="text" class="form-control fw-bold" id="trial_net_weight" value="0.00" placeholder="@lang('menu.net_weight')" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <div class="col-4 offset-4">
                                                        <a href="#" class="btn btn-sm btn-primary float-end" id="reset_form" tabindex="-1">@lang('menu.reset_form')</a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" onclick="return false;" class="btn btn-sm bg-secondary text-white float-end display-none" id="save_car_blur_btn">@lang('menu.save_weight')</a>
                                                        <a href="{{ route('scale.save-weight') }}" class="btn btn-sm btn-success float-end" id="save_weight">@lang('menu.save_weight')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row g-1 p-15">
                            <div class="col-md-8">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="row">
                                                    <div class="col-xl-5 col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.weight_id')</b></label>
                                                            <div class="col-8">
                                                                <input readonly type="text" id="display_weight_id" class="form-control fw-bold" placeholder="@lang('menu.weight_id')" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-5 col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.vehicle_no')</b></label>
                                                            <div class="col-8">
                                                                <input readonly type="text" id="display_vehicle_no" class="form-control fw-bold" placeholder="@lang('menu.vehicle_no')" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="sale-item-inner pt-2">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead class="">
                                                                <tr>
                                                                    <th class="text-center" style="font-size:30px; font-weight:900">
                                                                        @lang('menu.tare_weight')</th>
                                                                    <th class="text-center">@lang('menu.gross_weight')</th>
                                                                    <th class="text-center">@lang('menu.net_weight')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="">
                                                                        <input readonly type="text" id="tare_weight_value" class="form-control fw-bold text-center show_weights_input" tabindex="-1">
                                                                    </td>
                                                                    <td class="">
                                                                        <input readonly type="text" id="gross_weight_value" class="form-control fw-bold text-center show_weights_input" tabindex="-1">
                                                                    </td>
                                                                    <td class="">
                                                                        <input readonly type="text" id="net_weight_value" class="form-control fw-bold text-center show_weights_input" tabindex="-1">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <input type="hidden" name="total_qty" id="total_qty">
                                <input type="hidden" name="total_item" id="total_item">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="do_table_area">
                                            <table class="table table-sm selectable">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('menu.vehicle_no'). <input type="text" id="search_vehicle_input" class="search_vehicle_input" placeholder="Search" autocomplete="off"></th>
                                                        <th>@lang('menu.weight_id')</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="challan_table_rows_area"></tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="#" class="btn btn-sm btn-danger float-end d-inline" id="vehicle_done">Done</a>
                                                <a href="" class="btn btn-sm btn-success float-end d-inline me-2" id="vehicle_refresh">
                                                    @lang('menu.refresh')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <!-- Add Weight client Modal -->
    <div class="modal fade" id="addOrEditWeightClientModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Add Weight client  Modal End-->

    <!-- Weight Details Modal -->
    <div class="modal fade" id="weightDetailsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content weight-details-modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.weight_details')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="weightDetailsModalBody"></div>
            </div>
        </div>
    </div>
    <!-- Weight Details Modal End-->
    <input type="hidden" name="search_product" class="scanable" id="search_product">
@endsection
@push('scripts')
    {{-- Weight Machine JS --}}
    <script src="{{ asset('js/weight-machine.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";

        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";

        var lastCarWeight = 0;

        var ul = 'invoice_list';
        var selectObjClassName = '';

        $('#search_weight_id').mousedown(function(e) {

            afterClickOrFocusSearchWeightId();
        }).focus(function(e) {

            afterClickOrFocusSearchWeightId();
        });

        function afterClickOrFocusSearchWeightId() {

            resetForm();
            ul = document.getElementById('invoice_list');
            selectObjClassName = 'selected_weight';
        }

        $('#search_weight_id').on('input', function() {

            $('.invoice_search_result').hide();

            var key_word = $(this).val();

            if (key_word === '') {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();
                return;
            }

            var url = "{{ route('common.ajax.call.random.weight.search.challan.list', ':key_word') }}";
            var route = url.replace(':key_word', key_word);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        if (key_word == '') {

                            return;
                        }

                        $('.invoice_search_result').show();
                        $('#invoice_list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#selected_weight', function(e) {
            e.preventDefault();

            $('#search_weight_id').val('');

            var challan_no = $(this).data('challan_no');
            var client_id = $(this).data('client_id');
            var product_id = $(this).data('product_id');
            var date = $(this).data('date');
            var challan_date = $(this).data('challan_date');
            var inputed_challan_number = $(this).data('inputed_challan_number');
            var vehicle_number = $(this).data('vehicle_number');

            var weight_scale_primary_id = $(this).data('weight_scale_primary_id');

            var gross_weight_value = $(this).data('gross_weight_value');
            var tare_weight_value = $(this).data('tare_weight_value');
            var net_weight_value = $(this).data('net_weight_value');

            var weight_id = $(this).data('weight_id');

            $('#search_weight_id').val(weight_id);

            var serial_no = $(this).data('serial_no');
            var driver_name = $(this).data('driver_name');
            var quantity = $(this).data('quantity');

            var net_weight = $(this).data('net_weight');
            var driver_name = $(this).data('driver_name');
            var driver_phone = $(this).data('driver_phone');

            var vehicle_done_href = $(this).data('vehicle_done_href') != undefined ? $(this).data('vehicle_done_href') : '#';

            $('#vehicle_done').attr('href', vehicle_done_href);

            $('#weight').val(0);
            $('#client_id').val(client_id).trigger('change');
            $('#product_id').val(product_id).trigger('change');
            $('#challan_no').val(challan_no);
            $('#date').val(date);
            $('#challan_date').val(challan_date);
            $('#inputed_challan_number').val(inputed_challan_number);
            $('#vehicle_number').val(vehicle_number);
            $('#display_vehicle_no').val(vehicle_number);
            $('#weight_scale_primary_id').val(weight_scale_primary_id);
            $('#gross_weight_value').val(bdFormat(gross_weight_value));
            $('#tare_weight_value').val(bdFormat(tare_weight_value));
            $('#net_weight_value').val(bdFormat(net_weight_value));
            $('#weight_id').val(weight_id);
            $('#display_weight_id').val(weight_id);
            $('#serial_no').val(serial_no);
            $('#quantity').val(quantity);
            $('#net_weight').val(parseFloat(net_weight).toFixed(2));
            $('#trial_qnet_weight').val(parseFloat(net_weight).toFixed(2));
            $('#driver_name').val(driver_name);
            $('#driver_phone').val(driver_phone);
            $('.invoice_search_result').hide();
            $('.invoice_list').empty();

            var generatedUrl = "{{ route('random.scale.print.weight', [':weight_scale_primary_id']) }}";
            __generatedUrl = generatedUrl.replace(':weight_scale_primary_id', weight_scale_primary_id)
            $('#printWeightBtn').attr('href', __generatedUrl);

            $('#weight_type').val('');
            if (parseFloat(tare_weight_value) == 0) {

                $('#weight_type').val(2);
            } else if (parseFloat(gross_weight_value) == 0) {

                $('#weight_type').val(1);
            }
        });

        $(document).on('input', '#do_input_qty', function(e) {

            calculateSaleProcessingItem();
        });

        function calculateTotalAmount() {

            var net_weights = document.querySelectorAll('#net_weight');

            var total_item = 0;
            var total_qty = 0;
            net_weights.forEach(function(net_weight) {

                var tr = net_weight.closest('tr');

                total_qty += parseFloat(net_weight.value);
                total_item += 1;
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty).toFixed(2));
        }

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#invoice_list').empty();
            }
        });

        $(document).keypress(".scanable", function(event) {

            if (event.which == '10' || event.which == '13') {

                event.preventDefault();
            }
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {

                $('#completed_btn').click();
                return false;
            } else if (e.which == 27) {

                $('.invoice_search_result').hide();
                return false;
            }
        }

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#save_weight').on('click', function(e) {
            e.preventDefault();

            // var needWeight = $('#weight').val();
            // var setWehgt = $('#weight_type').val();

            // var gross_weight = 0;
            // var tare_weight = 0;
            // var weight = 0;
            // gross_weight = $('#gross_weight_value').val();
            // tare_weight = $('#tare_weight_value').val();
            // net_weight = $('#net_weight_value').val();

            // if(setWehgt == 1){

            //     gross_weight = needWeight;
            //     tare_weight = $('#tare_weight_value').val();
            // }else{

            //     tare_weight = needWeight;
            //     gross_weight = $('#gross_weight_value').val();
            // }

            $(this).hide();
            $('#save_car_blur_btn').show();

            // var client_id = $('#client_id').val();
            // var challan_date = $('#challan_date').val();
            // var inputed_challan_number = $('#inputed_challan_number').val();
            // var vehicle_number = $('#vehicle_number').val();
            // var weight_type = $('#weight_type').val();
            // var weight_scale_primary_id = $('#weight_scale_primary_id').val();
            // var driver_phone = $('#driver_phone').val();
            // var date = $('#date').val();
            // var weight = $('#weight').val();
            // var weight_id = $('#weight_id').val();
            // var quantity = $('#quantity').val();
            // var serial_no = $('#serial_no').val();
            // var product_id = $('#product_id').val();
            // var driver_name = $('#driver_name').val();
            var request = $('#weight_scale_from').serialize();
            url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                // data: { client_id, challan_date, inputed_challan_number, vehicle_number, driver_phone,
                //         date, weight, net_weight, weight_id, weight_type, quantity,
                //         serial_no, gross_weight, tare_weight, product_id, driver_name, weight_scale_primary_id
                //     },
                success: function(weight) {

                    $('.error').html('');
                    $('#save_weight').show();
                    $('#save_car_blur_btn').hide();

                    if (!$.isEmptyObject(weight.errorMsg)) {

                        toastr.error(weight.errorMsg);
                        $('.loading_button').hide();
                        return;
                    }

                    $('#weight_type').val('');
                    $('#weight').val(0);

                    $('#weight_scale_primary_id').val(weight.id);
                    $('#weight_id').val(weight.weight_id);
                    $('#display_weight_id').val(weight.weight_id);
                    $('#display_vehicle_no').val(weight.vehicle_number);

                    $("#gross_weight_value").val(bdFormat(weight.gross_weight));
                    $("#tare_weight_value").val(bdFormat(weight.tare_weight));
                    $("#net_weight_value").val(bdFormat(weight.net_weight));

                    toastr.success('Weight has been saved successfully.');

                    resetForm(false);
                    getWeightList();

                    var generatedUrl =
                        "{{ route('random.scale.print.weight', [':weight_scale_primary_id']) }}";
                    __generatedUrl = generatedUrl.replace(':weight_scale_primary_id', weight.id)
                    $('#printWeightBtn').attr('href', __generatedUrl);
                },
                error: function(err) {

                    $('.error').html('');
                    $('#save_weight').show();
                    $('#save_car_blur_btn').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                        return;
                    }

                    toastr.error('Please fill all required fields.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#vehicle_done').on('click', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            if (url == '#') {

                toastr.error('Vehicle is not selected.');
                return;
            }

            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure to done the do?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {

                            $.ajax({
                                url: url,
                                type: 'post',
                                success: function(data) {

                                    if (!$.isEmptyObject(data.errorMsg)) {

                                        toastr.error(data.errorMsg);
                                        $('.loading_button').hide();
                                        return;
                                    }

                                    if (data) {

                                        getWeightList();
                                    }
                                },
                                error: function(err) {

                                    if (err.status == 0) {

                                        toastr.error(
                                            'Net Connetion Error. Reload This Page.');
                                        return;
                                    } else if (err.status == 500) {

                                        toastr.error(
                                            'Server Error. Please contact to the support team.'
                                        );
                                        return;
                                    }
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
                    }
                }
            });
        });

        // vehicle refresh  code start
        $(document).on('click', '#vehicle_refresh', function(e) {
            e.preventDefault();

            getWeightList();
        });

        //  Challan List code start
        function getWeightList() {

            $('#search_vehicle_input').val('');
            search_table('');

            var url = "{{ route('common.ajax.call.random.weight.challan.list') }}";

            $.get(url, function(data) {

                $('#challan_table_rows_area').html(data);
            });
        }
        getWeightList();

        $('#addWeightClient').on('click', function() {

            $.get("{{ route('scale.add.weight.client.modal') }}", function(data) {

                $('#addOrEditWeightClientModal').html(data);
                $('#addOrEditWeightClientModal').modal('show');

                setTimeout(function() {

                    $('#client_name').focus();
                }, 500);
            });
        });

        $('#search_vehicle_input').keyup(function() {

            search_table($(this).val());
        });

        function search_table(value) {

            $('#challan_table_rows_area tr').each(function() {

                var found = 'false';
                $(this).each(function() {

                    if ($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {

                        found = 'true';
                    }
                });

                if (found == 'true') {

                    $(this).show();
                } else {

                    $(this).hide();
                }
            });
        }

        // Make print
        $(document).on('click', '#printWeightBtn', function(e) {
            e.preventDefault();

            // var weight_scale_primary_id = $('#weight_scale_primary_id').val();

            // if(! weight_scale_primary_id) {

            //     toastr.error('Please select a weight voucher.');
            //     return;
            // }

            // var url = "{{ route('random.scale.print.weight', [':weight_scale_primary_id']) }}";
            // var route = url.replace(':weight_scale_primary_id', weight_scale_primary_id);

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            });
        });

        $(document).on('click', '#reset_form', function(e) {
            e.preventDefault();

            resetForm();
        });

        $(document).on('click', '.vehicleSelectFromTable', function() {

            $('#weight').focus();
        });

        function resetForm(isResetNetWeight = true, isRestWeightPrimaryIdField = true) {
            $('#weight').val(0);
            $('#client_id').val('');
            $('#search_weight_id').val('');
            $('#challan_date').val('');
            $('#inputed_challan_number').val('');
            $('#vehicle_number').val('');
            $('#weight_type').val('');
            $('#weight_scale_primary_id').val('');
            $('#driver_phone').val('');
            $('#weight_id').val('');
            $('#quantity').val('');
            $('#serial_no').val('');
            $('#product_id').val('');
            $('#driver_name').val('');
            $('#trial_net_weight').val(0);

            $("#client_id").select2("destroy");
            $("#client_id").select2();
            $("#product_id").select2("destroy");
            $("#product_id").select2();
            $('.error').html('');

            if (isResetNetWeight == true) {

                $('#display_vehicle_no').val('');
                $('#display_weight_id').val('');
                $("#gross_weight_value").val('');
                $("#tare_weight_value").val('');
                $("#net_weight_value").val('');
            }
        }

        $(document).on('click', '#weight_connect', function() {

            getWeight().then(weight => {

                var getCurrentVehicleWeight = weight;
                $('#weight').val(parseFloat(getCurrentVehicleWeight).toFixed(2));
                calculateTrialNetWeight();
            });
        });

        $(document).on('change', '#weight_type', function(e) {

            calculateTrialNetWeight();
        });

        // Make print
        $(document).on('keypress', '#weight', function(e) {

            if (e.which == 13) {

                $('#weight_connect').click();
            }
        });

        function calculateTrialNetWeight() {

            var weightType = $('#weight_type').val();
            var tare_weight_value = $('#tare_weight_value').val() ? $('#tare_weight_value').val() : 0;
            var __tare_weight_value = tare_weight_value.toString().replaceAll(',', '');
            var gross_weight_value = $('#gross_weight_value').val() ? $('#gross_weight_value').val() : 0;
            var __gross_weight_value = gross_weight_value.toString().replaceAll(',', '');
            var weight = $('#weight').val() ? $('#weight').val() : 0;

            var trial_net_weight = 0;

            if (weightType) {

                if (weightType == 2) {

                    trial_net_weight = parseFloat(__gross_weight_value) - parseFloat(weight);
                } else if (weightType == 1) {

                    trial_net_weight = parseFloat(weight) - parseFloat(__tare_weight_value);
                }

                $('#trial_net_weight').val(parseFloat(trial_net_weight).toFixed(2));
            }
        }

        $(document).on('click', function(e) {

            if ($(e.target).closest(".invoice_search_result").length === 0) {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();

                $('.select_area').hide();
                $('#list').empty();
                $('#do_table_rows_area tr').removeClass('selected_weight');
            }
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

        new Litepicker({
            singleMode: true,
            element: document.getElementById('challan_date'),
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

    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
