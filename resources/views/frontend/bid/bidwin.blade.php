<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{env('APP_NAME')}}</title>

    <meta name="keywords" content="HTML5 Template"/>
    <meta name="description" content="Bootstrap eCommerce Template">
    <meta name="author" content="SW-THEMES">
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('frontend/assets/images/icons/favicon.ico') }}">

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/assets/css/bootstrap.min.css') }}">
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">--}}
<!-- Main CSS File -->
    {{--    <link rel="stylesheet" href="{{ URL::asset('frontend/assets/css/style.css') }}">--}}
    {{--<link rel="stylesheet" href="{{URL::asset('frontend/assets/css/category.css')}}">--}}

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('frontend/assets/css/bid.css')}}">

</head>
<body>

<div id="comingSoon">
    <div class="row">
        <div class="col-sm-5 col-xs-6 col-6"
             style="position: relative; height: 100vh; padding: 2% 6%;">
            <div class="container">
                <div id="logo">
                    <a href="{{url('/')}}">
                        <img src="{{asset('image/gghl-logo.png')}}" alt="_">
                    </a>
                </div>

                <div id="main">
                    <h1>Coming Soon..</h1>
                    <p>We are preparing something amazing and exciting for you.
                        Stay Tuned for the Surprise!!
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-7 col-xs-6 col-6 right">

        </div>
    </div>
</div>
<script src="{{ URL::asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>