
<?php $staffHeader = new \App\Library\StaffPermission(); ?>
<div class="page-wrapper-row">
    <div class="page-wrapper-top">
        <!-- BEGIN HEADER -->
        <div class="page-header">
            <!-- BEGIN HEADER TOP -->
            <div class="page-header-top">
                <div class="container">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">

                        @if(Request::is('admin/*') && Auth::guard('admin')->check())
                            <a href="{{ url('admin/dashboard') }}">
                                <img src="{{asset('image/gghl-logo.png')}}" alt="Logo" style="margin:10px;height: 50px">
                            </a>
                        @elseif(Request::is('merchant/*') && Auth::guard('merchant')->check())
                            <a href="{{ url('merchant/dashboard') }}">
                                <img src="{{asset('image/gghl-logo.png')}}" alt="Logo" style="margin:10px;height: 50px">
                            </a>
                        @else
                            <a href="{{ url('/') }}">
                                <img src="{{asset('image/gghl-logo.png')}}" alt="Logo" style="margin:10px;height: 60px">
                            </a>
                        @endif
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler"></a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">

                        {{--<a href="{{ url('/')}}">--}}
                        {{--<img src="{{url('frontend/assets/images/logo.png')}}" style="margin:0 10px;height: 71px" alt="">--}}
                        {{--</a>--}}

                        <ul class="nav navbar-nav pull-right">
                            @if(Request::is('admin/*') && Auth::guard('admin')->check())
                                <li class="dropdown dropdown-extended dropdown-notification dropdown-dark"
                                    id="header_notification_bar">
                                    <a href="javascript:;" class="dropdown-toggle seen-notification" data-type="admin"
                                       data-toggle="dropdown"
                                       data-hover="dropdown" data-close-others="true">
                                        <i class="icon-bell"></i>
                                        <span class="badge badge-default">{{$notifications['count']}}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="external">
                                            <h3>You have
                                                <strong>{{$notifications['count']}} </strong> notifications</h3>
                                            <a href="{{route('admin-notification')}}">view all</a>
                                        </li>
                                        <li>
                                            <ul class="dropdown-menu-list scroller" style="height: 250px;"
                                                data-handle-color="#637283">
                                                @foreach($notifications['notifications'] as $notification)
                                                    <li>
                                                        <a href="javascript:;">
                            <span
                                    class="time">{{$notification->created_at->diffForHumans()}}</span>
                                                            <span class="details">

                            </span> {{$notification->desc}}. </span>
                                                        </a>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                            @elseif (Request::is('merchant/*') && Auth::guard('merchant')->check())
                                <li class="dropdown dropdown-extended dropdown-notification dropdown-dark"
                                    id="header_notification_bar">
                                    <a href="javascript:;" class="dropdown-toggle seen-notification"
                                       data-type="merchant" data-toggle="dropdown"
                                       data-hover="dropdown" data-close-others="true">
                                        <i class="icon-bell"></i>
                                        <span class="badge badge-default">{{$notifications['count']}}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="external">
                                            <h3>You have
                                                <strong>{{$notifications['count']}} </strong> notifications</h3>
                                            <a href="{{route('merchant-notification')}}">view all</a>
                                        </li>
                                        <li>
                                            <ul class="dropdown-menu-list scroller" style="height: 250px;"
                                                data-handle-color="#637283">
                                                @foreach($notifications['notifications'] as $notification)
                                                    <li>
                                                        <a href="javascript:;">
                            <span
                                    class="time">{{$notification->created_at->diffForHumans()}}</span>
                                                            <span class="details">

                            </span> {{$notification->desc}}. </span>
                                                        </a>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            @else
                            @endif
                            <li class="dropdown dropdown-extended dropdown-notification dropdown-dark">
                                {{--<select class="language-selector" id="lang-select"--}}
                                {{--style="padding: 7px  0 7px 20px;margin-top: 6px; color: #7089a2;">--}}
                                {{--<option value="en" @if(Lang::locale() == 'en') selected @endif>English</option>--}}
                                {{--<option value="ch" @if(Lang::locale() == 'ch') selected @endif>--}}
                                {{--简体中文--}}
                                {{--</option>--}}
                                {{--<option value="tr-ch" @if(Lang::locale() == 'tr-ch') selected @endif>--}}
                                {{--繁體中文--}}
                                {{--</option>--}}
                                {{--</select>--}}
                            </li>

                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                                   data-hover="dropdown" data-close-others="true">

                                    @if(!Request::is('member/*'))
                                        <img alt=" " class="img-circle"
                                             src="{{ URL::asset('backend/user-img/avatar.png') }}">
                                    @else
                                        @if(Auth::user() !==null)
                                            <img alt=" " class="img-circle"
                                                 src="{{ URL::asset('backend/user-img/'.strtolower(Auth::user()->getWallet->getPackage->name).'.png') }}">
                                        @endif
                                    @endif
                                    <span class="username username-hide-mobile">
                                        @if(Request::is('admin/*'))
                                            @if(Auth::guard('admin')->check())
                                                {{ Auth::guard('admin')->user()->name }}
                                            @endif
                                        @endif
                                        @if(Request::is('member/*'))
                                            @if(Auth::user() !==null)
                                                {{ Auth::user()->name.' '.Auth::user()->surname }}
                                            @endif
                                        @endif
                                        @if(Request::is('merchant/*'))
                                            @if(Auth::guard('merchant')->check())
                                                {{ Auth::guard('merchant')->user()->name.' '.Auth::guard('merchant')->user()->surname }}
                                            @endif
                                        @endif
                                    </span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-default" style="background: #272723;">


                                    @if(Request::is('merchant/*'))
                                        @if(Auth::guard('merchant')->check())
                                            <li>
                                                <a href="{{route('merchant-profile')}}"><i
                                                            class="fa header-fa fa-user"></i> {{__('dashboard.My Profile')}}
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{url('merchant/login')}}"><i
                                                            class="fa header-fa fa-user"></i> {{__('dashboard.Login')}}
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                    {{--@elseif(Auth::guard('admin')->check())--}}

                                    @if(Request::is('member/*'))
                                        @if(Auth::user())
                                            <li>
                                                <a href="{{route('view-profile')}}"><i
                                                            class="fa header-fa fa-user"></i> {{__('dashboard.My Profile')}}
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                    <li>
                                        @if(Request::is('admin/*'))
                                            @if(Auth::guard('admin')->check())
                                                <a href="{{ url('admin/logout') }}"> <i
                                                            class="header-fa icon-key"></i>{{__('dashboard.Logout')}}
                                                </a>
                                            @endif
                                        @elseif(Request::is('member/*'))
                                            @if(Auth::user() !==null)
                                                <a href="{{route('customer-logout')}}"><i
                                                            class="header-fa icon-key"></i>{{__('dashboard.Logout')}}
                                                </a>
                                            @endif
                                        @elseif(Request::is('merchant/*'))
                                            @if(Auth::guard('merchant')->check())
                                                <a href="{{ url('merchant/logout') }}"><i
                                                            class="header-fa icon-key"></i>{{__('dashboard.Logout')}}
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ url('member/logout') }}"><i
                                                        class="header-fa icon-key"></i>{{__('dashboard.Logout')}}</a>
                                        @endif
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- END HEADER TOP -->
            <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                {{--<div class="container custom-container">--}}
                <div class="container ">


                    <div class="hor-menu">
                        <ul class="nav navbar-nav">
                            @if(Request::is('admin/*'))
                                @if(Auth::guard('admin')->check())

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                                        <a href="{{ url('admin/dashboard') }}"><i class="fa header-fa fa-home"
                                            ></i>
                                            {{--{{ __('dashboard.Home') }}--}}
                                            <span class="arrow"></span>
                                        </a>
                                    </li>
                                    {{--<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown hide-if-li-0">--}}
                                        {{--<a href="javascript:;"> <i--}}
                                                    {{--class="fa header-fa fa-user"></i> {{ __('dashboard.Member Master') }}--}}
                                            {{--<span class="arrow"></span>--}}
                                        {{--</a>--}}
                                        {{--<ul class="dropdown-menu pull-left">--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Member Master.Add New Member'))--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/admin/add-new-member') }}"--}}
                                                       {{--class="nav-link"> {{ __('dashboard.Add New Member') }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Member Master.List'))--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/admin/memberLists') }}"--}}
                                                       {{--class="nav-link">{{ __('dashboard.Member Lists') }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Member Master.Placement Tree'))--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/admin/standard-placement-tree') }}">--}}
                                                        {{--{{ __('dashboard.Standard Placement Tree') }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/admin/auto-placement-tree') }}">--}}
                                                        {{--{{ __('dashboard.Auto Placement Tree') }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/admin/special-placement-tree') }}">--}}
                                                        {{--{{ __('dashboard.Special Placement Tree') }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Member Master.Upgrade Membership'))--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/admin/upgrade-customer') }}">--}}
                                                        {{--{{ __('dashboard.Upgrade Membership') }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown hide-if-li-0">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-comments-dollar"></i> {{ __('dashboard.E-commerce') }}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            @if($staffHeader->staffHasPermission('1.E-Commerce.Category'))
                                                <li>
                                                    <a href="{{route('view-category-e-commerce-admin')}}"
                                                       class="nav-link">
                                                        {{ __('dashboard.Category') }}</a>
                                                </li>
                                            @endif
                                            {{--@if($staffHeader->staffHasPermission('1.E-Commerce.Member Cash Withdrawal Request'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}

                                                    {{--<a href="{{route('admin-member-cash-withdraw-request')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Member Cash Withdrawal Request') }}</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            @if($staffHeader->staffHasPermission('1.E-Commerce.Merchant Cash Withdrawal Request'))
                                                <li aria-haspopup="true" class=" ">

                                                    <a href="{{route('admin-merchant-cash-withdraw-request')}}"
                                                       class="nav-link  ">{{ __('dashboard.Merchant Cash Withdrawal Request') }}</a>
                                                </li>
                                            @endif
                                            @if($staffHeader->staffHasPermission('1.E-Commerce.Featured Product Request'))
                                                <li aria-haspopup="true" class=" ">

                                                    <a href="{{route('admin-merchant-featured-product-request')}}"
                                                       class="nav-link  ">{{ __('dashboard.Featured Product Request')}}</a>
                                                </li>
                                            @endif

                                        </ul>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown hide-if-li-0">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-user-lock"></i> {{ __('dashboard.Merchant Master') }}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            @if($staffHeader->staffHasPermission('1.Merchant.Add New Merchant'))
                                                <li aria-haspopup="true" class=" ">
                                                    <a href="{{route('admin-merchant-register')}}"
                                                       class="nav-link">{{ __('dashboard.Add New Merchant') }}</a>
                                                </li>
                                            @endif
                                            @if($staffHeader->staffHasPermission('1.Merchant.List'))
                                                <li>
                                                    <a href="{{route('merchant-list-admin')}}"
                                                       class="nav-link">{{ __('dashboard.Merchants List')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{route('product-approval-admin')}}"
                                                       class="nav-link">{{ __('dashboard.Product Approval List')}}</a>
                                                </li>
                                               <li>
                                                    <a href="{{route('order-list-admin')}}"
                                                       class="nav-link">{{ __('dashboard.Order List')}}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>

                                    @if($staffHeader->adminHasRole())
                                        <li aria-haspopup="true"
                                            class="menu-dropdown classic-menu-dropdown hide-if-li-0">
                                            <a href="javascript:;"> <i
                                                        class="fa header-fa fa-user-edit"></i> {{ __('dashboard.Staff Master') }}
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
                                                <li aria-haspopup="true" class=" ">
                                                    <a href="{{route('admin-staff-register')}}"
                                                       class="nav-link  ">{{ __('dashboard.Add new Staff')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{route('admin-staff-list')}}"
                                                       class="nav-link  ">{{ __('dashboard.Staffs List')}}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown hide-if-li-0">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-chart-line"></i> {{__('dashboard.Reports')}}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Member Cash Withdrawal Request'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('admin-member-cash-withdraw-report')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Member Cash Withdrawal Report')}}</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            @if($staffHeader->staffHasPermission('1.Reports.Merchant Cash Withdrawal Request'))
                                                <li aria-haspopup="true" class=" ">
                                                    <a href="{{route('admin-merchant-cash-withdraw-report')}}"
                                                       class="nav-link  ">{{ __('dashboard.Merchant Cash Withdrawal Report')}}</a>
                                                </li>
                                            @endif
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Member Wallet Convert Report'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('admin-member-wallet-convert-report')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Member Wallet Converts Report')}}</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Member Wallet Transfer Report'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('admin-member-wallet-transfer-report')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Member Wallet Transfers Report')}}</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Merchant Wallet Transfer Report'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('admin-merchant-wallet-transfer-report')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Merchant Wallet Transfers Report')}}</a>--}}
                                                {{--</li>--}}

                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Merchant Payment Report'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('admin-merchant-payment-report')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Merchant Payment Report')}}</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Monthly Bonus Report'))--}}

                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('monthly-bonus-report')}}"--}}
                                                       {{--class="nav-link  ">{{ __('dashboard.Monthly Bonus Report')}}</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Shopping Point Transform Report'))--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{route('admin-shop-point-withdraw-report')}}"--}}
                                                       {{--class="nav-link">{{__('dashboard.Shopping Point Transform Report')}}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                            @if($staffHeader->staffHasPermission('1.Reports.Product Purchase Report'))
                                                <li>
                                                    <a href="{{route('admin-purchase-report')}}"
                                                       class="nav-link">{{__('front.Product Purchase Report')}}
                                                    </a>
                                                </li>
                                            @endif
                                            {{--@if($staffHeader->staffHasPermission('1.Reports.Grant Wallet/ Retain Wallet Report'))--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{route('grant-retain-report')}}"--}}
                                                       {{--class="nav-link  ">Grant / Retain Wallet Report</a>--}}
                                                {{--</li>--}}
                                            {{--@endif--}}
                                        </ul>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown hide-if-li-0">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-file"></i> {{ __('dashboard.Customer Master') }}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            @if($staffHeader->staffHasPermission('1.Customer.List'))
                                                <li>
                                                    <a href="{{route('customer-list')}}" class="nav-link">
                                                        {{ __('dashboard.Customer List')}} </a>
                                                </li>
                                            @endif
                                            @if($staffHeader->staffHasPermission('1.Customer.Banners'))
                                                <li>
                                                    <a href="{{route('admin-banner')}}" class="nav-link">
                                                        {{ __('dashboard.Banners')}}</a>
                                                </li>
                                            @endif
                                            @if($staffHeader->staffHasPermission('1.Customer.Subscribers'))
                                                <li>
                                                    <a href="{{route('admin-subscribe')}}" class="nav-link">
                                                        {{ __('dashboard.Subscribers')}}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>

                                    @if($staffHeader->adminHasRole())
                                        <li aria-haspopup="true"
                                            class="menu-dropdown classic-menu-dropdown hide-if-li-0">
                                            <a href="javascript:;"> <i
                                                        class="fa header-fa fa-user-cog"></i> {{ __('dashboard.Configurations') }}
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
                                                {{--<li>--}}
                                                    {{--<a href="{{route('packages')}}" class="nav-link">--}}
                                                        {{--Packages </a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{route('refaral-bonus-list')}}" class="nav-link">--}}
                                                        {{--{{ __('dashboard.Referral Bonus')}} </a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{route('holiday-dates')}}" class="nav-link">--}}
                                                        {{--{{ __('dashboard.Holiday Date')}}</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{route('admin-shopping-list')}}"--}}
                                                       {{--class="nav-link">{{ __('dashboard.Shopping Bonus')}}</a>--}}
                                                {{--</li>--}}
                                                <li>
                                                    <a href="{{route('admin-withdraw-config')}}"
                                                       class="nav-link">{{ __('dashboard.Min-Max Config')}}</a>
                                                </li>

                                            </ul>
                                        </li>
                                    @endif
                                @endif
                            @endif

                            @if(Request::is('merchant/*'))

                                @if(Auth::guard('merchant')->check())

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                                        <a href="{{ url('merchant/dashboard') }}"><i class="fa header-fa fa-home"
                                            ></i>
                                            {{--{{__('dashboard.Home')}}--}}
                                            <span class="arrow"></span>
                                        </a>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-tags"></i> {{__('dashboard.Products')}}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{route('create-product-merchant')}}"
                                                   class="nav-link  ">{{__('dashboard.Add new product')}}</a></li>
                                            <li aria-haspopup="true" class=" "><a
                                                        href="{{route('view-product-merchant')}}"
                                                        class="nav-link  ">{{__('dashboard.Products List')}}</a>
                                            </li>
                                        <li aria-haspopup="true" class=" "><a
                                                        href="{{route('view-product-request-merchant')}}"
                                                        class="nav-link  ">{{__('dashboard.Product Requests')}}</a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-shopping-cart"></i> {{__('dashboard.Order Master')}}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{route('manage-order')}}" class="nav-link  ">
                                                    {{__('dashboard.Order List')}} </a>
                                            </li>

                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{route('manage-order',['tab'=>'pending'])}}"
                                                   class="nav-link  ">
                                                    {{__('dashboard.Pending Orders')}} </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-landmark"></i> {{__('dashboard.Business Master')}}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">


                                            {{--<li aria-haspopup="true" class=" ">--}}
                                                {{--<a href="{{route('merchant-merchant-wallet-transfer')}}" class="nav-link  ">--}}
                                                    {{--{{__('dashboard.Merchant Wallet Transfer')}} </a>--}}
                                            {{--</li>--}}

                                            {{--<li aria-haspopup="true" class=" ">--}}
                                                {{--<a href="{{route('manage-payment')}}" class="nav-link  ">--}}
                                                    {{--{{__('dashboard.Payment Request')}} </a>--}}
                                            {{--</li>--}}

                                            {{--<li aria-haspopup="true" class=" ">--}}
                                                {{--<a href="{{route('manage-payment-list')}}" class="nav-link  ">--}}
                                                    {{--{{__('dashboard.Payment List')}} </a>--}}
                                            {{--</li>--}}
                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{url('merchant/wallet-withdraw')}}" class="nav-link  ">
                                                    {{__('dashboard.Withdrawal Request')}} </a>
                                            </li>

                                            {{--<li aria-haspopup="true" class=" ">--}}
                                                {{--<a href="{{route('bonus-request-cash')}}" class="nav-link  ">--}}
                                                    {{--{{__('dashboard.Admin Bonus Request')}} </a>--}}
                                            {{--</li>--}}

                                        </ul>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                        <a href="javascript:;"> <i
                                                    class="fa header-fa fa-chart-line"></i> {{__('dashboard.Reports')}}
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-left">
                                            <li>
                                                <a href="{{route('merchant-cash-withdraw-report')}}"
                                                   class="nav-link">{{__('dashboard.Cash Withdrawal Report')}}
                                                </a>
                                            </li>

                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{route('order-reportmerchant')}}" class="nav-link  ">
                                                    {{__('dashboard.Order Product Report')}} </a>
                                            </li>
                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{route('wallet-reportmerchant')}}" class="nav-link  ">
                                                    {{__('dashboard.Order Wallet Report')}} </a>
                                            </li>
                                            {{--<li aria-haspopup="true" class=" ">--}}
                                                {{--<a href="{{route('payment-reportmerchant')}}" class="nav-link  ">--}}
                                                    {{--{{__('dashboard.Payment Report')}} </a>--}}
                                            {{--</li>--}}
                                            <li aria-haspopup="true" class=" ">
                                                <a href="{{route('purchase-reportmerchant')}}" class="nav-link  ">
                                                    {{__('front.Product Purchase Report')}} </a>
                                            </li>

                                            {{--<li aria-haspopup="true" class=" ">--}}
                                                {{--<a href="{{route('wallet-transfer-reportmerchant')}}"--}}
                                                   {{--class="nav-link  ">--}}
                                                    {{--{{__('dashboard.Wallet Transfer Report')}} </a>--}}
                                            {{--</li>--}}
                                            {{--<li aria-haspopup="true" class=" ">--}}

                                                {{--<a href="{{route('merchant-grant-retain-report')}}"--}}
                                                   {{--class="nav-link  ">Grant / Retain Wallet Report</a>--}}
                                            {{--</li>--}}

                                        </ul>
                                    </li>

                                @endif
                            @endif
                            {{--@if(Request::is('member/*'))--}}
                                {{--<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">--}}
                                    {{--<a href="{{ url('member/dashboard') }}"><i class="fa header-fa fa-home"--}}
                                        {{--></i>--}}
                                        {{--{{__('dashboard.Home')}}--}}
                                        {{--<span class="arrow"></span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}

                                {{--<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">--}}

                                    {{--<a href="javascript:;"> <i--}}
                                                {{--class="fa header-fa fa-user-tie"></i> {{__('dashboard.Member Master')}}--}}
                                        {{--<span class="arrow"></span>--}}
                                    {{--</a>--}}
                                    {{--<ul class="dropdown-menu pull-left">--}}
                                        {{--<li>--}}
                                            {{--<a href="{{ url('/member/add-new-member') }}"--}}
                                               {{--class="nav-link">{{__('dashboard.Add New Member')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{ url('/member/memberLists') }}"--}}
                                            {{--class="nav-link">{{__('dashboard.Member Lists')}}--}}
                                            {{--</a>--}}
                                            {{--<a href="{{ url('/member/memberLists') }}"--}}
                                               {{--class="nav-link">{{__('dashboard.Member Lists')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{ url('/member/standard-placement-tree') }}">--}}
                                                {{--{{__('dashboard.Standard Placement Tree')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{ url('/member/auto-placement-tree') }}">--}}
                                                {{--{{__('dashboard.Auto Placement Tree')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{ url('/member/special-placement-tree') }}">--}}
                                                {{--{{__('dashboard.Special Placement Tree')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}


                                    {{--</ul>--}}
                                {{--</li>--}}


                                {{--<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">--}}
                                    {{--<a href="javascript:;"> <i--}}
                                                {{--class="fa header-fa fa-clipboard-list"></i> {{__('dashboard.Business Management')}}--}}
                                        {{--<span class="arrow"></span>--}}
                                    {{--</a>--}}
                                    {{--<ul class="dropdown-menu pull-left" style="width:225px">--}}


                                        {{--<li aria-haspopup="true" class=" ">--}}
                                            {{--<a href="{{ url('/member/wallet-convert') }}"--}}
                                               {{--class="nav-link  "> {{__('dashboard.Wallet Convert')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li aria-haspopup="true" class=" ">--}}
                                            {{--<a href="{{ url('/member/wallet-transfer') }}"--}}
                                               {{--class="nav-link  "> {{__('dashboard.Wallet Transfer')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li aria-haspopup="true" class=" ">--}}
                                        {{--<a href="{{ url('/member/wallet-transfer-request') }}"--}}
                                        {{--class="nav-link  "> {{__('dashboard.Wallet Transfer Request')}}--}}
                                        {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li aria-haspopup="true" class=" ">--}}
                                            {{--<a href="{{url('/member/wallet-withdraw')}}"--}}
                                               {{--class="nav-link  "> {{__('dashboard.Withdrawal Request')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li aria-haspopup="true" class=" ">--}}
                                            {{--<a href="{{url('/member/shopping-withdraw')}}"--}}
                                               {{--class="nav-link  "> {{__('dashboard.Shopping Point Transform')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li aria-haspopup="true" class=" ">--}}
                                            {{--<a href="{{url('/member/dividend-withdraw')}}"--}}
                                               {{--class="nav-link  "> {{__('dashboard.Dividend Transform')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                    {{--</ul>--}}
                                {{--</li>--}}

                                {{--<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">--}}
                                    {{--<a href="javascript:;"> <i--}}
                                                {{--class="fa header-fa fa-chart-line"></i> {{__('dashboard.Report')}}--}}
                                        {{--<span class="arrow"></span>--}}
                                    {{--</a>--}}
                                    {{--<ul class="dropdown-menu pull-left">--}}
                                        {{--<li>--}}
                                            {{--<a href="{{route('cash-withdraw-report')}}"--}}
                                               {{--class="nav-link">{{__('dashboard.Cash Withdrawal Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{route('wallet-convert-report')}}"--}}
                                               {{--class="nav-link">{{__('dashboard.Wallet Converts Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{route('wallet-transfer-report')}}"--}}
                                               {{--class="nav-link">{{__('dashboard.Wallet Transfers Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li>--}}
                                            {{--<a href="{{route('payment-report')}}"--}}
                                               {{--class="nav-link">{{__('dashboard.Merchant Payment Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{route('purchase-report')}}"--}}
                                               {{--class="nav-link">{{__('front.Product Purchase Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}

                                        {{--<li>--}}
                                            {{--<a href="{{route('shop-point-withdraw-report')}}"--}}
                                               {{--class="nav-link">{{__('dashboard.Shopping Point Transform Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{route('bonus-report')}}"--}}
                                               {{--class="nav-link">{{__('dashboard.Generation Bonus Report')}}--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{route('daily-bonus-report')}}"--}}
                                               {{--class="nav-link">Dividend Report--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li aria-haspopup="true" class="dropdown-submenu">--}}
                                            {{--<a href="javascript:;"--}}
                                               {{--class="nav-link nav-toggle">Wallet--}}
                                                {{--<span class="arrow"></span>--}}
                                            {{--</a>--}}
                                            {{--<ul class="dropdown-menu">--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{ url('/member/ecash-wallet-report') }}"--}}
                                                       {{--class="nav-link">Cash Wallet</a>--}}
                                                {{--</li>--}}

                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{ url('/member/evoucher-wallet-report') }}"--}}
                                                       {{--class="nav-link">Voucher Wallet</a>--}}
                                                {{--</li>--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{ url('/member/rpoint-wallet-report') }}"--}}
                                                       {{--class="nav-link">R Wallet</a>--}}
                                                {{--</li>--}}
                                                {{--<li aria-haspopup="true" class=" ">--}}
                                                    {{--<a href="{{ url('/member/chip-wallet-report') }}"--}}
                                                       {{--class="nav-link">Chips</a>--}}
                                                {{--</li>--}}


                                            {{--</ul>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}

                            {{--@endif--}}
                        </ul>

                    </div>

                </div>
                <!-- END MEGA MENU -->
            </div>
        </div>
        <!-- END HEADER MENU -->
    </div>
    <!-- END HEADER -->
</div>

