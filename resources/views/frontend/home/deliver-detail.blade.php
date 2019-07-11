@extends('frontend.layouts.app')

@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
            {{--<div class="container">--}}
                {{--<ol class="breadcrumb">--}}
                    {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
                    {{--<li class="breadcrumb-item active">{{__('front.Profile')}}</li>--}}
                {{--</ol>--}}
            {{--</div><!-- End .container -->--}}
        {{--</nav>--}}


        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card p-3">
                        <img class="rounded-circle" src="{{asset('image/merchants/no-image.png')}}" alt="image"
                             style="max-width:220px;max-height:220px">
                        <br>
                        <h3 class="text-right">{{$user->name}} {{ $user->surname }}</h3>
                        <i class="fa fa-user mb-1"><i class="fa fa-key ml-1"></i> {{$user->user_name}}</i>
                        <i class="fa fa-envelope mb-1"> {{$user->email}}</i>
                        <i class="fa fa-phone mb-1"> {{$user->contact_number??' -'}}</i>
                        <i class="fa fa-birthday-cake mb-1"> {{$user->dob??' -'}}</i>
                        <i class="fa fa-map-marker mb-1">{{$user->address ??' - '}}, {{$user->city??' - '}}
                            , {{$user->getCountry->name}}</i>
                        <i class="fa fa-id-card mb-1"> {{$user->identification_type }}
                            : {{$user->identification_number??' - '}}</i>
                        <i class="fa fa-id-card-o mb-1"> Gender : {{$user->gender }} | Marital Status
                            : {{$user->marital_status}}</i>
                        @if($user->is_member)
                            <i class="fa fa-check text-success"> {{__('front.Member')}}</i>
                        @else
                            <i class="fa fa-times text-danger"> {{__('front.Member')}}</i>
                        @endif

                        <a class="btn btn-info m-3" href="{{route('edit-profile',$user->id)}}">{{__('front.Edit Profile')}}</a>
                        @if(Auth::user()->is_member ===1)
                            <a class="btn btn-info m-3" href="{{route('edit-bank',$user->id)}}">{{__('front.Bank Account Info')}}</a>
                        @endif
                        <a class="btn btn-info m-3" href="{{url('change/password')}}">{{__('front.Change Password')}}</a>
                    </div>
                </div>
                <div class="col-md-8">

                </div>
            </div><!-- End .col-lg-8 -->
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main><!-- End .main -->

@endsection