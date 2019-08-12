<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left header-dropdowns">
                <a href="{{url('/')}}">
                    <img src="{{asset('image/gghl-logo.png')}}" alt="Logo" style="margin-left:10px;height: 60px">
                </a>
            </div><!-- End .header-left -->
            <div class="header-center" style="width: 90px!important;margin: 0 !important;">
            </div>
            <div class="header-right" style="margin: 0!important;">

                <?php  if (!isset($type)) $type = 'product';?>
                <div class="header-search" style="height: 34px">
                    <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                    <form action="{{route('search-product')}}" method="GET">
                        <div class="header-search-wrapper">
                            <input type="search" name="term" class="form-control" placeholder="Search..."
                                   value="{{$search??''}}">
                            <div class="select-custom">

                                <select id="cat" name="type" style="font-size: 12px">

                                    {{--<option style="display:none" value="product" {{($type === 'none') ?'selected':''}}>--}}
                                    {{--{{__('front.Product/Merchant')}}--}}
                                    {{--</option>--}}
                                    {{--<option value="product"--}}
                                    {{--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{__('front.Products')}}--}}
                                    {{--style="display: {{($type === 'product') ?'none':'block'}}" --}}
                                    {{--{{($type === 'product') ?'selected':''}}>--}}
                                    {{--</option>--}}
                                    <option value="product" {{($type == 'product') ?'selected':''}}>All categories
                                    </option>
                                    @foreach($home_categories as $h_category)
                                        <option value="{{$h_category->id}}" {{($type == $h_category->id) ?'selected':''}}>{{$h_category->name}}</option>
                                    @endforeach
                                    {{--<option value="merchant"--}}
                                    {{--style="display: {{($type === 'merchant') ?'none':'block'}}" {{($type === 'merchant') ?'selected':''}}>{{__('front.Merchants')}}--}}
                                    {{--</option>--}}

                                </select>

                            </div><!-- End .select-custom -->
                            <button class="btn" type="submit"><i style="color:white;font-weight:bold"
                                                                 class="icon-magnifier"></i></button>
                        </div><!-- End .header-search-wrapper -->
                    </form>
                </div><!-- End .header-search -->

                <div class="login-register">
                    <div class="header-dropdown dropdown-expanded">
                        <div class="header-menu">
                            <ul style="margin: 0 50px 0 0">
                                @if(Auth::user())
                                    <li class="account-dropdown "> {{Auth::user()->name}} {{Auth::user()->surname}}
                                        !
                                        <div class="account-dropdown-menu" style="color: black">
                                            <a class="fa  m-3"
                                               href="{{route('view-profile')}}"> &nbsp;{{__('front.My Profile')}}</a>
                                            <a href="{{route('customer-logout')}}"
                                               class="fa  m-3">&nbsp;{{__('front.Logout')}}</a>
                                        </div>
                                    </li>
                                @endif
                                @if(Auth::user())

                                    @if(Auth::user() && Auth::user()->is_member ===1 )
                                        <li><a href="{{url('member/dashboard')}}" class="">
                                                &nbsp;{{ __('front.Dashboard') }}</a></li>
                                    @endif
                                    <li class="account-no-dropdown"><a href="{{route('order-list')}}"
                                                                       class=""> {{__('front.My Orders')}}</a></li>
                                @else
                                    <li class="account-no-dropdown"><a
                                                href="{{route('checkout-login')}}">{{__('front.Log In')}}</a></li>
                                    <li class="account-no-dropdown"><a
                                                href="{{route('customer-register')}}">{{__('front.Register Free')}}</a>
                                    </li>
                            @endif
                            <!-- End .header-dropown -->

                            </ul>
                        </div><!-- End .header-menu -->
                    </div><!-- End .header-dropown -->


                </div>

            </div>
            {{--<span style="width: 100px"></span>--}}
            {{--<a href="{{url('bid-win')}}" style="margin: 0 20px">--}}
            {{--<img class="bid-image" src="{{asset('image/bid.png')}}" alt="Bid n Win">--}}
            {{--</a>--}}
            <a href="{{route('cart-view')}}" id="my-cart-a">
                <i class="fa fa-shopping-cart text" style="font-size:18px;color: black"></i>
                <span class="cart-count">0</span>
                <span>{{__('front.My Cart')}}</span>
            </a>
            {{--@include('frontend.layouts.include.cart-list-dropdown')--}}

        </div><!-- End .header-right -->
    </div><!-- End .container -->

    <div class="header-middle" style="margin-top: -1px">
        <div class="container">
            <div class="header-left">
            {{--                @if(Request::path() != '/')--}}
            <!--------------------------------------------CATEGORIES DROPDOWN------------------------>
                {{--<div id="category">--}}
                {{--<h3 id="categories">--}}
                {{--<i class="cat-icon fa fa-bars"></i> {{__('front.Categories')}}--}}
                {{--</h3>--}}
                {{--<div id="dropdown">--}}
                {{--<ul>--}}

                {{--@forelse($home_categories as $category)--}}
                {{--@if(count($category->getSubCategory->where('status',1)) !== 0)--}}
                {{--<li class="liWithDropdown">--}}
                {{--<a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">--}}
                {{--<span class="">--}}
                {{--{{$category->name}}</span></a>--}}

                {{--<ul class="level1">--}}
                {{--@forelse($category->getSubCategory->where('status',1) as $subCategory)--}}

                {{--@if(count($subCategory->getSubChildCategory->where('status',1))===0)--}}
                {{--<li>--}}
                {{--<a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">--}}
                {{--{{$subCategory->name}}</a>--}}
                {{--</li>--}}

                {{--@elseif(count($subCategory->getSubChildCategory->where('status',1))>9)--}}

                {{--<li class="liWithDropdown1">--}}
                {{--<a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">--}}
                {{--<span class="">{{$subCategory->name}}</span></a>--}}
                {{--<!----------------------->--}}
                {{--<div class="level2">--}}
                {{--<div class="row">--}}
                {{--<div class="col-xs-6 cols colss">--}}
                {{--<ul>--}}
                {{--@forelse($subCategory->getSubChildCategory->where('status',1) as $key=>$subChildCategory)--}}
                {{--@if($key%2 ===0)--}}
                {{--<li>--}}
                {{--<a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subChildCategory->slug])}}">--}}
                {{--{{$subChildCategory->name}}</a>--}}
                {{--</li>--}}
                {{--@endif--}}
                {{--@empty--}}
                {{--@endforelse--}}
                {{--</ul>--}}
                {{--</div>--}}
                {{--<div class="col-xs-6 cols">--}}
                {{--<ul>--}}
                {{--@forelse($subCategory->getSubChildCategory->where('status',1) as $key=>$subChildCategory)--}}
                {{--@if($key%2 !==0)--}}
                {{--<li>--}}
                {{--<a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subChildCategory->slug])}}">{{$subChildCategory->name}}</a>--}}
                {{--</li>--}}
                {{--@endif--}}
                {{--@empty--}}
                {{--@endforelse--}}
                {{--</ul>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</li>--}}


                {{--@else--}}
                {{--<li class="liWithDropdown1">--}}
                {{--<a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">--}}
                {{--<span class="">{{$subCategory->name}}</span></a>--}}
                {{--<!----------------------->--}}
                {{--<div class="level2">--}}
                {{--<div class="row">--}}
                {{--<ul>--}}

                {{--@forelse($subCategory->getSubChildCategory->where('status',1) as $subChildCategory)--}}
                {{--<li>--}}
                {{--<a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subChildCategory->slug])}}">{{$subChildCategory->name}}</a>--}}
                {{--</li>--}}
                {{--@empty--}}
                {{--@endforelse--}}
                {{--</ul>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</li>--}}
                {{--@endif--}}
                {{--@empty--}}
                {{--@endforelse--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--@else--}}
                {{--<li>--}}
                {{--<a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">--}}
                {{--{{$category->name}}</a>--}}
                {{--</li>--}}
                {{--@endif--}}
                {{--@empty--}}
                {{--@endforelse--}}
                {{--<li>--}}
                {{--<a href="{{route('all-categories')}}"--}}
                {{--class="text-primary">{{__('front.All Categories')}} </a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@endif--}}

            </div><!-- End .header-left -->

            <div class="header-center" id="header-center" style="margin-left: 163px!important;">
                @include('frontend.layouts.include.header-category')

            </div><!-- End .headeer-center -->

            <div class="header-right">
                <button class="mobile-menu-toggler" type="button">
                    <i class="icon-menu"></i>
                </button>
            </div>
        </div><!-- End .container -->

    </div><!-- End .header-middle -->

    <!-- End .header-bottom -->
</header><!-- End .header -->
<br>
