@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item"><a href="{{ route('order-list') }}">{{__('front.Profile')}}</a></li>--}}
        {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Change Password')}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}

        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h2>{{__('front.Change Your Password')}}</h2>
                    <form action="{{route('change-password-user')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="control-label">{{__('front.Current Password')}}
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="old_password">
                            <span style="color: red">{{$errors->first('old_password')??''}}</span>
                            <span style="color: red">{{session('old_password')??''}}</span>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{__('front.New Password')}}
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="new_password">
                            <span style="color: red">{{$errors->first('new_password')??''}}</span>
                            <span style="color: red">{{session('new_password')??''}}</span>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{__('front.Confirm Password')}}
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="confirm_password">
                            <span style="color: red">{{$errors->first('confirm_password')??''}}</span>
                        </div>

                        <button type="submit"
                                class="btn btn-info">{{__('front.Submit User Password')}}
                        </button>
                    </form>
                </div>
                <div class="col-sm-6">
                    <h2>{{__('front.Change Your Transaction Password')}}</h2>
                    <form action="{{route('change-password-trans')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="control-label">{{__('front.Current Password')}}
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="old_transaction_password">
                            <span style="color: red">{{$errors->first('old_transaction_password')??''}}</span>
                            <span style="color: red">{{session('old_transaction_password')??''}}</span>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{__('front.New Password')}}
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="new_transaction_password">
                            <span style="color: red">{{$errors->first('new_transaction_password')??''}}</span>
                            <span style="color: red">{{session('new_transaction_password')??''}}</span>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{__('front.Confirm Password')}}
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="confirm_transaction_password">
                            <span style="color: red">{{$errors->first('confirm_transaction_password')??''}}</span>
                        </div>

                        <button type="submit"
                                class="btn btn-info">{{__('front.Submit Transaction Password')}}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection