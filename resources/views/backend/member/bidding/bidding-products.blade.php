@extends('frontend.layouts.app')
@section('content')
    <main class="main">


        <div class="featured-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">

                        <h2 class="carousel-title">
                         Bidding Products
                        </h2>

                        <div class="owl-theme">
                            <div class="row" style="margin: 0">
                                @forelse($products as $key=>$bidding_product)


                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="product"> <!--product1--------------->
                                            <figure class="product-image-container">
                                                <a href="{{url('bidding-product/'.$bidding_product->slug)}}"
                                                   class="product-image">
                                                    <img src="{{asset('image/bidding/'.$bidding_product->product_image)}}"
                                                         alt="product">
                                                </a>
                                            </figure>
                                            <div class="product-details">

                                                <h2 class="product-title">
                                                    <a href="{{url('product/'.$bidding_product->slug)}}">{{$bidding_product->title}}</a>
                                                </h2>



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




                    </div>
                </div><!-- End .featured-proucts -->
            </div><!-- End .container -->
        </div><!-- End .featured-section -->


    </main><!-- End .main -->
@endsection


@section('scripts')
    <script src="{{asset('frontend/assets/js/nouislider.min.js')}}"></script>

@stop