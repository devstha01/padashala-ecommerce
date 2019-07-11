@extends('frontend.layouts.app')

@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Register')}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}

        <div class="container">
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="heading">
                        <h2 class="title">{{__('front.Register')}}</h2>
                        {{--<p>If you have an account with us, please log in.--}}
                        {{--                            <a href="{{route('checkout-login')}}">Go to Login!</a>--}}
                        {{--</p>--}}
                    </div><!-- End .heading -->

                    <form action="{{route('customer-register-post')}}" method="post">
                        {{csrf_field()}}
                        <h4>{{__('front.PERSONAL DETAIL')}}</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.First Name')}}</label>
                                    <input type="text" name="name"
                                           class="form-control" value="{{old('name')??''}}">
                                    <span style="color: red">{{$errors->first('name')??''}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label class="control-label">{{__('front.Last Name')}}</label>
                                    <input type="text" name="surname"
                                           class="form-control" value="{{old('surname')??''}}">
                                    <span style="color: red">{{$errors->first('surname')??''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label class="control-label">{{__('front.User Name')}}</label>
                                    <input type="text" name="user_name"
                                           class="form-control" value="{{old('user_name')??''}}">
                                    <span style="color: red">{{$errors->first('user_name')??''}}</span>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Email')}}</label>
                                    <input type="text" name="email"
                                           class="form-control" value="{{old('email')??''}}">
                                    <span style="color: red">{{$errors->first('email')??''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Country')}}</label>
                                    <select name="country_id" class="form-control">
                                        <option value="">{{__('front.-- select a country --')}}</option>
                                        @forelse($countries as $country)
                                            <option value="{{$country->id}}" {{$country->id == old('country_id')?'selected':''}}>{{$country->name}}</option>
                                        @empty
                                            <option value="">{{__('front.no data available')}}</option>
                                        @endforelse
                                    </select>
                                    <span style="color: red">{{$errors->first('country_id')??''}}</span>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.City')}}</label>
                                    <input type="text" name="city"
                                           class="form-control" value="{{old('city')??''}}">
                                    <span style="color: red">{{$errors->first('city')??''}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Address')}}</label>
                                    <input type="text" name="address"
                                           class="form-control" value="{{old('address')??''}}">
                                    <span style="color: red">{{$errors->first('address')??''}}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Contact Number')}}</label>
                                    <input type="text" name="contact_number"
                                           class="form-control"
                                           value="{{old('contact_number')??''}}">
                                    <span style="color: red">{{$errors->first('contact_number')??''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Date of Birth')}}</label>
                                    <input class="form-control form-control-inline input-medium datepicker"
                                           size="16" type="text"
                                           name="dob_date" autocomplete="off" value="{{old('dob_date')??''}}">
                                    <span style="color: red">{{$errors->first('dob_date')??''}}</span>
                                </div>
                            </div>

                            <div class="col-sm-4">

                                {{--<div class="form-group">--}}
                                    {{--<label class="control-label">{{__('front.Marital Status')}}</label>--}}
                                    {{--<select name="marital_status" class="form-control">--}}
                                        {{--<option value="no" {{old('marital_status') === 'no'?'selected':''}}>--}}
                                            {{--{{__('dashboard.Single')}}--}}
                                        {{--</option>--}}
                                        {{--<option value="yes" {{old('marital_status') ==='yes'?'selected':''}}>--}}
                                            {{--{{__('dashboard.Married')}}--}}
                                        {{--</option>--}}
                                    {{--</select>--}}
                                    {{--<span style="color: red">{{$errors->first('marital_status')??''}}</span>--}}

                                {{--</div>--}}
                            </div>
                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label class="control-label">{{__('front.Gender')}}</label><br>
                                    <input type="radio"
                                           {{--class="custom-control-input"--}}
                                           id="customControlValidation1"
                                           name="gender" value="Male" checked>
                                    <label class="custom-control-label"
                                           for="customControlValidation1">{{__('front.Male')}}</label>

                                    <input type="radio"
                                           {{--class="custom-control-input"--}}
                                           id="customControlValidation2"
                                           name="gender"
                                           value="Female" {{old('gender') ==='Female' ? 'checked':''}}>
                                    <label class="custom-control-label"
                                           for="customControlValidation2">{{__('front.Female')}}</label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h3>{{__('front.Password')}}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>{{__('front.Login Password')}}</h5>
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Login Password')}}
                                    </label>
                                    <input type="password"
                                           class="form-control"
                                           name="new_password"
                                           placeholder="" value="{{old('new_password')??''}}">
                                    <span style="color: red">{{$errors->first('new_password')??''}}</span>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">{{__('dashboard.Retype Login Password')}}
                                    </label>
                                    <input type="password"
                                           class="form-control"
                                           name="retype_password"
                                           placeholder="" value="{{old('retype_password')??''}}">
                                    <span style="color: red">{{$errors->first('retype_password')??''}}</span>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <h5>{{__('front.Transaction Password')}}</h5>
                                <div class="form-group">
                                    <label class="control-label">{{__('front.Transaction Password')}}
                                    </label>
                                    <input type="password"
                                           class="form-control"
                                           name="transaction_password"
                                           placeholder=""
                                           value="{{old('transaction_password')??''}}">
                                    <span style="color: red">{{$errors->first('transaction_password')??''}}</span>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">{{__('dashboard.Retype Transaction Password')}}
                                    </label>
                                    <input type="password"
                                           class="form-control"
                                           name="retype_transaction_password"
                                           placeholder=""
                                           value="{{old('retype_transaction_password')??''}}">
                                    <span style="color: red">{{$errors->first('retype_transaction_password')??''}}</span>
                                </div>

                            </div>
                        </div>

                        {{--<hr>--}}

                        {{--<div class="row">--}}
                            {{--<div class="col-sm-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label class="control-label">{{__('front.Identification Type')}}</label>--}}
                                    {{--<select name="identification_type" class="form-control">--}}
                                        {{--<option value="citizenship" {{old('identification_type')==='citizenship' ?'selected':''}}>--}}
                                            {{--{{__('front.citizenship')}}--}}
                                        {{--</option>--}}
                                        {{--<option value="passport" {{old('identification_type')==='passport' ?'selected':''}}>--}}
                                            {{--{{__('front.passport')}}--}}
                                        {{--</option>--}}
                                    {{--</select>--}}
                                    {{--<span style="color: red">{{$errors->first('identification_type')??''}}</span>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                            {{--<div class="col-sm-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label class="control-label">{{__('front.Identification Number')}}--}}
                                    {{--</label>--}}
                                    {{--<input type="text"--}}
                                           {{--class="form-control"--}}
                                           {{--name="identification_number"--}}
                                           {{--placeholder=""--}}
                                           {{--value="{{old('identification_number')??''}}">--}}
                                    {{--<span style="color: red">{{$errors->first('identification_number')??''}}</span>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                        <br>
                        <b>{{__('front.By submitting your information, you agree to the Golden Gate (HK)')}}
                            <a href="{{route('home-terms-of-use')}}" class="text-primary">{{__('front.Terms & conditions')}}</a> and
                            <a href="{{route('home-privacy-policy')}}" class="text-primary">{{__('front.Privacy Policy')}}</a></b>
                        <br>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{__('front.REGISTER')}}</button>
                        </div><!-- End .form-footer -->
                    </form>
                </div>

            </div><!-- End .row -->
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main><!-- End .main -->
@endsection

@section('stylesheets')
    <style>
        .custom-control-label:before, .custom-control-label:after {
            display: none !important;
        }
    </style>
    <link href="{{ URL::asset('backend/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection



@section('scripts')
    <script src="{{ URL::asset('backend/assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('backend/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
            type="text/javascript"></script>

    <script>

        var currentTime = new Date();
        $('input[name="dob_date"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 22),
            maxDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 18),
            locale: {
                format: 'DD-MM-YYYY',
            },
        });
    </script>
@stop