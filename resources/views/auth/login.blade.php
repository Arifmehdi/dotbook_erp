@extends('layout.app')
@section('title', 'Login')
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
@endpush

@section('content')
    <style>
        .form-control::placeholder {
            color: #ddd;
            opacity: 1;
        }

        .back_btn_wrapper {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 6px;
            border-radius: 3px;
            box-shadow: -1px 0px 10px 1px #0a0a0a52;
        }

        .back_btn_wrapper .back_btn {
            border: 1px solid #0f76b673;
            padding: 0px 5px;
            border-radius: 3px;
            -webkit-box-shadow: inset 0 0 5px #666;
            box-shadow: inner 0 0 5px #666;
        }

        .back_btn_wrapper .back_btn a {
            color: white;
            font-size: 13px;
        }

        .back_btn_wrapper .back_btn a:focus {
            outline: unset;
            box-shadow: unset;
        }

        .user_login .form-title {
            background: unset;
            -webkit-box-shadow: unset;
            margin-top: -10px;
        }

        .user_login input.form-control.form-st {
            background: unset;
            border: 1px solid #ffffff69;
            border-radius: 4px;
            color: white;
        }

        .user_login .left-inner-addon.input-container {
            margin-bottom: 3px;
        }

        .main-form {
            padding: 6px;
            border-radius: 3px;
            box-shadow: -1px 0px 10px 1px #0a0a0a52;
        }

        .user_login .form_inner {
            border: 1px solid #0f76b673;
            padding: 12px 5px;
            border-radius: 3px;
            -webkit-box-shadow: inset 0 0 5px #666;
            box-shadow: inner 0 0 5px #666;
        }

        .left-inner-addon i {
            color: #f5f5f5 !important;
        }

        .btn-fn a {
            color: white;
        }

        .btn-fn a:hover {
            color: white;
        }

        .btn-fn.facebook {
            background: #3A5794;
        }

        .btn-fn.twitter {
            background: #1C9CEA;
        }

        .btn-fn.youtube {
            background: #F70000;
        }

        .version {
            margin-bottom: 40px;
            color: white;
            font-weight: 400;
            font-size: 14px
        }

        .login_opt_link .form-group input {
            display: inline-block;
            width: 12px;
            height: 12px;
        }

        .form-control {
            max-height: none;
        }

        .show-password {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
            width: max-content;
            font-size: 12px;
            color: #ffffff;
            cursor: pointer;
            transition: .2s;
        }

        .show-password:hover {
            color: #ffffff;
            opacity: 0.7;
        }

        p.details {
            display: flex;
        }

        p.details span {
            display: block;
            min-width: 60px;
        }
    </style>

    <div class="form-wraper user_login">
        <div class="container">
            <div class="form-content">
                <div class="inner-div col-lg-7">
                    <div class="border-div">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="main-form">
                                    <div class="form_inner">
                                        <div class="form-title">
                                            <p>@lang('menu.user_login')</p>
                                        </div>
                                        <form action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="left-inner-addon input-container mb-2">
                                                <i class="fa fa-user"></i>
                                                <input type="text" name="username" class="form-control form-st" value="{{ old('username') }}" placeholder="Username" required />
                                            </div>
                                            <div class="left-inner-addon input-container">
                                                <i class="fa fa-key"></i>
                                                <input name="password" type="Password" id="passwordInput" class="form-control form-st rounded-bottom" placeholder="Password" required />
                                                <a role="button" class="show-password" id="showPassword"><span class="far fa-eye"></span></a>
                                            </div>
                                            @if (Session::has('errorMsg'))
                                                <div class="bg-danger p-3 mt-4">
                                                    <p class="text-white">
                                                        {{ session('errorMsg') }}
                                                    </p>
                                                </div>
                                            @endif
                                            <button type="submit" class="submit-button">@lang('menu.login')</button>
                                            <div class="login_opt_link">
                                                @if (Route::has('password.request'))
                                                    <div class="form-group cx-box">
                                                        <a class="forget-pw" href="{{ route('password.request') }}">
                                                            &nbsp; {{ __('Forgot password?') }}
                                                        </a>
                                                    </div>
                                                @endif
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="remembar">
                                                    <label class="form-check-label mb-1" for="remembar">
                                                        {{ __('Remember me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-head addr">
                                    <div class="head addr-t">
                                        <div class="d-flex flex-column align-items-center">
                                            <p class="logo-main-sec">
                                                <img src="{{ asset('images/logo.png') }}" class="logo">
                                            </p>
                                            <p class="text-white">
                                                {{ __('Enterprise Resource Planning') }}
                                            </p>

                                            <div class="mt-4">
                                                <p class="details"><span>{{ __('Address') }} :</span>Uttara, Dhaka - 1230</p>
                                                <p class="details"><span>{{ __('Support') }} :</span>bbcoderbd@gmail.com</p>
                                                {{-- <p class="details"><span>{{ __('Website') }} :</span>www.marifsoft.com</p> --}}
                                            </div>

                                            <div class="function-btn">
                                                <a href="#" target="_blank"><span class="btn-fn facebook"><i class="fab fa-facebook"></i></span></a>
                                                <a href="#" target="_blank"><span class="btn-fn twitter"><i class="fab fa-twitter"></i></span></a>
                                                <a href="#" target="_blank"><span class="btn-fn youtube"><i class="fab fa-youtube"></i></span></a>
                                                {{-- <a href="https://www.facebook.com/speeddigit" target="_blank"><span class="btn-fn facebook"><i class="fab fa-facebook"></i></span></a>
                                                <a href="https://twitter.com/speeddigit" target="_blank"><span class="btn-fn twitter"><i class="fab fa-twitter"></i></span></a>
                                                <a href="https://www.youtube.com/channel/UCaAEw77OeMvjwu5vOjueWmQ" target="_blank"><span class="btn-fn youtube"><i class="fab fa-youtube"></i></span></a> --}}
                                            </div>
                                        </div>
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

    <script>
        $('#showPassword').on('click', function() {
            $(this).find('span').toggleClass('fa-eye-slash');
            var textType = $('#passwordInput').attr('type');
            var passType;
            if (textType == 'text') {
                passType = 'password';
            } else {
                passType = 'text';
            }
            $('#passwordInput').attr('type', passType);
        });
    </script>
    {{-- Allow Only Developer machine --}}
    @if (config('auth.is_developer'))
        <script>
            $('input[name="username"]').val('superadmin');
            $('input[name="password"]').val('password');
        </script>
    @endif
@endpush
