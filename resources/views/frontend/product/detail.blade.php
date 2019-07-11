@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    @include('frontend.product.include.left-detail-section')
                </div><!-- End .col-lg-9 -->

                <div class="sidebar-overlay"></div>
                <div class="sidebar-toggle"><i class="icon-sliders"></i></div>
                <aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
                    <div class="sidebar-wrapper">
                        {{--<div class="widget widget-brand">--}}
                            {{--<a href="#">--}}
                                {{--<img src="{{ URL::asset('frontend/assets/images/product-brand.png') }}"--}}
                                     {{--alt="brand name">--}}
                            {{--</a>--}}
                        {{--</div><!-- End .widget -->--}}

                        <div class="widget widget-info">
                            <ul>
                                <li>
                                    <i class="icon-shipping"></i>
                                    <h4>{{__('front.Shipping Title')}}</h4>
                                </li>
                                <li>
                                    <i class="icon-us-dollar"></i>
                                    <h4>{{__('front.Money Title')}}</h4>
                                </li>
                                <li>
                                    <i class="icon-online-support"></i>
                                    <h4>{{__('front.Support Title')}}</h4>
                                </li>
                            </ul>
                        </div><!-- End .widget -->

                        <div class="widget widget-banner">
                            <div class="banner banner-image">
                                <a href="#">
                                    <img src="{{ URL::asset('frontend/assets/images/banners/banner-sidebar.jpg') }}"
                                         alt="Banner Desc">
                                </a>
                            </div><!-- End .banner -->
                        </div><!-- End .widget -->

                        {{--<div class="widget widget-featured">--}}
                            {{--<h3 class="widget-title">{{__('front.Featured Products')}}</h3>--}}

                            {{--<div class="widget-body">--}}
                                {{--<div class="owl-carousel widget-featured-products">--}}
                                    {{--<div class="featured-col">--}}
                                        {{--<div class="product product-sm">--}}
                                            {{--<figure class="product-image-container">--}}
                                                {{--<a href="product.html" class="product-image">--}}
                                                    {{--<img src="{{ URL::asset('frontend/assets/images/products/small/product-1.jpg') }}"--}}
                                                         {{--alt="product">--}}
                                                {{--</a>--}}
                                            {{--</figure>--}}
                                            {{--<div class="product-details">--}}
                                                {{--<h2 class="product-title">--}}
                                                    {{--<a href="product.html">{{__('front.Ring')}}</a>--}}
                                                {{--</h2>--}}
                                                {{--<div class="ratings-container">--}}
                                                    {{--<div class="product-ratings">--}}
                                                        {{--<span class="ratings" style="width:80%"></span>--}}
                                                        {{--<!-- End .ratings -->--}}
                                                    {{--</div><!-- End .product-ratings -->--}}
                                                {{--</div><!-- End .product-container -->--}}
                                                {{--<div class="price-box">--}}
                                                    {{--<span class="product-price">$45.00</span>--}}
                                                {{--</div><!-- End .price-box -->--}}
                                            {{--</div><!-- End .product-details -->--}}
                                        {{--</div><!-- End .product -->--}}

                                        {{--<div class="product product-sm">--}}
                                            {{--<figure class="product-image-container">--}}
                                                {{--<a href="product.html" class="product-image">--}}
                                                    {{--<img src="{{ URL::asset('frontend/assets/images/products/small/product-2.jpg') }}"--}}
                                                         {{--alt="product">--}}
                                                {{--</a>--}}
                                            {{--</figure>--}}
                                            {{--<div class="product-details">--}}
                                                {{--<h2 class="product-title">--}}
                                                    {{--<a href="product.html">Headphone</a>--}}
                                                {{--</h2>--}}
                                                {{--<div class="ratings-container">--}}
                                                    {{--<div class="product-ratings">--}}
                                                        {{--<span class="ratings" style="width:20%"></span>--}}
                                                        {{--<!-- End .ratings -->--}}
                                                    {{--</div><!-- End .product-ratings -->--}}
                                                {{--</div><!-- End .product-container -->--}}
                                                {{--<div class="price-box">--}}
                                                    {{--<span class="old-price">$60.00</span>--}}
                                                    {{--<span class="product-price">$45.00</span>--}}
                                                {{--</div><!-- End .price-box -->--}}
                                            {{--</div><!-- End .product-details -->--}}
                                        {{--</div><!-- End .product -->--}}

                                        {{--<div class="product product-sm">--}}
                                            {{--<figure class="product-image-container">--}}
                                                {{--<a href="product.html" class="product-image">--}}
                                                    {{--<img src="{{ URL::asset('frontend/assets/images/products/small/product-3.jpg') }}"--}}
                                                         {{--alt="product">--}}
                                                {{--</a>--}}
                                            {{--</figure>--}}
                                            {{--<div class="product-details">--}}
                                                {{--<h2 class="product-title">--}}
                                                    {{--<a href="product.html">Shoes</a>--}}
                                                {{--</h2>--}}
                                                {{--<div class="ratings-container">--}}
                                                    {{--<div class="product-ratings">--}}
                                                        {{--<span class="ratings" style="width:100%"></span>--}}
                                                        {{--<!-- End .ratings -->--}}
                                                    {{--</div><!-- End .product-ratings -->--}}
                                                {{--</div><!-- End .product-container -->--}}
                                                {{--<div class="price-box">--}}
                                                    {{--<span class="product-price">$50.00</span>--}}
                                                {{--</div><!-- End .price-box -->--}}
                                            {{--</div><!-- End .product-details -->--}}
                                        {{--</div><!-- End .product -->--}}
                                    {{--</div><!-- End .featured-col -->--}}

                                    {{--<div class="featured-col">--}}
                                        {{--<div class="product product-sm">--}}
                                            {{--<figure class="product-image-container">--}}
                                                {{--<a href="product.html" class="product-image">--}}
                                                    {{--<img src="{{ URL::asset('frontend/assets/images/products/small/product-4.jpg') }}"--}}
                                                         {{--alt="product">--}}
                                                {{--</a>--}}
                                            {{--</figure>--}}
                                            {{--<div class="product-details">--}}
                                                {{--<h2 class="product-title">--}}
                                                    {{--<a href="product.html">Watch-Black</a>--}}
                                                {{--</h2>--}}
                                                {{--<div class="ratings-container">--}}
                                                    {{--<div class="product-ratings">--}}
                                                        {{--<span class="ratings" style="width:100%"></span>--}}
                                                        {{--<!-- End .ratings -->--}}
                                                    {{--</div><!-- End .product-ratings -->--}}
                                                {{--</div><!-- End .product-container -->--}}
                                                {{--<div class="price-box">--}}
                                                    {{--<span class="old-price">$50.00</span>--}}
                                                    {{--<span class="product-price">$35.00</span>--}}
                                                {{--</div><!-- End .price-box -->--}}
                                            {{--</div><!-- End .product-details -->--}}
                                        {{--</div><!-- End .product -->--}}

                                        {{--<div class="product product-sm">--}}
                                            {{--<figure class="product-image-container">--}}
                                                {{--<a href="product.html" class="product-image">--}}
                                                    {{--<img src="{{ URL::asset('frontend/assets/images/products/small/product-5.jpg') }}"--}}
                                                         {{--alt="product">--}}
                                                {{--</a>--}}
                                            {{--</figure>--}}
                                            {{--<div class="product-details">--}}
                                                {{--<h2 class="product-title">--}}
                                                    {{--<a href="product.html">Watch-Gray</a>--}}
                                                {{--</h2>--}}
                                                {{--<div class="ratings-container">--}}
                                                    {{--<div class="product-ratings">--}}
                                                        {{--<span class="ratings" style="width:60%"></span>--}}
                                                        {{--<!-- End .ratings -->--}}
                                                    {{--</div><!-- End .product-ratings -->--}}
                                                {{--</div><!-- End .product-container -->--}}
                                                {{--<div class="price-box">--}}
                                                    {{--<span class="product-price">$29.00</span>--}}
                                                {{--</div><!-- End .price-box -->--}}
                                            {{--</div><!-- End .product-details -->--}}
                                        {{--</div><!-- End .product -->--}}

                                        {{--<div class="product product-sm">--}}
                                            {{--<figure class="product-image-container">--}}
                                                {{--<a href="product.html" class="product-image">--}}
                                                    {{--<img src="assets/images/products/small/product-6.jpg"--}}
                                                         {{--alt="product">--}}
                                                {{--</a>--}}
                                            {{--</figure>--}}
                                            {{--<div class="product-details">--}}
                                                {{--<h2 class="product-title">--}}
                                                    {{--<a href="product.html">Hat</a>--}}
                                                {{--</h2>--}}
                                                {{--<div class="ratings-container">--}}
                                                    {{--<div class="product-ratings">--}}
                                                        {{--<span class="ratings" style="width:20%"></span>--}}
                                                        {{--<!-- End .ratings -->--}}
                                                    {{--</div><!-- End .product-ratings -->--}}
                                                {{--</div><!-- End .product-container -->--}}
                                                {{--<div class="price-box">--}}
                                                    {{--<span class="product-price">$40.00</span>--}}
                                                {{--</div><!-- End .price-box -->--}}
                                            {{--</div><!-- End .product-details -->--}}
                                        {{--</div><!-- End .product -->--}}
                                    {{--</div><!-- End .featured-col -->--}}
                                {{--</div><!-- End .widget-featured-slider -->--}}
                            {{--</div><!-- End .widget-body -->--}}
                        {{--</div><!-- End .widget -->--}}
                    </div>
                </aside><!-- End .col-md-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
        @include('frontend.product.include.detail-page-featured-product')
        @include('frontend.product.include.detail-page-related-products')
    </main><!-- End .main -->
@endsection

@section('stylesheets')
    <style>
        .products-margin-neutral {
            margin-left: -25px !important;
        }
    </style>
@stop