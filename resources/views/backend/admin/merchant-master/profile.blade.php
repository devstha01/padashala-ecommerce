@extends('backend.layouts.master')

@section('content')
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
                            <div class="row">
                                <div class="page-title">
                                    <h1>{{__('dashboard.Merchant Profile')}}
                                        <small></small>
                                    </h1>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE CONTENT BODY -->
                    <div class="page-content">
                        <div class="container">
                        @include('fragments.message')
                        <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">

                                <div class="portlet light">

                                    <div class="row">
                                        <div class=" col-md-3 col-sm-6">
                                            <div class="profile-userpic text-center">
                                                @if($merchant->logo !==null)
                                                    <img src="{{asset('image/merchantlogo/'.$merchant->logo)}}"
                                                         class="img-responsive"
                                                         alt="_" style="height: auto;width:100px">
                                                @else
                                                    <img src="{{asset('image/not-available.jpg')}}"
                                                         class="img-responsive"
                                                         alt="_" style="height: auto;width:100px">
                                                @endif
                                            </div>
                                        </div>
                                        <div class=" col-md-3 col-sm-6">

                                            <div class="profile-usertitle pull-left">
                                                <div class="profile-usertitle-name"> {{$merchant->name}} {{$merchant->surname}} </div>
                                                <div class="profile-usertitle-job"> {{$merchant->user_name}} </div>
                                                <div class="fa fa-envelope text-info "> {{$merchant->email}} </div>
                                                <br>
                                                {{--<br>--}}

                                                {{--<a href="{{route('admin-merchant-retain',$merchant->id)}}"--}}
                                                   {{--class="btn blue" style="margin-left: 5px">Retain Wallet</a>--}}
                                                {{--<a href="{{route('admin-merchant-grant',$merchant->id)}}"--}}
                                                   {{--class="btn blue">Grant Wallet</a>--}}
                                            </div>
                                        </div>


                                        <div class=" col-md-3 col-sm-6">
                                            <div class="row list-separated profile-stat">
                                                <div class="uppercase profile-stat-text">{{__('dashboard.Joined')}}<br>
                                                    {{\Carbon\Carbon::parse($merchant->joining_date)->diffForHumans()}}
                                                </div>
                                                {{--<div class="uppercase profile-stat-text">{{$merchant->identification_type}}</div>--}}
                                                {{--<div class="uppercase profile-stat-title"> {{$merchant->identification_number}} </div>--}}
                                            </div>
                                            <div class="profile-userbuttons">
                                                <a class="btn btn-circle green btn-sm" style="margin: 0 20px"
                                                   href="{{route('edit-merchant-id',$merchant->id)}}"><i
                                                            class="fa fa-edit"></i>
                                                    {{__('dashboard.Edit')}}
                                                </a>
                                                @if($merchant->status ===1)
                                                    <a title="click to disable"
                                                       class="btn btn-success btn-sm btn-circle"
                                                       href="{{route('change-status-merchant-admin',$merchant->id)}}"><i
                                                                class="fa fa-check"></i> {{__('dashboard.Status')}}</a>
                                                @else
                                                    <a title="click to enable" class="btn btn-danger btn-sm btn-circle"
                                                       href="{{route('change-status-merchant-admin',$merchant->id)}}"><i
                                                                class="fa fa-times"></i> {{__('dashboard.Status')}}</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class=" col-md-3 col-sm-6">


                                            <div>

                                                <h5 class="profile-desc-title">{{__('dashboard.About Merchant')}}</h5>
                                                <span class="profile-desc-text">
                                        <i class="fa fa-map"></i> {{$merchant->getCountry->name}}
                                                    <br><i class="fa fa-map-marker"></i> {{$merchant->city}}
                                                    , {{$merchant->address}}
                                                    <br><i class="fa fa-phone"></i> {{$merchant->contact_number}}
                                                    {{--<br>born on {{\Carbon\Carbon::parse($merchant->dob)->toFormattedDateString()}}--}}
                                                    {{--<br>{{$merchant->gender}} |--}}
                                                    {{--@if($merchant->marital_status === 'no')--}}
                                                    {{--{{__('dashboard.Unmarried')}}--}}
                                                    {{--@else--}}
                                                    {{--{{__('dashboard.Married')}}--}}
                                                    {{--@endif--}}
                                    </span>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="profile-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN PORTLET -->
                                                    <div class="portlet light ">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">{{__('dashboard.Detail')}}</span>
                                                            </div>
                                                            <div class="actions">
                                                                <div class="btn-group btn-group-devided"
                                                                     data-toggle="buttons">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">

                                                            <div class="tabbable-custom nav-justified">
                                                                <ul class="nav nav-tabs nav-justified">
                                                                    <li class="active">
                                                                        <a href="#tab_1_1"
                                                                           data-toggle="tab">{{__('dashboard.Products')}}</a>
                                                                    </li>

                                                                    <li class="">
                                                                        <a href="#tab_1_1_1"
                                                                           data-toggle="tab"> {{__('dashboard.All Orders')}} </a>
                                                                    </li>
                                                                    <li class="">
                                                                        <a href="#tab_1_1_2"
                                                                           data-toggle="tab">{{__('dashboard.Pending Orders')}}</a>
                                                                    </li>
                                                                    <li class="">
                                                                        <a href="#tab_1_1_3"
                                                                           data-toggle="tab">{{__('dashboard.Completed Orders')}}</a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <!-- PERSONAL INFO TAB -->
                                                                    <div class="tab-pane active" id="tab_1_1">
                                                                        <a class="float-right btn btn-primary "style="margin-left: 10px"
                                                                           href="{{route('admin-add-standard-product',$merchant->id)}}"><i
                                                                                    class="fa fa-plus"></i> Standard Product
                                                                        </a>
                                                                        <a class="float-right btn btn-primary"
                                                                           href="{{route('admin-add-product',$merchant->id)}}"><i
                                                                                    class="fa fa-plus"></i> {{__('dashboard.Product')}}
                                                                        </a>
                                                                        <br>
                                                                        <br>
                                                                        <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                                               id="sample_1">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>{{__('dashboard.Product')}}
                                                                                </th>
                                                                                <th style="width: 80px;">{{__('dashboard.Image')}}
                                                                                </th>
{{--                                                                                <th>{{__('dashboard.Detail')}}--}}
                                                                                {{--</th>--}}
                                                                                <th>
                                                                                    <i class="fa fa-edit">{{__('dashboard.Edit')}}</i>
                                                                                </th>
                                                                                <th>{{__('dashboard.Feature')}}</th>
                                                                                <th>{{__('dashboard.Status')}}</th>
                                                                                <th>{{__('dashboard.Approval')}}</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($merchant->getBusiness->getProducts as $key=>$product)
                                                                                <tr role="row"
                                                                                    class="{{($key%2==0)?'even':'odd'}}">
                                                                                    <td class="sorting_1"
                                                                                        tabindex="0">{{$product->name}}
                                                                                    </td>
                                                                                    <td>
                                                                                        <img src="{{asset('image/products/'.$product->featured_image)}}"
                                                                                             height="70px"
                                                                                             alt="image">
                                                                                    </td>
{{--                                                                                    <td>{{str_limit($product->detail,70)}}--}}
                                                                                    {{--</td>--}}
                                                                                    <td>
                                                                                        <a href="{{route('admin-edit-product',$product->slug)}}"><i
                                                                                                    class="fa fa-edit"> {{__('dashboard.Edit')}}</i></a>
                                                                                    </td>

                                                                                    <td>
                                                                                        @if($product->is_featured==1)
                                                                                            <i class="fa fa-check-circle text-success"> {{__('dashboard.Featured')}}</i>
                                                                                        @elseif(!\App\Models\FeatureProduct::where('product_id',$product->id)->where('flag',0)->first())
                                                                                            <a href="{{route('admin-featured-product-request',$product->id)}}"
                                                                                               class="fa fa-question-circle text-warning"> {{__('dashboard.Request')}} </a>
                                                                                        @else
                                                                                            <i class="fa fa-spinner text-danger"> {{__('dashboard.Processing')}}</i>
                                                                                        @endif

                                                                                    </td>
                                                                                    <td>
                                                                                        <form action="{{route('admin-change-product-status',$product->id)}}"
                                                                                              method="post"
                                                                                              style="display:inline-block">
                                                                                            {{csrf_field()}}
                                                                                            @if($product->status ===1)
                                                                                                <button class="btn btn-sm green"
                                                                                                        style="width:50px"
                                                                                                        type="submit"
                                                                                                        onclick="confirm('Are you sure you want to disable this product?')">
                                                                                                    <i class="fa fa-check"></i>
                                                                                                </button>
                                                                                            @else
                                                                                                <button class="btn btn-sm red"
                                                                                                        style="width:50px"
                                                                                                        type="submit"
                                                                                                        onclick="confirm('Are you sure you want to enable this product?')">
                                                                                                    <i class="fa fa-times"></i>
                                                                                                </button>

                                                                                            @endif
                                                                                        </form>
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$product->admin_flag?__('dashboard.Approved'):__('dashboard.Pending')}}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach

                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div class="tab-pane" id="tab_1_1_1">
                                                                        <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                                               id="sample_2">
                                                                            <thead>
                                                                            <tr>
                                                                                <th> {{__('dashboard.Invoice')}}#
                                                                                </th>
                                                                                <th>{{__('dashboard.Order Date')}}
                                                                                </th>
                                                                                <th>{{__('dashboard.Buyer Name')}}
                                                                                </th>
                                                                                <th style="min-width: 200px">{{__('dashboard.Product')}}
                                                                                </th>
                                                                                <th>{{__('dashboard.Action')}}
                                                                                </th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($orders as $k=>$order)
                                                                                @include('backend.admin.merchant-master.table-order-loop')
                                                                            @endforeach

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="tab-pane " id="tab_1_1_2">
                                                                        <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                                               id="sample_3">
                                                                            <thead>
                                                                            <tr>
                                                                                <th> {{__('dashboard.Invoice')}}#
                                                                                </th>
                                                                                <th>{{__('dashboard.Order Date')}}
                                                                                </th>
                                                                                <th>{{__('dashboard.Buyer Name')}}
                                                                                </th>
                                                                                <th style="min-width: 200px">{{__('dashboard.Product')}}
                                                                                </th>
                                                                                <th>{{__('dashboard.Action')}}
                                                                                </th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($p_orders as $k=>$order)
                                                                                @include('backend.admin.merchant-master.table-order-loop')
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="tab-pane " id="tab_1_1_3">
                                                                        <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                                               id="sample_4">
                                                                            <thead>
                                                                            <tr>
                                                                                <th> {{__('dashboard.Invoice')}}#
                                                                                </th>
                                                                                <th>{{__('dashboard.Order Date')}}
                                                                                </th>
                                                                                <th>{{__('dashboard.Buyer Name')}}
                                                                                </th>
                                                                                <th style="min-width: 200px">{{__('dashboard.Product')}}
                                                                                </th>
                                                                                <th>{{__('dashboard.Action')}}
                                                                                </th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($d_orders as $k=>$order)
                                                                                @include('backend.admin.merchant-master.table-order-loop')
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
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
    @endsection

    @section('scripts')
        <script>
            $(function () {
                $('#sample_4').dataTable();
                $('#sample_5').dataTable();
            });
        </script>
    @endsection
    @section('stylesheets')
        <link href="{{asset('backend/assets/pages/css/profile.min.css')}}" rel="stylesheet" type="text/css"/>
        <style>
            .dataTables_wrapper .dataTables_filter {
                display: block !important;
            }
        </style>
@endsection