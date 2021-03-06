<div class="product-single-container product-single-default">
    <div class="row">
        <div class="col-lg-7 col-md-6 product-single-gallery">
            <div class="product-slider-container product-item">
                <div class="product-single-carousel owl-carousel owl-theme">
                    <div class="product-item">
                        <div id="picturediv">
                            <img class="zoom_04c product-single-image"
                                 src="{{asset('image/products/'.$product->featured_image)}}"
                                 data-zoom-image="{{asset('image/products/'.$product->featured_image)}}">
                        </div>
                    </div>
                    @forelse($product->getProductImage as $key=> $prodImage)
                        <div class="product-item">
                            <img class="zoom_04c product-single-image"
                                 src="{{asset('image/products/'.$prodImage->image)}}"
                                 data-zoom-image="{{asset('image/products/'.$prodImage->image)}}">
                        </div>
                    @empty
                    @endforelse
                    @forelse($color_images as $colorImage)
                        <div class="product-item">
                            <img class="zoom_04c product-single-image"
                                 src="{{asset('image/products/color/'.$colorImage->image)}}"
                                 data-zoom-image="{{asset('image/products/color/'.$colorImage->image)}}">
                        </div>
                    @empty
                    @endforelse
                    {{--<div class="product-item">--}}
                    {{--<img style="display: none" class="zoom_04c product-single-image"--}}
                    {{--src="{{asset('image/products/'.$product->featured_image)}}"--}}
                    {{--data-zoom-image="{{asset('image/products/'.$product->featured_image)}}">--}}
                    {{--</div>--}}
                </div>
                <!-- End .product-single-carousel -->
                <span class="prod-full-screen" style="margin-bottom: 50px">
                    <i class="icon-plus"></i>
                </span>
            </div>
            <div class="featured-products owl-carousel owl-theme owl-dots-top owl-loaded owl-drag">
                <div class="owl-stage-outer">
                    <div class="owl-stage prod-thumbnail row" id='carousel-custom-dots'
                         style="transform: translate3d(0px, 0px, 0px); transition: all 0.25s ease 0s;">
                        <div class="col-xs-2 owl-dot owl-item active">
                            <img src="{{asset('image/products/'.$product->featured_image)}}"/>
                        </div>
                        @forelse($product->getProductImage as $prodImage)
                            <div class="col-xs-2 owl-dot owl-item active products-margin-neutral"
                                 style="margin-left: -25px;">
                                <img src="{{asset('image/products/'.$prodImage->image)}}"/>
                            </div>
                        @empty
                        @endforelse
                        @forelse($color_images as $colorImage)
                            <div class="col-xs-2 owl-dot owl-item active products-margin-neutral"
                                 style="margin-left: -25px;display:none" id="action-color-{{$colorImage->color_id}}">
                                <img src="{{asset('image/products/color/'.$colorImage->image)}}"/>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="owl-nav disabled">
                    <button type="button" role="presentation" class="owl-prev"><i class="icon-left-open-big"></i>
                    </button>
                    <button type="button" role="presentation" class="owl-next"><i class="icon-right-open-big"></i>
                    </button>
                </div>
            </div>
        </div><!-- End .col-lg-7 -->

        <div class="col-lg-5 col-md-6" style="position:relative">
            <div id="demo-container"></div>

            <div class="product-single-details">
                <h1 class="product-title">{{$product->name}}</h1>
                <a href="{{route('merchant-info',$product->getBusiness->slug)}}"
                   style="font-size: 16px;text-decoration:none">
                    <i class="fa fa-shopping-bag text-primary"></i> <b>{{$product->getBusiness->name}}</b>
                </a>
                <div class="price-box">
                    <span class="old-price old-price-cart"></span>
                    <span class="product-price product-price-cart"></span>
                </div><!-- End .price-box -->

                <div class="product-highlight"
                     style="border-bottom: 1px solid lightgrey;margin-bottom:20px;padding-bottom:5px"><?php echo htmlspecialchars_decode($product->detail)?></div>
                <div class="product-filters-container">
                    {{--<div class="product-single-filter">--}}

                    <div class="option-color-list">
                        <label class="custom-lead-1 mr-3 option-color-title">{{__('dashboard.Color')}}:</label>
                        @foreach($colors as $color)

                            @if($prod_color_image = \App\Models\ColorImage::where('product_id',$product->id)->where('color_id',$color->id)->first())
                                <a style="cursor:pointer;" class="trigger-image-zoom mr-3 color-select
                        {{$loop->index==0?'color-highlight':''}}"
                                   data-color_id="{{$color->id}}">
                                    {{--{{$color->name}}--}}
                                    <img style="height: 35px;display:inline-block;margin-top:-5px;"
                                         src="{{asset('image/products/color/'.$prod_color_image->image)}}" alt=" ">
                                </a>
                            @else
                                @if(count($colors )===1 && $color->id ===1)
                                @else
                                    <a style="cursor:pointer;" class=" mr-3 color-select
                        {{$loop->index==0?'color-highlight':''}}"
                                       data-color_id="{{$color->id}}">
                                        {{--{{$color->name}}--}}

                                        <img style="height: 35px;display:inline-block;margin-top:-5px;background:{{$color->color_code}}"
                                             src="{{asset('frontend/box.png')}}" alt=" ">
                                        {{--<i class="fa fa-square fa-3x"--}}
                                        {{--style="color: {{$color->color_code}};background:whitesmoke"></i>--}}
                                    </a>

                                @endif
                            @endif
                        @endforeach
                    </div>

                    <br>
                    <div class="option-size-list">
                        <label class="custom-lead-1 mr-3 option-size-title">{{__('dashboard.Size')}}:</label>

                        @forelse($options as $key=>$variant)
                            <label class="mr-3  color-options color-option-{{$variant->color_id}}
                            {{$variant->color_id ===($colors->first()->id??0)?'':'hide-size'}}
                            {{$loop->index==0?'highlight-checked':''}}">
                                {{$variant->size?? ' - '}}
                                <input type="radio" name="variant" class="variant"
                                       data-obj="{{$variant}}" {{$loop->index==0?'checked':''}}>
                                <span class="checkmark"></span>
                            </label>
                        @empty
                        @endforelse
                    </div>

                    <br>

                    @if($product->delivery_option)
                        <span id="low-stock-warning" style="color:orangered"></span>
                    @endif
                </div><!-- End .product-filters-container -->

                <div class="product-action product-all-icons">
                    <div class="product-single-qty">
                        <input class="horizontal-quantity form-control number-min-max" id="count" type="number"
                               name="qty">
                        <input type="hidden" class="max-value" value="{{$product->quantity}}">

                    </div><!-- End .product-single-qty -->
                    <form action="{{ route('cart.store', $product) }}" method="POST" id="addCart"
                          style="margin-top: 50px">
                        {{ csrf_field() }}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                                class="btn btn-product cart-btn paction add-cart">{{__('front.Add to cart')}}</button>
                        <div>
                        <span class="alert alert-info fa fa-shopping-cart" style="position: absolute;display:none"
                              id="cart-message"></span>
                        </div>
                    </form>
                    <br>
                </div>
            </div><!-- End .product-single-details -->
        </div><!-- End .col-lg-5 -->
    </div><!-- End .row -->

    <div class="product-desc">
        {{--<div class="row">--}}
        {{--<div class="col-sm-12">--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<p>{{$product->detail}}</p>--}}

        @if(!empty($product->description))
            <p><b>{{__('front.Description')}} : </b><br>
                <?php echo htmlspecialchars_decode($product->description)?></p>
        @endif

        {{--Specification--}}
        <hr>
        <div class="row">
            @foreach($product->specifications as $specification)
                <div class="col-md-6">
                    <b>{{$specification->name}}</b>
                    <br>
                    {{$specification->detail}}
                    <br>
                    <br>
                </div>
            @endforeach
        </div>
    </div>
    <br>
</div>