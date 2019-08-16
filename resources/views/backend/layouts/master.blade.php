<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{Config::get('app.name')}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="{{Config::get('app.name')}}" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<link rel="shortcut icon" type="image/png" href="/favicon.ico"/>--}}
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    {{--<link href="{{ URL::asset('backend/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}"--}}
    {{--rel="stylesheet" type="text/css"/>--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="{{ URL::asset('backend/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/css/error.min.css') }}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet"
          type="text/css"/>

    {{--<link href="{{ URL::asset('backend/plugin/DataTables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>--}}

    <link href="{{ URL::asset('backend/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet"
          type="text/css"/>

    <link href="{{URL::asset('backend/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}"
          rel="stylesheet" type="text/css"/>


    <link href="{{ URL::asset('backend/assets/layouts/layout3/css/layout.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('backend/assets/layouts/layout3/css/themes/default.min.css') }}" rel="stylesheet"
          type="text/css"
          id="style_color"/>

    <link href="{{ URL::asset('assets/custom/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/custom/css/admin.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/custom/css/global.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('backend/assets/layouts/layout3/css/custom.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <script src="{{ asset('backend/assets/apps/scripts/jquery.js') }}" type="text/javascript"></script>


    <link rel="stylesheet" href="{{ URL::asset('backend/assets/global/css/OrgChart/jquery.orgchart.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('backend/memlist/dist/themes/default/style.min.css') }}">


    <!--JS-->
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="{{ asset('backend/js/members/jquery.orgchart.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/memlist/dist/jstree.min.js') }}"></script>
    @yield('stylesheets')
</head>

@stack('styles')

<body class="page-container-bg-solid">
<div class="page-wrapper">
    @include('backend.includes.header')
    @yield('content')
</div>
<!-- begin::Page loader black transparent -->
<div class="m-page-loader m-page-loader--base page-load-new" style="display:none;">
    <div class="m-blockui">
				<span>
					Please wait...
				</span>
        <span>
					<div class="m-loader m-loader--brand"></div>
				</span>
    </div>
</div>
<!-- end::Page Loader -->
<script>
    var serverCustom = {
        base_url: "{{url('/')}}",
    };

    //currency symbol
    function cuSymbol() {
        return "$"
    }

    //currency convert
    function cuConvert(amount) {
        var multiplier = 1;
        return (amount * multiplier).toFixed(2);
    }

</script>

<script src="{{ URL::asset('assets/common.js') }}"></script>

<script src="{{ URL::asset('backend/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/select2/js/select2.min.js') }}"
        type="text/javascript"></script>
<script src="{{URL::asset('backend/assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
{{--<script src="{{URL::asset('backend/plugin/DataTables/datatables.min.js')}}" type="text/javascript"></script>--}}

<script src="{{ URL::asset('backend/assets/global/plugins/datatables/datatables.min.js') }}"
        type="text/javascript"></script>
<script src="{{URL::asset('backend/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}"
        type="text/javascript"></script>
<script src="{{URL::asset('backend/assets/pages/scripts/table-datatables-buttons.min.js')}}"
        type="text/javascript"></script>


<script src="{{ URL::asset('backend/assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"
        type="text/javascript"></script>

<script src="{{URL::asset('backend/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"
        type="text/javascript"></script>
<script src="{{URL::asset('backend/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"
        type="text/javascript"></script>

<script src="{{ URL::asset('backend/assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/fullcalendar/fullcalendar.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/pages/scripts/dashboard.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/layouts/layout3/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/scripts/sweetalert.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/custom/js/ajax-post.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/custom/repo.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('backend/assets/apps/scripts/jquery-ui.js') }}" type="text/javascript"></script>


<script type="text/javascript">
    var APP_URL = {!! json_encode(url('/')) !!};

    $('.date-picker').datepicker();

</script>
<script src="{{asset('backend/plugin/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.replace('ckeditor-replace');
</script>

@yield('scripts')
</body>

</html>
