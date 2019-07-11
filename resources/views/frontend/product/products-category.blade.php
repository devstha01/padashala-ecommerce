@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item"><a href="{{route('all-categories')}}">{{__('front.Categories')}}</a></li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}

        <div class="featured-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 border">

                        <form action="{{route('product-by-category')}}">
                            <input type="hidden" name="type" value="{{$type??''}}">
                            <input type="hidden" name="slug" value="{{$slug??''}}">
                            <br>
                            @if($type == 'category')
                                <b class=" border-bottom">
                                    &nbsp;
                                    <a class="text-primary" href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">{{$category->name}}</a></b>
                                @foreach($more_category->getSubCategory->where('status',1) as $more_sub)
                                    <br>
                                    <a class="fa pl-3"
                                       href="{{route('product-by-category',['type'=>'sub-category','slug'=>$more_sub->slug])}}">
                                        {{$more_sub->name}}
                                    </a>
                                @endforeach
                                <hr>

                            @elseif($type == 'sub-category')
                                <b class="border-bottom">
                                    <i class="fa fa-angle-left"></i>
                                    <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">{{$category->name}}</a>
                                </b>
                                <br>
                                <b class=" border-bottom">
                                    &nbsp;
                                    <a class="text-primary" href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCat->slug])}}">{{$subCat->name}}</a></b>
                                @foreach($more_category->getSubChildCategory->where('status',1) as $more_sub)
                                    <br>
                                    <a class="fa  pl-3"
                                       href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$more_sub->slug])}}">
                                        {{$more_sub->name}}
                                    </a>
                                @endforeach
                                <hr>
                            @elseif($type == 'sub-child-category')
                                <b class="border-bottom">
                                    <i class="fa fa-angle-left"></i>
                                    <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">{{$category->name}}</a>
                                </b>
                                <br>
                                <b class=" border-bottom">
                                    <i class="fa fa-angle-left"></i>
                                    <a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCat->slug])}}">{{$subCat->name}}</a></b>
                                @foreach($more_category->getSubChildCategory->where('status',1) as $more_sub)
                                    <br>
                                    <a class="fa pl-3 {{($more_sub->slug === $subChildCat->slug)?'text-primary':''}}"
                                       href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$more_sub->slug])}}">
                                        {{$more_sub->name}}
                                    </a>
                                @endforeach
                                <hr>
                            @endif
                            <b class="lead">{{__('front.Sort By')}}</b>
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

                            <br>
                            <br>
                            <b class="lead"> {{__('front.Filter By')}} </b>
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
                            <input type="hidden" name="price_max" id="price_max" value="{{$price_max??$filter_max}}">
                            <input type="hidden" id="filter_max" value="{{$filter_max??1000}}">

                            <div class="filter-price-action">
                                <button type="submit" class="btn btn-primary" id="submit-filter">Filter</button>
                            </div><!-- End .filter-price-action -->

                        </form>
                    </div>
                    <div class="col-md-9">

                        <h2 class="carousel-title">
                            @if($type == 'category')
                                {{$category->name}}
                            @elseif($type == 'sub-category')
                                {{$subCat->name}}
                            @elseif($type == 'sub-child-category')
                                {{$subChildCat->name}}
                            @endif
                        </h2>

                        <div class="owl-theme">
                            <div class="row" style="margin: 0">
                                @forelse($products as $key=>$featured_product)


                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="product"> <!--product1--------------->
                                            <figure class="product-image-container">
                                                <a href="{{url('product/'.$featured_product->slug)}}"
                                                   class="product-image">
                                                    <img src="{{asset('image/products/'.$featured_product->featured_image)}}"
                                                         alt="product">
                                                </a>
                                            </figure>
                                            <div class="product-details">

                                                <h2 class="product-title">
                                                    <a href="{{url('product/'.$featured_product->slug)}}">{{$featured_product->name}}</a>
                                                </h2>
                                                <div class="price-box">
                                                    @if(count($featured_product->getProductVariant->where('status',1)) === 0)
                                                        <span class="product-price">${{$featured_product->sell_price}}</span>
                                                    @else
                                                        <span class="product-price">${{$featured_product->getProductVariant->where('status',1)->first()->sell_price??''}}</span>
                                                    @endif
                                                </div><!-- End .price-box -->


                                            </div><!-- End .product-details -->
                                        </div><!-- End .product -->
                                    </div> <!--end of col-->
                                @empty
                                    <div class="col-xs-12">
                                        <h3 style="color:lightgrey">{{__('front.No related products for the category')}}
                                            .</h3>
                                    </div>
                                @endforelse
                            </div> <!--------end of row-->
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

                    </div>
                </div><!-- End .featured-proucts -->
            </div><!-- End .container -->
        </div><!-- End .featured-section -->
        @include('frontend.home.include.flash-sales')

    </main><!-- End .main -->
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