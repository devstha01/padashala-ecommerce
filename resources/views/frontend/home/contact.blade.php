@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="container text-right">
                <h3> {{__('front.Contact Us')}}</h3>
            </div>

            <div class="row">
                <div class="col-md-8">

                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1843.5988189531458!2d114.00050585828369!3d22.4592061750795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3403f088f52afc69%3A0xdae8e56de5a6fef!2sLynwood+Court!5e0!3m2!1sen!2snp!4v1561722084800!5m2!1sen!2snp" width="650" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

                    {{--<div id="map"></div><!-- End #map -->--}}
                </div>
                <div class="col-md-4">
                    <h3 class="light-title">
                    </h3>
                </div><!-- End .col-md-4 -->
            </div><!-- End .row -->
        </div><!-- End .container -->

        <div class="mb-8"></div><!-- margin -->
    </main><!-- End .main -->
@endsection