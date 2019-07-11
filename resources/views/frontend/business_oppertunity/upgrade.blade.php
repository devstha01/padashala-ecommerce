@extends('frontend.layouts.app')
@section('content')
    <main class="main">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="heading">

                        <h3> Let’s get connected.
                        </h3>
                        <h4>
                            Just confirm your information and Let our dedicated affiliates help you
                        </h4>
                    </div><!-- End .heading -->

                    @include('fragments.message')
                    <form action="{{route('upgrade-to-member-post')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <label for="name" class="col-sm-3">{{__('front.Full Name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}">
                                <span class="error-message">{{$errors->first('name')}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contact_number" class="col-sm-3">{{__('front.Contact Number')}}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="contact_number"
                                       value="{{Auth::user()->contact_number}}">
                                <span class="error-message">{{$errors->first('contact_number')}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3">{{__('front.Email')}}</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" value="{{Auth::user()->email}}">
                                <span class="error-message">{{$errors->first('email')}}</span>
                            </div>
                        </div>

                        <b>{{__('front.By submitting your information, you agree to the Golden Gate (HK)')}}
                            <a href="{{route('home-terms-of-use')}}" class="text-primary">{{__('front.Terms & conditions')}}</a> and
                            <a href="{{route('home-privacy-policy')}}" class="text-primary">{{__('front.Privacy Policy')}}</a></b>
                        <br>
                        <br>
                        <button class="btn btn-primary">{{__('front.Submit')}}</button>
                    </form>
                </div><!-- End .row -->
            </div>
            <div class="mb-5"></div><!-- margin -->
    </main><!-- End .main -->
@endsection