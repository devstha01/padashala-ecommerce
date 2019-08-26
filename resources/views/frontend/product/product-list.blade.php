@extends('frontend.layouts.app')
<style>

</style>
@section('content')
    <main class="main" id="merchantListing">
        <div class="container">

            <hr>
            @if(isset($search))
                <h4>{{__('front.Search results for term')}} '{{$search}}'</h4>
            @endif

            <div class="row">
                <div class="col-md-3">

                    <form action="{{route('search-product')}}">
                        <input type="hidden" name="type" value="{{$type??''}}">
                        <input type="hidden" name="term" value="{{$search??''}}">

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

                            <b class="lead">{{__('front.Filter By')}}</b>
                            <br>
                            {{--<b>{{__('front.Categories')}}</b>--}}
                            {{--<br>--}}
                            {{--@forelse($all_categories as $category)--}}
                            {{--<input class="trigger-submit-filter" type="checkbox" name="categories[]"--}}
                            {{--value="{{$category->id}}"--}}
                            {{--id="category-checkbox-{{$category->id}}"--}}
                            {{--{{(in_array($category->id,$checkbox_categories))?'checked':''}}>--}}
                            {{--<label for="category-checkbox-{{$category->id}}">{{$category->name}}</label>--}}
                            {{--<br>--}}
                            {{--@empty--}}
                            {{--@endforelse--}}
                            {{--<br>--}}
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
                                <button type="submit" style="visibility: hidden;" class="btn btn-primary"
                                        id="submit-filter">Filter
                                </button>
                            </div><!-- End .filter-price-action -->
                        </div>

                    </form>
                </div>

                <div class="col-md-7">
                    <div class="owl-theme">
                        @forelse($products as $key=>$prod)
                            <div class="row" style="border-bottom: 1px solid #f3f3f3;margin:0 0 10px 0">

                                <div class="col-sm-3">
                                    <div class="product"> <!--product1--------------->
                                        <figure class="product-image-container">
                                            <a href="{{url('product/'.$prod->slug)}}" class="product-image">
                                                <img src="{{asset('image/products/'.$prod->featured_image)}}"
                                                     alt="product">
                                            </a>
                                        </figure>
                                    </div><!-- End .product -->
                                </div>
                                <div class="col-sm-9">

                                    <h2 class="product-title">
                                        <a href="{{url('product/'.$prod->slug)}}" style="text-decoration: none">
                                            <p style="font-size: 20px">{{$prod->name}}</p>
                                            <div class="price-box">
                                                @if(count($prod->getProductVariant->where('status',1)) === 0)
                                                    <span class="product-price">${{$prod->sell_price}}</span>
                                                @else
                                                    <span class="product-price">${{$prod->getProductVariant->where('status',1)->first()->sell_price??''}}</span>
                                                @endif
                                            </div><!-- End .price-box -->
                                            <b>{{$prod->getBusiness->name}}</b>
                                            <div class="product-highlight"><?php echo htmlspecialchars_decode($prod->detail)?></div>

                                        </a>
                                    </h2>
                                </div>

                            </div>
                        @empty
                            <h3 style="color:lightgrey">{{__('front.No related products for the search term')}}
                                .</h3>
                        @endforelse
                        {{--</div> <!--------end of row-->--}}
                    </div><!-- End .featured-proucts -->
                    <div class="col-md-2"></div>
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
                                    <a class="page-link page-link-btn" href="{{Request::fullUrl().'&page='.($page-1)}}"><i
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
                                    <a class="page-link page-link-btn" href="{{Request::fullUrl().'&page='.($page+1)}}"><i
                                                class="icon-angle-right"></i></a>
                                </li>
                            @endif

                        </ul>
                    </nav>

                </div>
            </div>
        </div>
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