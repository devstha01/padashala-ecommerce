@extends('frontend.layouts.app')
@section('content')
    <main class="main" id="merchantListing">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}

        {{--</nav>--}}

        <div id="coverPhotoDiv" style="background: lightskyblue;min-height:100px">
            <img src="{{asset('image/banner1600x100.jpg')}}" alt="wave">
        </div>
        <div class="container merchant">
            <div id="merchantInfoDiv">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div id="merchantLogoDiv">
                            @if($business->getMerchant->logo !== null)
                                <img class="shopImg"
                                     src="{{asset('image/merchantlogo/'.$business->getMerchant->logo)}}"
                                     alt="">
                            @else
                                <img class="shopImg"
                                     src="{{asset('image/not-available.jpg')}}" alt="">
                            @endif
                        </div>
                    </div>

                    <div class="col-md-9 col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div id="merchantInfo">
                                    <br>
                                    <a href="{{route('merchant-info',$business->slug)}}"><h3
                                                id="name">{{$business->name}}</h3>
                                    </a>
                                    <p id="description"># {{$business->registration_number}}</p>
                                    <p id="location"><i class="fa fa-map-marker-alt"></i> {{$business->address}}
                                        , {{$business->getCountry->name}}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div id="cashInfo" class="float-right">
                                    {{--<div class="cashInfoBox">{{__('front.Cash on Delivery')}}</div>--}}
                                    @if($business->getMerchant->qr_image !==null)
                                        {{--<div class="cashInfoBox">{{__('front.Pay by Qr code')}}</div>--}}
                                        <a title="Save Qr Code" href="{{$business->getMerchant->qr_image}}">
                                            {{--                                            <img src="{{asset('image/qr_image/merchant/'.$business->getMerchant->qr_image)}}"--}}
                                            <img src="{{$business->getMerchant->qr_image}}"
                                                 style="height: 220px">
                                        </a>
                                    @endif

                                </div>
                            </div>
                            {{--<div id="closed" class="col-sm-2 col-xs-12">--}}
                            {{--<span>closed</span>--}}
                            {{--</div>--}}
                        </div>
                    </div> <!---------end of col for des-->
                </div>
            </div> <!---------merchant info ends-->
            <hr id="merchantHr">
            <div id="merchantTabs">
                <ul class="nav nav-tabs nav-justified">
                    {{--<li class="nav-item"><a class="nav-link {{(isset($merchant_tab))?'':'active'}}" href="#a"--}}
                    {{--data-toggle="tab">{{__('front.Featured Sales')}}!</a></li>--}}
                    <li class="nav-item"><a class="nav-link active" href="#b"
                                            data-toggle="tab">{{__('front.Products')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#c" data-toggle="tab">{{__('front.About')}}</a></li>
                </ul>


                <div class="tab-content">

                    <div class="tab-pane active" id="b">

                        <div class="row">
                            <div class="col-md-3 mt-4">
                                <form action="{{route('merchant-info',$business->slug)}}">
                                    <input type="hidden" name="merchant_tab" value="true">

                                    <div id="sortBy">

                                        <b class="lead"> {{__('front.Sort By')}} </b>
                                        <br>
                                        <input class="trigger-submit-filter" type="radio" id="asc" name="sorting"
                                               value="asc" {{$old_sorting =='asc'?'checked':''}}>
                                        <label for="asc">{{__('front.A - Z')}}</label>
                                        <br>
                                        <input class="trigger-submit-filter" type="radio" id="desc" name="sorting"
                                               value="desc" {{$old_sorting =='desc'?'checked':''}}>
                                        <label for="desc">{{__('front.Z - A')}}</label>
                                        <br>
                                        <input class="trigger-submit-filter" type="radio" id="low" name="sorting"
                                               value="low" {{$old_sorting =='low'?'checked':''}}>
                                        <label for="low">{{__('front.Low to High')}}</label>
                                        <br>
                                        <input class="trigger-submit-filter" type="radio" id="high" name="sorting"
                                               value="high" {{$old_sorting =='high'?'checked':''}}>
                                        <label for="high">{{__('front.High to Low')}}</label>
                                    </div>

                                    <div id="sortBy">

                                        <b class="lead"> {{__('front.Filter By')}} </b>
                                        <br>
                                        <b>Categories</b>
                                        <br>
                                        @forelse($all_categories as $category)
                                            <input class="trigger-submit-filter" type="checkbox" name="categories[]"
                                                   value="{{$category->id}}"
                                                   id="category-checkbox-{{$category->id}}"
                                                    {{(in_array($category->id,$checkbox_categories))?'checked':''}}>
                                            <label for="category-checkbox-{{$category->id}}">{{$category->name}}</label>
                                            <br>
                                        @empty
                                        @endforelse
                                        <br>
                                        <b>{{__('front.Sell Price')}}</b>
                                        <br>

                                        <div class="price-slider-wrapper">
                                            <div id="price-slider-test"></div><!-- End #price-slider -->
                                        </div><!-- End .price-slider-wrapper -->

                                        <div class="filter-price-text">
                                            <span id="filter-price-range-test"></span>
                                        </div><!-- End .filter-price-text -->

                                        <input type="hidden" name="price_min" id="price_min" value="{{$price_min??0}}">
                                        <input type="hidden" name="price_max" id="price_max"
                                               value="{{$price_max??$filter_max}}">
                                        <input type="hidden" id="filter_max" value="{{$filter_max??1000}}">

                                        <div class="filter-price-action">
                                            <button type="submit" style="visibility: hidden" class="btn btn-primary"
                                                    id="submit-filter">{{__('front.Filter')}}</button>
                                        </div><!-- End .filter-price-action -->
                                    </div>

                                </form>
                            </div>
                            <div class="col-md-9">

                                <div class="featured-section">
                                    <div class="owl-theme">
                                        <div class="row">
                                            @foreach($products as $key=>$product)
                                                @if($key<12)
                                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                                        <div class="product"> <!--product1--------------->
                                                            <figure class="product-image-container">
                                                                <a href="{{url('product/'.$product->slug)}}"
                                                                   class="product-image">
                                                                    <img src="{{asset('image/products/'.$product->featured_image)}}"
                                                                         alt="product">
                                                                </a>
                                                            </figure>
                                                            <div class="product-details">

                                                                <h2 class="product-title">
                                                                    <a href="{{url('product/'.$product->slug)}}">{{$product->name}}</a>
                                                                </h2>
                                                                <div class="price-box">
                                                                    @if(count($product->getProductVariant->where('status',1)) == 0)
                                                                        <span class="product-price">Rs. {{$product->sell_price}}</span>
                                                                    @else
                                                                        <span class="product-price">Rs. {{$product->getProductVariant->where('status',1)->first()->sell_price??''}}</span>
                                                                    @endif
                                                                </div><!-- End .price-box -->
                                                            </div><!-- End .product-details -->
                                                        </div><!-- End .product -->
                                                    </div> <!--end of col-->
                                                @endif
                                            @endforeach

                                        </div> <!--------end of row-->

                                    </div>
                                </div>

                                <nav class="toolbox toolbox-pagination">
                                    <div class="toolbox-item toolbox-show">
                                        <label>{{__('front.Showing')}}
                                            @if($total >((($page - 1)*$perPage)+1))
                                                {{(($page - 1)*$perPage)+1}}
                                            @else
                                                {{($total)}}
                                            @endif
                                            -
                                            @if($total >($page *$perPage))
                                                {{($page *$perPage)}}
                                            @else
                                                {{($total)}}
                                            @endif
                                            {{__('front.of')}} {{$total}}
                                            {{__('front.results')}}</label>
                                    </div><!-- End .toolbox-item -->

                                    <ul class="pagination">
                                        @if($page>1)
                                            <li class="page-item">
                                                <a class="page-link page-link-btn"
                                                   href="{{Request::fullUrl().'&page='.($page-1)}}"><i
                                                            class="icon-angle-left"></i></a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                                     href="{{Request::fullUrl().'&page='.($page-1)}}">{{($page-1)}}</a>
                                            </li>
                                        @endif
                                        <li class="page-item active"><a class="page-link ">{{($page)}}</a></li>

                                        @if($total >($page *$perPage))
                                            <li class="page-item"><a class="page-link"
                                                                     href="{{Request::fullUrl().'&page='.($page+1)}}">{{($page+1)}}</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link page-link-btn"
                                                   href="{{Request::fullUrl().'&page='.($page+1)}}"><i
                                                            class="icon-angle-right"></i></a>
                                            </li>
                                        @endif

                                    </ul>
                                </nav>

                            </div><!-- End .featured-proucts -->
                        </div><!-- End .featured-section -->
                    </div> <!--end of b (featured products)-->

                    <div class="tab-pane" id="c">
                        <div class="row">
                            <div class="col-md-8 mt-3">

                                <h2 style="font-family: Oswald, sans-serif;">{{__('front.Merchant Info')}}</h2>
                                <h4>{{$business->getMerchant->name}} {{$business->getMerchant->surname}}</h4>
                                <p><i class="fa fa-map-marker"></i> {{$business->getMerchant->address}}
                                    , {{$business->getMerchant->getCountry->name}}
                                    <br>
                                    <i class="fa fa-envelope"></i> {{$business->getMerchant->email}}

                                </p>
                                <hr>
                                <h4 style="font-family: Oswald, sans-serif;">{{__('front.Contact us')}}:</h4>
                                <p>
                                    <b><i class="fa fa-map-marker"></i> {{__('front.Location')}} :
                                    </b>{{$business->address}}, {{$business->getCountry->name}}<br>
                                    <b><i class="fa fa-phone"></i> {{__('front.Phone')}} :
                                    </b>{{$business->contact_number}}<br>

                                </p>
                            </div>
                            <div class="col-md-4 border mt-4">

                                @foreach($others as $other)
                                    @if($other->getMerchant !==null)
                                        <div class="store" style="box-shadow: 1px 3px #0088cc;border:1px solid whitesmoke;border-radius:20px;padding:10px;margin:15px 0">
                                        {{--<div class="store">--}}
                                            <a href="{{route('merchant-info',$other->slug)}}" style="text-decoration: none">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="shopImgDiv">
                                                                @if($other->getMerchant->logo !== null)
                                                                    <img class="shopImg"
                                                                         src="{{asset('image/merchantlogo/'.$other->getMerchant->logo)}}"
                                                                         alt="">
                                                                @else
                                                                    <img class="shopImg"
                                                                         src="{{asset('image/not-available.jpg')}}"
                                                                         alt="">
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-8 col-sm-12 mt-1">
                                                            <h3>{{$other->name}}</h3>
                                                            {{--<h3>Electronics, Fashion, Sports</h3>--}}
                                                            <p><i class="fa fa-phone"></i> {{$other->contact_number}}
                                                                <br>
                                                                <i class="fa fa-map-marker"> {{$other->address}}
                                                                    , {{$other->getCountry->name}}</i>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                                <div>
                                    <a href="{{route('merchant-list')}}" class="float-right text-info">view all</a>
                                </div>
                            </div>

                        </div>
                    </div><!--End of merchant information-->
                </div> <!---end of tab content-->
            </div>
        </div> <!--end of container-->
    </main>
@endsection



@section('scripts')
    <script src="{{asset('frontend/assets/js/nouislider.min.js')}}"></script>
    <script>
        var slider = $('#price-slider-test')[0];
        var pointstopredicts = $('#filter-price-range-test');
        var filter_max = parseInt($('#filter_max').val());
        var price_min = parseInt($('#price_min').val());
        var price_max = parseInt($('#price_max').val());
        // console.log($.type(price_min));
        noUiSlider.create(slider, {
            start: [price_min, price_max],
            range: {min: 0, max: filter_max},
            step: 50,
            connect: true,
        });

        slider.noUiSlider.on('update', function (values, handle) {
            // console.log(values);
            pointstopredicts.html('$ ' + values[0] + ' - $' + values[1]);
            $('#price_min').val(values[0]);
            $('#price_max').val(values[1]);
        });
        slider.noUiSlider.on('end', function () {
            $('#submit-filter').click();
        });

    </script>
@stop
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('frontend/assets/css/tabs.css')}}">
    <style>
        .store {
            border-bottom: 1px solid grey;
        }

        .store:hover {
            background: #08c;
            border: 1px solid grey;
        }

    </style>
@endsection