<footer class="footer">
    <div class="footer-middle">
        <div class="container">
            <div class="footer-ribbon">
                {{__('front.Get in touch')}}
            </div>
            <div class="col-lg-12">
                <div class="widget widget-newsletter" style="border-bottom:0;!important;">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h4 class="widget-title">{{__('front.Know Us More')}}</h4>
                                    <ul class="links">
                                        <li><a href="{{route('about')}}">{{__('front.About Us')}}</a></li>
                                        <li><a href="{{route('contact')}}">{{__('front.Contact Us')}}</a></li>
                                        {{--                                        <li><a href="{{route('view-profile')}}">{{__('front.Blogs')}}</a></li>--}}
                                    </ul>
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <h4 class="widget-title">{{__('front.Earn With Us')}}</h4>
                                    <ul class="links">
                                        <li>
                                            <a href="{{route('home-sell-on')}}">{{__('front.Sell on ')}} {{env('APP_NAME')}}</a>
                                        </li>
                                        {{--<li>--}}
                                            {{--<a href="{{route('home-become-affiliate')}}">--}}
                                                {{--@if(!Auth::check())--}}
                                                    {{--{{__('front.Become an Affiliate')}}--}}
                                                {{--@else--}}
                                                    {{--@if(!Auth::user()->is_member)--}}
                                                        {{--{{__('front.Upgrade Membership')}}--}}
                                                    {{--@endif--}}
                                                {{--@endif--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <h4 class="widget-title">{{__('front.Follow Us')}}</h4>
                                    <ul class="links">
                                        <li><a href="#"><i class="fa fa-facebook-square text-white"></i></a></li>
                                        <li>
                                            <a href="https://play.google.com/store/apps/details?id=com.goldengatehk.goldengate">{{__('front.Download App')}}</a>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-5">
                            <h4 class="widget-title">{{__('front.Subscribe newsletter')}}</h4>

                            <p>Get all the latest information on Events,Sales and Offers. Sign up for newsletter
                                today</p>
                            <br>
                            <form action="#" style="position: relative">
                                <span class="fade" id="subscribe-message"></span>
                                <input type="email" id="sub-email" class="form-control" placeholder="Email address">

                                <input id="sub-submit" type="submit" class="btn" value="Subscribe">
                            </form>
                        </div><!-- End .col-md-6 -->
                    </div><!-- End .row -->

                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <a href="{{route('home-privacy-policy')}}">{{__('front.Privacy Policy')}}</a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{route('home-terms-of-use')}}">{{__('front.Terms & conditions')}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div><!-- End .widget -->

            </div><!-- End .container -->
        </div><!-- End .footer-middle -->

        <div class="container">
            <div class="footer-bottom">
                <p class="footer-copyright">{{env('APP_NAME')}} &copy; {{Carbon\Carbon::now()->format('Y')}}
                    . {{__('front.All Rights Reserved')}}</p>

                {{--                    <img src="{{ URL::asset('frontend/assets/images/payments.png')}}" alt="payment methods" class="footer-payments">--}}
            </div><!-- End .footer-bottom -->
        </div><!-- End .container -->
</footer><!-- End .footer -->