@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.notification')</h6>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
                </div>
            </div>
            <div class="notification-area">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-3 d-xl-block d-none">
                            <div class="left-side">
                                <ul>
                                    <li class="list-1">@lang('menu.today')</li>
                                    <li class="list-2">yesterday</li>
                                    <li class="list-3">22 august 2022</li>
                                    <li class="list-4">21 august 2022</li>
                                    <li class="list-5">20 august 2022</li>
                                    <li class="list-6">19 august 2022</li>
                                    <li class="list-7">18 august 2022</li>
                                    <li class="list-8">17 august 2022</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-8 offset-xl-1 col-md-8">
                            <div class="right-side">
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">today</span>
                                        <span class="dot"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>md jack</b> Lorem ipsum dolor sit amet <a href="#">The trasure of the three witches</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>a day ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">yesterday</span>
                                        <span class="dot dissable"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>snuck</b> Lorem ipsum dolor sit amet <a href="#">Adventures on the high sess</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>2 @lang('menu.days') ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">22 august 2022</span>
                                        <span class="dot"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>

                                        <span><b>angus dagnabbit</b> Lorem ipsum
                                            <a href="#">
                                                The trasure from <span class="progress-btn btn">In progress</span> to
                                                <span class="done-btn btn">@lang('menu.done')</span>
                                            </a>
                                        </span>

                                    </div>
                                    <div class="notification-time">
                                        <span>3 @lang('menu.days') ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">21 august 2022</span>
                                        <span class="dot"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>harryson roy</b> Lorem ipsum dolor sit amet <a href="#">Adventures on the high sess</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>4 @lang('menu.days') ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">20 august 2022</span>
                                        <span class="dot"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>angus dagnabbit</b> Lorem ipsum dolor sit amet <a href="#">The trasure of the three witches</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>5 @lang('menu.days') ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">19 august 2022</span>
                                        <span class="dot"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>harryson roy</b> Lorem ipsum dolor sit amet <a href="#">Adventures on the high sess</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>6 @lang('menu.days') ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">18 august 2022</span>
                                        <span class="dot dissable"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>harryson roy</b> Lorem ipsum dolor sit amet <a href="#">Adventures on the high sess</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>7 @lang('menu.days') ago</span>
                                    </div>
                                </div>
                                <div class="single-notification">
                                    <div class="notification-content">
                                        <span class="date">17 august 2022</span>
                                        <span class="dot"></span>
                                        <span class="icon"><i class="fa-light fa-user"></i></span>
                                        <span><b>harryson roy</b> Lorem ipsum dolor sit amet <a href="#">Adventures on the high sess</a></span>
                                    </div>
                                    <div class="notification-time">
                                        <span>8 @lang('menu.days') ago</span>
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
