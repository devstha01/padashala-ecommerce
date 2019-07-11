<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Golden Gate (hk)</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/pages/css/login.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <style>
        .error-message {
            color: red;
        }

        .reset-form {
            display: none;
        }

        .has-error .form-control {
            border-color: #e73d4a !important;
        }

        .login {
            /*background-color: #364150 !important;*/
            background-color: whitesmoke !important;
            position: relative;
        }

        img {
            max-width: 400px;
        }

        ul {
            list-style: none;
            /*line-height:2.5em;*/
        }

        li {
            display: inline-block;
            margin-right: 65px;
        }

        .selector {
            margin-left: -20px;
        }

        img.logo-select {
            max-width: 50px;
            width: 100%;
            height: auto;
        }

        .flag {
            margin-top: 10px;
            border: none;
        }

        .selector ul li {
            width: 30%;
            float: left;
            margin-right: 5px;
        }

    </style>
</head>
<!-- END HEAD -->

<body class=" login">
<!-- BEGIN LOGO -->
<div class="logo">
{{--    <a href="{{ url('/') }}">--}}
        <img src="{{ asset('image/gghl-logo.png') }}" height="60px" alt="_"/>
    {{--</a>--}}
</div>

<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content" >
    <!-- BEGIN LOGIN FORM -->
    <form action="{{route('b-post-reset',['url'=>$url,'token'=>$recover])}}" method="post">
        {{csrf_field()}}

        <h3 class="form-title ">{{__('dashboard.Reset Password')}}</h3>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span>{{__('dashboard.Enter Password')}}</span>
        </div>
        @include('fragments.message')
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{__('dashboard.Password')}}</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Password" name="password"/>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{__('dashboard.Confirm Password')}}</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Confirm Password" name="password_confirmation"/>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn custom-blue uppercase">{{__('dashboard.Submit')}}</button>
        </div>
    </form>

</div>

<script src="{{ URL::asset('backend/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/pages/scripts/login.js') }}" type="text/javascript"></script>

</body>

</html>