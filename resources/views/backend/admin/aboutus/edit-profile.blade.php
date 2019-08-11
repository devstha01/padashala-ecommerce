@extends('backend.layouts.master')

@section('content')
    <div class="page-container">
        <div class="page-content">
            <div class="container">
                <div class="page-head">
                    <div class="page-title">
                        <h1>{{__('dashboard.Edit Profile')}}
                        </h1>
                    </div>

                </div>
                @include('fragments.message')
                <form action="{{route('admin-customer-update',$user->id)}}" method="post">
                    {{csrf_field()}}
                    <div class="form-body">


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="control-label">{{__('dashboard.Surname')}}</label>
                                    <input type="text" class="form-control" name="surname" value="{{$user->surname}}">
                                    <span class="error-message"></span>
                                    @if ($errors->has('surname'))
                                        <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('surname') }}</strong>
						</span>
                                    @endif
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="control-label">{{__('dashboard.Gender')}}</label>
                                    <div class="radio-list">
                                        <label class="radio-registration">
                                            <input type="radio" name="gender" id="male"
                                                   value="male" {{$user->gender=='male'?'checked':''}}> {{__('dashboard.Male')}}
                                        </label>
                                        <label class="radio-registration">
                                            <input type="radio" name="gender" id="female"
                                                   value="female"
                                                    {{$user->gender=='female'?'checked':''}}> {{__('dashboard.Female')}}
                                        </label>
                                    </div>
                                    <span class="error-message"></span>
                                    @if ($errors->has('gender'))
                                        <span class="has-error help-block"
                                              style="color:red"><strong>{{ $errors->first('gender') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('dashboard.Name')}}</label>
                                    {{ Form::text('name', $user->name , ['class'=> 'form-control', 'placeholder' => 'Name', 'id'=>"name"]) }}
                                    <span class="error-message"></span>
                                    @if ($errors->has('name'))
                                        <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('name') }}</strong>
						</span>
                                    @endif
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="control-label">{{__('dashboard.Contact Number')}}</label>
                                    {{ Form::text('contact_number',$user->contact_number,['class'=>'form-control','id'=>'contact_number','placeholder'=>'Contact Number']) }}
                                    <span class="error-message"></span>
                                    @if ($errors->has('contact_number'))
                                        <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('contact_number') }}</strong>
						</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('dashboard.Country')}}</label>

                                    {{ Form::select('country', $countries ,$user->country_id,['class'=>'form-control','id'=>'country']) }}


                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('dashboard.Address')}}</label>
                                    {{ Form::text('address',$user->address,['class'=>'form-control','id'=>'address','placeholder'=>'Address']) }}
                                    <span class="error-message"></span>
                                    @if ($errors->has('address'))
                                        <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('address') }}</strong>
						</span>
                                    @endif
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="control-label">{{__('dashboard.Email')}}</label>
                                    {{ Form::text('email',$user->email,['class'=>'form-control','id'=>'email','placeholder'=>'Email']) }}
                                    <span class="error-message"></span>
                                    @if ($errors->has('email'))
                                        <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-actions right">
                                    <button type="submit" class="btn blue">
                                        <i class="fa fa-check"></i> {{__('dashboard.UPDATE')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <style type="text/css">
        .select2-container {
            border: 1px solid #ccc;

        }

        select .select2-design.select2-hidden-accessible {
            border: 0px solid #000 !important;
            /* clip: rect(0 0 0 0) !important; */
            height: 1px !important;
            margin: -1px !important;
            /* overflow: hidden !important; */

            position: absolute !important;
            width: 1px !important;
            /* z-index: 9999999; */
            right: 18px;
            top: 34px;
        }
    </style>

    <!-- <script src="{{ URL::asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script> -->
    <script src="{{ URL::asset('assets/custom/repo.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/custom/js/register.js') }}" type="text/javascript"></script>


@stop
