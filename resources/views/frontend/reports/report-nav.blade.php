<ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link {{$active_nav=='payment'?'active':''}}"
                            href="{{route('my-reports','payment')}}">{{__('front.Merchant Payment Report')}}</a></li>
    <li class="nav-item"><a class="nav-link {{$active_nav=='purchase'?'active':''}}"
                            href="{{route('my-reports','purchase')}}">{{__('front.Product Purchase Report')}}</a></li>
    <li class="nav-item"><a class="nav-link {{$active_nav=='transfer'?'active':''}}"
                            href="{{route('my-reports','transfer')}}">{{__('front.Wallet Transfer Report')}}</a></li>
</ul>