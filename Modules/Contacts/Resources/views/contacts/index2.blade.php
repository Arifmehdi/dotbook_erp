@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.js')}}" />
    <link rel="stylesheet" href="https://raw.githubusercontent.com/weareoutman/clockpicker/gh-pages/dist/jquery-clockpicker.css">
    <style>
        .common-btn {
            color: #e7e8f7 !important;
            border: 1px solid #06f526;
            border-radius: 10px;
        }

        .common-btn:hover,
        .common-btn.active {
            background: #ffffff !important;
            color: #0f0f0f !important;
            border: 1px solid #0c0c0c;
            border-radius: 15px;
        }

        .form-title {
            background: transparent;
            color: #0c0c0c;
            text-shadow: 0 0;
            height: 50px;
            line-height: 50px;
            margin: 0px;
        }


        .contact-card-group {
            display: flex;
            flex-wrap: wrap;
        }

        .contact-card {
            width: 100%;
            border: 1px solid rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-card.contact-card-header {
            font-weight: 600;
        }

        .contact-card:not(:first-child) {
            border-top-width: 0;
        }

        .contact-card .part-img {
            display: none;
            width: 100px;
            position: relative;
        }

        .contact-card .company-logo {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 30px;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .contact-card .part-txt {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .contact-card .part-txt * {
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            display: -webkit-box;
        }

        .contact-card .form-check {
            display: flex;
        }

        .contact-card .contact-name {
            width: 10%;
            padding-right: 5px;
        }

        .contact-card .contact-profession {
            width: 20%;
            padding-right: 5px;
        }

        .contact-card .contact-address {
            display: flex;
            width: 25%;
            padding-right: 5px;
        }

        .contact-card .contact-address >* {
            width: 50%;
        }

        .contact-card .contact-address .comma {
            display: none;
        }

        .contact-card .contact-email {
            width: 15%;
            padding-right: 5px;
        }

        .contact-card .contact-number {
            width: 10%;
            padding-right: 5px;
        }

        .contact-card .contact-sales-person {
            width: 10%;
            padding-right: 5px;
        }

        .contact-card .contact-info {
            display: none;
        }

        .contact-card .company-name {
            width: 10%;
            padding-right: 5px;
        }


        .grid-view {
            gap: 10px;
        }

        .grid-view .contact-card-header {
            display: none;
        }

        .grid-view .contact-card {
            width: calc(100% / 4 - 7.5px);
            padding: 0;
            border-top-width: 1px;
            border-radius: 3px;
        }

        .grid-view .contact-card .part-img {
            display: block;
        }

        .grid-view .contact-card .part-txt {
            width: calc(100% - 110px);
            display: block;
            line-height: 1.4;
        }

        .grid-view .contact-card .part-txt * {
            width: 100%;
        }

        .grid-view .contact-card .form-check {
            display: none;
        }

        .grid-view .contact-card .contact-name {
            font-weight: 600;
            margin-bottom: 1px;
        }

        .grid-view .contact-card .contact-number {
            display: none;
        }

        .grid-view .contact-card .contact-address * {
            width: auto;
        }

        .grid-view .contact-card .contact-address .comma {
            display: block;
            margin-right: 2px;
        }

        .grid-view .contact-card .contact-sales-person {
            display: none;
        }

        .grid-view .contact-card .contact-info {
            padding-top: 2px;
            display: flex;
            gap: 5px;
        }

        .contact-card .contact-info .contact-info-badge {
            width: auto;
            display: flex;
            align-items: center;
            gap: 3px;
            border: 1px solid #0b8e7d;
            border-radius: 10px;
            padding: 0 5px;
            height: 18px;
            line-height: 16px;
            font-size: 12px;
        }

        .contact-card .contact-info .contact-info-badge i {
            width: auto;
            font-size: 10px;
            line-height: 1;
        }

        .grid-view .contact-card .company-name {
            display: none;
        }
    </style>
@endpush
@section('title', 'Contact List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.contacts')</h6>
                </div>

                <div class="d-flex gap-2">
                    <x-table-stat :card-id="'info_item'" :items="[
                        ['id' => 'totalitem', 'name' => __('Total Contacts'), 'value' => '000'],
                        ['id' => 'activeStat', 'name' => __('Active Contacts'), 'value' => '000'],
                        ['id' => 'inactiveStat', 'name' => __('In-active Contacts'), 'value' => '000']
                    ]" />

                    <x-all-buttons>
                        <x-add-button :text="'Create New'" />
                        <button class="btn btn-sm"><span><i class="fa-thin fa-download"></i><br>Download</span></button>
                    </x-all-buttons>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-12">
                    <div class="card m-0">
                        <div class="card-body p-2">
                            <div class="row g-2 align-items-end justify-content-between">
                                <div class="col-xl-2 col-md-6">
                                    <label for="">Group By</label>
                                    <input type="text" class="form-control form-control-sm">
                                </div>
                                <div class="col-xl-1 col-lg-2 d-flex justify-content-end">
                                    <div class="contact-card-view-button btn-group">
                                        <button class="btn fz-14 btn-outline-secondary px-3 py-0 show-list active"><span><i class="fa-solid fa-list"></i></span></button>
                                        <button class="btn fz-14 btn-outline-secondary px-3 py-0 show-grid"><span><i class="fa-solid fa-grid-2"></i></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card m-0">
                        <div class="card-body p-2">
                            <div class="contact-card-group">
                                <div class="contact-card contact-card-header">
                                    <div class="part-txt">
                                        <div class="form-check ps-1 pe-2">
                                            <input class="form-check-input m-0" type="checkbox" value="">
                                        </div>
                                        <span class="contact-name">Name</span>
                                        <span class="contact-profession"></span>
                                        <span class="contact-number">Phone</span>
                                        <span class="contact-address">
                                            <span class="city">City</span>
                                            <span class="country">Country</span>
                                        </span>
                                        <span class="contact-email">Email</span>
                                        <span class="contact-sales-person">Salesperson</span>
                                        <span class="company-name">Company</span>
                                    </div>
                                </div>
                                <div class="contact-card">
                                    <div class="part-img">
                                        <img src="{{ asset('images/avatar.jpg') }}" alt="image">
                                        <span class="company-logo">
                                            <img src="{{ asset('images/icon.png') }}" alt="logo">
                                        </span>
                                    </div>
                                    <div class="part-txt">
                                        <div class="form-check ps-1 pe-2">
                                            <input class="form-check-input m-0" type="checkbox" value="">
                                        </div>
                                        <span class="contact-name">Mr. John Doe</span>
                                        <span class="contact-profession">Creative Director of Azure Interior</span>
                                        <span class="contact-number">+(123) 456 789</span>
                                        <span class="contact-address">
                                            <span class="city">Fremont</span>
                                            <span class="comma">,</span>
                                            <span class="country">United States</span>
                                        </span>
                                        <span class="contact-email">example@mail.com</span>
                                        <span class="contact-sales-person">Abc David</span>
                                        <span class="contact-info">
                                            <span class="contact-info-badge"><i class="fa-solid fa-calendar-days"></i> 2</span>
                                            <span class="contact-info-badge"><i class="fa-solid fa-star"></i> 2</span>
                                            <span class="contact-info-badge"><i class="fa-solid fa-cart-shopping"></i> 2</span>
                                            <span class="contact-info-badge"><i class="fa-solid fa-dollar-sign"></i> 1</span>
                                        </span>
                                        <span class="company-name">Abc Defgh Ijkl</span>
                                    </div>
                                </div>
                                <div class="contact-card">
                                    <div class="part-img">
                                        <img src="{{ asset('images/avatar.jpg') }}" alt="image">
                                        <span class="company-logo">
                                            <img src="{{ asset('images/icon.png') }}" alt="logo">
                                        </span>
                                    </div>
                                    <div class="part-txt">
                                        <div class="form-check ps-1 pe-2">
                                            <input class="form-check-input m-0" type="checkbox" value="">
                                        </div>
                                        <span class="contact-name">Mr. John Doe</span>
                                        <span class="contact-profession">Creative Director of Azure Interior</span>
                                        <span class="contact-number">+(123) 456 789</span>
                                        <span class="contact-address">
                                            <span class="city">Fremont</span>
                                            <span class="comma">,</span>
                                            <span class="country">United States</span>
                                        </span>
                                        <span class="contact-email">example@mail.com</span>
                                        <span class="contact-sales-person">Abc David</span>
                                        <span class="contact-info">
                                            <span class="contact-info-badge"><i class="fa-solid fa-calendar-days"></i> 2</span>
                                            <span class="contact-info-badge"><i class="fa-solid fa-star"></i> 2</span>
                                            <span class="contact-info-badge"><i class="fa-solid fa-cart-shopping"></i> 2</span>
                                            <span class="contact-info-badge"><i class="fa-solid fa-dollar-sign"></i> 1</span>
                                        </span>
                                        <span class="company-name">Abc Defgh Ijkl</span>
                                    </div>
                                </div>
                                <div class="contact-card">
                                    <div class="part-img">
                                        <img src="{{ asset('images/avatar.jpg') }}" alt="image">
                                        <span class="company-logo">
                                            <img src="{{ asset('images/icon.png') }}" alt="logo">
                                        </span>
                                    </div>
                                    <div class="part-txt">
                                        <div class="form-check ps-1 pe-2">
                                            <input class="form-check-input m-0" type="checkbox" value="">
                                        </div>
                                        <span class="contact-name">Mr. John Doe</span>
                                        <span class="contact-profession">Creative Director of Azure Interior</span>
                                        <span class="contact-number">+(123) 456 789</span>
                                        <span class="contact-address">
                                            <span class="city">Fremont</span>
                                            <span class="comma">,</span>
                                            <span class="country">United States</span>
                                        </span>
                                        <span class="contact-email">example@mail.com</span>
                                        <span class="contact-sales-person">Abc David</span>
                                        <span class="contact-info">
                                        </span>
                                        <span class="company-name">Abc Defgh Ijkl</span>
                                    </div>
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
    <script src="https://raw.githubusercontent.com/weareoutman/clockpicker/gh-pages/dist/jquery-clockpicker.js"></script>
    <script>
        $('.show-list').on('click', function() {
            $(this).addClass('active').siblings().removeClass('active');
            $('.contact-card-group').removeClass('grid-view');
        });
        $('.show-grid').on('click', function() {
            $(this).addClass('active').siblings().removeClass('active');
            $('.contact-card-group').addClass('grid-view');
        });
    </script>
@endpush
