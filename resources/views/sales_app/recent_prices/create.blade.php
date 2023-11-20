@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.add_new_price')</h6>
            <x-all-buttons />
        </div>
        <div class="container-fluid p-0">
            <div class="p-15">
                <div class="row g-1">
                    <div class="col-xl-7 col-md-6">
                        <form id="add_new_price_form" action="{{ route('sales.recent.price.store') }}" method="POST">
                            @csrf
                            <div class="form_element rounded m-0">

                                <div class="element-body">
                                    <div class="row g-1">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.start_date') </b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="start_date" class="form-control"
                                                        id="start_date"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                                                    <span class="error error_start_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.end_date') </b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="end_date" class="form-control"
                                                        id="end_date"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                                                    <span class="error error_end_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.start_time') </b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="time" name="start_time" class="form-control"
                                                        id="start_time">
                                                    <span class="error error_start_time"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.end_time')</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="time" name="end_time" class="form-control"
                                                        id="end_time">
                                                    <span class="error error_end_time"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.category') </b> </label>
                                                <div class="col-8">
                                                    <select name="category_id" id="category_id"
                                                        class="form-control form-select">
                                                        <option value="">@lang('menu.select_category')</option>
                                                        @foreach ($categories as $cate)
                                                            <option data-cate_name="{{ $cate->name }}"
                                                                value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <table class="table modal-table table-sm price_list_table">
                                                <tbody id="subcategory_list"></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-3">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="loading-btn-box">
                                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                                        class="fas fa-spinner"></i></button>
                                                <button type="submit"
                                                    class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded m-0 mt-1">
                                <div class="element-body">
                                    <div class="row mt-3">
                                        <table id="" class="table modal-table table-sm price_list_table">
                                            <thead>
                                                <tr class="bg-primary text-white">
                                                    <th>@lang('menu.item_name')</th>
                                                    <th>@lang('menu.category')</th>
                                                    <th>@lang('menu.item_cost')</th>
                                                    <th>@lang('menu.current_price')</th>
                                                    <th>@lang('menu.new_price')</th>
                                                </tr>
                                            </thead>
                                            <tbody id="price_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-xl-5 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <p class="px-0 py-1"><strong>@lang('menu.all_pricing_list')</strong> </p>
                                </div>
                                <div class="table-responsive" id="data_list">
                                    <table class="display table-hover data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.start_end_time')</th>
                                                <th>@lang('menu.category')</th>
                                                <th>@lang('menu.price')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var price_table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            searchable: true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            ajax: "{{ route('sales.recent.price.for.create.page') }}",
            columns: [{
                    data: 'startAndEndTime',
                    name: 'start_time'
                },
                {
                    data: 'categoryAndSubCategory',
                    name: 'categories.name'
                },
                {
                    data: 'new_price',
                    name: 'subcategories.name'
                }
            ]
        });

        // Add user by ajax
        $(document).on('submit', '#add_new_price_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();

            var totalPrice = 0;
            var table = $('.price_list_table');
            table.find('tbody').find('tr').each(function() {

                var price = $(this).find('#new_price').val() ? $(this).find('#new_price').val() : 0;
                totalPrice += parseFloat(price);
            });

            if (totalPrice == 0) {

                toastr.error('All price is empty or 0.', 'Something Went Wrong.');
                $('.loading_button').hide();
                return;
            }

            $('.loading_button').show();
            $('.submit_button').prop('type', 'button');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.submit_button').prop('type', 'submit');
                    $('.loading_button').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#add_new_price_form')[0].reset();
                    $('#price_list').empty();
                    $('#subcategory_list').empty();
                    toastr.success(data);
                    price_table.ajax.reload();
                },
                error: function(err) {

                    $('.submit_button').prop('type', 'submit');
                    $('.loading_button').hide();
                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                    $('.error').html('');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#category_id').on('change', function() {

            var category_id = $(this).val();

            $.get("{{ url('common/ajax/call/category/subcategories/') }}" + "/" + category_id, function(
                subCategories) {

                $('#subcategory_list').empty();

                var tr = '';
                $.each(subCategories, function(key, val) {

                    // $('#subcategory_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                    tr += '<tr>';
                    tr += '<th>' + val.name + ' Category</th>';
                    tr += '<th>:</th>';
                    tr += '<th><input type="number" step="any" data-tr_class="' + category_id + val
                        .id + '" id="all_price" class="form-control" placeholder="' + val.name +
                        '  Price"></th>';
                    tr += '</tr>';
                });

                $('#subcategory_list').html(tr);
            });

            var url = "{{ route('common.ajax.call.category.items', ':category_id') }}";
            var route = url.replace(':category_id', category_id);

            $.get(route, function(data) {

                $('#price_list').html(data);
            });
        });

        $('#subcategory_id').on('change', function() {

            var subcategory_id = $(this).val();
            var url = "{{ route('common.ajax.call.subcategory.items', ':subcategory_id') }}";
            var route = url.replace(':subcategory_id', subcategory_id);

            $.get(route, function(data) {

                $('#price_list').html(data);
            });
        });

        $(document).on('click', '#remove_row', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
        });

        $(document).on('input', '#new_price', function() {

            var new_price = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            var unit_cost = tr.find('#unit_cost').val() ? tr.find('#unit_cost').val() : 0;
            var profit = parseFloat(new_price) - parseFloat(unit_cost);
            var __cost = parseFloat(unit_cost) > 0 ? parseFloat(unit_cost) : parseFloat(profit);
            var calcXmargin = parseFloat(profit) / parseFloat(__cost) * 100;
            var __calcXmaring = calcXmargin ? calcXmargin : 0;
            tr.find('#x_margin').val(parseFloat(__calcXmaring).toFixed(2));
        });

        $(document).on('input', '#all_price', function() {

            var allPrice = $(this).val() > 0 ? $(this).val() : '';
            var trClass = $(this).data('tr_class');

            var table = $('.price_list_table');

            table.find('tbody').find('tr.' + trClass).each(function() {

                if (allPrice != '' && allPrice > 0) {

                    $(this).find('#new_price').val(parseFloat(allPrice).toFixed(2));
                    var unit_cost = $(this).find('#unit_cost').val() ? $(this).find('#unit_cost').val() : 0;
                    var profit = parseFloat(allPrice) - parseFloat(unit_cost);
                    var __cost = parseFloat(unit_cost) > 0 ? parseFloat(unit_cost) : parseFloat(profit);
                    var calcXmargin = parseFloat(profit) / parseFloat(__cost) * 100;
                    var __calcXmaring = calcXmargin ? calcXmargin : 0;
                    $(this).find('#x_margin').val(parseFloat(__calcXmaring).toFixed(2));
                } else {

                    $(this).find('#new_price').val('');
                    $(this).find('#x_margin').val('');
                }
            });
        });
    </script>

    <script>
        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

        new Litepicker({
            singleMode: true,
            element: document.getElementById('start_date'),
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

        new Litepicker({
            singleMode: true,
            element: document.getElementById('end_date'),
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
