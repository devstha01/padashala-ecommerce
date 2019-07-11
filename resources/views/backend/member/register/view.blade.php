@extends('backend.layouts.master')

@section('content')
    <div class="page-container">
        <div class="page-content">
            <div class="container">
                <div class="page-head">
                    <div class="page-title">
                        <h1>{{__('dashboard.Member')}}
                        </h1>
                    </div>

                </div>
            {{ Form::model($member,[
         'class' => 'horizontal-form',
         ])
         }}

            <!-- <form action="#" class="horizontal-form"> -->
                <div class="form-body">
                    @include('backend.includes.flash')

                    <h3 class="form-section">{{__('dashboard.Personnel Detail')}}</h3>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="control-label">{{__('dashboard.Surname')}}</label>
                                {{ Form::text('surname',null , ['class'=> 'form-control', 'placeholder' => 'surname', 'id'=>"surname",'disabled']) }}
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
                                               value="male" @if($member->gender=='male') selected @endif> {{__('dashboard.Male')}} </label>
                                    <label class="radio-registration">
                                        <input type="radio" name="gender" id="female"
                                               value="female" @if($member->gender=='female') selected @endif> {{__('dashboard.Female')}}</label>
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
                                {{ Form::text('name', null , ['class'=> 'form-control', 'placeholder' => 'Name', 'id'=>"name",'disabled']) }}
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
                                {{ Form::text('contact_number',null,['class'=>'form-control','id'=>'contact_number','placeholder'=>'Contact Number','disabled']) }}
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
                                <label class="control-label">{{__('dashboard.Martial Status')}}</label>
                                <select name="marital_status" class="form-control"
                                        data-placeholder="Choose a Marital Status" tabindex="1">
                                    <option value="no" @if($member->marital_status=='no') selected @endif>{{__('dashboard.Single')}}</option>
                                    <option value="yes" @if($member->marital_status=='yes') selected="selected" @endif>{{__('dashboard.Married')}}</option>
                                </select>
                                <span class="error-message"></span>
                                @if ($errors->has('marital_status'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('marital_status') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Country')}}</label>


                                {{ Form::select('country', $countries ,$member->country_id,['class'=>'form-control','id'=>'country','disabled']) }}


                                <span class="error-message"></span>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.ID type')}}</label>
                                {{ Form::select('identification_type_id', $identificationType , $member->identification_type,['class'=>'form-control','disabled']) }}
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="control-label">{{__('dashboard.Identification Number')}}</label>
                                {{ Form::text('identification_number',null,['class'=>'form-control','id'=>'identification_number','placeholder'=> 'Identification Number','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('identification_number'))
                                    <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('identification_number') }}</strong>
                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="control-label">{{__('dashboard.Email')}}</label>
                                {{ Form::text('email',null,['class'=>'form-control','id'=>'email','placeholder'=>'Email','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('email'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Date Of Birth')}}</label>
                                {{ Form::text('dob',null,['class'=>'form-control datepicker','id'=>'dob','placeholder'=>'Date Of Birth','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('dob'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('dob') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Address')}}</label>
                                {{ Form::text('address',null,['class'=>'form-control','id'=>'address','placeholder'=>'Address','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('address'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('address') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Joining Date')}}</label>
                                {{ Form::text('joining_date',null,['class'=>'form-control datepicker','id'=>'joining_date','placeholder'=>'Joining Date','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('joining_date'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('joining_date') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                        <!--/span-->

                    </div>

                    {{--//Register Nominee Detail--}}


                    <h3 class="form-section">{{__('dashboard.Nominee Detail')}}</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Name')}}</label>
                                {{ Form::text('nominee_name', $nominee->nominee_name , ['class'=> 'form-control', 'placeholder' => 'Nominee Detail', 'id'=>"nominee_name",'disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('nominee_name'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('nominee_name') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Contact Number')}}</label>
                                {{ Form::text('nominee_contact_number',$nominee->contact_number,['class'=>'form-control','id'=>'nominee_contact_number','placeholder'=>'Contact Number','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('nominee_contact_number'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('nominee_contact_number') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                    </div>




                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.ID Type')}}</label>
                                {{ Form::select('nominee_identification_type_id', $identificationType ,$nominee->identification_type,['class'=>'form-control','id'=>'nominee_identification_type_id','disabled']) }}
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.ID/Passport Number')}}</label>
                                {{ Form::text('nominee_identification_number',$nominee->identification_number,['class'=>'form-control','id'=>'nominee_identification_number','placeholder'=> 'ID/Passport Number','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('nominee_identification_number'))
                                    <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('nominee_identification_number') }}</strong>
                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Relationship')}}</label>
                                {{ Form::text('relationship',$nominee->relationship,['class'=>'form-control','id'=>'relationship','placeholder'=>'Relationship','disabled']) }}
                                <span class="error-message"></span>
                                @if ($errors->has('relationship'))
                                    <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('relationship') }}</strong>
						</span>
                                @endif
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row">

                    <div class="col-md-6">
                        <h3 class="form-section">{{__('dashboard.Payments Details')}}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    <label class="control-label">{{__('dashboard.Packages')}}</label>
                                    {{ Form::select('package_id', $packages ,$asset->package_id, ['class'=> 'form-control', 'placeholder' => 'Choose Package', 'id'=>"packageId",'disabled']) }}
                                    <span class="error-message"></span>
                                    @if ($errors->has('package_id'))
                                        <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('package_id') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>
                        </div>





                    </div>

                </div>



            </div>

            {{ Form::close() }}

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