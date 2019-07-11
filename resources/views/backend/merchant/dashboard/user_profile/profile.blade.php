@extends('backend.layouts.master')
@section('stylesheets')
    <link href="{{asset('backend/assets/pages/css/profile.min.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <body class="page-container-bg-solid">
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{__('dashboard.Profile')}}
                                    <small></small>
                                </h1>
                            </div>
                            <!-- END PAGE TITLE -->
                            <!-- BEGIN PAGE TOOLBAR -->
                        </div>
                    </div>
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE CONTENT BODY -->
                    <div class="page-content">
                        <div class="container">
                        @include('fragments.message')
                        <!-- BEGIN PAGE BREADCRUMBS -->
                        {{--<ul class="page-breadcrumb breadcrumb">--}}
                        {{--<li>--}}
                        {{--<a href="">Home</a>--}}
                        {{--<i class="fa fa-circle"></i>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="{{route('merchant-profile')}}">Profile</a>--}}
                        {{--<i class="fa fa-circle"></i>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<span>User</span>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN PROFILE SIDEBAR -->
                                        @include('fragments.merchant.left-part-merchant')

                                        <div class="profile-content">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <!-- BEGIN PORTLET -->
                                                    <div class="portlet light ">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">{{__('dashboard.Your Business Detail')}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body ">
                                                            <div class="row">
                                                                <div class="col-sm-8">

                                                                    <h4>
                                                                        {{$merchant->getBusiness->name}}
                                                                    </h4>

                                                                    <span class="profile-desc-text">
                                                                <b>
                                                                <?php echo $merchant->getBusiness->registration_number ? "# " . $merchant->getBusiness->registration_number . "<br>" : ''?>
                                                                </b>
                                                                        {{$merchant->getBusiness->getCountry->name}}
                                                                        <br>{{$merchant->getBusiness->city}}
                                                                        <br>{{$merchant->getBusiness->address}}
                                                                        <br>{{$merchant->getBusiness->contact_number}}
                                                            </span>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    {{__('dashboard.Merchant Rate')}}
                                                                    - {{$merchant->getShoppingRate->merchant_rate??0}}%
                                                                    <br>
                                                                    {{$merchant->getShoppingRate->admin_rate??0}}% - {{__('dashboard.Admin Rate')}}

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END PORTLET -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PAGE CONTENT INNER -->
                        </div>
                    </div>
                    <!-- END PAGE CONTENT BODY -->
                    <!-- END CONTENT BODY -->
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT -->
@stop