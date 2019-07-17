<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{Config::get('app.name')}}</title>

    <meta name="keywords" content="HTML5 Template"/>
    <meta name="description" content="{{Config::get('app.name')}}">
    <meta name="author" content="SW-THEMES">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('frontend/assets/images/icons/favicon.ico') }}">

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ URL::asset('backend/assets/global/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>


    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/assets/css/style.css') }}">
{{--<link rel="stylesheet" href="{{ URL::asset('frontend/assets/css/delete.css')}}">--}}

<!--custom css-->
{{--    <link rel="stylesheet" href="{{URL::asset('frontend/assets/css/custom.css')}}">--}}

<!--merchantlist css-->
    <link rel="stylesheet" href="{{URL::asset('frontend/assets/css/merchantlist.css')}}">
    <link rel="stylesheet" href="{{URL::asset('frontend/assets/css/account.css')}}">
    <link rel="stylesheet" href="{{URL::asset('frontend/assets/css/category.css')}}">

    @yield('stylesheets')


    <script src="{{ URL::asset('frontend/assets/js/jquery.min.js') }}"></script>

</head>
<body>
<div class="page-wrapper">

    @include('frontend.layouts.include.header')

    @yield('content')

    @include('frontend.layouts.include.footer')

</div><!-- End .page-wrapper -->


@include('frontend.layouts.include.mobile')

<a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

<script>
    var serverCustom = {
        base_url: "{{url('/')}}",
        current_uri: "{{Request::path()}}",
    };
</script>
<!-- Plugins JS File -->
<script src="{{ URL::asset('assets/common.js') }}"></script>
<script src="{{ URL::asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('frontend/assets/js/plugins.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('frontend/assets/js/elevatezoom-plus-master/src/jquery.ez-plus.js')}}"></script>

<!-- Main JS File -->
<script src="{{ URL::asset('frontend/assets/js/main.js') }}"></script>

{{--Custom js File--}}
<script src="{{ URL::asset('frontend/assets/js/category.js')}}"></script>
<script src="{{ URL::asset('frontend/assets/js/cart.js')}}"></script>
<script src="{{ URL::asset('frontend/assets/js/header.js')}}"></script>


<!-- Google Map-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDc3LRykbLB-y8MuomRUIY0qH5S6xgBLX4"></script>

<script src="{{ URL::asset('frontend/assets/js/map.js')}}"></script>
<script src="{{URL::asset('frontend/assets/js/submit-filter.js')}}"></script>
<script src="{{ URL::asset('backend/assets/global/scripts/sweetalert.min.js') }}" type="text/javascript"></script>

<!-- www.addthis.com share plugin -->
{{--<script src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b927288a03dbde6"></script>--}}
@yield('scripts')
</body>
</html>
