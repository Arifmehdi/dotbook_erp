@extends('layout.app')
@section('title', 'Forget Password')
@section('content')
<div class="form-wraper">
    <div class="container">
        <div class="form-content">
            <div class="col-lg-4 col-md-5 col-12">
                <div class="form-head">
                    <div class="head">
                        <p class="logo-main-sec">
                            <img src="{{ asset('images/logo.png') }}" class="logo">
                        </p>
                    </div>
                </div>
                <div>
                    @if (session('status'))
                    <div class="bg-success p-3 mt-4 text-white">
                        {{ session('status') }}
                    </div>
                    @endif
                </div>

                <div class="main-form">
                    <div class="form-title">
                        <p>@lang('menu.forgot_password')</p>
                    </div>
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="left-inner-addon input-container">
                            <i class="fa fa-envelope"></i>
                            <input type="email" name="email" class="form-control form-st rounded-bottom
                            @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter Your Email" autocomplete="email" required autofocus />

                        </div>
                        @if (Session::has('errorMsg'))
                        <div class="bg-danger p-3 mt-4">
                            <p class="text-white">
                                {{ session('errorMsg') }}
                            </p>
                        </div>
                        @endif

                        @if($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="bg-danger p-3 mt-4">
                            <p class="text-white">
                                {{ $error }}
                            </p>
                        </div>
                        @endforeach
                        @endif
                        <button type="submit" class="submit-button">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
