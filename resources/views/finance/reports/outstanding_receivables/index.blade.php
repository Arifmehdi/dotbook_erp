@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 100%;
            padding: 0px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            vertical-align: baseline;
        }

        table {
            border: none !important;
        }

        .net_total_balance_footer tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        td.outstanding_receivable_area {
            line-height: 17px !important;
        }

        .header_text {
            letter-spacing: 3px;
            border-bottom: 1px solid;
            background-color: #fff !important;
            color: #000 !important
        }

        .outstanding_receivable_area tbody tr td {
            line-height: 16px;
        }

        tr.account_list td {
            border-bottom: 1px solid lightgray;
        }
    </style>
@endpush
@section('title', __('menu.outstanding_receivables') . ' - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.outstanding_receivables')</h6>
                </div>
                <x-all-buttons>
                    <a href="#" id="print_report_btn" class="btn text-white btn-sm m-0"><span><i
                                class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></a>
                    <x-help-button />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded m-0 h-100">
                        <div class="element-body">
                            <form id="filter_outstanding_receivable"
                                action="{{ route('reports.outstanding.receivable.data.view') }}" method="get">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-md-2">
                                        <label><strong>{{ __("Sr.") }}</strong></label>
                                        <select name="user_id" class="form-control select2 form-select" id="user_id"
                                            autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($users as $user)
                                                <option
                                                    data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}"
                                                    value="{{ $user->id }}">
                                                    {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.accounts_group_head') </strong></label>
                                        <select name="sub_sub_group_number" class="form-control select2 form-select"
                                            id="sub_sub_group_number">
                                            <option data-account_group_head_name="All" value="">@lang('menu.all')
                                            </option>
                                            @foreach ($accountGroups as $accountGroup)
                                                <option data-account_group_head_name="{{ $accountGroup->name }}"
                                                    value="{{ $accountGroup->sub_sub_group_number }}">
                                                    {{ $accountGroup->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.from_date') :</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.to_date') :</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="outstanding_receivable_area">
                                <div id="data-list" class="table-responsive">
                                    <table class="w-100">
                                        <thead>
                                            <tr>
                                                <th class="header_text text-start fw-bold ps-1">@lang('menu.period')</th>
                                                <th class="header_text ps-1">@lang('menu.account_name')</th>
                                                <th class="header_text ps-1">@lang('menu.group')</th>
                                                <th class="header_text ps-1">@lang('menu.pending_amount')</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td class="text-start ps-1">01-01-2023 To 01-03-2023</td>
                                                <td class="text-start ps-1">Customer A</td>
                                                <td class="text-start ps-1">Account Receivable</td>
                                                <td class="text-start fw-bold ps-1">10,000.00</td>
                                            </tr>
                                        </tbody>

                                        <tfoot class="net_total_balance_footer">
                                            <tr>
                                                <td colspan="3" class="footer_total ps-1">@lang('menu.total') :</td>
                                                <td class="footer_total_amount ps-1">0.00</td>
                                            </tr>
                                        </tfoot>
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

    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>

    <script>
        $('.select2').select2();
        // Set accounts in payment and payment edit form
        function getOutstandingReceivables() {

            $('.data_preloader').show();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: "{{ route('reports.outstanding.receivable.data.view') }}",
                data: {
                    from_date,
                    to_date
                },
                success: function(data) {

                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getOutstandingReceivables();

        $('#filter_outstanding_receivable').on('submit', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'get',
                data: request,
                success: function(data) {

                    $('.data_preloader').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#data-list').html(data);
                }
            });
        });

        // Print account ledger
        $(document).on('click', '#print_report_btn', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.outstanding.receivable.data.print') }}";

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var user_id = $('#user_id').val();
            var sub_sub_group_number = $('#sub_sub_group_number').val();
            var account_group_head_name = $('#sub_sub_group_number').find('option:selected').data(
                'account_group_head_name');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date,
                    user_id,
                    sub_sub_group_number,
                    account_group_head_name
                },
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
                    });
                }
            });
        });
    </script>

    <script>
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
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
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('to_date'),
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
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
