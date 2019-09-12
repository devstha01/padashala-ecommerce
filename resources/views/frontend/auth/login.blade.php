@extends('frontend.layouts.app')
@section('content')
    {{--<div id="login-facebook"></div>--}}
    {{--<script>--}}
        {{--window.fbAsyncInit = function () {--}}
            {{--FB.init({--}}
                {{--appId: 'login-facebook',--}}
                {{--cookie: true,--}}
                {{--xfbml: true,--}}
                {{--version: '3.3'--}}
            {{--});--}}

            {{--FB.AppEvents.logPageView();--}}

        {{--};--}}

        {{--(function (d, s, id) {--}}
            {{--var js, fjs = d.getElementsByTagName(s)[0];--}}
            {{--if (d.getElementById(id)) {--}}
                {{--return;--}}
            {{--}--}}
            {{--js = d.createElement(s);--}}
            {{--js.id = id;--}}
            {{--js.src = "https://connect.facebook.net/en_US/sdk.js";--}}
            {{--fjs.parentNode.insertBefore(js, fjs);--}}
        {{--}(document, 'script', 'facebook-jssdk'));--}}
    {{--</script>--}}
    {{--<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.3&appId=2286193824790008&autoLogAppEvents=1"></script>--}}

    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Login')}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="heading">
                        <h2 class="title">{{__('front.Login')}}</h2>
                        {{--<p>If you have an account with us, please log in.--}}
                        {{--<a href="{{route('customer-register')}}"> Create an account!</a>--}}
                        {{--</p>--}}
                    </div><!-- End .heading -->

                    @if(!empty($errors->first('no-match-error')))
                        <div class="alert alert-danger">
                            {{$errors->first('no-match-error')}}
                        </div>
                    @endif

                    @if(!empty(session('verify-email')))
                        <div class="alert alert-danger">
                            {{session('verify-email')}}
                            <a class="pull-right" href="{{route('customer-verify')}}"><i
                                        class="fa fa-envelope"></i> {{__('email.Send verification email again!')}}</a>
                        </div>
                    @endif

                    @include('fragments.message')
                    <form action="{{route('login-customer')}}" method="post">
                        {{csrf_field()}}

                        @if(URL::previous() == url('cart-view') )
                            <input type="hidden" name="url" value="{{ URL::previous()}}">
                        @else
                            <input type="hidden" name="url" value="{{ url('/')}}">
                        @endif
                        <input type="text" class="form-control" name="user_name" value="{{old('user_name')}}"
                               placeholder="Username">
                        <input type="password" class="form-control" name="password" placeholder="Password">

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{__('front.Login')}}</button>
                            <a href="{{route('frontend-recovery')}}"
                               class="forget-pass">{{__('front.Forgot password?')}}</a>
                        </div><!-- End .form-footer -->
                    </form>
                    <hr>

                    {{--<div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false"></div>--}}
                    {{--<a href="#" id="login-facebook"><i class="fa fa-facebook-square"></i> Login with Facebook</a>--}}
                    <a href="{{ url('/login/facebook') }}" class="btn btn-lg btn-primary btn-block">
                        <strong>Login With Facebook</strong>
                    </a>
                    <a href="{{ url('/login/google') }}" class="btn btn-lg btn-primary btn-block">
                        <strong>Login With Google </strong>
                    </a>
                </div>

            </div><!-- End .row -->
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main><!-- End .main -->
@endsection
