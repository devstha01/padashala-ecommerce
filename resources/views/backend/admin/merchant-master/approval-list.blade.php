@extends('backend.layouts.master')

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
                            <div class="row">
                                <div class="page-title">
                                    <h1>{{__('dashboard.Product Approval List')}}
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
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                           id="sample_1">
                                        <thead>
                                        <tr>
                                            <th>{{__('dashboard.Merchant')}}</th>
                                            <th>{{__('dashboard.Product')}}</th>
                                            <th style="width: 80px;">{{__('dashboard.Image')}}
                                            </th>
                                            <th>{{__('dashboard.Detail')}}
                                            </th>
                                            <th>
                                                <i class="fa fa-edit">{{__('dashboard.Edit')}}</i>
                                            </th>
                                            <th>{{__('dashboard.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($products as $key=>$product)
                                            <tr role="row"
                                                class="{{($key%2==0)?'even':'odd'}}">
                                                <td class="sorting_1" tabindex="0">
                                                    {{$product->getBusiness->getMerchant->user_name}}
                                                    <br>
                                                    {{$product->getBusiness->name}}
                                                </td>
                                                <td>{{$product->name}}</td>
                                                <td>
                                                    <img src="{{asset('image/products/'.$product->featured_image)}}"
                                                         height="70px"
                                                         alt="image">
                                                </td>
                                                <td>{{str_limit($product->detail,70)}}
                                                </td>
                                                <td>
                                                    <a href="{{route('admin-edit-product',$product->slug)}}"><i
                                                                class="fa fa-edit"> {{__('dashboard.Edit')}}</i></a>
                                                </td>

                                                <td>
                                                    <form action="{{route('admin-approve-status',$product->id)}}"
                                                          method="post"
                                                          style="display:inline-block">
                                                        {{csrf_field()}}
                                                        <button class="btn btn-sm green"
                                                                style="width:80px"
                                                                type="submit"
                                                                onclick="confirm('Are you sure you want to approve this product?')">
                                                            <i class="fa fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{route('admin-delete-product-status',$product->id)}}"
                                                          method="post"
                                                          style="display:inline-block">
                                                        {{csrf_field()}}
                                                        <button class="btn btn-sm red"
                                                                style="width:80px"
                                                                type="submit"
                                                                onclick="confirm('Are you sure you want to delete this product?')">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
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
        <link href="{{asset('backend/assets/pages/css/profile.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <style>
            .dataTables_wrapper .dataTables_filter {
                display: block !important;
            }
        </style>
@endsection