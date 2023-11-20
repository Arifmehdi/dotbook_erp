<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <base target="_top" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @if (request()->secure())
        <!-- HTTPS configured to load HTTP resources (Like scale server from localhost) -->
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <title>@yield('title') {{ config('app.name') }}</title>

    @include('layout._stylesheet')
    @stack('stylesheets')
    @stack('css')
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>

<body class="dark-theme fullDiv" id="dashboard-8">
    @if (config('app.show_loader'))
        <div class="preloader">
            <div class="loader"></div>
        </div>
    @endif

    <div class="all__content" id="fullDiv">

        <!-- Navbar Start -->
        @include('layout.partial._navbar')
        <!-- Navbar Ends -->

        <div class="main-wraper" id="main-wraper">

            <!-- Header start -->
            <div class="toggle_for_right_navigation">
                <span class="fas fa-bars"></span>
            </div>
            @include('layout.partial._header')
            <div id="hiddenDivFullWidth" class=""></div>
            @include('layout.partial._right_sidebar')
            <!-- Header ends -->
            <div class="body-wraper bg-color-body tab-body-content pb-0" id="body-wraper">
                @yield('content')
            </div>

        </div>

        {{-- @include('layout.partial._footer') --}}
    </div>

    <div class="modal fade" id="todaySummeryModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Today Summary</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="today_summery_modal_body">
                    <div class="today_summery_modal_contant">

                    </div>
                    <div class="print-button-area">
                        <a href="#" class="btn btn-sm btn-primary float-end"
                            id="today_summery_print_btn">@lang('menu.print')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Logout form for global -->
    <form id="logout_form" class="d-none" action="{{ route('logout') }}" method="POST">@csrf</form>
    @include('layout._script', ['custom_modal' => $custom_modal ?? true])
    @stack('scripts')
    @stack('js')
    <script>
        $(document).on('click', '#today_summery', function(e) {
            e.preventDefault();

            todaySummery();
        });

        function todaySummery() {
            $('.loader').show();
            $.ajax({
                url: "{{ route('dashboard.today.summery') }}",
                type: 'get',
                success: function(data) {

                    $('.today_summery_modal_contant').html(data);
                    $('#todaySummeryModal').modal('show');
                    $('.loader').hide();
                }
            });
        }

        $(document).on('click', '#today_summery_print_btn', function(e) {
            e.preventDefault();

            var body = $('.print_body').html();
            var header = $('.print_today_summery_header').html();
            var footer = $('.print_today_summery_footer').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        function countSalesOrdersQuotationDo() {
            $.ajax({
                url: "{{ route('common.ajax.call.count.sales.quotations.orders.do') }}",
                type: 'get',
                success: function(data) {

                    $('.validate_count').html(0);
                    $('#validate_order_count').html(data[0].total_ordered);
                    $('#validate_quotation_count').html(data[0].total_quotation);
                    $('#validate_do_count').html(data[0].total_uncompleted_do);
                }
            });
        }
        countSalesOrdersQuotationDo();

        $('#headerSearch button').on('click', function() {

            $('.search-bar').addClass('open');
        });

        $(document).on('click', function(e) {

            if ($(e.target).closest("#headerSearch").length === 0) {
                
                $('.search-bar').removeClass('open');
            }
        });

        var isDebugging = "{{ config('app.debug') }}";

        if (!isDebugging) {

            window.console.log = function(...param) {

                /* If the App running on production, then print nothing on console */
            }
        }
    </script>

</body>

</html>
