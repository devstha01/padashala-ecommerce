@extends('frontend.layouts.app')
@section('content')
    <main class="main">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="heading">
                        <br>
                        <br>
                        <p class="lead text-custom-blue" style="font-weight: bold;font-size:28px;">YOUR PRODUCTS, YOUR CONTROLS
                            <br>
                            $0 SIGN UP FEES. $0 POSTING FEES.
                            <br>
                            GET DISCOVERED WITH $0 UPFRONT FEES.
                        </p>
                        <br>
                        <a href="{{route('free-merchant-register')}}"
                           class="btn affilateButton-sellon">{{__('front.START SELLING ONLINE')}}</a>
                        <hr>
                        <p class="lead text-custom-blue" style="font-weight: bold">GET CONNECTED WITH THE CUSTOMERS ALL AROUND THE WORLD.
                            <br> GET YOUR PRODUCTS SEEN BY MORE SHOPPERS.
                        </p>
                    </div><!-- End .heading -->

                    <div class="">
                        <a href="{{route('free-merchant-register')}}"
                           class="btn affilateButton-sellon">{{__('front.REGISTER FREE')}}</a>
                        <br>
                        <br>
                        <br>
                        <p class="lead text-custom-blue">
                            Don’t have any registered company? Or just have few items to sell?
                            <br>
                            <a href="{{route('independent-merchant-register')}}" class="text-primary"><b>Register for free </b></a>and become an Independent
                            business owner.
                        </p>
                    </div>
                </div>
            </div><!-- End .row -->
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main><!-- End .main -->
@endsection
