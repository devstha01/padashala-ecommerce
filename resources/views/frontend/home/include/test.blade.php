<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left header-dropdowns">
                <a href="{{url('/')}}">
                    <img src="{{asset('image/gghl-logo.png')}}" alt="Logo" style="margin-left:10px;height: 50px">
                </a>
            </div><!-- End .header-left -->
            <div class="header-center">
            </div>
            <div class="header-right">
                <a href="{{url('bid-win')}}" class="mr-3">
                    <img class="bid-image" src="{{asset('image/bid.png')}}" alt="Bid n Win">
                </a>
                <div class="header-dropdown dropdown-expanded">
                    <div class="header-menu">
                        <ul>
                            @if(Auth::user())
                                <li class="account-dropdown">{{ __('front.Welcome') }} {{Auth::user()->name}} {{Auth::user()->surname}}
                                    !
                                    <div class="account-dropdown-menu">
                                        <a class="fa fa-user-o m-3"
                                           href="{{route('view-profile')}}"> &nbsp;{{__('front.My Profile')}}</a>
                                        <a href="{{route('customer-logout')}}"
                                           class="fa fa-sign-out m-3">&nbsp;{{__('front.Logout')}}</a>
                                    </div>
                                </li>
                            @endif
                            @if(Auth::user())

                                @if(Auth::user() && Auth::user()->is_member ===1 )
                                    <li><a href="{{url('member/dashboard')}}" class="fa fa-dashboard">
                                            &nbsp;{{ __('front.Dashboard') }}</a></li>
                                @endif
                                <li><a href="{{route('order-list')}}"
                                       class="fa fa-bars">&nbsp;{{__('front.My Orders')}}</a></li>
                            @else
                                <li><a href="{{route('checkout-login')}}">{{__('front.Log In')}}</a></li>
                                <li><a href="{{route('customer-register')}}">{{__('front.Register')}}</a></li>
                            @endif
                            <li class="account-dropdown">
                                @if(Lang::locale() =='ch')
                                    ????????????
                                @elseif(Lang::locale() =='tr-ch')
                                    ????????????
                                @else
                                    English
                                @endif
                                <div class="account-dropdown-menu">
                                    <a class="lang-select m-3" style="cursor:pointer" data-lang="en">English</a>
                                    <a class="lang-select m-3" style="cursor:pointer" data-lang="ch">????????????</a>
                                    <a class="lang-select m-3" style="cursor:pointer" data-lang="tr-ch">????????????</a>
                                </div>
                            </li>
                            <!-- End .header-dropown -->

                        </ul>
                    </div><!-- End .header-menu -->
                </div><!-- End .header-dropown -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-top -->
    @if(Request::path() != 'view-profile' && Request::path()!='register')

        <div class="header-middle">
            <div class="container">
                <div class="header-left">
                @if(Request::path() != '/')
                    <!--------------------------------------------CATEGORIES DROPDOWN------------------------>
                        <div id="category">
                            <h3 id="categories">
                                <span>{{__('front.Categories')}}</span>
                            </h3>
                            <div id="dropdown">
                                <ul>

                                    @forelse($all_categories as $category)
                                        @if(count($category->getSubCategory->where('status',1)) !== 0)
                                            <li class="liWithDropdown">
                                                <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                                                <span class="arrowAfterHover">
                                                {{$category->name}}</span></a>

                                                <ul class="level1">
                                                    @forelse($category->getSubCategory->where('status',1) as $subCategory)

                                                        @if(count($subCategory->getSubChildCategory->where('status',1))===0)
                                                            <li>
                                                                <a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">
                                                                    {{$subCategory->name}}</a>
                                                            </li>

                                                        @elseif(count($subCategory->getSubChildCategory->where('status',1))>9)

                                                            <li class="liWithDropdown1">
                                                                <a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">
                                                                    <span class="arrowAfterHover">{{$subCategory->name}}</span></a>
                                                                <!----------------------->
                                                                <div class="level2">
                                                                    <div class="row">
                                                                        <div class="col-xs-6 cols colss">
                                                                            <ul>
                                                                                @forelse($subCategory->getSubChildCategory->where('status',1) as $key=>$subChildCategory)
                                                                                    @if($key%2 ===0)
                                                                                        <li>
                                                                                            <a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subChildCategory->slug])}}">
                                                                                                {{$subChildCategory->name}}</a>
                                                                                        </li>
                                                                                    @endif
                                                                                @empty
                                                                                @endforelse
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-xs-6 cols">
                                                                            <ul>
                                                                                @forelse($subCategory->getSubChildCategory->where('status',1) as $key=>$subChildCategory)
                                                                                    @if($key%2 !==0)
                                                                                        <li>
                                                                                            <a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subChildCategory->slug])}}">{{$subChildCategory->name}}</a>
                                                                                        </li>
                                                                                    @endif
                                                                                @empty
                                                                                @endforelse
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>


                                                        @else
                                                            <li class="liWithDropdown1">
                                                                <a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">
                                                                    <span class="arrowAfterHover">{{$subCategory->name}}</span></a>
                                                                <!----------------------->
                                                                <div class="level2">
                                                                    {{--<div class="row">--}}
                                                                    <ul>

                                                                        @forelse($subCategory->getSubChildCategory->where('status',1) as $subChildCategory)
                                                                            <li>
                                                                                <a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subChildCategory->slug])}}">{{$subChildCategory->name}}</a>
                                                                            </li>
                                                                        @empty
                                                                        @endforelse
                                                                    </ul>
                                                                    {{--</div>--}}
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </ul>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                                                    {{$category->name}}</a>
                                            </li>
                                        @endif
                                    @empty
                                    @endforelse

                                </ul>
                            </div>
                        </div>
                    @else
                        <div style="min-width: 298px"></div>
                    @endif
                </div><!-- End .header-left -->

                <div class="header-center">

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
                                        <option value="product"
                                                style="display: {{($type === 'product') ?'none':'block'}}" {{($type === 'product') ?'selected':''}}>
                                            {{__('front.Products')}}
                                        </option>
                                        <option value="merchant"
                                                style="display: {{($type === 'merchant') ?'none':'block'}}" {{($type === 'merchant') ?'selected':''}}>{{__('front.Merchants')}}
                                        </option>

                                    </select>

                                </div><!-- End .select-custom -->
                                <button class="btn" type="submit"><i class="icon-magnifier"></i></button>
                            </div><!-- End .header-search-wrapper -->
                        </form>
                    </div><!-- End .header-search -->
                </div><!-- End .headeer-center -->

                <div class="header-right">
                    <button class="mobile-menu-toggler" type="button">
                        <i class="icon-menu"></i>
                    </button>
                    @include('frontend.layouts.include.cart-list-dropdown')
                </div><!-- End .header-right -->
            </div><!-- End .container -->

        </div><!-- End .header-middle -->
@endif

<!-- End .header-bottom -->
</header><!-- End .header -->