@extends('frontend.layouts.app')
@section('content')
    <main class="main">

        <div class="container" style="min-height:300px">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <br>
                    @if(!Auth::check())
                        <div class="heading">
                            <h2 class=" text-custom-blue">
                                {{__('front.Not yet a customer?')}}
                            </h2>
                            <p class="lead text-custom-blue">
                                {{__('front.Its easy and free.')}}
                            </p>
                            <a href="{{url('/register')}}"
                               class="btn  affilateButton-sellon">{{__('front.JOIN FREE')}}</a>
                        </div><!-- End .heading -->
                        <hr>
                    @else
                        <div class="">
                            {{--<h2 class="text-custom-blue">Are you already a Customer</h2>--}}
                            <p class="lead text-custom-blue">
                                <b>Hey {{Auth::user()->name??'Customer'}}</b>, You are already a Customer. Upgrade and
                                open up yourself to the world of opportunity. Don't wait anymore to change your shopping
                                into your earnings. You are full of potential, explore it.Meet the community of like
                                minded. Take the charge of your life. <br> You are just one click away.
                            </p>
                            <a href="{{route('upgrade-to-member')}}"
                               class="btn  affilateButton-sellon">{{__('front.UPGRADE NOW')}}</a>
                        </div>
                        <hr>
                    @endif

                </div>
            </div><!-- End .row -->
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main><!-- End .main -->
@endsection
