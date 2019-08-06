@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="home-top-container">
            <div class="container">
                <div class="whiteBox">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div id="market">
                                <div class="side-custom-menu">
                                    <h2><span id="mainMarket"><i class="fa fa-list"></i></span>My Categories
                                        <a href="{{route('all-categories')}}">see all
                                            <i class="fa fa-arrow-circle-right"></i></a></h2>

                                    <div class="side-menu-body">
                                        <ul>
                                            @foreach($home_categories as $category)
                                                @if($loop->index <8)
                                                    <li>
                                                        <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                                                            {{$category->name}}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div><!-- End .side-menu-body -->
                                </div><!-- End .side-custom-menu -->
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div id="homedemo" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ul class="carousel-indicators">
                                    @foreach($homebanners as $k1=>$homebanner)
                                        <li data-target="#homedemo" data-slide-to="{{$k1}}"
                                            class="{{($k1==0)?'active':''}}"></li>
                                    @endforeach
                                </ul>

                                <!-- The slideshow -->
                                <div class="carousel-inner">
                                    @foreach($homebanners as $k1=>$homebanner)
                                        <div class="carousel-item {{($k1==0)?'active':''}}">
                                            <a href="{{$homebanner->url}}">
                                                <img src="{{asset('image/homebanner/'.$homebanner->image)}}" alt="_">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Left and right controls -->
                                <a class="carousel-control-prev" href="#homedemo" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a class="carousel-control-next" href="#homedemo" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>

                            </div><!-- End .col-lg-9 -->
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div id="rightImg">
                                <img src="/frontend/mobile.jpg" alt=" ">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="info-boxes-container">
                    <div class="container">
                        <div class="info-box">
                            <i class="icon-shipping"></i>

                            <div class="info-box-content">
                                <h4>{{__('front.Shipping Title')}}</h4>
                                <p>{{__('front.Shipping Detail')}}</p>
                            </div><!-- End .info-box-content -->
                        </div><!-- End .info-box -->

                        <div class="info-box">
                            <i class="icon-us-dollar"></i>

                            <div class="info-box-content">
                                <h4>{{__('front.Money Title')}}</h4>
                                <p>{{__('front.Money Detail')}}</p>
                            </div><!-- End .info-box-content -->
                        </div><!-- End .info-box -->

                        <div class="info-box">
                            <i class="icon-support"></i>

                            <div class="info-box-content">
                                <h4>{{__('front.Support Title')}}</h4>
                                <p>{{__('front.Support Detail')}}</p>
                            </div><!-- End .info-box-content -->
                        </div><!-- End .info-box -->
                    </div><!-- End .container -->
                </div><!-- End .info-boxes-container -->


            {{--<div class="mb-2"></div><!-- margin -->--}}

            @include('frontend.home.include.featured')


            @include('frontend.home.include.flash-sales')



            <!---------------------------------------Featured Products-------------------------->
                <div class="featured-section">
                    <div class="container">
                        <h2 class="carousel-title">{{__('front.Products')}}</h2>

                        <div class="owl-theme">
                            <div class="row">
                                @foreach($latest_products as $key=>$latest_prod)
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <div class="product"> <!--product1--------------->
                                            <figure class="product-image-container">
                                                <a href="{{url('product/'.$latest_prod->slug)}}"
                                                   class="product-image">
                                                    <img
                                                            src="{{asset('image/products/'.$latest_prod->featured_image)}}"
                                                            alt="product">
                                                </a>
                                            </figure>
                                            <div class="product-details">

                                                <h2 class="product-title">
                                                    <a href="{{url('product/'.$latest_prod->slug)}}">{{$latest_prod->name}}</a>
                                                </h2>
                                                <div class="price-box">
                                                    @if(count($latest_prod->getProductVariant->where('status',1)) === 0)
                                                        <span
                                                                class="product-price">${{$latest_prod->sell_price}}</span>
                                                    @else
                                                        <span
                                                                class="product-price">${{$latest_prod->getProductVariant->where('status',1)->first()->sell_price??''}}</span>
                                                    @endif
                                                </div><!-- End .price-box -->


                                            </div><!-- End .product-details -->
                                        </div><!-- End .product -->
                                    </div> <!--end of col-->
                                @endforeach

                            </div> <!--------end of row-->
                            <hr>

                            <a href="{{route('search-product',['type'=>'product'])}}" class="btn btn-primary "
                               style="margin-top: -136px;margin-left:40%;padding:10px 50px"><i
                                        class="fa fa-shopping-basket fa-2x text-white mr-3"></i> {{__('front.All Products')}}
                            </a>

                        </div>
                    </div><!-- End .container -->
                </div>
            </div>
        </div>
    </main>
