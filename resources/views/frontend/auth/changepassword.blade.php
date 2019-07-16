@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
        @include('fragments.message')
            <div class="row justify-content-center">
                <div class="col-md-6">
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
                                class="btn btn-info">{{__('front.Submit')}}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection