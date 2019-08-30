<!----------------------------for sales section------------------------------------------------>

<div class="featured-section" id="saleContainer">
    <div class="container">
        <h2 class="carousel-title">{{__('front.Related Products')}}</h2>

        <div class="owl-theme">
            <div class="row">
                @foreach($related as $key=>$relate)
                    @if($key<12)
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <div class="product"> <!--product1--------------->
                                <figure class="product-image-container">
                                    <a href="{{url('product/'.$relate->slug)}}" class="product-image">
                                        <img src="{{asset('image/products/'.$relate->featured_image)}}"
                                             alt="product">
                                    </a>
                                </figure>
                                <div class="product-details">

                                    <h2 class="product-title">
                                        <a href="{{url('product/'.$relate->slug)}}">{{$relate->name}}</a>
                                    </h2>
                                    <div class="price-box">
                                        @if(count($relate->getProductVariant) === 0)
                                            <span class="product-price">Rs. {{$relate->sell_price}}</span>
                                        @else
                                            <span class="product-price">Rs. {{$relate->getProductVariant[0]->sell_price??''}}</span>
                                        @endif
                                    </div><!-- End .price-box -->


                                </div><!-- End .product-details -->
                            </div><!-- End .product -->
                        </div> <!--end of col-->
                    @endif
                @endforeach

            </div> <!--------end of row-->
        </div><!-- End .featured-products -->
    </div><!-- End .container -->
</div><!-- End .featured-section -->
