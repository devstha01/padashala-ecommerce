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
                                    <h1>Products
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

                                <div class="portlet light ">

                                    <div class="tabbable-custom nav-justified">
                                        <ul class="nav nav-tabs nav-justified">

                                            <li class="{{$active_tab==='all'?'active':''}}">
                                                <a href="{{route('all-product-admin')}}"> All Products </a>
                                            </li>
                                            <li class="{{$active_tab==='standard'?'active':''}}">
                                                <a href="{{route('standard-product-admin')}}">Standard Products</a>
                                            </li>
                                            <li class="{{$active_tab==='normal'?'active':''}}">
                                                <a href="{{route('normal-product-admin')}}">Normal Products</a>
                                            </li>
                                            <li class="{{$active_tab==='inactive'?'active':''}}">
                                                <a href="{{route('inactive-product-admin')}}">Inactive Products</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                   id="sample_1">
                                                <thead>
                                                <tr>
                                                    <td>S/N</td>
                                                    <td>Merchant</td>
                                                    <td>Product</td>
                                                    <td>Image</td>
                                                    <td>Standard</td>
                                                    <td>Status</td>
                                                    <td>Action</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($products as $key=>$product)
                                                    <tr>
                                                        <td>{{++$key}}</td>
                                                        <td>{{$product->getBusiness->name}}</td>
                                                        <td>{{$product->name}}</td>
                                                        <td>
                                                            <img src="{{asset('image/products/'.$product->featured_image)}}"
                                                                 height="70px"
                                                                 alt="image">
                                                        </td>
                                                        <td>
                                                            <form action="{{route('admin-change-product-standard',$product->id)}}"
                                                                  method="post"
                                                                  style="display:inline-block">
                                                                {{csrf_field()}}
                                                                @if($product->standard_product ===1)
                                                                    <button class="btn btn-sm green"
                                                                            style="width:50px"
                                                                            type="submit">
                                                                        <i class="fa fa-check"></i>
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-sm red"
                                                                            style="width:50px"
                                                                            type="submit">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>

                                                                @endif
                                                            </form>
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
                                                            <a href="{{route('admin-edit-product',$product->slug)}}"><i
                                                                        class="fa fa-edit"> {{__('dashboard.Edit')}}</i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
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
@endsection
@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@endsection