@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
            {{--<div class="container">--}}
                {{--<ol class="breadcrumb">--}}
                    {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
                    {{--<li class="breadcrumb-item"><a href="{{route('checkout-login')}}">{{__('front.Login')}}</a></li>--}}
                    {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Password Recovery')}}</li>--}}
                {{--</ol>--}}
            {{--</div><!-- End .container -->--}}
        {{--</nav>--}}

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="heading">
                        <h2 class="title">{{__('front.Password Recovery')}}</h2>
                    </div><!-- End .heading -->

                    @include('fragments.message')

                    <form action="{{route('post-frontend-recovery')}}" method="post">
                        {{csrf_field()}}
                        <input type="email" class="form-control" name="email" value="{{old('email')}}"
                               placeholder="Email">
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{__('front.Submit')}}</button>
                        </div><!-- End .form-footer -->
                    </form>
                </div>

            </div><!-- End .row -->
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main><!-- End .main -->
@endsection