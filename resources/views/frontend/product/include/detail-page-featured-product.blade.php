<div class="featured-section">
    <div class="container">
        <h2 class="carousel-title">{{__('front.Featured Products')}}</h2>

        <div class="owl-theme">
            <div class="row">
                @foreach($featured_products as $key=>$featured_product)
                    @if($key<6)
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <div class="product"> <!--product1--------------->
                                <figure class="product-image-container">
                                    <a href="{{url('product/'.$featured_product->slug)}}" class="product-image">
                                        <img src="{{asset('image/products/'.$featured_product->featured_image)}}"
                                             alt="product">
                                    </a>
                                </figure>
                                <div class="product-details">

                                    <h2 class="product-title">
                                        <a href="{{url('product/'.$featured_product->slug)}}">{{$featured_product->name}}</a>
                                    </h2>
                                    <div class="price-box">
                                        @if(count($featured_product->getProductVariant) == 0)
                                            <span class="product-price">Rs. {{$featured_product->sell_price}}</span>
                                        @else
                                            <span class="product-price">Rs. {{$featured_product->getProductVariant[0]->sell_price??''}}</span>
                                        @endif
                                    </div><!-- End .price-box -->



                                </div><!-- End .product-details -->
                            </div><!-- End .product -->
                        </div> <!--end of col-->
                    @endif
                @endforeach
            </div> <!--------end of row-->
        </div><!-- End .featured-proucts -->
    </div><!-- End .container -->
    <!-- End .featured-section -->

</div>