@endsection

@section('stylesheets')
    <style>
        .carousel-indicators {
            position: absolute;
            bottom: 10px;
            left: 50%;
            z-index: 15;
            width: 60%;
            padding-left: 0;
            margin-left: -30%;
            text-align: center;
            list-style: none;
        }

        .carousel-indicators li {
            padding: 4px;
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 1px;
            text-indent: -999px;
            cursor: pointer;
            background-color: #000 \9;
            background-color: rgba(0, 0, 0, 0);
            border: 1px solid #fff;
            border-radius: 10px;
        }

        #homedemo {
            background: lightgrey;
        }

        .carousel-control-prev-icon, .carousel-control-next-icon {
            outline: black;
            background-size: 100%, 100%;
            background-image: none;
            width: 30px;
            height: 60px;
            background: #ccc;
            background-color: rgba(0, 0, 0, .2);
            overflow: hidden;
            text-align: center;
            cursor: pointer;
        }

        .carousel-control-next-icon:after {
            content: '\276F';
            /*content: '>';*/
            font-size: 42px;
            color: #fff;
            line-height: 100px;
            font-size: 30px;
            line-height: 60px;
            color: #fff;
        }

        .carousel-control-prev-icon:after {
            content: '\276E';
            /*content: '<';*/
            font-size: 42px;
            color: #fff;
            line-height: 100px;
            font-size: 30px;
            line-height: 60px;
            color: #fff;
        }

        .side-menu-body {
            padding: 0;
        }

        .carousel-control-next, .carousel-control-prev {
            width: 30px;
        }

        /*home banner side products*/
        #dealsBox {
            text-align: justify;
            width: 100%;
            /* margin: auto; */
            position: relative;
            overflow-y: scroll;
            overflow-x: hidden;
            height: 337px;
            background: #f9f9f9;
            border: 2px solid;
            border-color: #f4f4f4 #cbd3d5 #f4f4f4 #f4f4f4;
        }

        #dealsBox::-webkit-scrollbar {
            width: 6px;
            background-color: #cbd3d5;
        }

        #dealsBox a:hover {
            text-decoration: none;
        }

        #dealsBox::-webkit-scrollbar-thumb {
            background-color: #fff;
            border-left: 2px solid #cbd3d5;
            /* outline: 2px solid #cbd3d5;
            color: #cbd3d5; */
            border-radius: 8px;
        }

        #stealDeal {
            /* border-bottom: 2px solid #fff; */
            background: #f9f9f9;
            padding: 2%;
            height: 85px;
        }

        #stealDealImageDiv {
            float: left;
            padding-right: 2%;
            margin-right: 1%;
            margin-top: 5px;
        }

        #stealDealImageDiv img {
            max-width: 70px;
            max-height: 61px;
        }

        #stealDealDes p {
            margin-bottom: 2px;
            /* font-family: Montserrat; */
            font-size: 16px;
            font-weight: 500;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.33;
            letter-spacing: normal;
            text-align: left;
            color: #858897;
            max-height: 42px;
            font-weight: 600;
            overflow: hidden;
        }

        #stealPrice {
            font-family: Roboto;
            font-size: 14px;
            font-weight: normal;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.43;
            letter-spacing: normal;
            text-align: left;
            color: #634099;
        }

        #fixedBox {
            background: #f9f9f9;
            font-family: "Open Sans", sans-serif;
            font-size: 18px;
            font-weight: 600;
            font-style: normal;
            font-stretch: normal;
            line-height: 48px;
            letter-spacing: normal;
            text-align: left;
            color: #858897;
            height: 48px;
            padding-left: 15px;
            border-bottom: 3px solid #e9eaece6;
        }

        #stealDealDes {
            color: #b4792f;
            font-weight: 600;
        }
    </style>
    <link rel="stylesheet" href="{{asset('frontend/assets/css/padashala.css')}}">
@endsection

@section('scripts')
    <script>
        $('.arrow-on-hover').hover(function () {
            $(this).addClass('needsArrow');
        }, function () {
            $(this).removeClass('needsArrow');
        });

    </script>
@endsection
