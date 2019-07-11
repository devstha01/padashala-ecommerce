<div class="mobile-menu-overlay"></div>

<div class="mobile-menu-container">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="icon-cancel text-white"></i></span>
        <nav class="mobile-nav">
            <ul class="mobile-menu">
                <li><a href="{{url('/')}}">Home</a></li>
                <li>
                    <a href="{{route('all-categories')}}">{{__('front.Categories')}}</a>
                </li>
                @if(Auth::user())
                    @if(Auth::user() && Auth::user()->is_member ===1 )
                        <li>
                            <a href="{{url('member/dashboard')}}"><i
                                        class="fa fa-dashboard text-white"></i>&nbsp; {{ __('front.Dashboard') }}
                            </a>
                        </li>
                    @endif
                    <li><a href="{{route('order-list')}}">
                            <i class="fa fa-shopping-cart text-white"></i>&nbsp; {{__('front.My Orders')}}
                        </a>
                    </li>
                    <li><a href="{{route('customer-logout')}}"><i
                                    class="fa fa-sign-out text-white"></i>&nbsp; {{__('front.Logout')}}
                        </a>
                    </li>
                @else
                    <li><a href="{{route('checkout-login')}}">{{__('front.Log In')}}</a></li>
                    <li><a href="{{route('customer-register')}}">{{__('front.Register')}}</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>