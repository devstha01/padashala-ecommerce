@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item"><a href="{{ route('order-list') }}">{{__('front.Profile')}}</a></li>--}}
        {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Edit Profile')}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="heading">
                        <h3 class="title">{{__('front.Edit Profile')}}

                            <br> <div class="float-right">
                                <form action="{{route('regen-user-qr')}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="user_id" value="{{Auth::id()}}">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-qrcode"> </i> Regenerate QR Code
                                    </button>
                                </form>
                            </div>
                        </h3>
                </div>
                <br>
                @include('fragments.message')
                <form action="{{route('update-profile')}}" method="post">
                    {{csrf_field()}}
                    <h3>{{__('front.Personal Detail')}}</h3>
                    {{--<div class="row">--}}
                    {{--<div class="col-sm-6">--}}
                    {{--<div class="form-group">--}}
                    {{--<label class="control-label">{{__('front.First Name')}}</label>--}}
                    {{--<input type="text" name="name"--}}
                    {{--class="form-control" value="{{($users->name??'')}}">--}}
                    {{--<span style="color: red">{{$errors->first('name')??''}}</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-6">--}}

                    {{--<div class="form-group">--}}
                    {{--<label class="control-label">{{__('front.Last Name')}}</label>--}}
                    {{--<input type="text" name="surname"--}}
                    {{--class="form-control" value="{{($users->surname??'')}}">--}}
                    {{--<span style="color: red">{{$errors->first('surname')??''}}</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Email')}}</label>
                                <input type="text" name="email"
                                       class="form-control" value="{{($users->email??'')}}">
                                <span style="color: red">{{$errors->first('email')??''}}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Contact Number')}}</label>
                                <input type="text" name="contact_number"
                                       class="form-control"
                                       value="{{($users->contact_number??'')}}">
                                <span style="color: red">{{$errors->first('contact_number')??''}}</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Country')}}</label>
                                <select name="country_id" class="form-control">
                                    <option value="">{{__('front.-- select a country --')}}</option>
                                    @forelse($countries as $country)
                                        <option value="{{$country->id}}" {{$country->id == $users->country_id?'selected':''}}>{{$country->name}}</option>
                                    @empty
                                        <option value="">{{__('front.no data available')}}</option>
                                    @endforelse
                                </select>
                                <span style="color: red">{{$errors->first('country_id')??''}}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.City')}}</label>
                                <input type="text" name="city"
                                       class="form-control" value="{{($users->city??'')}}">
                                <span style="color: red">{{$errors->first('city')??''}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Address')}}</label>
                                <input type="text" name="address"
                                       class="form-control" value="{{($users->address??'')}}">
                                <span style="color: red">{{$errors->first('address')??''}}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Marital Status')}}</label>
                                <select name="marital_status" class="form-control">
                                    <option value="no" {{(strtoupper($users->marital_status) == 'NO')?'selected':''}}>
                                        {{__('dashboard.Single')}}
                                    </option>
                                    <option value="yes" {{strtoupper($users->marital_status) =='YES'?'selected':''}}>
                                        {{__('dashboard.Married')}}
                                    </option>
                                </select>
                                <span style="color: red">{{$errors->first('marital_status')??''}}</span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Identification Type')}}</label>
                                <select name="identification_type" class="form-control">
                                    <option value="citizenship" {{strtoupper($users->identification_type) =='CITIZENSHIP' ?'selected':''}}>
                                        {{__('front.citizenship')}}
                                    </option>
                                    <option value="passport" {{strtoupper($users->identification_type) =='PASSPORT' ?'selected':''}}>
                                        {{__('front.passport')}}
                                    </option>
                                </select>
                                <span style="color: red">{{$errors->first('identification_type')??''}}</span>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{__('front.Identification Number')}}
                                </label>
                                <input type="text"
                                       class="form-control"
                                       name="identification_number"
                                       value="{{($users->identification_number??'')}}"
                                >
                                <span style="color: red">{{$errors->first('identification_number')??''}}</span>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->is_member === 1)
                        <hr>
                        <h3>{{__('front.Nominee Detail')}}</h3>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Name')}}</label>
                                    <input type="text" name="nominee_name"
                                           class="form-control" value="{{($users->getNominee->nominee_name??'')}}">
                                    <span style="color: red">{{$errors->first('nominee_name')??''}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label class="control-label">{{__('front.Contact Number')}}</label>
                                    <input type="text" name="nominee_contact_number"
                                           class="form-control"
                                           value="{{($users->getNominee->contact_number??'')}}">
                                    <span style="color: red">{{$errors->first('nominee_contact_number')??''}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Identification Type')}}</label>
                                    <select name="nominee_identification_type" class="form-control">
                                        <option value="citizenship" {{strtoupper($users->getNominee->identification_type) =='CITIZENSHIP' ?'selected':''}}>
                                            {{__('front.citizenship')}}
                                        </option>
                                        <option value="passport" {{strtoupper($users->getNominee->identification_type) =='PASSPORT' ?'selected':''}}>
                                            {{__('front.passport')}}
                                        </option>
                                    </select>
                                    <span style="color: red">{{$errors->first('nominee_identification_type')??''}}</span>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Identification Number')}}
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           name="nominee_identification_number"
                                           value="{{($users->getNominee->identification_number??'')}}"
                                    >
                                    <span style="color: red">{{$errors->first('nominee_identification_number')??''}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Relationship')}}
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           name="nominee_relationship"
                                           value="{{($users->getNominee->relationship??'')}}"
                                    >
                                    <span style="color: red">{{$errors->first('nominee_relationship')??''}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">{{__('front.Submit')}}</button>
                    </div><!-- End .form-footer -->
                </form>
            </div>

        </div><!-- End .row -->
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main>
@endsection

@section('stylesheets')
    <style>
        .custom-control-label:before, .custom-control-label:after {
            display: none !important;
        }
    </style>
@endsection